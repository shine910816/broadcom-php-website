<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.png"/>
<title>开奖</title>
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
.wheel-number {
  text-align:center;
  color:#FFF;
  position:absolute;
}
.submit-button {
  text-align:center;
  color:#FFF;
  background-color:#F60;
  border-radius:10px;
  position:absolute;
}
.drawn-table {
  height:auto;
  padding:5px;
  background-color:rgba(255,255,255,0.7);
  position:absolute;
}
.drawn-table table {
  width:100%;
}
.drawn-table table tr th {
  width:33.3%;
  text-align:center;
}
.drawn-table table tr td {
  text-align:center;
}
</style>
<script type="text/javascript">
var startButtonText = "开始";
var endButtonText = "确定";
var resize = function(){
    var bg_option = {
        width:7087,
        height:3543
    };
    var resizeWidth = window.innerWidth;
    var resizeHeight = window.innerWidth * bg_option.height / bg_option.width;
    var wheelSize = resizeWidth / 10;
    $("#background-image").css({
        "width":resizeWidth,
        "height":resizeHeight
    });
    $(".wheel-number").css({
        "width":wheelSize,
        "height":wheelSize,
        "line-height":wheelSize + "px",
        "top":wheelSize * 2.9,
        "left":wheelSize * 1.7,
        "font-size":wheelSize * 0.7 + "px"
    });
    $(".submit-button").css({
        "width":wheelSize * 2,
        "height":wheelSize / 2,
        "line-height":wheelSize / 2 + "px",
        "top":wheelSize * 4,
        "left":wheelSize * 1.9,
        "font-size":wheelSize * 0.25 + "px"
    });
    $(".wheel-number").empty();
    $(".submit-button").empty().html(startButtonText);
    $(".drawn-table").css({
        "width":wheelSize * 2,
        "top":wheelSize * 2.9,
        "left":wheelSize * 3
    });
};
var userList = [];
var userListCount = 0;
var listIndex = 0;
var roundFlg = false;
var drawnNum = "";
var timer;
var requestDrawn = function(){
    $.get("./?ajax=3", function(result){
        var res = jQuery.parseJSON(result);
        drawnNum = res.res;
    });
};
var requestTable = function(){
    $.get("./?ajax=2", function(result){
        drawnUserList = jQuery.parseJSON(result);
        var drawnTableHtml = "";
        $.each(drawnUserList, function(index,item){
            if (index < drawnUserList.length) {
                drawnTableHtml += "<tr><td>" + item.number + "</td><td>" + item.name + "</td><td>" + item.mobile + "</td></tr>";
            }
        });
        $("#drawn-table").empty().html(drawnTableHtml);
    });
};
$(document).ready(function(){
    resize();
    requestTable();
    $(".submit-button").click(function(){
        if (roundFlg) {
            clearInterval(timer);
            $(".wheel-number").empty().html(drawnNum);
            $(this).empty().html(startButtonText);
            requestTable();
            roundFlg = false;
        } else {
            $.get("./?ajax=1", function(result){
                userList = jQuery.parseJSON(result);
                userListCount = userList.length;
            });
            requestDrawn();
            timer = setInterval(function(){
                $(".wheel-number").empty().html(userList[listIndex]);
                if (listIndex < userListCount - 1) {
                    listIndex++;
                } else {
                    listIndex = 0;
                }
            }, 50);
            $(this).empty().html(endButtonText);
            roundFlg = true;
        }
    });
});
</script>
</head>
<body>
<img src="bg.jpg" id="background-image" />
<div class="wheel-number"></div>
<div class="submit-button"></div>
<div class="drawn-table">
  <table>
    <thead>
      <tr>
        <th>中奖号码</th>
        <th>姓名</th>
        <th>手机号码</th>
      </tr>
    </thead>
    <tbody id="drawn-table"></tbody>
  </table>
</div>
</body>
</html>