<?php

/**
 * 数据库操作类-member_login
 * @author Kinsama
 * @version 2020-01-31
 */
class BroadcomMemberLoginDBI
{

    public static function selectMemberLogin($member_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM member_login" .
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

    public static function selectMemberLoginByName($member_login_name)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM member_login" .
               " WHERE del_flg = 0 AND member_login_name = " . $dbi->quote($member_login_name) .
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

    public static function insertMemberLogin($login_name, $login_password, $login_salt, $position_level)
    {
        $dbi = Database::getInstance();
        $insert_data = array();
        $salt_info = Utility::transSalt();
        $insert_data["member_login_name"] = $login_name;
        $insert_data["member_login_password"] = $login_password;
        $insert_data["member_login_salt"] = $login_salt;
        $insert_data["member_level"] = "1";
        $insert_data["member_position_level"] = $position_level;
        $result = $dbi->insert("member_login", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateMemberLogin($update_data, $member_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("member_login", $update_data, "member_id = " . $member_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>