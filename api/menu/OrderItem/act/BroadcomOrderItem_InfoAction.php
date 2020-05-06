<?php

/**
 * 合同信息详细画面
 * @author Kinsama
 * @version 2020-04-16
 */
class BroadcomOrderItem_InfoAction extends ActionBase
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
        if (!$request->hasParameter("order_item_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: order_item_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_item_id = $request->getParameter("order_item_id");
        $order_item_info = BroadcomOrderDBI::selectOrderItemDetail($order_item_id);
        if ($controller->isError($order_item_info)) {
            $order_item_inforet->setPos(__FILE__, __LINE__);
            return $order_item_info;
        }
        if (empty($order_item_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter invalid: order_item_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $achieve_order_item_info = BroadcomOrderDBI::selectOrderItemAchieve($order_item_id);
        if ($controller->isError($achieve_order_item_info)) {
            $achieve_order_item_info->setPos(__FILE__, __LINE__);
            return $achieve_order_item_info;
        }
        $audition_order_item_info = BroadcomOrderDBI::selectOrderItemAudition($order_item_id);
        if ($controller->isError($audition_order_item_info)) {
            $audition_order_item_info->setPos(__FILE__, __LINE__);
            return $audition_order_item_info;
        }
        $request->setAttribute("order_item_id", $order_item_id);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("achieve_order_item_info", $achieve_order_item_info);
        $request->setAttribute("audition_order_item_info", $audition_order_item_info);
        return VIEW_DONE;
    }

    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        $order_item_info = $request->getAttribute("order_item_info");
        $achieve_order_item_info = $request->getAttribute("achieve_order_item_info");
        $audition_order_item_info = $request->getAttribute("audition_order_item_info");
        $item_method_list = BroadcomItemEntity::getItemMethodList();
        $order_item_info["item_method_name"] = $item_method_list[$order_item_info["item_method"]];
        $order_item_info["item_unit_hour"] = round($order_item_info["item_unit_hour"], 1);
        $order_item_info["order_item_price"] = round($order_item_info["order_item_price"], 2);
        $order_item_info["order_item_discount_amount"] = round($order_item_info["order_item_discount_amount"], 2);
        $order_item_info["order_item_payable_amount"] = round($order_item_info["order_item_payable_amount"], 2);
        $order_item_info["order_item_trans_price"] = round($order_item_info["order_item_trans_price"], 2);
        $order_item_info["order_item_remain"] = round($order_item_info["order_item_remain"], 1);
        $order_item_info["order_item_arrange"] = round($order_item_info["order_item_arrange"], 1);
        $order_item_info["order_item_confirm"] = round($order_item_info["order_item_confirm"], 1);
        foreach ($achieve_order_item_info as $member_id => $achieve_info) {
            $achieve_order_item_info[$member_id]["achieve_amount"] = round($order_item_info["order_item_amount"] * $order_item_info["order_item_trans_price"] * $achieve_info["achieve_ratio"] / 100, 2);
        }
        $order_item_info["achieve_member"] = $achieve_order_item_info;
        $order_item_info["audition_teacher"] = $audition_order_item_info;
        return $order_item_info;
    }
}
?>