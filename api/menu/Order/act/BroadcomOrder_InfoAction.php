<?php

/**
 * 订单信息
 * @token 02842FFC-AA6B-91AD-3FCD-78620325C8A6
 * @author Kinsama
 * @version 2020-08-07
 */
class BroadcomOrder_InfoAction extends ActionBase
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
        if (!$request->hasParameter("order_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: order_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_id = $request->getParameter("order_id");
        $request->setAttribute("order_id", $order_id);
        return VIEW_DONE;
    }

    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        $order_id = $request->getAttribute("order_id");
        $result = array();
        // TODO Order detail info should be got in here
        // TODO Now only implement the pattern of payment flow
        $post_data = array(
            "school_id" => $request->member()->schoolId()
        );
        $repond_member_list = Utility::getJsonResponse("?t=589049D8-F35C-2E6A-E792-D576E8002A2C&m=" . $request->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_member_list)) {
            $repond_member_list->setPos(__FILE__, __LINE__);
            return $repond_member_list;
        }
        $member_list = $repond_member_list["member_list"];
        // 获取账面流水信息
        $payment_info = BroadcomPaymentDBI::selectPaymentDetail($order_id);
        if ($controller->isError($payment_info)) {
            $payment_info->setPos(__FILE__, __LINE__);
            return $payment_info;
        }
        foreach ($payment_info as $payment_id => $payment_detail) {
            if (isset($member_list[$payment_detail["operated_by"]])) {
                $payment_info[$payment_id]["creater_name"] = $member_list[$payment_detail["operated_by"]]["m_name"];
            } else {
                $payment_info[$payment_id]["creater_name"] = "";
            }
            $payment_info[$payment_id]["red_flg"] = false;
            if ($payment_detail["payment_amount"] < 0) {
                $payment_info[$payment_id]["red_flg"] = true;
            }
            $payment_info[$payment_id]["payment_amount"] = number_format($payment_detail["payment_amount"], 2) . "元";
        }
        $result["payment_flow"] = $payment_info;
        return $result;
    }
}
?>