<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 前台业务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_OrderInfoAction extends BroadcomFrontActionBase
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
        $present_hours_list = array();
        foreach ($order_item_info as $order_tmp) {
            if ($order_tmp["main_order_item_id"]) {
                if (!isset($present_hours_list[$order_tmp["main_order_item_id"]])) {
                    $present_hours_list[$order_tmp["main_order_item_id"]] = 0;
                }
                $present_hours_list[$order_tmp["main_order_item_id"]] += $order_tmp["order_item_amount"];
            }
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
        $pass_able_flg = false;
        $cancel_able_flg = false;
        // TODO 权限设定Admin?
        $auth_able_flg = false;
        if ($user->isAdmin() || $user->member()->position() == BroadcomMemberEntity::POSITION_HR_FINANCE) {
            $auth_able_flg = true;
        }
        $passable_order_status_array = array(
            BroadcomOrderEntity::ORDER_STATUS_1,
            BroadcomOrderEntity::ORDER_STATUS_2
        );
        if ($auth_able_flg && $order_info["order_status"] == BroadcomOrderEntity::ORDER_STATUS_2) {
            $pass_able_flg = true;
        }
        if ($auth_able_flg && in_array($order_info["order_status"], $passable_order_status_array)) {
            $cancel_able_flg = true;
        }
        $back_link = "./?menu=front&act=order_list";
        if ($request->hasParameter("b")) {
            $back_link = Utility::decodeBackLink($request->getParameter("b"));
        }
        $request->setAttribute("order_id", $order_id);
        $request->setAttribute("order_info", $order_info);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("present_hours_list", $present_hours_list);
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("item_list", $item_list);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
        $request->setAttribute("pass_able_flg", $pass_able_flg);
        $request->setAttribute("cancel_able_flg", $cancel_able_flg);
        $request->setAttribute("order_status_list", BroadcomOrderEntity::getOrderStatusList());
        $request->setAttribute("back_link", $back_link);
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
        if (!$request->getAttribute("pass_able_flg")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_id = $request->getAttribute("order_id");
        $order_item_info = $request->getAttribute("order_item_info");
        $present_hours_list = $request->getAttribute("present_hours_list");
        $student_id = $request->getAttribute("student_id");
        $student_info = $request->getAttribute("student_info");
        $item_list = $request->getAttribute("item_list");
        $main_order_trans_price = array();
        foreach ($order_item_info as $order_item_id => $order_item_tmp) {
            if (is_null($order_item_tmp["main_order_item_id"])) {
                $present_hours = 0;
                if (isset($present_hours_list[$order_item_id])) {
                    $present_hours = $present_hours_list[$order_item_id];
                }
                if ($item_list[$order_item_tmp["item_id"]]["item_method"] == BroadcomItemEntity::ITEM_METHOD_CLASS) {
                    $main_order_trans_price[$order_item_id] = round($order_item_tmp["order_item_payable_amount"] / ($order_item_tmp["order_item_amount"] + $present_hours) / $item_info["item_unit_amount"], 2);
                } else {
                    $main_order_trans_price[$order_item_id] = round($order_item_tmp["order_item_payable_amount"] / ($order_item_tmp["order_item_amount"] + $present_hours), 2);
                }
            }
        }
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $order_update_data = array();
        $order_update_data["order_status"] = BroadcomOrderEntity::ORDER_STATUS_3;
        $order_update_data["order_examine_flg"] = "1";
        $order_update_data["order_examiner_id"] = $user->member()->id();
        $order_update_data["order_examine_date"] = date("Y-m-d H:i:s");
        $order_update_res = BroadcomOrderDBI::updateOrder($order_update_data, $order_id);
        if ($controller->isError($order_update_res)) {
            $order_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_update_res;
        }
        foreach ($order_item_info as $order_item_id => $order_item_tmp) {
            $item_info = $item_list[$order_item_tmp["item_id"]];
            $order_item_update_data = array();
            $order_item_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_2;
            if (is_null($order_item_tmp["main_order_item_id"])) {
                $order_item_update_data["order_item_trans_price"] = $main_order_trans_price[$order_item_id];
            } else {
                $order_item_update_data["order_item_trans_price"] = $main_order_trans_price[$order_item_tmp["main_order_item_id"]];
            }
            if ($item_info["item_method"] == BroadcomItemEntity::ITEM_METHOD_CLASS) {
                $order_item_update_data["order_item_remain"] = $order_item_tmp["order_item_amount"] * $item_info["item_unit_amount"] * $item_info["item_unit_hour"];
            } else {
                $order_item_update_data["order_item_remain"] = $order_item_tmp["order_item_amount"];
            }
            $order_item_update_data["order_item_confirm"] = "0";
            $order_item_update_res = BroadcomOrderDBI::updateOrderItem($order_item_update_data, $order_item_id);
            if ($controller->isError($order_item_update_res)) {
                $order_item_update_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $order_item_update_res;
            }
        }
        $student_update_data = array();
        $student_update_data["follow_status"] = BroadcomStudentEntity::FOLLOW_STATUS_3;
        $student_update_res = BroadcomStudentInfoDBI::updateStudentInfo($student_update_data, $student_id);
        if ($controller->isError($student_update_res)) {
            $student_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $student_update_res;
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=front&act=order_list");
        return VIEW_DONE;
    }

    private function _doCancelExecute(Controller $controller, User $user, Request $request)
    {
        if (!$request->getAttribute("cancel_able_flg")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_id = $request->getAttribute("order_id");
        $order_info = $request->getAttribute("order_info");
        $order_item_info = $request->getAttribute("order_item_info");
        $student_id = $request->getAttribute("student_id");
        $student_info = $request->getAttribute("student_info");
        $payment_amount = $order_info["order_payment"];
        $student_audition_hours = $student_info["audition_hours"];
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $order_update_data = array();
        $order_update_data["order_payment"] = 0;
        $order_update_data["order_debt"] = $order_info["order_payable"];
        $order_update_data["order_status"] = BroadcomOrderEntity::ORDER_STATUS_4;
        $order_update_data["order_examine_flg"] = "1";
        $order_update_data["order_examiner_id"] = $user->member()->id();
        $order_update_data["order_examine_date"] = date("Y-m-d H:i:s");
        $order_update_res = BroadcomOrderDBI::updateOrder($order_update_data, $order_id);
        if ($controller->isError($order_update_res)) {
            $order_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_update_res;
        }
        $payment_insert_data = array();
        $payment_insert_data["student_id"] = $student_id;
        $payment_insert_data["order_id"] = $order_id;
        $payment_insert_data["order_item_id"] = null;
        $payment_insert_data["payment_amount"] = 0 - $payment_amount;
        $payment_insert_res = BroadcomPaymentDBI::insertPayment($payment_insert_data);
        if ($controller->isError($payment_insert_res)) {
            $payment_insert_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $payment_insert_res;
        }
        $order_item_update_data = array();
        $order_item_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_4;
        foreach ($order_item_info as $order_item_id => $order_item_tmp) {
            $order_item_update_res = BroadcomOrderDBI::updateOrderItem($order_item_update_data, $order_item_id);
            if ($controller->isError($order_item_update_res)) {
                $order_item_update_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $order_item_update_res;
            }
        }
        $student_update_data = array();
        if ($student_info["follow_status"] != BroadcomStudentEntity::FOLLOW_STATUS_3) {
            if ($student_audition_hours < 2) {
                $student_update_data["follow_status"] = BroadcomStudentEntity::FOLLOW_STATUS_2;
            } else {
                $student_update_data["follow_status"] = BroadcomStudentEntity::FOLLOW_STATUS_1;
            }
        }
        $student_update_res = BroadcomStudentInfoDBI::updateStudentInfo($student_update_data, $student_id);
        if ($controller->isError($student_update_res)) {
            $student_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $student_update_res;
        }
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