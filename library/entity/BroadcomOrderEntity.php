<?php

/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomOrderEntity
{
    const ACHIEVE_TYPE_1 = "1";
    const ACHIEVE_TYPE_2 = "2";
    const ACHIEVE_TYPE_3 = "3";
    const ACHIEVE_TYPE_4 = "4";

    const ACHIEVE_TYPE_1_1 = "1";
    const ACHIEVE_TYPE_1_2 = "2";
    const ACHIEVE_TYPE_1_3 = "3";
    const ACHIEVE_TYPE_1_4 = "4";
    const ACHIEVE_TYPE_1_5 = "5";
    const ACHIEVE_TYPE_1_6 = "6";
    const ACHIEVE_TYPE_1_7 = "7";

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

    public static function getAchieveTypeList()
    {
        return array(
            self::ACHIEVE_TYPE_1 => "新签",
            self::ACHIEVE_TYPE_2 => "学管推荐",
            self::ACHIEVE_TYPE_4 => "教师推荐",
            self::ACHIEVE_TYPE_3 => "续费"
        );
    }

    public static function getSubAchieveTypeList()
    {
        return array(
            self::ACHIEVE_TYPE_1 => array(
                self::ACHIEVE_TYPE_1_1 => "市场-直访",
                self::ACHIEVE_TYPE_1_2 => "市场-热线",
                self::ACHIEVE_TYPE_1_3 => "市场-Leads",
                self::ACHIEVE_TYPE_1_4 => "市场-外呼",
                self::ACHIEVE_TYPE_1_5 => "市场-拉上",
                self::ACHIEVE_TYPE_1_6 => "智能营销媒体",
                self::ACHIEVE_TYPE_1_7 => "介绍"
            )
        );
    }
}
?>