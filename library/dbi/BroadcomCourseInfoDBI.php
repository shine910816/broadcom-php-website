<?php

/**
 * 数据库操作类-course_info
 * @author Kinsama
 * @version 2020-02-25
 */
class BroadcomCourseInfoDBI
{

    public static function selectCourseInfo($course_id, $multi_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT c.course_id," .
               " c.multi_course_id," .
               " c.course_type," .
               " c.audition_type," .
               " c.school_id," .
               " c.student_id," .
               " s.student_name," .
               " s.student_mobile_number," .
               " s.student_entrance_year," .
               " s.audition_hours," .
               " s.follow_status," .
               " c.order_item_id," .
               " oi.contract_number," .
               " oi.order_item_amount," .
               " oi.order_item_status," .
               " oi.order_item_remain," .
               " oi.order_item_arrange," .
               " oi.order_item_confirm," .
               " oi.assign_member_id AS `order_assign_member_id`," .
               " c.item_id," .
               " i.item_name," .
               " c.teacher_member_id," .
               " c.subject_id," .
               " c.course_trans_price," .
               " c.course_start_date," .
               " c.course_expire_date," .
               " c.course_hours," .
               " c.actual_start_date," .
               " c.actual_expire_date," .
               " c.actual_course_hours," .
               " c.confirm_flg," .
               " c.confirm_member_id," .
               " c.confirm_date," .
               " c.assign_member_id," .
               " c.assign_date," .
               " c.operated_by," .
               " c.insert_date" .
               " FROM course_info c" .
               " LEFT OUTER JOIN student_info s ON s.student_id = c.student_id" .
               " LEFT OUTER JOIN order_item_info oi ON oi.order_item_id = c.order_item_id" .
               " LEFT OUTER JOIN item_info i ON i.item_id = c.item_id" .
               " WHERE c.del_flg = 0" .
               " AND s.del_flg = 0";
        if ($multi_flg) {
            $sql .= " AND c.multi_course_id = " . $dbi->quote($course_id);
        } else {
            $sql .= " AND c.course_id = " . $course_id;
        }
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
        $sql = "SELECT c.course_id," .
               " c.course_type," .
               " c.audition_type," .
               " c.multi_course_id," .
               " c.student_id," .
               " c.school_id," .
               " c.assign_member_id," .
               " c.item_id," .
               " i.item_name," .
               " c.order_item_id," .
               " o.contract_number," .
               " o.order_item_amount," .
               " o.order_item_status," .
               " o.order_item_remain," .
               " o.order_item_arrange," .
               " o.order_item_confirm," .
               " o.assign_member_id AS `order_assign_member_id`," .
               " c.confirm_flg," .
               " c.course_start_date," .
               " c.course_expire_date," .
               " c.course_hours," .
               " c.actual_start_date," .
               " c.actual_expire_date," .
               " c.actual_course_hours," .
               " c.teacher_member_id," .
               " c.subject_id," .
               " c.confirm_member_id," .
               " c.confirm_date," .
               " c.course_trans_price" .
               " FROM course_info c" .
               " LEFT OUTER JOIN item_info i ON i.item_id = c.item_id" .
               " LEFT OUTER JOIN order_item_info o ON o.order_item_id = c.order_item_id" .
               " WHERE c.del_flg = 0" .
               " AND c.school_id = " . $school_id .
               " AND c.course_start_date <= " . $dbi->quote($course_date_to) .
               " AND c.course_expire_date >= " . $dbi->quote($course_date_from) .
               " ORDER BY c.course_start_date DESC, c.course_id DESC";
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

    public static function selectResetCourseInfo($course_id, $multi_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM course_reset_info WHERE del_flg = 0";
        if ($multi_flg) {
            $sql .= " AND multi_course_id = " . $dbi->quote($course_id);
        } else {
            $sql .= " AND course_id = " . $course_id;
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

    public static function selectResetCourseList($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT r.course_id," .
               " c.multi_course_id," .
               " c.course_type," .
               " c.audition_type," .
               " c.student_id," .
               " c.order_item_id," .
               " c.item_id," .
               " i.item_name," .
               " c.teacher_member_id," .
               " c.subject_id," .
               " c.actual_start_date," .
               " c.actual_expire_date," .
               " c.confirm_member_id," .
               " c.confirm_date," .
               " r.insert_date" .
               " FROM course_reset_info r" .
               " LEFT OUTER JOIN course_info c ON c.course_id = r.course_id" .
               " LEFT OUTER JOIN item_info i ON i.item_id = c.item_id" .
               " WHERE r.del_flg = 0" .
               " AND r.school_id = " . $school_id .
               " AND c.del_flg = 0" .
               " AND r.reset_confirm_flg = 0" .
               " ORDER BY r.insert_date ASC";
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

    public static function selectCourseInfoForPositionChange($school_id, $member_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT COUNT(*) AS `count` FROM course_info" .
               " WHERE del_flg = 0" .
               " AND teacher_member_id = " . $member_id .
               " AND school_id = " . $school_id .
               " AND confirm_flg = 0" .
               " LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["count"];
        }
        $result->free();
        if (count($data) == 1) {
            return $data[0];
        }
        return false;
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

    public static function deleteMultiCourse($student_id, $order_item_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update(
            "course_info",
            array("del_flg" => "1"),
            "order_item_id = " . $order_item_id . " AND student_id = " . $student_id . " AND confirm_flg = 0"
        );
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function deleteMultiCourseById($course_id)
    {
        $dbi = Database::getInstance();
        if (!is_array($course_id)) {
            $course_id = array($course_id);
        }
        $result = $dbi->update("course_info", array("del_flg" => "1"), "course_id IN (" . implode(", ", $course_id) . ")");
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function insertCourseReset($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("course_reset_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateCourseReset($update_data, $course_id)
    {
        $dbi = Database::getInstance();
        if (!is_array($course_id)) {
            $course_id = array($course_id);
        }
        $result = $dbi->update("course_reset_info", $update_data, "course_id IN (" . implode(", ", $course_id) . ")");
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>