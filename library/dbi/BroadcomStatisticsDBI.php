<?php

/**
 * 数据库操作类-*
 * @author Kinsama
 * @version 2020-03-10
 */
class BroadcomStatisticsDBI
{

    public static function selectOrderList($start_date, $end_date, $school_id, $member_list = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT order_id," .
               " order_number," .
               " achieve_type," .
               " school_id," .
               " student_id," .
               " order_payable," .
               " order_payment," .
               " order_debt," .
               " order_status," .
               " operated_by," .
               " insert_date" .
               " FROM order_info" .
               " WHERE del_flg = 0" .
               " AND insert_date >= " . $dbi->quote($start_date) .
               " AND insert_date <= " . $dbi->quote($end_date) .
               " AND school_id = " . $school_id;
        if (!is_null($member_list)) {
            $sql .= " AND operated_by IN (" . implode(", ", $member_list) . ")";
        }
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["order_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectOrderItemCount($start_date, $end_date, $school_id, $member_list = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT achieve_type," .
               " COUNT(*) AS order_count," .
               " SUM(order_item_payable_amount) AS order_amount" .
               " FROM order_item_info" .
               " WHERE del_flg = 0" .
               " AND main_order_item_id IS NULL" .
               // TODO 全部订单？
               " AND order_item_status IN (2, 3, 4)" .
               " AND insert_date >= " . $dbi->quote($start_date) .
               " AND insert_date <= " . $dbi->quote($end_date) .
               " AND school_id = " . $school_id;
        if (!is_null($member_list)) {
            $sql .= " AND assign_member_id IN (" . implode(", ", $member_list) . ")";
        }
        $sql .= " GROUP BY achieve_type";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["achieve_type"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectCancelOrderItemCount($start_date, $end_date, $school_id, $member_list = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM order_item_info" .
               " WHERE del_flg = 0" .
               " AND main_order_item_id IS NULL" .
               " AND order_item_status = 4" .
               " AND update_date >= " . $dbi->quote($start_date) .
               " AND update_date <= " . $dbi->quote($end_date) .
               " AND school_id = " . $school_id;
        if (!is_null($member_list)) {
            $sql .= " AND assign_member_id IN (" . implode(", ", $member_list) . ")";
        }
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["order_item_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectCourseStats($start_date, $end_date, $school_id, $member_list = null, $teacher_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT c.actual_start_date," .
               " c.teacher_member_id," .
               " c.course_type," .
               " c.multi_course_id," .
               " MAX(c.actual_course_hours) AS course_hours," .
               " c.course_type," .
               " i.item_type," .
               " i.item_method" .
               " FROM course_info c" .
               " LEFT OUTER JOIN item_info i ON i.item_id = c.item_id" .
               " WHERE c.del_flg = 0" .
               " AND c.confirm_flg = 1" .
               " AND c.actual_start_date >= " . $dbi->quote($start_date) .
               " AND c.actual_start_date <= " . $dbi->quote($end_date) .
               " AND c.school_id = " . $school_id;
        if (!is_null($member_list)) {
            if ($teacher_flg) {
                $sql .= " AND teacher_member_id IN (" . implode(", ", $member_list) . ")";
            } else {
                $sql .= " AND assign_member_id IN (" . implode(", ", $member_list) . ")";
            }
        }
        $sql .= " GROUP BY c.actual_start_date, c.teacher_member_id";
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

    public static function selectCourseStatsDetail($start_date, $end_date, $school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT c.course_id," .
               " c.school_id," .
               " c.multi_course_id," .
               " c.actual_course_hours," .
               " c.course_trans_price," .
               " c.course_type," .
               " i.item_type," .
               " i.item_method" .
               " FROM course_info c" .
               " LEFT OUTER JOIN item_info i ON i.item_id = c.item_id" .
               " WHERE c.del_flg = 0" .
               " AND c.confirm_flg = 1" .
               " AND c.actual_start_date >= " . $dbi->quote($start_date) .
               " AND c.actual_start_date <= " . $dbi->quote($end_date) .
               " AND c.school_id = " . $school_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["school_id"]][$row["course_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectOrderItemBySchool($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM order_item_info WHERE del_flg = 0" .
               " AND school_id = " . $school_id .
               " AND order_item_status = " . BroadcomOrderEntity::ORDER_ITEM_STATUS_2 .
               " ORDER BY student_id ASC, order_item_id ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["student_id"]][$row["order_item_id"]] = $row;
        }
        $result->free();
        return $data;
    }
}
?>