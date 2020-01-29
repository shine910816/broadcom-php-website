<?php
// desc: 自定义常量
// author: Kinsama
// version: 2016-06-20
// +------------------------------------
// | 时间
// +------------------------------------
/**
 * 系统默认时区
 */
define("DATE_DEFAULT_TIMEZONE", "Asia/Shanghai");
/**
 * 系统默认今年
 */
define("DATE_DEFAULT_THIS_YEAR", date("Y"));
// +------------------------------------
// | 系统默认
// +------------------------------------
/**
 * 系统默认标题
 */
define("SYSTEM_DEFAULT_TITLE", "教务管理系统 - 博通教育");
/**
 * 系统默认域名
 */
define("SYSTEM_APP_HOST", "http://" . $_SERVER['HTTP_HOST'] . "/");
/**
 * 系统文件名头
 */
define("SYSTEM_FILE_HEADER", "Broadcom");
/**
 * 系统默认MENU
 */
define("SYSTEM_DEFAULT_MENU", "home");
/**
 * 系统默认ACT
 */
define("SYSTEM_DEFAULT_ACT", "top");
/**
 * 普通权限
 */
define("SYSTEM_AUTH_COMMON", 1);
/**
 * 用户权限
 */
define("SYSTEM_AUTH_LOGIN", 2);
/**
 * 管理员权限
 */
define("SYSTEM_AUTH_ADMIN", 3);
/**
 * 系统默认错误警报MENU
 */
define("SYSTEM_ERROR_MENU", "common");
/**
 * 系统默认错误警报ACT
 */
define("SYSTEM_ERROR_ACT", "error");
/**
 * 测试模式
 */
define("TEST_MODE_FLAG", true);

// +------------------------------------
// | 画面
// +------------------------------------
/**
 * 画面默认关键字
 */
define("SYSTEM_PAGE_KEYWORD", "");
/**
 * 画面默认描述
 */
define("SYSTEM_PAGE_DESCRIPTION", "");
/**
 * Smarty左边界符
 */
define("SMARTY_LT_DELIMITER", "{^");
/**
 * Smarty右边界符
 */
define("SMARTY_RT_DELIMITER", "^}");
/**
 * 画面显示
 */
define("VIEW_DONE", 1);
/**
 * 画面不显示
 */
define("VIEW_NONE", null);
/**
 * 画面显示条目数量
 */
define("DISPLAY_NUMBER_PER_PAGE", 20);
// +------------------------------------
// | GLOBAL KEY
// +------------------------------------
/**
 * 登录跳转全局主键
 */
define("REDIRECT_URL", "68E8CD70-A70F-E965-F11C-8A183033F96A");
?>