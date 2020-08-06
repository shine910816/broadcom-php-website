<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 前台业务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_RefundInfoAction extends BroadcomFrontActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_cancel")) {
            $ret = $this->_doCancelExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_pass")) {
            $ret = $this->_doPassExecute($controller, $user, $request);
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
        if (!$request->hasParameter("order_item_id")) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_item_id = $request->getParameter("order_item_id");
        $member_id = $user->member()->id();
        $school_id = $user->member()->schoolId();
        $refund_list = BroadcomRefundDBI::selectRefundInfoList($school_id);
        if ($controller->isError($refund_list)) {
            $refund_list->setPos(__FILE__, __LINE__);
            return $refund_list;
        }
        if (!isset($refund_list[$order_item_id])) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $refund_info = $refund_list[$order_item_id];
        $refund_info["refund_amount"] = $refund_info["order_item_amount"] - $refund_info["order_item_confirm"];
Utility::testVariable($refund_info);
        $oppo_student_name = "";
        if ($refund_info["refund_type"] == "2") {
            $student_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
            if ($controller->isError($student_list)) {
                $student_list->setPos(__FILE__, __LINE__);
                return $student_list;
            }
            $oppo_student_name = $student_list[$refund_info["oppo_student_id"]]["student_name"];
        }
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("order_item_id", $order_item_id);
        $request->setAttribute("refund_info", $refund_info);
        $request->setAttribute("oppo_student_name", $oppo_student_name);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
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

    private function _doPassExecute(Controller $controller, User $user, Request $request)
    {
        $member_id = $request->getAttribute("member_id");
        $school_id = $request->getAttribute("school_id");
        $order_item_id = $request->getAttribute("order_item_id");
        $refund_info = $request->getAttribute("refund_info");
        $refund_update_data = array();
        $refund_update_data["refund_examine_flg"] = "1";
        $refund_update_data["refund_examine_id"] = $member_id;
        $refund_update_data["refund_examine_date"] = date("Y-m-d H:i:s");
        $payment_insert_data = array();
        $order_item_update_data = array();
        if ($refund_info["refund_type"] == "1") {
            $payment_insert_data["student_id"] = $refund_info["student_id"];
            $payment_insert_data["order_id"] = $refund_info["order_id"];
            $payment_insert_data["order_item_id"] = $order_item_id;
            $payment_insert_data["payment_amount"] = 0 - round($refund_info["order_item_payable_amount"] * $refund_info["refund_amount"] / $refund_info["order_item_amount"] * $refund_info["refund_precent"], 2);
            $order_item_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_4;
            $order_item_update_data["order_item_remain"] = "0";
            $order_item_update_data["order_item_arrange"] = "0";
        } else {
            $order_item_update_data["student_id"] = $refund_info["oppo_student_id"];
        }
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        if (!empty($payment_insert_data)) {
            $insert_res = BroadcomPaymentDBI::insertPayment($payment_insert_data);
            if ($controller->isError($insert_res)) {
                $insert_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $insert_res;
            }
        }
        $order_item_res = BroadcomOrderDBI::updateOrderItem($order_item_update_data, $order_item_id);
        if ($controller->isError($order_item_res)) {
            $order_item_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_item_res;
        }
        $refund_res = BroadcomRefundDBI::updateRefund($refund_update_data, $order_item_id);
        if ($controller->isError($refund_res)) {
            $refund_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $refund_res;
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=front&act=refund_list");
        return VIEW_DONE;
    }

    private function _doCancelExecute(Controller $controller, User $user, Request $request)
    {
        $order_item_id = $request->getAttribute("order_item_id");
        $delete_res = BroadcomRefundDBI::removeRefund($order_item_id);
        if ($controller->isError($delete_res)) {
            $delete_res->setPos(__FILE__, __LINE__);
            return $delete_res;
        }
        $controller->redirect("./?menu=front&act=refund_list");
        return VIEW_DONE;
    }
}
?>