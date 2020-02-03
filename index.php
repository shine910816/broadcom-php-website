<?php
/**
 * 系统主目录
 */
//define("SRC_PATH", str_replace("\\", "/", __DIR__));
define("SRC_PATH", "F:/Website");
//define("SRC_PATH", "D:/Test");
require_once SRC_PATH . "/library/ActionBase.php";
require_once SRC_PATH . "/library/Config.php";
require_once SRC_PATH . "/library/Controller.php";
require_once SRC_PATH . "/library/Database.php";
require_once SRC_PATH . "/library/Error.php";
require_once SRC_PATH . "/library/Utility.php";
require_once SRC_PATH . "/library/Init.php";
require_once SRC_PATH . "/library/Launcher.php";
require_once SRC_PATH . "/library/Request.php";
require_once SRC_PATH . "/library/User.php";
require_once SRC_PATH . "/library/Validate.php";
require_once SRC_PATH . "/library/View.php";
date_default_timezone_set(DATE_DEFAULT_TIMEZONE);
mb_internal_encoding("UTF-8");
$controller = Controller::getInstance();
$user = User::getInstance();
$request = Request::getInstance();
$launcher = Launcher::getInstance();
if (!$user->isLogin()) {
    $controller->redirect(SYSTEM_APP_HOST . "login/");
} else {
    $launcher->start($controller, $user, $request);
}
?>