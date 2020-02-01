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
        $result["member"]["top"] = array("个人设定");
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