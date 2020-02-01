<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png"/>
<title>{^$page_title^}</title>
<link type="text/css" rel="stylesheet" href="css/common.css" />
<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div class="top-panel">
  <div class="logo-box"></div>
  <div class="main-nav-bar">
    <ul>
      <li><a href="./"{^if $current_menu eq "home"^} class="selected"{^/if^}>首页</a></li>
      <li><a href="./?menu=front&act=top"{^if $current_menu eq "front"^} class="selected"{^/if^}>前台业务</a></li>
      <li><a href="./?menu=education&act=top"{^if $current_menu eq "education"^} class="selected"{^/if^}>学员教务</a></li>
      <li><a href="./?menu=human_resource&act=top"{^if $current_menu eq "human_resource"^} class="selected"{^/if^}>人力资源</a></li>
      <li><a href="./?menu=data&act=top"{^if $current_menu eq "data"^} class="selected"{^/if^}>数据统计</a></li>
    </ul>
  </div>
  <div class="login-status-bar">
    <a href="./?menu=member&act=top" title="前往个人设定">{^$user_member_position^} {^$user_member_name^}</a>
    <a href="./login/?do_logout=1" title="登出当前用户">登出</a>
  </div>
</div>
<div class="bottom-panel">
<div class="left-panel">
  <div class="left-logo-box"><img src="img/logo_s.png" /></div>
{^if !empty($left_content)^}
  <ul class="left-item-box">
{^foreach from=$left_content item=left_item^}
    <a href="./?menu={^$current_menu^}&act={^$left_item[0]^}"><li{^if $current_act eq $left_item[0]^} class="selected"{^/if^}>{^$left_item[1]^}</li></a>
{^/foreach^}
  </ul>
{^/if^}
</div>
<div class="main-panel">
{^include file=$comnaviga_file^}
</div>
