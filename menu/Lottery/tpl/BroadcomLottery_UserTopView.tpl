<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="../img/favicon.png"/>
<title>抽奖注册</title>
<link type="text/css" rel="stylesheet" href="../css/font-awesome.css" />
<script type="text/javascript" src="../js/jquery-2.0.0.min.js"></script>
<style type="text/css">
* {
  margin:0;
  padding:0;
  border:0;
}
body {
  background-color:#4E16AD;
}
.form-box {
  background-color:rgba(255,255,255,0.7);
  position:absolute;
  height:180px;
}
.form-box table {
  width:100%;
  padding:12px 0;
}
.form-box table tr td.title {
  width:40%;
  height:48px;
  text-align:center;
}
.form-box table tr td input {
  width:70%;
  height:36px;
  margin:6px 0;
  line-height:36px;
  padding:0 12px;
}
.form-box table tr td input.submit {
  margin:6px auto!important;
  display:block;
  background-color:#ff2b2b;
  color:#FFF;
}
</style>
<script type="text/javascript">
var resize = function(){
    var bg_option = {
        width:1440,
        height:2160
    };
    var resizeWidth = window.innerWidth;
    var resizeHeight = window.innerWidth * bg_option.height / bg_option.width;
    $("#background-image").css({
        "width":resizeWidth,
        "height":resizeHeight
    });
    $(".form-box").css({
        "width":resizeWidth / 2,
        "top":resizeWidth * 1,
        "left":resizeWidth / 4
    });
};
$(document).ready(function(){
    resize();
});
</script>
</head>
<body>
<img src="bg.jpg" id="background-image" />
<form action="./" method="post" class="form-box">
  <table>
    <tr>
      <td class="title">姓名</td>
      <td style="width:60%;"><input type="text" name="user_name" value="{^$user_name^}" /></td>
    </tr>
    <tr>
      <td class="title">手机号码</td>
      <td><input type="text" name="user_mobile" value="{^$user_mobile^}" /></td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" name="do_register" value="领取抽奖号码" class="submit" /></td>
    </tr>
  </table>
</form>
</body>
</html>