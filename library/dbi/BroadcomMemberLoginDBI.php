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
        $sql = "SELECT l.member_id," .
               " l.member_login_name," .
               " l.member_login_password," .
               " l.member_login_salt" .
               " FROM member_login l LEFT OUTER JOIN member_position p ON p.member_id = l.member_id" .
               " WHERE l.del_flg = 0" .
               " AND p.del_flg = 0" .
               " AND p.member_employed_status != 0" .
               " AND member_login_name = " . $dbi->quote($member_login_name) .
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

    public static function selectMemberList($school_id = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT l.member_id," .
               " i.m_name," .
               " l.member_login_name," .
               " i.m_mobile_number," .
               " i.m_mail_address," .
               " p.school_id," .
               " p.member_position," .
               " p.member_position_level" .
               " FROM member_login l" .
               " LEFT OUTER JOIN member_position p ON p.member_id = l.member_id" .
               " LEFT OUTER JOIN member_info i ON i.member_id = l.member_id" .
               " WHERE l.del_flg = 0" .
               " AND p.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND l.member_level = 1";
        if (!is_null($school_id)) {
            $sql .= " AND school_id = " . $school_id;
        }
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["member_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectBaseNameCount($base_name)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM member_login WHERE member_login_name_base = " . $dbi->quote($base_name) . " LIMIT 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row["COUNT(*)"];
        }
        $result->free();
        if (count($data) == 1) {
            return $data[0];
        }
        return false;
    }

    public static function insertMemberLogin($insert_data)
    {
        $dbi = Database::getInstance();
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