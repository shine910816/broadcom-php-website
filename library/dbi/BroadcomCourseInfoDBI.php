<?php

/**
 * 数据库操作类-course_info
 * @author Kinsama
 * @version 2020-02-25
 */
class BroadcomCourseInfoDBI
{

    public static function selectCourseInfo($course_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0 AND course_id = " . $course_id . " LIMIT 1";
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

    public static function selectCourseInfoByMember($member_id, $course_date_from, $course_date_to, $teacher_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0";
        if ($teacher_flg) {
            $sql .= " AND teacher_member_id = " . $member_id;
        } else {
            $sql .= " AND operated_by = " . $member_id;
        }
        $sql .= " AND course_start_date <= " . $dbi->quote($course_date_to);
        $sql .= " AND course_expire_date >= " . $dbi->quote($course_date_from);
        $sql .= " ORDER BY confirm_flg ASC, course_start_date ASC";
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

    public static function selectCourseInfoBySchool($school_id, $course_date_from, $course_date_to)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0" .
               " AND school_id = " . $school_id .
               " AND course_start_date <= " . $dbi->quote($course_date_to) .
               " AND course_expire_date >= " . $dbi->quote($course_date_from) .
               " ORDER BY confirm_flg ASC, course_start_date ASC";
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

    public static function selectCourseInfoByStudent($student_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0 AND student_id = " . $student_id .
               " ORDER BY confirm_flg ASC, course_start_date ASC";
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

    public static function selectClassCourseInfoBySchedule($schedule_id, $schedule_index)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT c.course_id," .
               " c.order_item_id," .
               " c.course_hours," .
               " oi.order_item_trans_price," .
               " oi.order_item_status," .
               " oi.order_item_remain" .
               " FROM course_info c" .
               " LEFT OUTER JOIN order_item_info oi ON oi.order_item_id = c.order_item_id" .
               " WHERE c.del_flg = 0" .
               " AND oi.del_flg = 0" .
               " AND c.course_type = " . BroadcomCourseEntity::COURSE_TYPE_CLASS .
               " AND c.schedule_id = " . $schedule_id .
               " AND c.schedule_index = " . $schedule_index;
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

    public static function selectAuditionCourseInfoByStudent($student_id)
    {
        $dbi = Database::getInstance();
        $audition_type_list = array(
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
        );
        $sql = "SELECT * FROM course_info WHERE del_flg = 0 AND student_id = " . $student_id .
               " AND course_type IN (" . implode(", ", $audition_type_list) .
               ") ORDER BY course_start_date ASC";
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
        $sql = "SELECT * FROM course_info WHERE del_flg = 0" .
               " AND order_item_id = " . $order_item_id .
               " ORDER BY course_start_date ASC";
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

    public static function selectMultiCourseInfoByItem($student_id, $start_date, $item_id = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0" .
               " AND student_id != " . $student_id .
               " AND course_start_date >= " . $dbi->quote($start_date);
        if (!is_null($item_id)) {
            $sql .= " AND item_id = " . $item_id;
        } else {
            $sql .= " AND course_type = " . BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD;
        }
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

    public static function selectResetCourseInfo($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_info WHERE del_flg = 0" .
               " AND confirm_flg = 1 AND reset_flg != 0 AND school_id = " . $school_id;
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
        $result = $dbi->update("course_info", $update_data, "course_id = " . $course_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>