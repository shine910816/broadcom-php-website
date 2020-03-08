<?php

/**
 * 数据库操作类-refund_info
 * @author Kinsama
 * @version 2020-03-07
 */
class BroadcomRefundDBI
{

    public static function selectRefundInfoList($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT r.order_item_id," .
               " r.refund_type," .
               " r.school_id," .
               " r.refund_precent," .
               " r.oppo_student_id," .
               " r.refund_examine_flg," .
               " r.refund_examine_id," .
               " r.refund_examine_date," .
               " i.item_id," .
               " i.item_name," .
               " i.item_type," .
               " i.item_method," .
               " oi.contract_number," .
               " oi.order_id," .
               " oi.order_item_status," .
               " oi.order_item_payable_amount," .
               " oi.order_item_amount," .
               " oi.order_item_remain," .
               " s.student_id," .
               " s.student_name," .
               " s.student_mobile_number" .
               " FROM refund_info r" .
               " LEFT OUTER JOIN order_item_info oi ON oi.order_item_id = r.order_item_id" .
               " LEFT OUTER JOIN item_info i ON i.item_id = oi.item_id" .
               " LEFT OUTER JOIN student_info s ON s.student_id = oi.student_id" .
               " WHERE r.del_flg = 0" .
               " AND s.del_flg = 0" .
               " AND oi.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND r.school_id = " . $school_id .
               " AND r.refund_examine_flg = 0";
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

    public static function insertRefund($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("refund_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateRefund($update_data, $order_item_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("refund_info", $update_data, "order_item_id = " . $order_item_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function removeRefund($order_item_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->delete("refund_info", "order_item_id = " . $order_item_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>