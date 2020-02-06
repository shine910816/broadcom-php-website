<?php
/**
 * 错误代码-默认
 */
define("ERROR_CODE_DEFAULT", "1000");
/**
 * 错误代码-ACTION文件不存在
 */
define("ERROR_CODE_NONE_ACTION_FILE", "1001");
/**
 * 错误代码-ACTION类不存在
 */
define("ERROR_CODE_NONE_ACTION_CLASS", "1002");
/**
 * 错误代码-TPL文件不存在
 */
define("ERROR_CODE_NONE_TPL_FILE", "1003");
/**
 * 错误代码-数据库参数
 */
define("ERROR_CODE_DATABASE_PARAM", "2001");
/**
 * 错误代码-数据库结果
 */
define("ERROR_CODE_DATABASE_RESULT", "2002");
/**
 * 错误代码-数据库结果
 */
define("ERROR_CODE_DATABASE_DISACCEPT", "2003");
/**
 * 错误代码-用户窜改画面
 */
define("ERROR_CODE_USER_FALSIFY", "3001");
/**
 * 错误代码-外部画面POST
 */
define("ERROR_CODE_OUTSIDE_FALSIFY", "3002");
/**
 * 错误代码-用户窜改验证码
 */
define("ERROR_CODE_VERIFY_FALSIFY", "3003");
/**
 * 错误代码-用户无权限
 */
define("ERROR_CODE_NO_AUTH", "3004");
/**
 * 错误代码-用户API获取失败
 */
define("ERROR_CODE_API_GET_FALSIFY", "4001");
/**
 * 错误代码-用户API存在错误
 */
define("ERROR_CODE_API_ERROR_FALSIFY", "4002");
/**
 * 错误代码-第三方API存在错误
 */
define("ERROR_CODE_THIRD_ERROR_FALSIFY", "5001");

/**
 * 错误警报
 *
 * @author Kinsama
 * @version 2016-12-30
 */
class Error
{

    /**
     * 错误信息
     */
    private $_err_msg;

    /**
     * 错误代码
     */
    public $err_code;

    /**
     * 错误发生时间
     */
    public $err_date;

    /**
     * 错误文件位置
     */
    private $_pos = array();

    /**
     * 错误标识
     */
    public $error_flg = false;

    /**
     * 生成一个错误信息
     */
    public function raiseError($err_code = ERROR_CODE_DEFAULT, $err_msg = null)
    {
        $this->err_code = $err_code;
        $this->_err_msg = $err_msg;
        $this->err_date = date("Y-m-d H:i:s");
        $this->error_flg = true;
    }

    /**
     * 设置错误文件及行数
     */
    public function setPos($file, $line)
    {
        $this->_pos[] = sprintf("%s(%d)", basename($file), $line);
    }

    /**
     * 记录错误信息
     */
    public function writeLog()
    {
        $postion = $this->_pos;
        $code_list = $this->getErrorCode();
        $text = sprintf("[%s] %s-%s", $this->err_date, $this->err_code, $code_list[$this->err_code]);
        if (!is_null($this->_err_msg)) {
            $text .= ": " . $this->_err_msg;
        }
        if (!empty($postion)) {
            krsort($postion);
            $text .= " in " . implode(", ", $postion);
        }
        $text .= "\n";
        $fo = fopen(SRC_PATH . "/temp/log/error.log", "a");
        fwrite($fo, $text);
        fclose($fo);
        return;
    }

    /**
     * 判断对象是否为错误对象
     *
     * @param object $obj 判断对象
     * @return boolean
     */
    public static function isError($obj)
    {
        if (!is_object($obj)) {
            return false;
        }
        if (!property_exists($obj, 'error_flg')) {
            return false;
        }
        if (!$obj->error_flg) {
            return false;
        }
        return true;
    }

    public function getMessage()
    {
        return $this->_err_msg;
    }

    /**
     * 获取错误编码信息
     */
    public function getErrorCode()
    {
        return array(
            ERROR_CODE_DEFAULT => '未知原因',
            ERROR_CODE_NONE_ACTION_FILE => '当前ACTION文件不存在',
            ERROR_CODE_NONE_ACTION_CLASS => '当前ACTION类不存在',
            ERROR_CODE_NONE_TPL_FILE => '当前TPL文件不存在',
            ERROR_CODE_DATABASE_PARAM => '数据库参数错误',
            ERROR_CODE_DATABASE_RESULT => '数据库结果错误',
            ERROR_CODE_DATABASE_DISACCEPT => '数据库既存数据不准确',
            ERROR_CODE_USER_FALSIFY => '用户窜改画面',
            ERROR_CODE_OUTSIDE_FALSIFY => '外部画面POST',
            ERROR_CODE_VERIFY_FALSIFY => '用户窜改验证码',
            ERROR_CODE_NO_AUTH => '用户无权限',
            ERROR_CODE_API_GET_FALSIFY => '用户API获取失败',
            ERROR_CODE_API_ERROR_FALSIFY => '用户API存在错误',
            ERROR_CODE_THIRD_ERROR_FALSIFY => '第三方插件存在错误'
        );
    }

    /**
     * 获取本类实例化对象
     */
    public static function getInstance()
    {
        return new Error();
    }
}
?>