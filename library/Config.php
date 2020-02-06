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
        return $result;
    }

    public static function getPositionAllowedCurrent()
    {
        $result = array();
        $result["human_resource"]["member_info"] = array(
            BroadcomMemberEntity::POSITION_LEVEL_HEADMASTER,
            BroadcomMemberEntity::POSITION_LEVEL_HR_FINANCE
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