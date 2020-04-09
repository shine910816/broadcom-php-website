<?php

/**
 * 员工数据包
 * @author Kinsama
 * @version 2020-04-08
 */
class MemberRequest
{
    private $member_id = false;
    private $m_name;
    private $m_mobile_number;
    private $member_login_name;
    private $member_level;
    private $target_object_id;
    private $school_id;
    private $member_position;
    private $member_employed_status;

    public function __construct($target_object_id)
    {
        $member_info = $this->_selectMember($target_object_id);
        if (!Error::isError($member_info) && !empty($member_info)) {
            $this->member_id = $member_info["member_id"];
            $this->m_name = $member_info["m_name"];
            $this->m_mobile_number = $member_info["m_mobile_number"];
            $this->member_login_name = $member_info["member_login_name"];
            $this->member_level = $member_info["member_level"];
            $this->target_object_id = $member_info["target_object_id"];
            $this->school_id = $member_info["school_id"];
            $this->member_position = $member_info["member_position"];
            $this->member_employed_status = $member_info["member_employed_status"];
        }
    }

    private function _selectMember($target_object_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT l.member_id," .
               " i.m_name," .
               " i.m_mobile_number," .
               " l.member_login_name," .
               " l.member_level," .
               " l.target_object_id," .
               " p.school_id," .
               " p.member_position," .
               " p.member_employed_status" .
               " FROM member_login l" .
               " LEFT OUTER JOIN member_info i ON i.member_id = l.member_id" .
               " LEFT OUTER JOIN member_position p ON p.member_id = l.member_id" .
               " WHERE l.del_flg = 0" .
               " AND i.del_flg = 0" .
               " AND p.del_flg = 0" .
               " AND l.target_object_id = " . $dbi->quote($target_object_id) .
               " AND p.member_employed_status != 0" .
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

    public function id()
    {
        return $this->member_id;
    }

    public function name()
    {
        return $this->m_name;
    }

    public function mobileNumber()
    {
        return $this->m_mobile_number;
    }

    public function loginName()
    {
        return $this->member_login_name;
    }

    public function level()
    {
        return $this->member_level;
    }

    public function targetObjectId()
    {
        return $this->target_object_id;
    }

    public function schoolId()
    {
        return $this->school_id;
    }

    public function position()
    {
        return $this->member_position;
    }

    public function employedStatus()
    {
        return $this->member_employed_status;
    }

    public static function getInstance($target_object_id)
    {
        return new MemberRequest($target_object_id);
    }
}
?>