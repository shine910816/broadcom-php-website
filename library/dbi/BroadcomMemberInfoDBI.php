<?php

/**
 * 数据库操作类-member_info
 * @author Kinsama
 * @version 2020-01-29
 */
class BroadcomMemberInfoDBI
{

    public static function selectMemberInfo($member_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM member_info" .
               " WHERE del_flg = 0 AND member_id = " . $member_id .
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

    public static function insertMemberInfo($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("member_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateMemberInfo($update_data, $member_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("member_info", $update_data, "member_id = " . $member_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>