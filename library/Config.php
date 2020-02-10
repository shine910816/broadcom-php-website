<?php

/**
 * 配置控制器
 * @author Kinsama
 * @version 2017-08-02
 */
class Config
{

    public static function getAllowedCurrent()
    {
        $list_data = array();
        $result = array();
        $result["home"]["top"] = SYSTEM_AUTH_LOGIN;
        $result["member"]["login"] = SYSTEM_AUTH_COMMON;
        $result["member"]["top"] = SYSTEM_AUTH_LOGIN;
        $result["member"]["info"] = SYSTEM_AUTH_LOGIN;
        $result["member"]["password"] = SYSTEM_AUTH_LOGIN;
        $result["human_resource"]["top"] = SYSTEM_AUTH_LOGIN;
        $result["human_resource"]["member_info"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["top"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["my_leads"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["school_leads"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["create_leads"] = SYSTEM_AUTH_LOGIN;
        $result["admin"]["top"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["school_list"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["item_list"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["item_input"] = SYSTEM_AUTH_ADMIN;
        $list_data["php"] = $result;
        $result = array();
        $list_data["api"] = $result;
        return $list_data;
    }

    public static function getNavigation()
    {
        $result = array();
        $result["home"]["top"] = array();
        $result["member"]["login"] = array("成员登录");
        $result["member"]["top"] = array("个人信息管理");
        $result["member"]["info"] = array('<a href="./?menu=member&act=top">个人信息管理</a>', "修改个人信息");
        $result["member"]["password"] = array('<a href="./?menu=member&act=top">个人信息管理</a>', "修改登录密码");
        $result["human_resource"]["top"] = array("成员列表");
        $result["human_resource"]["member_info"] = array('<a href="./?menu=human_resource&act=top">成员列表</a>', "");
        $result["front"]["top"] = array("前台业务");
        $result["front"]["my_leads"] = array('<a href="./?menu=front&act=top">前台业务</a>', "我的意向客户");
        $result["front"]["school_leads"] = array('<a href="./?menu=front&act=top">前台业务</a>', "校区意向客户");
        $result["front"]["create_leads"] = array('<a href="./?menu=front&act=top">前台业务</a>', '<a href="./?menu=front&act=my_leads">我的意向客户</a>', "新增意向客户");
        $result["admin"]["top"] = array("后台管理");
        $result["admin"]["school_list"] = array('<a href="./?menu=admin&act=top">后台管理</a>', "校区管理");
        $result["admin"]["item_list"] = array('<a href="./?menu=admin&act=top">后台管理</a>', "课程管理");
        $result["admin"]["item_input"] = array('<a href="./?menu=admin&act=top">后台管理</a>', '<a href="./?menu=admin&act=item_list">课程管理</a>', "课程信息");
        return $result;
    }

    public static function getPositionAllowedCurrent()
    {
        $result = array();
        $result["human_resource"]["member_info"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,
            BroadcomMemberEntity::POSITION_HR_FINANCE
        );
        return $result;
    }

    public static function getDataSourceName()
    {
        return array(
            "host" => "127.0.0.1",
            "user" => "root",
            "pswd" => "",
            "name" => "broadcom",
            "port" => "3306"
        );
    }

    public static function getUsableGlobalKeys()
    {
        $result = array();
        $result[REDIRECT_URL] = array("member:login");
        return $result;
    }
}
?>