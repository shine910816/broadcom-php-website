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
               " p.payment_amount," .
               " p.operated_by," .
               " p.insert_date" .
               " FROM payment_info p" .
               " LEFT OUTER JOIN order_info o ON o.order_id = p.order_id" .
               " LEFT OUTER JOIN order_item_info oi ON oi.order_item_id = p.order_item_id" .
               " WHERE p.del_flg = 0" .
               " AND p.order_id = " . $order_id .
               " AND o.del_flg = 0" .
               " ORDER BY p.payment_id ASC";
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
               " p.student_id," .
               " p.order_id," .
               " o.order_status," .
               " o.order_number," .
               " o.achieve_type," .
               " o.insert_date AS order_create_date," .
               " o.order_examine_flg," .
               " o.order_examine_date," .
               " p.payment_amount," .
               " p.operated_by," .
               " p.insert_date" .
               " FROM payment_info p" .
               " LEFT OUTER JOIN order_info o ON o.order_id = p.order_id" .
               " WHERE p.del_flg = 0" .
               " AND o.del_flg = 0" .
               " AND o.school_id = " . $school_id .
               " AND p.insert_date >= " . $dbi->quote($start_date) .
               " AND p.insert_date <= " . $dbi->quote($expire_date);
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array(
            "detail" => array(),
            "ids" => array()
        );
        while ($row = $result->fetch_assoc()) {
            // TODO
            //$data["detail"][$row["payment_id"]] = $row;
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