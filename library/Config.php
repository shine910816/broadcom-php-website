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
        $result["common"]["home"] = SYSTEM_AUTH_LOGIN;
        $result["user"]["login"] = SYSTEM_AUTH_COMMON;
        $result["user"]["disp"] = SYSTEM_AUTH_LOGIN;
        $result["user"]["change_password"] = SYSTEM_AUTH_LOGIN;
        $result["admin"]["admin_list"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["admin_create"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["package_list"] = SYSTEM_AUTH_ADMIN;
        $result["custom"]["search"] = SYSTEM_AUTH_LOGIN;
        $result["custom"]["input"] = SYSTEM_AUTH_LOGIN;
        $result["custom"]["detail"] = SYSTEM_AUTH_LOGIN;
        $result["custom"]["invest"] = SYSTEM_AUTH_LOGIN;
        $result["custom"]["sale"] = SYSTEM_AUTH_LOGIN;
        $result["statistics"]["daily_report"] = SYSTEM_AUTH_ADMIN;
        $result["statistics"]["weekly_report"] = SYSTEM_AUTH_ADMIN;
        $result["statistics"]["monthly_report"] = SYSTEM_AUTH_ADMIN;
        $result["statistics"]["yearly_report"] = SYSTEM_AUTH_ADMIN;
        $result["statistics"]["custom_list"] = SYSTEM_AUTH_ADMIN;
        $list_data["php"] = $result;
        $result = array();
        $list_data["api"] = $result;
        return $list_data;
    }

    public static function getNavigation()
    {
        $result = array();
        $result["common"]["home"] = array();
        $result["user"]["login"] = array("用户登录");
        $result["user"]["disp"] = array("个人设定");
        $result["user"]["change_password"] = array('<a href="./?menu=user&act=disp" data-ajax="false">个人设定</a>', "修改密码");
        $result["admin"]["admin_list"] = array("成员管理");
        $result["admin"]["admin_create"] = array('<a href="./?menu=admin&act=admin_list" data-ajax="false">成员管理</a>', "创建用户");
        $result["admin"]["package_list"] = array("套餐管理");
        $result["custom"]["search"] = array("会员管理");
        $result["custom"]["input"] = array('<a href="./?menu=custom&act=search" data-ajax="false">会员管理</a>', "");
        $result["custom"]["detail"] = array('<a href="./?menu=custom&act=search" data-ajax="false">会员管理</a>', "会员详细");
        $result["custom"]["invest"] = array("续费充值");
        $result["custom"]["sale"] = array("洗车消费");
        $result["statistics"]["daily_report"] = array("账目管理");
        $result["statistics"]["weekly_report"] = array('<a href="./?menu=statistics&act=daily_report" data-ajax="false">账目管理</a>', "周度账目");
        $result["statistics"]["monthly_report"] = array('<a href="./?menu=statistics&act=daily_report" data-ajax="false">账目管理</a>', "月度账目");
        $result["statistics"]["yearly_report"] = array('<a href="./?menu=statistics&act=daily_report" data-ajax="false">账目管理</a>', "年度账目");
        $result["statistics"]["custom_list"] = array('<a href="./?menu=statistics&act=daily_report" data-ajax="false">账目管理</a>', "会员一览");
        return $result;
    }

    public static function getDataSourceName()
    {
        return array(
            "host" => "127.0.0.1",
            "user" => "root",
            "pswd" => "",
            "name" => "club",
            "port" => "3306"
        );
    }

    public static function getUsableGlobalKeys()
    {
        $result = array();
        $result[REDIRECT_URL] = array("user:login");
        return $result;
    }
}
?>