<?php

/**
 * 数据库操作类-target_info
 * @author Kinsama
 * @version 2020-03-17
 */
class BroadcomTargetDBI
{

    public static function selectTarget($school_id, $target_date)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM target_info WHERE del_flg = 0" .
               " AND school_id = " . $school_id .
               " AND target_date = " . $target_date .
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

    public static function insertTarget($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("target_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>