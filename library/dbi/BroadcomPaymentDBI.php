<?php

/**
 * 数据库操作类-payment_info
 * @author Kinsama
 * @version 2020-02-16
 */
class BroadcomPaymentDBI
{

    public static function selectPaymentDetail($order_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT p.payment_id," .
               " p.student_id," .
               " p.order_id," .
               " o.order_number," .
               " p.order_item_id," .
               " oi.contract_number," .
               " p.payment_status," .
               " p.payment_amount," .
               " p.operated_by," .
               " p.insert_date" .
               " FROM payment_info p" .
               " LEFT OUTER JOIN order_info o ON o.order_id = p.order_id" .
               " LEFT OUTER JOIN order_item_info oi ON oi.order_item_id = p.order_item_id" .
               " WHERE p.del_flg = 0" .
               " AND p.order_id = " . $order_id .
               " AND o.del_flg = 0" .
               " ORDER BY p.insert_date ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["payment_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    /**
     * 查询订单账面流水
     * @param $order_id 订单ID
     */
    public static function selectSimplePaymentForOrder($order_id)
    {
        $dbi = Database::getInstance();
        if (!is_array($order_id)) {
            $order_id = array($order_id);
        }
        $sql = "SELECT * FROM payment_info WHERE del_flg = 0 AND order_id IN (" . implode(", ", $order_id) . ") ORDER BY insert_date ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["order_id"]][] = $row["payment_amount"];
        }
        $result->free();
        return $data;
    }

    public static function selectPaymentByDate($school_id, $start_date, $expire_date)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT p.payment_id," .
               " p.order_item_id," .
               " i.achieve_type," .
               " i.order_examine_flg," .
               " i.operated_by AS `creator_id`," .
               " i.order_examine_date AS `created_date`," .
               " oi.order_item_status," .
               " oi.order_item_payable_amount AS `payable_amount`," .
               " p.payment_status," .
               " p.payment_amount," .
               " p.operated_by AS `payment_operator`," .
               " p.insert_date AS `payment_date`" .
               " FROM payment_info p" .
               " LEFT OUTER JOIN order_item_info oi ON oi.order_item_id = p.order_item_id" .
               " LEFT OUTER JOIN order_info i ON i.order_id = p.order_id" .
               " WHERE p.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND oi.del_flg = 0" .
               " AND i.school_id = " . $school_id .
               " AND p.insert_date >= " . $dbi->quote($start_date) .
               " AND p.insert_date <= " . $dbi->quote($expire_date) .
               " ORDER BY p.order_item_id ASC," .
               " p.insert_date ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            if (!isset($data[$row["order_item_id"]])) {
                $data[$row["order_item_id"]] = array(
                    "order_item_id" => $row["order_item_id"],
                    "achieve_type" => $row["achieve_type"],
                    "order_item_status" => $row["order_item_status"],
                    "payable_amount" => $row["payable_amount"],
                    "creator_id" => $row["creator_id"],
                    "created_date" => $row["created_date"],
                    "payment_detail" => array()
                );
            }
            $data[$row["order_item_id"]]["payment_detail"][$row["payment_id"]] = array(
                "payment_id" => $row["payment_id"],
                "payment_status" => $row["payment_status"],
                "payment_amount" => $row["payment_amount"],
                "payment_operator" => $row["payment_operator"],
                "payment_date" => $row["payment_date"]
            );
        }
        $result->free();
        return $data;
    }

    public static function insertPayment($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("payment_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>