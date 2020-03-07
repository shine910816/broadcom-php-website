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
        $result["front"]["order_info"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["top"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["my_student_list"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["student_list"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["student_info"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["schedule_list"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["schedule_create"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["schedule_info"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["course_create"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["my_course_list"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["course_list"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["course_confirm"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["reset_list"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["reset_confirm"] = SYSTEM_AUTH_LOGIN;
        // TODO 
        $result["education"]["student_edit"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["student_assign"] = SYSTEM_AUTH_LOGIN;
        $result["education"]["contract_refund"] = SYSTEM_AUTH_LOGIN;
        $result["admin"]["top"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["school_list"] = SYSTEM_AUTH_ADMIN;
        $result["admin"]["room_info"] = SYSTEM_AUTH_ADMIN;
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
        $result["front"]["order_info"] = array('<a href="./?menu=front&act=top">前台业务</a>', "订单详情");
        $result["education"]["top"] = array("学员教务");
        $result["education"]["my_student_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "我的学员管理");
        $result["education"]["student_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "学员管理");
        $result["education"]["student_info"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=my_student_list">我的学员管理</a>', "学员信息");
        $result["education"]["schedule_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "课表管理");
        $result["education"]["schedule_create"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=schedule_list">班课课程管理</a>', "添加课表");
        $result["education"]["schedule_info"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=schedule_list">班课课程管理</a>', "课表详情");
        $result["education"]["course_create"] = array('<a href="./?menu=education&act=top">学员教务</a>', "课程安排");
        $result["education"]["my_course_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "我的排课列表");
        $result["education"]["course_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "校区排课列表");
        $result["education"]["course_confirm"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=my_course_list">我的排课列表</a>', "排课确认");
        $result["education"]["reset_list"] = array('<a href="./?menu=education&act=top">学员教务</a>', "返课列表");
        $result["education"]["reset_confirm"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=reset_list">返课列表</a>', "返课确认");
        $result["education"]["student_edit"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=my_student_list">我的学员管理</a>', "修改学员信息");
        $result["education"]["student_assign"] = array('<a href="./?menu=education&act=top">学员教务</a>', '<a href="./?menu=education&act=my_student_list">我的学员管理</a>', "学员受理分配");
        $result["education"]["contract_refund"] = array('<a href="./?menu=education&act=top">学员教务</a>', "退款转让");
        $result["admin"]["top"] = array("后台管理");
        $result["admin"]["school_list"] = array('<a href="./?menu=admin&act=top">后台管理</a>', "校区管理");
        $result["admin"]["room_info"] = array('<a href="./?menu=admin&act=top">后台管理</a>', '<a href="./?menu=admin&act=school_list">校区管理</a>', "教室管理");
        $result["admin"]["item_list"] = array('<a href="./?menu=admin&act=top">后台管理</a>', "课程管理");
        $result["admin"]["item_input"] = array('<a href="./?menu=admin&act=top">后台管理</a>', '<a href="./?menu=admin&act=item_list">课程管理</a>', "课程信息");
        return $result;
    }

    public static function getPositionAllowedCurrent()
    {
        $result = array();
        // 我的意向客户
        $result["front"]["my_leads"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        // 添加意向客户
        $result["front"]["create_leads"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        // 校区意向客户
        $result["front"]["school_leads"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_HR_FINANCE         // 财务人事
        );
        // 订单管理
        $result["front"]["order_list"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT,         // 学管
            BroadcomMemberEntity::POSITION_HR_FINANCE         // 财务人事
        );
        $result["front"]["cart_fill"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        $result["front"]["cart_info"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        $result["front"]["order_create"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,   // 营销主管
            BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
            BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        // 添加课表
        $result["education"]["schedule_create"] = array(
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        // TODO 全部权限 排课
        $result["education"]["course_create"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
        );
        // 消课
        $result["education"]["course_confirm"] = array(
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            BroadcomMemberEntity::POSITION_ASSISTANT,         // 学管
            BroadcomMemberEntity::POSITION_TEACH_MANAGER,     // 教学主管
            BroadcomMemberEntity::POSITION_TEACHER,           // 教师
            BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER // 兼职教师
        );
        // 返课确认
        $result["education"]["course_confirm"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_HR_FINANCE         // 财务人事
        );
        // 添加新成员
        $result["human_resource"]["member_info"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_HR_FINANCE         // 财务人事
        );
        // 教师信息
        $result["human_resource"]["teacher_info"] = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            BroadcomMemberEntity::POSITION_HR_FINANCE         // 财务人事
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