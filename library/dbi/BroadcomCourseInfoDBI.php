<?php

/**
 * 数据库操作类-course_info
 * @author Kinsama
 * @version 2020-02-25
 */
class BroadcomCourseInfoDBI
{

    public static function selectAuditionCourseInfoByStudent($student_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0" .
               " AND student_id = " . $student_id .
               " AND course_type = " . BroadcomCourseEntity::COURSE_TYPE_AUDITION;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["course_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectCourseInfoByOrderItem($order_item_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0 AND order_item_id = " . $order_item_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["course_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectMultiCourseInfoByItem($item_id, $student_id = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0 AND item_id = " . $item_id;
        if (!is_null($student_id)) {
            $sql .= " AND student_id != " . $student_id;
        }
        $sql .= " AND student_id != " . $student_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["course_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function insertCourseInfo($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("course_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateCourseInfo($update_data, $course_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("course_info", $update_data, "course_id = " . $course_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>