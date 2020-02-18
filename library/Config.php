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
        $result["human_resource"]["member_list"] = SYSTEM_AUTH_LOGIN;
        $result["human_resource"]["member_info"] = SYSTEM_AUTH_LOGIN;
        $result["human_resource"]["teacher_list"] = SYSTEM_AUTH_LOGIN;
        $result["human_resource"]["teacher_info"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["top"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["my_leads"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["school_leads"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["create_leads"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["cart_fill"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["cart_info"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["order_list"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["order_create"] = SYSTEM_AUTH_LOGIN;
        $result["front"]["order_payment"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["top"] = SYSTEM_AUTH_ADMIN;
        $result["education"]["student_list"] = SYSTEM_AUTH_ADMIN;
        $result["education"]["student_info"] = SYSTEM_AUTH_ADMIN;
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
        $result["human_resource"]["top"] = array("人力资源");
        $result["human_resource"]["member_list"] = array('<a href="./?menu=human_resource&act=top">人力资源</a>', "成员列表");
        $result["human_resource"]["member_info"] = array('<a href="./?menu=human_resource&act=top">人力资源</a>', '<a href="./?menu=human_resource&act=member_list">成员列表</a>', "");
        $result["human_resource"]["teacher_list"] = array('<a href="./?menu=human_resource&act=top">人力资源</a>', "教师列表");
        $result["human_resource"]["teacher_info"] = array('<a href="./?menu=human_resource&act=top">人力资源</a>', '<a href="./?menu=human_resource&act=teacher_list">教师列表</a>', "教师信息");
        $result["front"]["top"] = array("前台业务");
        $result["front"]["my_leads"] = array('<a href="./?menu=front&act=top">前台业务</a>', "我的意向客户");
        $result["front"]["school_leads"] = array('<a href="./?menu=front&act=top">前台业务</a>', "校区意向客户");
        $result["front"]["create_leads"] = array('<a href="./?menu=front&act=top">前台业务</a>', '<a href="./?menu=front&act=my_leads">我的意向客户</a>', "新增意向客户");
        $result["front"]["cart_fill"] = array('<a href="./?menu=front&act=top">前台业务</a>', "添加课程");
        $result["front"]["cart_info"] = array('<a href="./?menu=front&act=top">前台业务</a>', "已选择课程");
        $result["front"]["order_list"] = array('<a href="./?menu=front&act=top">前台业务</a>', "订单管理");
        $result["front"]["order_create"] = array('<a href="./?menu=front&act=top">前台业务</a>', "创建订单");
        $result["front"]["order_payment"] = array('<a href="./?menu=front&act=top">前台业务</a>', "支付订单");
        $result["education"]["top"] = array("学员教务");
        $result["education"]["student_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "学员管理");
        $result["education"]["student_info"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=student_list">学员管理</a>', "学员信息");
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
        $result["human_resource"]["teacher_info"] = array(
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