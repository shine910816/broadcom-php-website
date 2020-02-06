<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="../img/favicon.png"/>
<title>{^$page_title^}</title>
<link type="text/css" rel="stylesheet" href="../css/common.css" />
<style type="text/css">
.login_box {
  width:500px;
  height:350px;
  /*border:1px solid #000;*/
  border-radius:10px 10px 5px 5px;
  margin:30px auto;
  background-color:#FFF;
}
.login_title {
  width:100%;
  height:50px;
  text-align:center;
  line-height:50px;
  background-color:#2C4C60;
  color:#FFF;
  font-size:1.5em;
  font-weight:bold;
  border-radius:5px 5px 0 0;
}
.colspan_field {
  width:100%;
  height:30px;
  margin-top:10px;
}
.colspan_name {
  width:150px;
  height:30px;
  text-align:center;
  line-height:30px;
  float:left;
}
.colspan_item {
  width:350px;
  height:30px;
  float:left;
}
.input_text {
  width:270px!important;
}
.error_hint {
  width:343px;
  color:#F30;
  height:30px;
  text-align:left;
  line-height:30px;
  float:right;
}
</style>
</head>
<body style="background-color:#4C637B;">
<form action="./" method="post" class="login_box">
  <div class="login_title">教务管理系统</div>
  <div class="colspan_field"></div>
  <div class="colspan_field">
    <div class="colspan_name">登录名</div>
    <div class="colspan_item"><input type="text" name="member_login_name" value="{^$member_login_name^}" class="text-field input_text" /></div>
  </div>
  <div class="colspan_field">
    <div class="error_hint">{^if isset($user_err_list["member_login_name"])^}{^$user_err_list["member_login_name"]^}{^/if^}</div>
  </div>
  <div class="colspan_field">
    <div class="colspan_name">密码</div>
    <div class="colspan_item"><input type="password" name="member_login_password" class="text-field input_text" /></div>
  </div>
  <div class="colspan_field">
    <div class="error_hint">{^if isset($user_err_list["member_login_password"])^}{^$user_err_list["member_login_password"]^}{^/if^}</div>
  </div>
  <div class="colspan_field">
    <input type="submit" name="do_login" value="登录" class="button-field ui-btn-a" />
  </div>
</form>
</body>
</html>