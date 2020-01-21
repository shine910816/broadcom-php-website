<?php

/**
 * 数据库应用类-menber_*
 * @author Kinsama
 * @version 2020-01-21
 */
class BroadcomMenberInfoEntity
{
    const POSITION_LEVEL_DIRECTOR_CITY = "1100";
    const POSITION_LEVEL_DIRECTOR_OPERATION = "1110";
    const POSITION_LEVEL_DIRECTOR_HUMANRESOURCE = "1120";
    const POSITION_LEVEL_DIRECTOR_FINANCE = "1130";
    const POSITION_LEVEL_DIRECTOR_MARKETING = "1140";
    const POSITION_LEVEL_DIRECTOR_EDUCATION = "1150";
    const POSITION_LEVEL_REGIONAL_MANAGER = "2100";
    const POSITION_LEVEL_HEADMASTER = "3100";
    const POSITION_LEVEL_EDUCATE_MANAGER = "3110";
    const POSITION_LEVEL_MARKETING = "3111";
    const POSITION_LEVEL_MANAGER = "3112";
    const POSITION_LEVEL_TEACH_MANAGER = "3120";
    const POSITION_LEVEL_TEACHER = "3121";
    const POSITION_LEVEL_HR_FINANCE = "3200";

    public static function getPositionLevelList()
    {
        return array(
            self::POSITION_LEVEL_DIRECTOR_CITY => "城市总监",
            self::POSITION_LEVEL_DIRECTOR_OPERATION => "运营总监",
            self::POSITION_LEVEL_DIRECTOR_HUMANRESOURCE => "人事总监",
            self::POSITION_LEVEL_DIRECTOR_FINANCE => "财务总监",
            self::POSITION_LEVEL_DIRECTOR_MARKETING => "市场总监",
            self::POSITION_LEVEL_DIRECTOR_EDUCATION => "教学总监",
            self::POSITION_LEVEL_REGIONAL_MANAGER => "区域经理",
            self::POSITION_LEVEL_HEADMASTER => "校长",
            self::POSITION_LEVEL_EDUCATE_MANAGER => "学管主管",
            self::POSITION_LEVEL_MARKETING => "市场专员",
            self::POSITION_LEVEL_MANAGER => "学管",
            self::POSITION_LEVEL_TEACH_MANAGER => "教学主管",
            self::POSITION_LEVEL_TEACHER => "教师",
            self::POSITION_LEVEL_HR_FINANCE> = "财务人事"
        );
    }
}
?>