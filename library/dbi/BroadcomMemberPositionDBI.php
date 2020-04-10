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

    public static function selectMemberPositionListBySchool($school_id, $member_position)
    {
        $dbi = Database::getInstance();
        if (!is_array($member_position)) {
            $member_position = array($member_position);
        }
        $sql = "SELECT p.member_id," .
               " i.m_name," .
               " p.member_position," .
               " p.member_position_level," .
               " p.member_employed_status" .
               " FROM member_position p" .
               " LEFT OUTER JOIN member_info i ON i.member_id = p.member_id" .
               " WHERE p.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND p.member_employed_status != 0" .
               " AND p.school_id = " . $school_id .
               " AND p.member_position IN (" . implode(", ", $member_position) . ")";
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

    public static function selectMemberListBySchoolGroupPosition($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT p.member_id," .
               " i.m_name," .
               " p.member_position" .
               " FROM member_position p" .
               " LEFT OUTER JOIN member_info i ON i.member_id = p.member_id" .
               " WHERE p.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND p.member_employed_status != 0" .
               " AND p.school_id = " . $school_id .
               " ORDER BY p.member_position ASC, p.member_position_level ASC, p.member_id ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        $section_position_list = BroadcomMemberEntity::getSectionPositionList();
        while ($row = $result->fetch_assoc()) {
            foreach ($section_position_list as $section_id => $allow_position) {
                if (in_array($row["member_position"], $allow_position)) {
                    $data[$section_id][$row["member_id"]] = $row["m_name"];
                } else {
                    continue;
                }
            }
        }
        $result->free();
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