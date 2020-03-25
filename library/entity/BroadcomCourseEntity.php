<?php

/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-02-26
 */
class BroadcomCourseEntity
{
    //const COURSE_TYPE_AUDITION = "1";
    //const COURSE_TYPE_SINGLE = "2";
    //const COURSE_TYPE_MULTI = "3";
    //const COURSE_TYPE_CLASS = "4";
    const COURSE_TYPE_SINGLE = "1";
    const COURSE_TYPE_DOUBLE = "2";
    const COURSE_TYPE_TRIBLE = "3";
    const COURSE_TYPE_CLASS = "4";
    const COURSE_TYPE_AUDITION_SOLO = "5";
    const COURSE_TYPE_AUDITION_SQUAD = "6";

    const COURSE_RESET_REASON_CODE_1 = "1";
    const COURSE_RESET_REASON_CODE_2 = "2";

    public static function getCourseTypeList()
    {
        return array(
            //self::COURSE_TYPE_AUDITION => "试听课",
            //self::COURSE_TYPE_SINGLE => "一对一课",
            //self::COURSE_TYPE_MULTI => "一对多课",
            //self::COURSE_TYPE_CLASS => "班课"
            self::COURSE_TYPE_SINGLE => "一对一课",
            self::COURSE_TYPE_DOUBLE => "一对二课",
            self::COURSE_TYPE_TRIBLE => "一对三课",
            self::COURSE_TYPE_CLASS => "班课",
            self::COURSE_TYPE_AUDITION_SOLO => "一对一试听课",
            self::COURSE_TYPE_AUDITION_SQUAD => "一对多试听课"
        );
    }

    public static function getCourseResetReasonCodeList()
    {
        return array(
            self::COURSE_RESET_REASON_CODE_1 => "主动返课",
            self::COURSE_RESET_REASON_CODE_2 => "被动返课"
        );
    }
}
?>