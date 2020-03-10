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
               " AND order_item_status IN (2, 3)" .
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
}
?>