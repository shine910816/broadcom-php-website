<?php

/**
 * 数据库操作类-member_position
 * @author Kinsama
 * @version 2020-02-03
 */
class BroadcomMemberPositionDBI
{

    public static function selectMemberPosition($member_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM member_position" .
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

    public static function insertMemberPosition($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("member_position", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateMemberPosition($update_data, $member_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("member_position", $update_data, "member_id = " . $member_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>