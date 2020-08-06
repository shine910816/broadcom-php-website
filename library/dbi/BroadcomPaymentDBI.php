<?php

/**
 * 数据库操作类-payment_info
 * @author Kinsama
 * @version 2020-02-16
 */
class BroadcomPaymentDBI
{

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