<?php

/**
 * 数据库操作类-*
 * @author Kinsama
 * @version 2020-03-10
 */
class BroadcomStatisticsDBI
{

    public static function selectOrderItemCount($start_date, $end_date, $school_id)
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
               " AND school_id = " . $school_id .
               " GROUP BY achieve_type";
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

    public static function selectCancelOrderItemCount($start_date, $end_date, $school_id)
    {
        $dbi = Database::getInstance();
        //$sql = "SELECT achieve_type," .
        //       " COUNT(*) AS cancel_order_count," .
        //       " SUM(order_item_payable_amount) AS cancel_order_amount" .
        //       " FROM order_item_info" .
        //       " WHERE del_flg = 0" .
        //       " AND main_order_item_id IS NULL" .
        //       " AND order_item_status = 4" .
        //       " AND update_date >= " . $dbi->quote($start_date) .
        //       " AND update_date <= " . $dbi->quote($end_date) .
        //       " AND school_id = " . $school_id .
        //       " GROUP BY achieve_type";
        $sql = "SELECT * FROM order_item_info" .
               " WHERE del_flg = 0" .
               " AND main_order_item_id IS NULL" .
               " AND order_item_status = 4" .
               " AND update_date >= " . $dbi->quote($start_date) .
               " AND update_date <= " . $dbi->quote($end_date) .
               " AND school_id = " . $school_id;
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

    public static function selectCourseStats($start_date, $end_date, $school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT actual_start_date," .
               " teacher_member_id," .
               " course_type," .
               " MAX(actual_course_hours)" .
               " FROM course_info" .
               " WHERE del_flg = 0" .
               " AND confirm_flg = 1" .
               " AND reset_flg = 0" .
               " AND reset_examine_flg = 0" .
               " AND actual_start_date >= " . $dbi->quote($start_date) .
               " AND actual_start_date <= " . $dbi->quote($end_date) .
               " AND school_id = " . $school_id .
               " GROUP BY actual_start_date, teacher_member_id";
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
}
?>