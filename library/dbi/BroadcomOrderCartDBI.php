<?php

/**
 * 数据库操作类-order_cart
 * @author Kinsama
 * @version 2020-02-13
 */
class BroadcomOrderCartDBI
{

    public static function selectOrderCartInfoList($student_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT c.item_id," .
               " i.item_name," .
               " i.item_type," .
               " i.item_method," .
               " i.item_grade," .
               " i.item_price," .
               " i.item_unit," .
               " c.item_amount," .
               " c.item_present_flg," .
               " c.main_item_id" .
               " FROM order_cart c" .
               " LEFT OUTER JOIN item_info i ON i.item_id = c.item_id" .
               " WHERE i.del_flg = 0" .
               " AND c.del_flg = 0" .
               " AND c.student_id = " . $student_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["item_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectOrderCartList($student_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM order_cart WHERE del_flg = 0 AND student_id = " . $student_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            if ($row["item_present_flg"]) {
                if (!isset($data[$row["main_item_id"]])) {
                    $data[$row["main_item_id"]] = array("amount" => "0");
                }
                $data[$row["main_item_id"]]["present"][$row["item_id"]] = $row["item_amount"];
            } else {
                if (!isset($data[$row["item_id"]])) {
                    $data[$row["item_id"]] = array("amount" => "0");
                }
                $data[$row["item_id"]]["amount"] = $row["item_amount"];
            }
        }
        $result->free();
        return $data;
    }

    public static function insertOrderCart($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("order_cart", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateOrderCart($update_data, $student_id, $item_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("order_cart", $update_data, "student_id = " . $student_id . " AND item_id = " . $item_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function clearOrderCart($student_id, $item_id = array())
    {
        $dbi = Database::getInstance();
        $where = "student_id = " . $student_id;
        if (!empty($item_id)) {
            $where .= " AND item_id IN (" . implode(", ", $item_id) . ")";
        }
        $result = $dbi->delete("order_cart", $where);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>