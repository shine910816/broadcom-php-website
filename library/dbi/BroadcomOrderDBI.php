<?php

/**
 * 数据库操作类-order_*
 * @author Kinsama
 * @version 2020-02-16
 */
class BroadcomOrderDBI
{

    public static function selectOrderInfo($order_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM order_info WHERE del_flg = 0 AND order_id = " . $order_id . " LIMIT 1";
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

    public static function selectOrderInfoByStudentId($student_id, $order_status = null)
    {
        $dbi = Database::getInstance();
        $where = "del_flg = 0 AND student_id = " . $student_id;
        if (!is_null($order_status)) {
            if (!is_array($order_status)) {
                $order_status = array($order_status);
            }
            $where = " AND order_status IN (" . implode(", ", $order_status) . ")";
        }
        $sql = "SELECT * FROM order_info WHERE " . $where;
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

    public static function selectOrderItemByOrderId($order_id)
    {
        $dbi = Database::getInstance();
        if (!is_array($order_id)) {
            $order_id = array($order_id);
        }
        $sql = "SELECT * FROM order_item_info WHERE del_flg = 0 AND order_id IN (" . implode(", ", $order_id) . ")";
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

    public static function selectOrderItem($order_item_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT oi.order_item_id," .
               " oi.contract_number," .
               " oi.order_id," .
               " o.order_number," .
               " oi.student_id," .
               " s.student_name," .
               " s.student_entrance_year," .
               " s.school_id," .
               " oi.item_id," .
               " i.item_name," .
               " i.item_method," .
               " i.item_labels," .
               " oi.order_item_trans_price" .
               " FROM order_item_info oi" .
               " LEFT OUTER JOIN order_info o ON o.order_id = oi.order_id" .
               " LEFT OUTER JOIN student_info s ON s.student_id = oi.student_id" .
               " LEFT OUTER JOIN item_info i ON i.item_id = oi.item_id" .
               " WHERE oi.del_flg = 0" .
               " AND o.del_flg = 0" .
               " AND s.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND oi.order_item_id = " . $order_item_id .
               " LIMIT 1";
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

    public static function selectOrderListByStatus($order_status)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM order_info WHERE del_flg = 0 AND order_status = " . $order_status;
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

    public static function selectOrderCountForCreate($current_date = null)
    {
        $dbi = Database::getInstance();
        if (is_null($current_date)) {
            $current_date = date("Y-m-d");
        }
        $start_date = $dbi->quote($current_date . " 00:00:00");
        $end_date = $dbi->quote($current_date . " 23:59:59");
        $sql = "SELECT COUNT(*) FROM order_info WHERE insert_date >= " . $start_date . " AND insert_date <= " . $end_date . " LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["COUNT(*)"];
        }
        $result->free();
        return $data[0];
    }

    public static function selectOrderItemCountForCreate($current_date = null)
    {
        $dbi = Database::getInstance();
        if (is_null($current_date)) {
            $current_date = date("Y-m-d");
        }
        $start_date = $dbi->quote($current_date . " 00:00:00");
        $end_date = $dbi->quote($current_date . " 23:59:59");
        $sql = "SELECT COUNT(*) FROM order_item_info WHERE insert_date >= " . $start_date .
               " AND insert_date <= " . $end_date . " AND main_order_item_id IS NULL LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["COUNT(*)"];
        }
        $result->free();
        return $data[0];
    }

    public static function selectPresentOrderItemCountForCreate($main_order_item_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM order_item_info WHERE main_order_item_id = " . $main_order_item_id . " LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["COUNT(*)"];
        }
        $result->free();
        return $data[0];
    }

    public static function insertOrder($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("order_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateOrder($update_data, $order_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("order_info", $update_data, "order_id = " . $order_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function insertOrderItem($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("order_item_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateOrderItem($update_data, $order_item_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("order_item_info", $update_data, "order_item_id = " . $order_item_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>