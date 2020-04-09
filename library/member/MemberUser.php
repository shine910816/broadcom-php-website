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
    private $member_employed_status;

    public function __construct()
    {
        if (Error::isError($member_info)) {
            $member_info->setPos(__FILE__, __LINE__);
        } else {
            if (!empty($member_info)) {
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

    public static function getInstance()
    {
        return new MemberUser();
    }
}
?>