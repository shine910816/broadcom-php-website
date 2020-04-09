<?php

/**
 * 员工数据包
 * @author Kinsama
 * @version 2020-04-08
 */
class MemberUser
{
    private $member_id = false;
    private $m_name;
    private $m_mobile_number;
    private $member_login_name;
    private $member_level;
    private $target_object_id;
    private $school_id;
    private $member_position;
    private $member_position_name;
    private $member_employed_status;

    public function __construct()
    {
        if (isset($_SESSION[LOGIN_MEMBER_INFO])) {
            $member_info = Utility::decodeCookieInfo($_SESSION[LOGIN_MEMBER_INFO]);
            $this->member_id = $member_info["i"];
            $this->m_name = $member_info["n"];
            $this->m_mobile_number = $member_info["m"];
            $this->member_login_name = $member_info["a"];
            $this->member_level = $member_info["l"];
            $this->target_object_id = $member_info["t"];
            $this->school_id = $member_info["s"];
            $this->member_position = $member_info["p"];
            $this->member_position_name = $member_info["k"];
            $this->member_employed_status = $member_info["e"];
        }
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

    public function positionName()
    {
        return $this->member_position_name;
    }

    public function employedStatus()
    {
        return $this->member_employed_status;
    }

    public static function getInstance()
    {
        return new MemberUser();
    }
}
?>