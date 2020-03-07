<?php

/**
 * 数据库操作类-refund_info
 * @author Kinsama
 * @version 2020-03-07
 */
class BroadcomRefundDBI
{

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
}
?>