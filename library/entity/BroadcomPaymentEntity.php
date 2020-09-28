<?php
/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-09-28
 */
class BroadcomPaymentEntity
{
    const PAYMENT_STATUS_1 = "1";
    const PAYMENT_STATUS_2 = "2";
    const PAYMENT_STATUS_3 = "3";
    const PAYMENT_STATUS_4 = "4";

    public static function getPaymentStatusList()
    {
        return array(
            self::PAYMENT_STATUS_1 => "订单收款",
            self::PAYMENT_STATUS_2 => "订单退款",
            self::PAYMENT_STATUS_3 => "合同自动圈款",
            self::PAYMENT_STATUS_4 => "合同退款"
        );
    }
}
?>