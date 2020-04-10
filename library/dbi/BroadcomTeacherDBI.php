<?php

/**
 * 数据库操作类-teacher_*
 * @author Kinsama
 * @version 2020-02-15
 */
class BroadcomTeacherDBI
{

    public static function selectSchoolTeacherList($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM teacher_info WHERE del_flg = 0 AND school_id = " . $school_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["subject_id"]][] = $row["member_id"];
        }
        $result->free();
        return $data;
    }

    public static function selectSchoolTeacherSubjectList($school_id, $member_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM teacher_info WHERE del_flg = 0" .
               " AND school_id = " . $school_id . " AND member_id = " . $member_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["subject_id"];
        }
        $result->free();
        return $data;
    }

    public static function selectTeacherInfoList($school_id = null)
    {
        $dbi = Database::getInstance();
        $position_arr = array(
            BroadcomMemberEntity::POSITION_TEACH_MANAGER,
            BroadcomMemberEntity::POSITION_TEACHER,
            BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER
        );
        $sql = "SELECT i.member_id," .
               " i.m_name," .
               " p.school_id," .
               " p.member_position," .
               " p.member_employed_status," .
               " i.m_primary_star_level," .
               " i.m_junior_star_level," .
               " i.m_senior_star_level," .
               " i.m_licence_number" .
               " FROM member_info i" .
               " LEFT OUTER JOIN member_position p ON p.member_id = i.member_id" .
               " WHERE i.del_flg = 0" .
               " AND p.del_flg = 0" .
               " AND p.member_position IN (" . implode(", ", $position_arr) .
               ") AND p.member_employed_status != 0";
        if (!is_null($school_id)) {
            $sql .= " AND school_id = " . $school_id;
        }
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["member_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function insertTeacher($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("teacher_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function removeTeacher($school_id, $member_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->delete("teacher_info", "school_id = " . $school_id . " AND member_id = " . $member_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>