<?php

/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomSubjectEntity
{
    const SUBJECT_CHINESE = "1";
    const SUBJECT_MATHS = "2";
    const SUBJECT_ENGLISH = "3";
    const SUBJECT_HISTORY = "4";
    const SUBJECT_POLITICS = "5";
    const SUBJECT_GEOGRAPHY = "6";
    const SUBJECT_PHYSICS = "7";
    const SUBJECT_CHEMISTRY = "8";
    const SUBJECT_BIOLOGY = "9";
    const SUBJECT_OTHER = "0";

    public static function getSubjectList()
    {
        return array(
            self::SUBJECT_CHINESE => "语文",
            self::SUBJECT_MATHS => "数学",
            self::SUBJECT_ENGLISH => "英语",
            self::SUBJECT_HISTORY => "历史",
            self::SUBJECT_POLITICS => "政治",
            self::SUBJECT_GEOGRAPHY => "地理",
            self::SUBJECT_PHYSICS => "物理",
            self::SUBJECT_CHEMISTRY => "化学",
            self::SUBJECT_BIOLOGY => "生物",
            self::SUBJECT_OTHER => "其他"
        );
    }
}
?>