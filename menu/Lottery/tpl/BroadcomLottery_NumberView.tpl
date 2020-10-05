<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.png"/>
<title>抽奖号码</title>
<link type="text/css" rel="stylesheet" href="../../css/font-awesome.css" />
<script type="text/javascript" src="../../js/jquery-2.0.0.min.js"></script>
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
  position:absolute;
  color:#fddd48;
  text-align:center;
}
</style>
<script type="text/javascript">
var resize = function(){
    var bg_option = {
        width:400,
        height:600
    };
    var resizeWidth = window.innerWidth;
    var resizeHeight = window.innerWidth * bg_option.height / bg_option.width;
    $("#background-image").css({
        "width":resizeWidth,
        "height":resizeHeight
    });
    $(".form-box").css({
        "width":resizeWidth / 4,
        "height":resizeWidth / 4,
        "line-height":resizeWidth / 4 + "px",
        "font-size":resizeWidth / 5 + "px",
        "top":resizeWidth * 1.157,
        "left":resizeWidth * 0.375
    });
};
$(document).ready(function(){
    resize();
});
</script>
</head>
<body>
<img src="bg.gif" id="background-image" />
<div class="form-box">{^$user_info["u_id"]^}</div>
</body>
</html>