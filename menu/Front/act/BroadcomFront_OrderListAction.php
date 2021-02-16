<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 前台业务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_OrderListAction extends BroadcomFrontActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        $ret = $this->_doDefaultExecute($controller, $user, $request);
        if ($controller->isError($ret)) {
            $ret->setPos(__FILE__, __LINE__);
            return $ret;
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
        $order_status_list = BroadcomOrderEntity::getOrderStatusList();
        $order_status = BroadcomOrderEntity::ORDER_STATUS_2;
        if ($request->hasParameter("order_status")) {
            $order_status = $request->getParameter("order_status");
            if (!Validate::checkAcceptParam($order_status, array_keys($order_status_list))) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        }
        $order_item_flg = false;
        if ($order_status == BroadcomOrderEntity::ORDER_STATUS_4 && $request->hasParameter("order_item")) {
            $order_item_flg = true;
        }
        $content_text = "订单";
        $order_list = array();
        $page_url = "./?menu=" . $request->current_menu . "&act=" . $request->current_act . "&order_status=" . $order_status;
        if ($order_item_flg) {
            $content_text = "合同";
            $page_url .= "&order_item=1";
            $order_list = BroadcomOrderDBI::selectCanceledOrderItem();
            if ($controller->isError($order_list)) {
                $order_list->setPos(__FILE__, __LINE__);
                return $order_list;
            }
        } else {
            $order_list = BroadcomOrderDBI::selectOrderListByStatus($order_status);
            if ($controller->isError($order_list)) {
                $order_list->setPos(__FILE__, __LINE__);
                return $order_list;
            }
        }
        $order_list = Utility::getPaginationData($request, $order_list, $page_url);
        if ($controller->isError($order_list)) {
            $order_list->setPos(__FILE__, __LINE__);
            return $order_list;
        }
        if (!empty($order_list)) {
            foreach ($order_list as $order_id => $order_info) {
                if ($order_item_flg) {
                    $order_list[$order_id]["order_number"] = $order_list[$order_id]["contract_number"];
                    $order_list[$order_id]["order_payable"] = number_format($order_list[$order_id]["order_item_payable_amount"], 2);
                    $order_list[$order_id]["order_payment"] = "0.00";
                    $order_list[$order_id]["order_debt"] = number_format($order_list[$order_id]["order_item_payable_amount"], 2);
                } else {
                    $order_list[$order_id]["order_payable"] = number_format($order_info["order_payable"], 2);
                    $order_list[$order_id]["order_payment"] = number_format($order_info["order_payment"], 2);
                    $order_list[$order_id]["order_debt"] = number_format($order_info["order_debt"], 2);
                }
            }
        }
        $request->setAttribute("order_status", $order_status);
        $request->setAttribute("order_item_flg", $order_item_flg);
        $request->setAttribute("content_text", $content_text);
        $request->setAttribute("order_status_list", $order_status_list);
        $request->setAttribute("order_list", $order_list);
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
        $school_id = $user->member()->schoolId();
        $student_info_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
        if ($controller->isError($student_info_list)) {
            $student_info_list->setPos(__FILE__, __LINE__);
            return $student_info_list;
        }
        foreach ($student_info_list as $student_id => $student_info) {
            $student_info_list[$student_id]["grade_name"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
            $student_info_list[$student_id]["covered_mobile_number"] = Utility::coverMobileNumber($student_info["student_mobile_number"]);
        }
        $back_param = array(
            "order_status" => $request->getAttribute("order_status"),
            "page" => $request->current_page
        );
        $order_item_flg = $request->getAttribute("order_item_flg");
        if ($order_item_flg) {
            $back_param["order_item"] = "1";
        }
        $back_link = Utility::encodeBackLink("front", "order_list", $back_param);
        $request->setAttribute("student_info_list", $student_info_list);
        $request->setAttribute("back_link", $back_link);
        return VIEW_DONE;
    }
}
?>