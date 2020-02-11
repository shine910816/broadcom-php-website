<?php

/**
 * 数据库操作类-item_info
 * @author Kinsama
 * @version 2020-02-12
 */
class BroadcomItemInfoDBI
{

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