<?php

/**
 * 数据库操作类-school_info
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomSchoolInfoDBI
{

    public static function selectSchoolInfoList()
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM school_info WHERE del_flg = 0";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["school_id"]] = $row["school_name"];
        }
        $result->free();
        return $data;
    }

    public static function insertSchoolInfo($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("school_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateSchoolInfo($update_data, $school_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("school_info", $update_data, "school_id = " . $school_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>