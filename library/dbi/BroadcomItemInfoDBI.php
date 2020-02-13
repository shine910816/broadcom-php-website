<?php

/**
 * 数据库操作类-item_info
 * @author Kinsama
 * @version 2020-02-12
 */
class BroadcomItemInfoDBI
{

    public static function selectItemInfo($item_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM item_info WHERE del_flg = 0 AND item_id = " . $item_id . " LIMIT 1";
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

    public static function selectItemInfoList()
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM item_info WHERE del_flg = 0";
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

    public static function selectSaleAbleItemInfoList($item_grade, $present_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM item_info WHERE del_flg = 0 AND item_sale_status = 1";
        $sql .= " AND (item_grade = 100 OR item_grade = " . $item_grade . ")";
        if ($present_flg) {
            $sql .= " AND item_type = 3";
        } else {
            $sql .= " AND (item_type = 1 OR item_type = 2)";
        }
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

    public static function insertSchoolInfo($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("item_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateSchoolInfo($update_data, $item_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("item_info", $update_data, "item_id = " . $item_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>