<?php
define("SRC_PATH", str_replace("\\", "/", dirname(dirname(__DIR__))));
header("Content-type:text/plain; charset=utf-8");
$clear_list = array();
$temp_path_text = SRC_PATH . "/temp/";
$temp_path = opendir($temp_path_text);
while ($temp_file = readdir($temp_path)) {
    if (is_readable($temp_path_text . $temp_file) && substr($temp_file, -8) == ".tpl.php") {
        $clear_list[] = $temp_file;
    }
}
if (!empty($clear_list)) {
    echo "清除以下缓存模板文件:\n";
    foreach ($clear_list as $tpl_file_name) {
        echo $tpl_file_name . "\n";
        unlink($temp_path_text . $tpl_file_name);
    }
} else {
    echo "缓存模板文件已清除干净";
}
?>