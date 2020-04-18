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

    public static function selectMemberNameList()
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM member_info" .
               " WHERE del_flg = 0";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["member_id"]] = $row["m_name"];
        }
        $result->free();
        return $data;
    }

    public static function selectMemberList($school_id, $section_id = null)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT l.member_id," .
               " i.m_name," .
               " i.m_id_code," .
               " i.m_mobile_number," .
               " i.m_mail_address," .
               " i.m_gender," .
               " i.m_birthday," .
               " i.m_married_type," .
               " i.m_address," .
               " i.m_college," .
               " i.m_major," .
               " i.m_college_start_date," .
               " i.m_college_end_date," .
               " i.m_educated," .
               " i.m_educated_type," .
               " i.m_contact_name," .
               " i.m_contact_mobile_number," .
               " i.m_contact_relationship," .
               " i.m_licence_number," .
               " i.m_primary_star_level," .
               " i.m_junior_star_level," .
               " i.m_senior_star_level," .
               " l.member_login_name," .
               " l.member_login_name_base," .
               " l.member_login_password," .
               " l.member_login_salt," .
               " l.member_level," .
               " l.target_object_id," .
               " p.school_id," .
               " p.member_position," .
               " p.member_position_level," .
               " p.member_employed_status" .
               " FROM member_login l" .
               " LEFT OUTER JOIN member_position p ON p.member_id = l.member_id" .
               " LEFT OUTER JOIN member_info i ON i.member_id = l.member_id" .
               " WHERE l.del_flg = 0" .
               " AND p.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND l.member_level = 1" .
               " AND school_id = " . $school_id;
        $member_position = array();
        if (!is_null($section_id)) {
            if (!is_array($section_id)) {
                $section_id = array($section_id);
            }
            $section_position_list = BroadcomMemberEntity::getSectionPositionList();
            foreach ($section_id as $section_id_tmp) {
                foreach ($section_position_list[$section_id_tmp] as $position_id) {
                    $member_position[] = $position_id;
                }
            }
        }
        if (!empty($member_position)) {
            $sql .= " AND p.member_position IN (" . implode(", ", $member_position) . ")";
        }
        $sql .= " ORDER BY p.member_position ASC, i.m_id_code ASC, l.member_id ASC";
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