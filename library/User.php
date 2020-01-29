<?php

/**
 * 用户控制器
 * @author Kinsama
 * @version 2016-12-30
 */
class User
{

    /**
     * 初始化
     */
    public function __construct()
    {
        session_start();
        if (!$this->hasVariable("custom_id")) {
            $this->setVariable("custom_id", "0");
        }
        if (!$this->hasVariable("admin_lvl")) {
            $this->setVariable("admin_lvl", "0");
        }
    }

    /**
     * 检测用户是否登陆
     *
     * @return boolean
     */
    public function isLogin()
    {
        return $this->getVariable("custom_id") != "0";
    }

    /**
     * 检测用户是否具有管理权限
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->getVariable("admin_lvl") != "0";
    }

    public function getCustomId()
    {
        if ($this->isLogin()) {
            return $this->getVariable("custom_id");
        } else {
            return "0";
        }
    }

    public function getCustomNick()
    {
        if ($this->isLogin()) {
            return $this->getVariable("custom_nick");
        } else {
            return "";
        }
    }

    public function getAuthLevel()
    {
        if ($this->isLogin() && $this->isAdmin()) {
            return 3;
        } elseif ($this->isLogin() && !$this->isAdmin()) {
            return 2;
        } else {
            return 1;
        }
    }

    /**
     * 获取用户登录IP地址
     *
     * @return string
     */
    public function getRemoteAddr()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * 设置SESSION
     *
     * @param string $name SESSION名
     * @param mixed $value SESSION值
     * @return null
     */
    public function setVariable($name, $value)
    {
        $_SESSION[$name] = $value;
        return;
    }

    /**
     * 批量设置SESSION
     *
     * @param array $value SESSION名与值
     * @return null
     */
    public function setVariables($value)
    {
        if (!is_array($value)) {
            return;
        }
        foreach ($value as $var_key => $var_value) {
            $_SESSION[$var_key] = $var_value;
        }
        return;
    }

    /**
     * 根据SESSION名判断SESSION是否存在
     *
     * @param string $name SESSION名
     * @return boolean
     */
    public function hasVariable($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * 根据SESSION名获取SESSION值
     *
     * @param string $name SESSION名
     * @return mixed
     */
    public function getVariable($name)
    {
        if (!$this->hasVariable($name)) {
            return null;
        }
        return $_SESSION[$name];
    }

    /**
     * 根据多个SESSION名获取SESSION值
     *
     * @param array $names SESSION名数组(索引序列)
     * @return mixed
     */
    public function getVariablesByNames($names)
    {
        if (!is_array($names)) {
            return null;
        }
        $data = array();
        foreach ($names as $name) {
            if (!$this->hasVariable($name)) {
                $data[$name] = null;
            }
            $data[$name] = $this->getVariable($name);
        }
        return $data;
    }

    /**
     * 获取全部SESSION值
     *
     * @return array
     */
    public function getVariables()
    {
        return $_SESSION;
    }

    /**
     * 释放SESSION值
     *
     * @param string $name SESSION名数组(索引序列)
     * @return array
     */
    public function freeVariable($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * 获取本类实例化对象
     *
     * @return object
     */
    public static function getInstance()
    {
        return new User();
    }
}
?>