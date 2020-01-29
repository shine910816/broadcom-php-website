<?php

/**
 * 核心控制器
 * @author Kinsama
 * @version 2016-12-30
 */
class Controller
{

    /**
     * 转向目标menu
     */
    public $forward_menu;

    /**
     * 转向目标act
     */
    public $forward_act;

    /**
     * 转向目标flag
     */
    public $forward_flg = false;

    /**
     * 设置画面转向
     *
     * @param string $menu menu名
     * @param string $act act名
     */
    public function forward($menu, $act)
    {
        $this->forward_menu = $menu;
        $this->forward_act = $act;
        $this->forward_flg = true;
    }

    /**
     * 画面URL跳转
     *
     * @param string $url 目标URL
     */
    public function redirect($url = "")
    {
        if (substr($url, 0, 4) != "http") {
            $url = SYSTEM_APP_HOST . $url;
        }
        header("Location: $url");
        exit();
    }

    /**
     * 生成一个错误信息
     */
    public function raiseError($err_code = ERROR_CODE_DEFAULT, $err_msg = null)
    {
        $error = Error::getInstance();
        $error->raiseError($err_code, $err_msg);
        return $error;
    }

    /**
     * 判断对象是否为错误对象
     *
     * @param object $obj 判断对象
     * @return boolean
     */
    public function isError($obj)
    {
        return Error::isError($obj);
    }

    /**
     * 获取本类实例化对象
     *
     * @return object
     */
    public static function getInstance()
    {
        return new Controller();
    }
}
?>