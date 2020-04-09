<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-03-07
 */
class BroadcomEducation_ContractRefundAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_create")) {
            $ret = $this->_doCreateExecute($controller, $user, $request);
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
        $order_item_info = BroadcomOrderDBI::selectOrderItem($order_item_id);
        if ($controller->isError($order_item_info)) {
            $order_item_info->setPos(__FILE__, __LINE__);
            return $order_item_info;
        }
        if (empty($order_item_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $order_item_info["student_id"];
        $student_info = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
        if ($controller->isError($student_info)) {
            $student_info->setPos(__FILE__, __LINE__);
            return $student_info;
        }
        if (empty($student_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $student_info["school_id"];
        $refund_list = BroadcomRefundDBI::selectRefundInfoList($school_id);
        if ($controller->isError($refund_list)) {
            $refund_list->setPos(__FILE__, __LINE__);
            return $refund_list;
        }
        $student_list_tmp = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
        if ($controller->isError($student_list_tmp)) {
            $student_list_tmp->setPos(__FILE__, __LINE__);
            return $student_list_tmp;
        }
        $student_list = array();
        foreach ($student_list_tmp as $student_info_tmp) {
            if ($student_info_tmp["student_entrance_year"] == $student_info["student_entrance_year"] && $student_info_tmp["student_id"] != $student_id) {
                $student_list[$student_info_tmp["student_id"]] = $student_info_tmp;
            }
        }
        $allow_refund_flg = false;
        $allow_refund_method_list = array(
            BroadcomItemEntity::ITEM_METHOD_1_TO_1,
            BroadcomItemEntity::ITEM_METHOD_1_TO_2,
            BroadcomItemEntity::ITEM_METHOD_1_TO_3
        );
        $allow_transfer_flg = false;
        $refund_info = array();
        if (!isset($refund_list[$order_item_id])) {
            if (in_array($order_item_info["item_method"], $allow_refund_method_list) && $order_item_info["order_item_status"] == BroadcomOrderEntity::ORDER_ITEM_STATUS_2) {
                $allow_refund_flg = true;
            }
            if (!empty($student_list) && $allow_refund_flg) {
                $allow_transfer_flg = true;
            }
        } else {
            $refund_info = $refund_list[$order_item_id];
        }
        $item_info = BroadcomItemInfoDBI::selectItemInfo($order_item_info["item_id"]);
        if ($controller->isError($item_info)) {
            $item_info->setPos(__FILE__, __LINE__);
            return $item_info;
        }
        if (empty($item_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $item_list = BroadcomItemInfoDBI::selectItemInfoList();
        if ($controller->isError($item_list)) {
            $item_list->setPos(__FILE__, __LINE__);
            return $item_list;
        }
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("order_item_id", $order_item_id);
        $request->setAttribute("refund_info", $refund_info);
        $request->setAttribute("allow_refund_flg", $allow_refund_flg);
        $request->setAttribute("allow_transfer_flg", $allow_transfer_flg);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("student_list", $student_list);
        $request->setAttribute("item_info", $item_info);
        $request->setAttribute("item_list", $item_list);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("order_item_status_list", BroadcomOrderEntity::getOrderItemStatusList());
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

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        $member_id = $request->getAttribute("member_id");
        $student_id = $request->getAttribute("student_id");
        $school_id = $request->getAttribute("school_id");
        $order_item_id = $request->getAttribute("order_item_id");
        $refund_type = $request->getParameter("refund_type");
        $insert_data = array();
        if ($refund_type == "1") {
            $allow_refund_flg = $request->getAttribute("allow_refund_flg");
            $refund_precent = $request->getParameter("refund_precent");
            if ($allow_refund_flg) {
                $insert_data["order_item_id"] = $order_item_id;
                $insert_data["refund_type"] = "1";
                $insert_data["school_id"] = $school_id;
                $insert_data["refund_precent"] = $refund_precent;
            }
        } else {
            $allow_transfer_flg = $request->getAttribute("allow_transfer_flg");
            $oppo_student_id = $request->getParameter("oppo_student_id");
            if ($allow_transfer_flg) {
                $insert_data["order_item_id"] = $order_item_id;
                $insert_data["refund_type"] = "2";
                $insert_data["school_id"] = $school_id;
                $insert_data["oppo_student_id"] = $oppo_student_id;
            }
        }
        if (!empty($insert_data)) {
            $insert_res = BroadcomRefundDBI::insertRefund($insert_data);
            if ($controller->isError($insert_res)) {
                $insert_res->setPos(__FILE__, __LINE__);
                return $insert_res;
            }
        }
        $controller->redirect("./?menu=education&act=student_info&student_id=" . $student_id);
        return VIEW_DONE;
    }
}
?>