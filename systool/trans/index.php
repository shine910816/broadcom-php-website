<?php
if (isset($_GET["k"])) {
    $keyword = $_GET["k"];
    $object_id = "";
    if (strlen($keyword) > 0) {
        $object_id_str = strtoupper(md5(urldecode($keyword)));
        $object_id_arr = array();
        $object_id_arr[] = substr($object_id_str, 0, 8);
        $object_id_arr[] = substr($object_id_str, 8, 4);
        $object_id_arr[] = substr($object_id_str, 12, 4);
        $object_id_arr[] = substr($object_id_str, 16, 4);
        $object_id_arr[] = substr($object_id_str, 20);
        $object_id = implode("-", $object_id_arr);
    }
    echo $object_id;
    exit;
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>关键字转换</title>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#do_trans").click(function(){
        var url = "./?k=" + encodeURI($("#text_from").val());
        $.get(url, function(data){
            $("#text_to").val(data);
        });
    });
    $("#text_to").focus(function(){
        $(this).select();
    });
});
</script>
<style type="text/css">
.text_box {
  text-align:center!important;
}
</style>
</head>
<body>
<div data-role="content">
  <div class="ui-body ui-body-a ui-corner-all">
    <h4>关键字转换</h4>
    <input type="text" id="text_from" class="text_box" />
    <input type="button" id="do_trans" value="转换" />
    <input type="text" id="text_to" class="text_box" readonly />
  </div>
</div>
</body>
</html>
<?php } ?>