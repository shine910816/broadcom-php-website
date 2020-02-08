<?php

/**
 * 数据库应用类-menber_*
 * @author Kinsama
 * @version 2020-01-21
 */
class BroadcomMemberEntity
{
    const POSITION_DIRECTOR_CITY = "1100";
    const POSITION_DIRECTOR_OPERATION = "1110";
    const POSITION_DIRECTOR_HUMANRESOURCE = "1120";
    const POSITION_DIRECTOR_FINANCE = "1130";
    const POSITION_DIRECTOR_MARKETING = "1140";
    const POSITION_DIRECTOR_EDUCATION = "1150";
    const POSITION_REGIONAL_MANAGER = "2100";
    const POSITION_HEADMASTER = "3100";
    const POSITION_EDUCATE_MANAGER = "3110";
    const POSITION_MARKETING = "3111";
    const POSITION_MANAGER = "3112";
    const POSITION_TEACH_MANAGER = "3120";
    const POSITION_TEACHER = "3121";
    const POSITION_HR_FINANCE = "3200";

    const POSITION_LEVEL_0 = "0";
    const POSITION_LEVEL_1 = "1";
    const POSITION_LEVEL_2 = "2";
    const POSITION_LEVEL_3 = "3";
    const POSITION_LEVEL_4 = "4";
    const POSITION_LEVEL_5 = "5";
    const POSITION_LEVEL_6 = "6";
    const POSITION_LEVEL_7 = "7";
    const POSITION_LEVEL_8 = "8";
    const POSITION_LEVEL_9 = "9";
    const POSITION_LEVEL_10 = "10";

    const EDUCATED_1 = "1";
    const EDUCATED_2 = "2";
    const EDUCATED_3 = "3";
    const EDUCATED_4 = "4";
    const EDUCATED_5 = "5";
    const EDUCATED_6 = "6";
    const EDUCATED_7 = "7";
    const EDUCATED_8 = "8";

    const EDUCATED_TYPE_1 = "1";
    const EDUCATED_TYPE_2 = "2";
    const EDUCATED_TYPE_3 = "3";
    const EDUCATED_TYPE_4 = "4";
    const EDUCATED_TYPE_5 = "5";
    const EDUCATED_TYPE_6 = "6";

    const MARRIED_TYPE_1 = "1";
    const MARRIED_TYPE_2 = "2";
    const MARRIED_TYPE_3 = "3";
    const MARRIED_TYPE_4 = "4";

    const CONTACT_RELATIONSHIP_1 = "1";
    const CONTACT_RELATIONSHIP_2 = "2";
    const CONTACT_RELATIONSHIP_3 = "3";
    const CONTACT_RELATIONSHIP_4 = "4";
    const CONTACT_RELATIONSHIP_5 = "5";
    const CONTACT_RELATIONSHIP_6 = "6";

    public static function getPositionList()
    {
        return array(
            //self::POSITION_DIRECTOR_CITY => "城市总监",
            //self::POSITION_DIRECTOR_OPERATION => "运营总监",
            //self::POSITION_DIRECTOR_HUMANRESOURCE => "人事总监",
            //self::POSITION_DIRECTOR_FINANCE => "财务总监",
            //self::POSITION_DIRECTOR_MARKETING => "市场总监",
            //self::POSITION_DIRECTOR_EDUCATION => "教学总监",
            //self::POSITION_REGIONAL_MANAGER => "区域经理",
            self::POSITION_HEADMASTER => "校长",
            self::POSITION_EDUCATE_MANAGER => "学管主管",
            self::POSITION_MARKETING => "市场专员",
            self::POSITION_MANAGER => "学管",
            self::POSITION_TEACH_MANAGER => "教学主管",
            self::POSITION_TEACHER => "教师",
            self::POSITION_HR_FINANCE => "财务人事"
        );
    }

    public static function getPositionLevelList()
    {
        return array(
            self::POSITION_LEVEL_0 => "无",
            self::POSITION_LEVEL_1 => "实习生",
            self::POSITION_LEVEL_2 => "专员级",
            self::POSITION_LEVEL_3 => "主管级",
            self::POSITION_LEVEL_4 => "高级主管/校长",
            self::POSITION_LEVEL_5 => "经理级/高级督导/教务校长",
            self::POSITION_LEVEL_6 => "高级经理级/区域督导/区域经理",
            self::POSITION_LEVEL_7 => "副总监级/总监",
            self::POSITION_LEVEL_8 => "高级总监级/事业部总经理",
            self::POSITION_LEVEL_9 => "VP/CTO",
            self::POSITION_LEVEL_10 => "执行董事"
        );
    }

    public static function getEducatedList()
    {
        return array(
            self::EDUCATED_1 => "博士",
            self::EDUCATED_2 => "硕士",
            self::EDUCATED_3 => "本科",
            self::EDUCATED_4 => "专科",
            self::EDUCATED_5 => "高中",
            self::EDUCATED_6 => "职高",
            self::EDUCATED_7 => "初中",
            self::EDUCATED_8 => "小学"
        );
    }

    public static function getEducatedTypeList()
    {
        return array(
            self::EDUCATED_TYPE_1 => "统招",
            self::EDUCATED_TYPE_2 => "自考",
            self::EDUCATED_TYPE_3 => "专接本",
            self::EDUCATED_TYPE_4 => "成人高考",
            self::EDUCATED_TYPE_5 => "3+2",
            self::EDUCATED_TYPE_6 => "其他"
        );
    }

    public static function getMarriedTypeList()
    {
        return array(
            self::MARRIED_TYPE_1 => "未婚",
            self::MARRIED_TYPE_2 => "已婚",
            self::MARRIED_TYPE_3 => "离异",
            self::MARRIED_TYPE_4 => "丧偶"
        );
    }

    public static function getContactRelationshipList()
    {
        return array(
            self::CONTACT_RELATIONSHIP_1 => "父亲",
            self::CONTACT_RELATIONSHIP_2 => "母亲",
            self::CONTACT_RELATIONSHIP_3 => "配偶",
            self::CONTACT_RELATIONSHIP_4 => "兄弟姐妹",
            self::CONTACT_RELATIONSHIP_5 => "朋友",
            self::CONTACT_RELATIONSHIP_6 => "无"
        );
    }
}
?>