<?php

/**
 * 订单列表
 * @token DD6BE1A4-420A-F46D-E42A-F72CACFB1E09
 * @author Kinsama
 * @version 2020-04-16
 */
class BroadcomOrder_ListAction extends ActionBase
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
        // 必要参数检证
        if (!$request->hasParameter("school_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: school_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if (!$request->hasParameter("start_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: start_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if (!$request->hasParameter("end_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: end_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $request->getParameter("school_id");
        $start_date = $request->getParameter("start_date") . " 00:00:00";
        $end_date = $request->getParameter("end_date") . " 23:59:59";
        $member_id_list = null;
        if ($request->hasParameter("member_text")) {
            $member_id_list = explode(",", $request->getParameter("member_text"));
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("start_date", $start_date);
        $request->setAttribute("end_date", $end_date);
        $request->setAttribute("member_id_list", $member_id_list);
        return VIEW_DONE;
    }

    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $start_date = $request->getAttribute("start_date");
        $end_date = $request->getAttribute("end_date");
        $member_id_list = $request->getAttribute("member_id_list");
        // 获取订单
        $order_list = BroadcomStatisticsDBI::selectOrderList($start_date, $end_date, $school_id, $member_id_list);
        if ($controller->isError($order_list)) {
            $order_list->setPos(__FILE__, __LINE__);
            return $order_list;
        }
        if (empty($order_list)) {
            return array();
        }
        $order_id_list = array_keys($order_list);
        // 获取账面流水
        $payment_info = BroadcomPaymentDBI::selectSimplePaymentForOrder($order_id_list);
        if ($controller->isError($payment_info)) {
            $payment_info->setPos(__FILE__, __LINE__);
            return $payment_info;
        }
        foreach ($payment_info as $order_id => $order_payment) {
            $order_list[$order_id]["payment_history"] = $order_payment;
        }
        // 获取合同详细
        $order_item_list = BroadcomOrderDBI::selectOrderItemByOrderId($order_id_list);
        if ($controller->isError($order_item_list)) {
            $order_item_list->setPos(__FILE__, __LINE__);
            return $order_item_list;
        }
        foreach ($order_item_list as $order_item_id => $order_item_info) {
            $oi_temp = array();
            $oi_temp["order_item_id"] = $order_item_info["order_item_id"];
            $oi_temp["contract_number"] = $order_item_info["contract_number"];
            $oi_temp["order_item_status"] = $order_item_info["order_item_status"];
            $order_list[$order_item_info["order_id"]]["order_item"][$order_item_id] = $oi_temp;
        }
        return $order_list;
    }
}
?>