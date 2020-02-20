<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 前台业务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_OrderPaymentAction extends BroadcomFrontActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_payment")) {
            $ret = $this->_doPaymentExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        }
        return $ret;
    }

    /**
     * 执行参数检测
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainValidate(Controller $controller, User $user, Request $request)
    {
        if (!$request->hasParameter("order_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_id = $request->getParameter("order_id");
        $order_info = BroadcomOrderDBI::selectOrderInfo($order_id);
        if ($controller->isError($order_info)) {
            $order_info->setPos(__FILE__, __LINE__);
            return $order_info;
        }
        if (empty($order_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_item_info = BroadcomOrderDBI::selectOrderItemByOrderId($order_id);
        if ($controller->isError($order_item_info)) {
            $order_item_info->setPos(__FILE__, __LINE__);
            return $order_item_info;
        }
        $student_id = $order_info["student_id"];
        $student_info = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
        if ($controller->isError($student_info)) {
            $student_info->setPos(__FILE__, __LINE__);
            return $student_info;
        }
        if (empty($student_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_level_list = BroadcomStudentEntity::getStudentLevelList();
        $student_info["grade_name"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
        $student_info["student_level_name"] = $student_level_list[$student_info["student_level"]];
        $item_list = BroadcomItemInfoDBI::selectItemInfoList();
        if ($controller->isError($item_list)) {
            $item_list->setPos(__FILE__, __LINE__);
            return $item_list;
        }
        $payment_amount = "0";
        if ($request->hasParameter("do_payment")) {
            $payment_amount = $request->getParameter("payment_amount");
            if (!Validate::checkNotNull($payment_amount) || !Validate::checkDecimalNumber($payment_amount, array("min" => "1", "max" => $order_info["order_debt"]))) {
                $request->setError("payment_amount", "请填写的有效付款额");
            }
        }
        $request->setAttribute("order_id", $order_id);
        $request->setAttribute("order_info", $order_info);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("item_list", $item_list);
        $request->setAttribute("payment_amount", $payment_amount);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
        return VIEW_DONE;
    }

    /**
     * 执行默认命令
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     * @access private
     */
    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    private function _doPaymentExecute(Controller $controller, User $user, Request $request)
    {
        $order_id = $request->getAttribute("order_id");
        $order_info = $request->getAttribute("order_info");
        $payment_amount = $request->getAttribute("payment_amount");
        $student_id = $request->getAttribute("student_id");
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $order_update_data = array();
        $order_update_data["order_payment"] = $order_info["order_payment"] + $payment_amount;
        $order_update_data["order_debt"] = $order_info["order_debt"] - $payment_amount;
        if ($order_update_data["order_debt"] == 0) {
            $order_update_data["order_status"] = BroadcomOrderEntity::ORDER_STATUS_2;
        }
        $order_update_res = BroadcomOrderDBI::updateOrder($order_update_data, $order_id);
        if ($controller->isError($order_update_res)) {
            $order_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_update_res;
        }
        //------------------------------
        // TODO START PAYMENT PLAN A
        //------------------------------
        $payment_insert_data = array();
        $payment_insert_data["student_id"] = $student_id;
        $payment_insert_data["order_id"] = $order_id;
        $payment_insert_data["order_item_id"] = null;
        $payment_insert_data["payment_amount"] = $payment_amount;
        $payment_insert_res = BroadcomPaymentDBI::insertPayment($payment_insert_data);
        if ($controller->isError($payment_insert_res)) {
            $payment_insert_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $payment_insert_res;
        }
        //------------------------------
        // TODO END PAYMENT PLAN A
        //------------------------------
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=front&act=order_list");
        return VIEW_DONE;
    }
}
?>