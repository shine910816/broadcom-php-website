<?php

/**
 * 前台业务基类
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFrontActionBase extends ActionBase
{

    /**
     * 左边栏
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doLeftContent(Controller $controller, User $user, Request $request)
    {
        $result = array();
        if ($user->checkPositionAble("front", "my_leads")) {
            $result[] = array("my_leads", "我的意向客户");
        }
        if ($user->checkPositionAble("front", "school_leads")) {
            $result[] = array("school_leads", "校区意向客户");
        }
        if ($user->checkPositionAble("front", "order_list")) {
            $result[] = array("order_list", "订单审核退款");
        }
        if ($user->checkPositionAble("front", "refund_list")) {
            $result[] = array("refund_list", "合同退转审核");
        }
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }

    protected function _getTotalPrice($item_price, $item_amount, $item_discount_type, $item_discount_amount)
    {
        $result = $item_price * $item_amount;
        if ($item_discount_type == BroadcomItemEntity::ITEM_DISCOUNT_TYPE_DIRECT) {
            $result = $result - $item_discount_amount;
        } elseif ($item_discount_type == BroadcomItemEntity::ITEM_DISCOUNT_TYPE_PERCENT) {
            $result = $result * (100 - $item_discount_amount) / 100;
        }
        if ($result < 0) {
            return 0;
        }
        return round($result, 2);
    }
}
?>
