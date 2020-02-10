<?php

/**
 * 数据库应用类-item_*
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomItemEntity
{
    const ITEM_TYPE_NORMAL = "1";
    const ITEM_TYPE_PROMOTE = "2";
    const ITEM_TYPE_PRESENT = "3";

    const ITEM_METHOD_1_TO_1 = "1";
    const ITEM_METHOD_1_TO_2 = "2";
    const ITEM_METHOD_1_TO_3 = "3";
    const ITEM_METHOD_CLASS = "4";

    const ITEM_GRADE_BEFORE = "0";
    const ITEM_GRADE_1 = "1";
    const ITEM_GRADE_2 = "2";
    const ITEM_GRADE_3 = "3";
    const ITEM_GRADE_4 = "4";
    const ITEM_GRADE_5 = "5";
    const ITEM_GRADE_6 = "6";
    const ITEM_GRADE_JUNIOR_1 = "7";
    const ITEM_GRADE_JUNIOR_2 = "8";
    const ITEM_GRADE_JUNIOR_3 = "9";
    const ITEM_GRADE_SENIOR_1 = "10";
    const ITEM_GRADE_SENIOR_2 = "11";
    const ITEM_GRADE_SENIOR_3 = "12";
    const ITEM_GRADE_TOTAL = "100";

    const ITEM_UNIT_HOURS = "1";
    const ITEM_UNIT_PERIOD = "2";

    const ITEM_SALE_ON = "1";
    const ITEM_SALE_OFF = "0";

    public static function getItemTypeList()
    {
        return array(
            self::ITEM_TYPE_NORMAL => "正课",
            self::ITEM_TYPE_PROMOTE => "促销课",
            self::ITEM_TYPE_PRESENT => "赠课"
        );
    }

    public static function getItemMethodList()
    {
        return array(
            self::ITEM_METHOD_1_TO_1 => "一对一",
            self::ITEM_METHOD_1_TO_2 => "一对二",
            self::ITEM_METHOD_1_TO_3 => "一对三",
            self::ITEM_METHOD_CLASS => "班课"
        );
    }

    public static function getItemGradeList()
    {
        return array(
            self::ITEM_GRADE_BEFORE => "幼小年级",
            self::ITEM_GRADE_1 => "小学一年级",
            self::ITEM_GRADE_2 => "小学二年级",
            self::ITEM_GRADE_3 => "小学三年级",
            self::ITEM_GRADE_4 => "小学四年级",
            self::ITEM_GRADE_5 => "小学五年级",
            self::ITEM_GRADE_6 => "小学六年级",
            self::ITEM_GRADE_JUNIOR_1 => "初中一年级",
            self::ITEM_GRADE_JUNIOR_2 => "初中二年级",
            self::ITEM_GRADE_JUNIOR_3 => "初中三年级",
            self::ITEM_GRADE_SENIOR_1 => "高中一年级",
            self::ITEM_GRADE_SENIOR_2 => "高中二年级",
            self::ITEM_GRADE_SENIOR_3 => "高中三年级",
            self::ITEM_GRADE_TOTAL => "全年级"
        );
    }

    public static function getItemUnitList()
    {
        return array(
            self::ITEM_UNIT_HOURS => "元/课时",
            self::ITEM_UNIT_PERIOD => "元/期"
        );
    }

    public static function getItemSaleStatusList()
    {
        return array(
            self::ITEM_SALE_ON => "在售",
            self::ITEM_SALE_OFF => "下架"
        );
    }
}
?>