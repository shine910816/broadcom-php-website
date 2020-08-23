<?php

/**
 * 数据库应用类-student_*
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomStudentEntity
{
    const GRADE_BEFORE = "0";
    const GRADE_1 = "1";
    const GRADE_2 = "2";
    const GRADE_3 = "3";
    const GRADE_4 = "4";
    const GRADE_5 = "5";
    const GRADE_6 = "6";
    const GRADE_JUNIOR_1 = "7";
    const GRADE_JUNIOR_2 = "8";
    const GRADE_JUNIOR_3 = "9";
    const GRADE_SENIOR_1 = "10";
    const GRADE_SENIOR_2 = "11";
    const GRADE_SENIOR_3 = "12";
    const GRADE_AFTER = "13";

    const STUDENT_LEVEL_NONE = "0";
    const STUDENT_LEVEL_1 = "1";

    const MEDIA_CHANNEL_1_1 = "1";
    const MEDIA_CHANNEL_1_2 = "2";
    const MEDIA_CHANNEL_1_3 = "3";
    const MEDIA_CHANNEL_1_4 = "4";
    const MEDIA_CHANNEL_1_5 = "5";

    const PURPOSE_LEVEL_HIGH = "3";
    const PURPOSE_LEVEL_MIDDLE = "2";
    const PURPOSE_LEVEL_LOW = "1";
    const PURPOSE_LEVEL_NONE = "0";

    const RELATIVES_TYPE_1 = "1";
    const RELATIVES_TYPE_2 = "2";
    const RELATIVES_TYPE_3 = "3";
    const RELATIVES_TYPE_4 = "4";
    const RELATIVES_TYPE_5 = "5";
    const RELATIVES_TYPE_6 = "6";

    const FOLLOW_STATUS_1 = "1";
    const FOLLOW_STATUS_2 = "2";
    const FOLLOW_STATUS_3 = "3";

    const INREAD_STATUS_0 = "0";
    const INREAD_STATUS_1 = "1";
    const INREAD_STATUS_2 = "2";
    const INREAD_STATUS_3 = "3";

    public static function getColumnNames()
    {
        return array(
            "student_name" => "学员姓名",
            "student_gender" => "性别",
            "student_entrance_year" => "入学年",
            "student_mobile_number" => "手机号",
            "media_channel_code" => "一级渠道",
            "student_school_name" => "在读学校",
            "student_address" => "家庭住址",
            "purpose_level" => "意向程度",
            "student_relatives_type" => "亲属关系",
            "student_relatives_name" => "家长姓名",
            "student_relatives_mobile_number" => "家长电话"
        );
    }

    public static function getAdjustedYear()
    {
        $result = date("Y");
        if (date("nd") < "901") {
            return $result - 1;
        }
        return $result;
    }

    public static function getGradeList()
    {
        return array(
            self::GRADE_BEFORE => "幼小年级",
            self::GRADE_1 => "小学一年级",
            self::GRADE_2 => "小学二年级",
            self::GRADE_3 => "小学三年级",
            self::GRADE_4 => "小学四年级",
            self::GRADE_5 => "小学五年级",
            self::GRADE_6 => "小学六年级",
            self::GRADE_JUNIOR_1 => "初中一年级",
            self::GRADE_JUNIOR_2 => "初中二年级",
            self::GRADE_JUNIOR_3 => "初中三年级",
            self::GRADE_SENIOR_1 => "高中一年级",
            self::GRADE_SENIOR_2 => "高中二年级",
            self::GRADE_SENIOR_3 => "高中三年级"
        );
    }

    public static function getGradeName($entrance_year, $output_key = false)
    {
        $diff_year = self::getAdjustedYear() - $entrance_year + 1;
        $grade_key = $diff_year;
        if ($diff_year < self::GRADE_BEFORE) {
            $grade_key = self::GRADE_BEFORE;
        } elseif ($diff_year > self::GRADE_SENIOR_3) {
            $grade_key = self::GRADE_AFTER;
        }
        if ($output_key) {
            return $grade_key;
        } else {
            if ($grade_key == self::GRADE_AFTER) {
                return "高中毕业";
            } else {
                $grade_list = self::getGradeList();
                return $grade_list[$grade_key];
            }
        }
    }

    public static function getMediaChannelList()
    {
        return array(
            self::MEDIA_CHANNEL_1_1 => "课程顾问介绍",
            self::MEDIA_CHANNEL_1_2 => "智能营销媒体",
            self::MEDIA_CHANNEL_1_3 => "市场",
            self::MEDIA_CHANNEL_1_4 => "学习顾问推荐",
            self::MEDIA_CHANNEL_1_5 => "教师推荐"
        );
    }

    public static function getPurposeLevelList()
    {
        return array(
            self::PURPOSE_LEVEL_HIGH => "高",
            self::PURPOSE_LEVEL_MIDDLE => "中",
            self::PURPOSE_LEVEL_LOW => "低",
            self::PURPOSE_LEVEL_NONE => "未标记"
        );
    }

    public static function getRelativesTypeList()
    {
        return array(
            self::RELATIVES_TYPE_1 => "父亲",
            self::RELATIVES_TYPE_2 => "母亲",
            self::RELATIVES_TYPE_3 => "祖父",
            self::RELATIVES_TYPE_4 => "祖母",
            self::RELATIVES_TYPE_5 => "外祖父",
            self::RELATIVES_TYPE_6 => "外祖母"
        );
    }

    public static function getFollowStatusList()
    {
        return array(
            self::FOLLOW_STATUS_1 => "待跟进",
            self::FOLLOW_STATUS_2 => "已试听",
            self::FOLLOW_STATUS_3 => "已签单"
        );
    }

    public static function getStudentLevelList()
    {
        return array(
            self::STUDENT_LEVEL_NONE => "非会员",
            self::STUDENT_LEVEL_1 => "会员"
        );
    }

    public static function getInreadStatusList()
    {
        return array(
            self::INREAD_STATUS_0 => "新人",
            self::INREAD_STATUS_1 => "在读",
            self::INREAD_STATUS_2 => "停课",
            self::INREAD_STATUS_3 => "结课"
        );
    }
}
?>