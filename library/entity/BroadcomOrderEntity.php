<?php

/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomOrderEntity
{
    const ORDER_STATUS_1 = "1";
    const ORDER_STATUS_2 = "2";
    const ORDER_STATUS_3 = "3";
    const ORDER_STATUS_4 = "4";

    const ORDER_ITEM_STATUS_1 = "1";
    const ORDER_ITEM_STATUS_2 = "2";
    const ORDER_ITEM_STATUS_3 = "3";
    const ORDER_ITEM_STATUS_4 = "4";

    public static function getOrderStatusList()
    {
        return array(
            self::ORDER_STATUS_1 => "未付清",
            self::ORDER_STATUS_2 => "待审核",
            self::ORDER_STATUS_3 => "已审核",
            self::ORDER_STATUS_4 => "已退款"
        );
    }

    public static function getOrderItemStatusList()
    {
        return array(
            self::ORDER_ITEM_STATUS_1 => "待审核",
            self::ORDER_ITEM_STATUS_2 => "进行中",
            self::ORDER_ITEM_STATUS_3 => "已结课",
            self::ORDER_ITEM_STATUS_4 => "已退款"
        );
    }
}
?>