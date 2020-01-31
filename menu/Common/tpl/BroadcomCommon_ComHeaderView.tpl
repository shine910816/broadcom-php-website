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
      <li><a href="./"{^if $current_menu eq "front"^} class="selected"{^/if^}>前台业务</a></li>
      <li><a href="./"{^if $current_menu eq "education"^} class="selected"{^/if^}>学员教务</a></li>
      <li><a href="./"{^if $current_menu eq "human_resource"^} class="selected"{^/if^}>人力资源</a></li>
      <li><a href="./"{^if $current_menu eq "data"^} class="selected"{^/if^}>数据统计</a></li>
    </ul>
  </div>
  <!--div>登录状态</div-->
</div>
<div class="bottom-panel">
<div class="left-panel">
  <!--div style="width:10px; height:1000px; background-color:#F60;"></div-->
</div>
<div class="main-panel">
{^include file=$comnaviga_file^}
</div>
