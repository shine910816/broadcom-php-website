<?php

/**
 * 数据库操作类-member_info
 * @author Kinsama
 * @version 2020-01-29
 */
class BroadcomMemberInfoDBI
{

    public static function insertCustom($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("member_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateCustom($update_data, $custom_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("member_info", $update_data, "member_id = " . $custom_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>