<?php

/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-02-26
 */
class BroadcomCourseEntity
{
    const COURSE_TYPE_AUDITION = "1";
    const COURSE_TYPE_SINGLE = "2";
    const COURSE_TYPE_MULTI = "3";
    const COURSE_TYPE_CLASS = "4";

    public static function getCourseTypeList()
    {
        return array(
            self::COURSE_TYPE_AUDITION => "试听课",
            self::COURSE_TYPE_SINGLE => "一对一课",
            self::COURSE_TYPE_MULTI => "一对多课",
            self::COURSE_TYPE_CLASS => "班课"
        );
    }
}
?>