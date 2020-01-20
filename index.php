<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png"/>
<title>博通教育教务管理系统</title>
<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<style type="text/css">
* {
  margin:0;
  padding:0;
  border:0;
  font-family:"Microsoft Yahei","Arial","SimSun";
  font-size:14px;
}
body {
  height:auto;
  background-color:#FFFFFF;
}
div.top-panel {
  background-color:#2C4C60;
  position:fixed;

}
div.left-panel {
  background-color:#4C637B;
  position:fixed;
}
div.main-panel {
  height:auto;
}
div.bread-crumbs-bar {
  padding:0.3em;
}
div.bread-crumbs-bar ul {
  width:100%;
  height:1.5em;
  list-style:none;
}
div.bread-crumbs-bar ul li {
  float:left;
  height:1.5em;
  line-height:1.5em;
  color:#999999;
}
div.bread-crumbs-bar ul li.nav_item:before {
  content:">";
  padding:0 0.5em;
  color:#999999;
}
div.bread-crumbs-bar ul li a {
  color:#3E70C5;
  text-decoration:none;
}
div.bread-crumbs-bar ul li a:hover {
  color:#428BCA;
}
div.logo-box {
  width:157px;
  height:40px;
  margin:15px;
  display:block;
  background-image:url("img/logo.png");
  float:left;
}
div.main-nav-bar {
  width:50%;
  height:70px;
  float:left;
}
div.main-nav-bar ul {
  width:100%;
  height:100%;
  list-style:none;
}
div.main-nav-bar ul li {
  float:left;
  display:block;
}
div.main-nav-bar ul li a {
  height:70px;
  padding:0 0.7em;
  text-align:center;
  line-height:70px;
  color:#BED9FC;
  font-size:1.15em;
  text-decoration:none;
  display:block;
}
div.main-nav-bar ul li a:hover {
  color:#FFFFFF;
}
div.main-nav-bar ul li a.selected {
  color:#FFFFFF;
  font-size:1.4em;
}
</style>
<script type="text/javascript">
var adjustOption = {mainMinWidth:990, topHeight:70, leftWidth:220, mainPadding:10};
var adjustWindow = function(opt){
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    $(document.body).width(windowWidth);
    $("div.top-panel").css({
        "width":"100%",
        "min-width":opt.topHeight + opt.leftWidth + opt.mainPadding * 4,
        "height":opt.topHeight
    });
    $("div.left-panel").css({
        "width":opt.leftWidth,
        "height":windowHeight - opt.topHeight,
        "top":opt.topHeight
    });
    $("div.main-panel").css({
        "width":windowWidth - opt.leftWidth - opt.mainPadding * 4,
        "min-width":opt.mainMinWidth,
        "padding-top":opt.topHeight + opt.mainPadding,
        "padding-right":opt.mainPadding * 2,
        "padding-bottom":opt.mainPadding,
        "padding-left":opt.leftWidth + opt.mainPadding * 2
    });
};
$(document).ready(function(){
    adjustWindow(adjustOption);
});
$(window).on("resize", function(){
    adjustWindow(adjustOption);
});
</script>
</head>
<body>
<div class="top-panel">
  <div class="logo-box"></div>
  <div class="main-nav-bar">
    <ul>
      <li><a href="./">首页</a></li>
      <li><a href="./">前台业务</a></li>
      <li><a href="./" class="selected">学员教务</a></li>
      <li><a href="./">人力资源</a></li>
      <li><a href="./">数据统计</a></li>
    </ul>
  </div>
</div>
<div class="left-panel"></div>
<div class="main-panel">
  <div class="bread-crumbs-bar">
    <ul>
      <li><a href="./">首页</a></li>
      <li class="nav_item"><a href="./">天生我材必有用</a></li>
      <li class="nav_item">千金散尽还复来</li>
    </ul>
  </div>
</div>
</body>
</html>