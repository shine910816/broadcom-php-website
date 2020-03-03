<?php

/**
 * 数据库操作类-student_info
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomStudentInfoDBI
{

    public static function selectStudentInfo($student_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM student_info WHERE del_flg = 0 AND student_id = " . $student_id . " LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
        if (count($data) == 1) {
            return $data[0];
        }
        return $data;
    }

    public static function selectLeadsStudentInfo($school_id, $member_id = null, $follow_status = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT student_id," .
               " student_name," .
               " student_mobile_number," .
               " student_level," .
               " student_entrance_year," .
               " media_channel_code," .
               " purpose_level," .
               " follow_status," .
               " student_school_name," .
               " member_id," .
               " accept_date," .
               " operated_by," .
               " insert_date" .
               " FROM student_info" .
               " WHERE del_flg = 0" .
               " AND school_id = " . $school_id;
        if (!is_null($member_id)) {
            $sql .= " AND member_id = " . $member_id;
        }
        if (!is_null($follow_status)) {
            if (!is_array($follow_status)) {
                $follow_status = array($follow_status);
            }
            $sql .= " AND follow_status IN (" . implode(", ", $follow_status) . ")";
        }
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["student_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectStudentMobileNumber($student_mobile_number)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM student_info WHERE del_flg = 0" .
               " AND student_mobile_number = " . $dbi->quote($student_mobile_number) . " LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["COUNT(*)"];
        }
        $result->free();
        return $data[0];
    }

    public static function insertStudentInfo($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("student_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateStudentInfo($update_data, $student_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("student_info", $update_data, "student_id = " . $student_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>