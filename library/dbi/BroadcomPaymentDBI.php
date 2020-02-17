<?php

/**
 * 数据库操作类-payment_info
 * @author Kinsama
 * @version 2020-02-16
 */
class BroadcomPaymentDBI
{

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