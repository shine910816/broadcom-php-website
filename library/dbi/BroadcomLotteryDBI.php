<?php
class BroadcomLotteryDBI
{
    public static function selectLotteryInfo($l_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM lottery_info WHERE del_flg = 0" .
               " AND l_id = " . $l_id . " LIMIT 1";
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

    public static function selectLottery($u_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM lottery_user_info WHERE del_flg = 0" .
               " AND u_id = " . $u_id . " LIMIT 1";
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

    public static function selectSpecialUndrawn($l_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM lottery_user_info WHERE del_flg = 0" .
               " AND l_id = " . $l_id . " AND l_drawn_flg = 0 AND u_level = 2";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["u_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectLotteryByDrawn($l_id, $drawn_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM lottery_user_info WHERE del_flg = 0" .
               " AND l_id = " . $l_id . " AND l_drawn_flg = ";
        if ($drawn_flg) {
            $sql .= "1";
        } else {
            $sql .= "0";
        }
        $sql .= " ORDER BY l_drawn_date ASC, u_id ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["u_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectLotteryInfoByMobile($lottery_id, $mobile)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM lottery_user_info WHERE del_flg = 0" .
               " AND l_id = " . $lottery_id .
               " AND u_mobile = " . $dbi->quote($mobile) . " LIMIT 1";
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

    public static function insertLottery($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("lottery_user_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateLottery($update_data, $u_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("lottery_user_info", $update_data, "u_id = " . $u_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>