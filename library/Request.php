<?php

/**
 * 数据控制器
 * @author Kinsama
 * @version 2016-12-30
 */
class Request
{

    /**
     * 页面menu
     */
    public $current_menu = SYSTEM_DEFAULT_MENU;

    /**
     * 页面act
     */
    public $current_act = SYSTEM_DEFAULT_ACT;

    /**
     * 页面page
     */
    public $current_page = 1;

    /**
     * 页面level
     */
    public $current_level = true;

    /**
     * 页面参数
     *
     * @access private
     */
    private $_parameter;

    /**
     * 画面显示变量
     *
     * @access private
     */
    private $_attribute = array();

    /**
     * 错误信息
     *
     * @access private
     */
    private $_error = array();

    /**
     * API标识
     */
    public $api_flg = false;

    /**
     * 初始化
     */
    public function __construct()
    {
        $parameter = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $_POST : $_GET;
        if (isset($parameter['menu']) && isset($parameter['act'])) {
            $this->current_menu = $parameter['menu'];
            $this->current_act = $parameter['act'];
        } elseif (isset($_GET['menu']) && isset($_GET['act'])) {
            $this->current_menu = $_GET['menu'];
            $this->current_act = $_GET['act'];
        } elseif (isset($_GET['t']) && isset($_GET['m'])) {
            // TODO
            //$this->current_menu = $_GET['menu'];
            //$this->current_act = $_GET['act'];
            $this->setApiParameter();
        }
        if (isset($parameter['page'])) {
            $this->current_page = $parameter['page'];
        }
        $this->_parameter = $parameter;
    }

    /**
     * 启动API参数识别
     */
    public function setApiParameter()
    {
        $this->api_flg = true;
        //if (isset($this->_parameter['menu'])) {
        //    $this->current_menu = $this->_parameter['menu'];
        //} elseif (isset($_GET['menu'])) {
        //    $this->current_menu = $_GET['menu'];
        //} else {
        //    $this->current_menu = SYSTEM_DEFAULT_API_MENU;
        //}
        //if (isset($this->_parameter['act'])) {
        //    $this->current_act = $this->_parameter['act'];
        //} elseif (isset($_GET['act'])) {
        //    $this->current_act = $_GET['act'];
        //} else {
        //    $this->current_act = "";
        //}
        
    }

    /**
     * 根据参数名判断参数是否存在
     *
     * @param string $name 参数名
     * @return boolean
     */
    public function hasParameter($name)
    {
        if (!array_key_exists($name, $this->_parameter)) {
            return false;
        }
        return true;
    }

    /**
     * 根据参数名获取参数值
     *
     * @param string $name 参数名
     * @return mixed
     */
    public function getParameter($name)
    {
        if (!$this->hasParameter($name)) {
            return null;
        }
        return $this->_parameter[$name];
    }

    /**
     * 根据多个参数名获取参数值
     *
     * @param array $names 参数名数组(索引序列)
     * @return mixed
     */
    public function getParametersByNames($names)
    {
        if (!is_array($names)) {
            return null;
        }
        $data = array();
        foreach ($names as $name) {
            if (!$this->hasParameter($name)) {
                $data[$name] = null;
            } else {
                $data[$name] = $this->getParameter($name);
            }
        }
        return $data;
    }

    /**
     * 从Submit按钮内获取参数
     *
     * @param string $name 参数名
     * @return array or Error Object
     */
    public function getParameterByInput($name)
    {
        $parameter = $this->getParameter($name);
        if (!is_array($parameter) || (is_array($parameter) && count($parameter) == 0)) {
            return null;
        }
        $key_arr = array_keys($parameter);
        if (count($key_arr) == 1) {
            return $key_arr[0];
        } else {
            return $key_arr;
        }
    }

    /**
     * 获取全部参数
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameter;
    }

    /**
     * 根据画面变量名判断画面变量是否存在
     *
     * @param string $name 画面变量名
     * @return boolean
     */
    public function hasAttribute($name)
    {
        if (!isset($this->_attribute[$name])) {
            return false;
        }
        return true;
    }

    /**
     * 根据画面变量名获取画面变量值
     *
     * @param string $name 画面变量名
     * @return mixed
     */
    public function getAttribute($name)
    {
        if (!$this->hasAttribute($name)) {
            return null;
        }
        return $this->_attribute[$name];
    }

    /**
     * 根据多个画面变量名获取画面变量值
     *
     * @param array $names 画面变量名数组(索引序列)
     * @return mixed
     */
    public function getAttributesByNames($names)
    {
        if (!is_array($names)) {
            return null;
        }
        $data = array();
        foreach ($names as $name) {
            if (!$this->hasAttribute($name)) {
                $data[$name] = null;
            } else {
                $data[$name] = $this->getAttribute($name);
            }
        }
        return $data;
    }

    /**
     * 获取全部画面变量
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attribute;
    }

    /**
     * 设置画面变量
     *
     * @param string $name 画面变量名
     * @param mixed $value 画面变量值
     */
    public function setAttribute($name, $value)
    {
        $this->_attribute[$name] = $value;
        return;
    }

    /**
     * 以数组设置画面变量
     *
     * @param array $value 画面变量数组
     */
    public function setAttributes($value)
    {
        if (!is_array($value)) {
            return;
        }
        foreach ($value as $tmp_key => $tmp_value) {
            $this->_attribute[$tmp_key] = $tmp_value;
        }
        return;
    }

    /**
     * 根据错误名判断错误是否存在
     *
     * @param string $name 错误名
     * @return boolean
     */
    public function isError($name = null)
    {
        if ($name === null) {
            if (empty($this->_error)) {
                return false;
            }
        } else {
            if (!isset($this->_error[$name])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取错误
     *
     * @param string $name 错误名
     * @return array
     */
    public function getError($name = null)
    {
        if ($name === null) {
            return $this->_error;
        } else {
            if (array_key_exists($name, $this->_error)) {
                return $this->_error[$name];
            } else {
                return null;
            }
        }
    }

    /**
     * 设置错误
     *
     * @param string $name 错误名
     * @param string $description 错误描述
     */
    public function setError($name, $description)
    {
        $this->_error[$name] = $description;
        return;
    }

    /**
     * 获取本类实例化对象
     *
     * @return object
     */
    public static function getInstance()
    {
        return new Request();
    }
}
?>