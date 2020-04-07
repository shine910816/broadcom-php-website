<?php

/**
 * 数据库登录信息
 * @author Kinsama
 * @version 2020-04-08
 */
class ConnectInfo
{

    private $_host = "127.0.0.1";
    private $_user = "root";
    private $_pswd = "";
    private $_name = "broadcom";
    private $_port = "3306";

    public function host()
    {
        return $this->_host;
    }

    public function user()
    {
        return $this->_user;
    }

    public function password()
    {
        return $this->_pswd;
    }

    public function databaseName()
    {
        return $this->_name;
    }

    public function port()
    {
        return $this->_port;
    }
}
?>