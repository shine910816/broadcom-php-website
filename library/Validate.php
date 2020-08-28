<?php

/**
 * 检测控制器
 * @author Kinsama
 * @version 2017-02-10
 */
class Validate
{

    /**
     * 检测值是否为empty(不全等)
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkNotEmpty($value)
    {
        return !empty($value);
    }

    /**
     * 检测值是否为null(全等)
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkNotNull($value)
    {
        if (is_null($value)) {
            return false;
        }
        if (is_array($value)) {
            if (count($value) == 0) {
                return false;
            }
            foreach ($value as $val) {
                if (!Validate::checkNotNull($val)) {
                    return false;
                }
            }
            return true;
        }
        return (strlen($value) != 0);
    }

    /**
     * 检测全角值是否为null(全等)
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkFullNotNull($value)
    {
        if (is_null($value)) {
            return false;
        }
        if (is_array($value)) {
            if (count($value) == 0) {
                return false;
            }
            foreach ($value as $val) {
                if (!Validate::checkFullNotNull($val)) {
                    return false;
                }
            }
            return true;
        }
        return (mb_strlen($value) != 0);
    }

    /**
     * 检测值是否为半角数字
     *
     * @param mixed $value 待检测值
     * @param array $opt 期待最小值最大值 'min' =>最小值(大于等于) 'max' =>最大值(小于等于)
     * @return boolean
     */
    public static function checkNumber($value, $opt = null)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!Validate::checkNumber($val, $opt)) {
                    return false;
                }
            }
        } else {
            if (!preg_match("/^\d+$/", $value)) {
                return false;
            }
            if (isset($opt["min"]) && ($value < $opt["min"])) {
                return false;
            }
            if (isset($opt["max"]) && ($value > $opt["max"])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测值是否为十进制半角数字
     *
     * @param mixed $value 待检测值
     * @param array $opt 期待最小值最大值 'min' =>最小值(大于等于) 'max' =>最大值(小于等于)
     * @return boolean
     */
    public static function checkDecimalNumber($value, $opt = null)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!Validate::checkDecimalNumber($val, $opt)) {
                    return false;
                }
            }
        } else {
            if (!preg_match("/^(((\-)?[1-9]\d*)|0)(.\d{1,})?$/", $value)) {
                return false;
            }
            if (isset($opt["min"]) && ($value < $opt["min"])) {
                return false;
            }
            if (isset($opt["max"]) && ($value > $opt["max"])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测值是否为半角英文
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkAlpha($value)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!Validate::checkAlpha($val)) {
                    return false;
                }
            }
        } else {
            if (!preg_match("/^[a-zA-Z]+$/", $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测值是否为半角英数字
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkAlphaNumber($value)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!Validate::checkAlphaNumber($val)) {
                    return false;
                }
            }
        } else {
            if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测值是否为全角汉字
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkChinese($value)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!Validate::checkChinese($val)) {
                    return false;
                }
            }
        } else {
            if (!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测值是否在指定文字数内
     *
     * @param mixed $value 待检测值
     * @param array $opt 期待最小值最大值 'min_length' =>最小值(大于等于) 'max_length' =>最大值(小于等于)
     * @return boolean
     */
    public static function checkLength($value, $opt)
    {
        if (is_null($value) || $value === '') {
            if (isset($opt["min_length"]) && ($opt["min_length"] > 0)) {
                return false;
            } else {
                return true;
            }
        }
        if (is_array($value)) {
            if (count($value) == 0) {
                if (isset($opt["min_length"]) && ($opt["min_length"] > 0)) {
                    return false;
                } else {
                    return true;
                }
            }
            foreach ($value as $val) {
                if (!Validate::checkLength($val, $opt)) {
                    return false;
                }
            }
        } else {
            $length = strlen($value);
            if ((isset($opt["min_length"]) && ($length < $opt["min_length"])) || (isset($opt["max_length"]) && ($length > $opt["max_length"]))) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测值是否在指定全角文字数内
     *
     * @param mixed $value 待检测值
     * @param array $opt 期待最小值最大值 'min_length' =>最小值(大于等于) 'max_length' =>最大值(小于等于)
     * @return boolean
     */
    public static function checkFullLength($value, $opt)
    {
        if (is_null($value) || $value === '') {
            if (isset($opt["min_length"]) && ($opt["min_length"] > 0)) {
                return false;
            } else {
                return true;
            }
        }
        if (is_array($value)) {
            if (count($value) == 0) {
                if (isset($opt["min_length"]) && ($opt["min_length"] > 0)) {
                    return false;
                } else {
                    return true;
                }
            }
            foreach ($value as $val) {
                if (!Validate::checkFullLength($val, $opt)) {
                    return false;
                }
            }
        } else {
            $length = mb_strlen($value);
            if ((isset($opt["min_length"]) && ($length < $opt["min_length"])) || (isset($opt["max_length"]) && ($length > $opt["max_length"]))) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测日期是否正确
     *
     * @param string $year 年
     * @param string $month 月
     * @param string $day 日
     * @return boolean
     */
    public static function checkDate($year, $month, $day)
    {
        if (!strlen($year) && !strlen($month) && !strlen($day)) {
            return true;
        } elseif (!strlen($year) || !strlen($month) || !strlen($day)) {
            return false;
        }
        if (!Validate::checkNumber($year) || !Validate::checkNumber($month, array(
            'min' => 1,
            'max' => 12
        )) || !Validate::checkNumber($day, array(
            'min' => 1,
            'max' => 31
        ))) {
            return false;
        }
        return checkdate($month, $day, $year);
    }

    /**
     * 检测时间是否正确
     *
     * @param string $hour 时
     * @param string $minute 分
     * @param string $second 秒
     * @return boolean
     */
    public static function checkTime($hour, $minute, $second)
    {
        if (!strlen($hour) && !strlen($minute) && !strlen($second)) {
            return true;
        } elseif (!strlen($hour) || !strlen($minute) || !strlen($second)) {
            return false;
        }
        if (!Validate::checkNumber($hour, array(
            'min' => 0,
            'max' => 23
        )) || !Validate::checkNumber($minute, array(
            'min' => 0,
            'max' => 59
        )) || !Validate::checkNumber($second, array(
            'min' => 0,
            'max' => 59
        ))) {
            return false;
        }
        return true;
    }

    /**
     * 检测日期时间是否正确
     *
     * @param string $year 年
     * @param string $month 月
     * @param string $day 日
     * @param string $hour 时
     * @param string $minute 分
     * @param string $second 秒
     * @return boolean
     */
    public static function checkDateTime($year, $month, $day, $hour, $minute, $second)
    {
        if (!strlen($year) && !strlen($month) && !strlen($day) && !strlen($hour) && !strlen($minute) && !strlen($second)) {
            return true;
        } elseif (!strlen($year) || !strlen($month) || !strlen($day) || !strlen($hour) || !strlen($minute) || !strlen($second)) {
            return false;
        }
        return Validate::checkDate($year, $month, $day) && Validate::checkTime($hour, $minute, $second);
    }

    /**
     * 检测IP地址是否正确
     *
     * @param string $value IP地址
     * @return boolean
     */
    public static function checkInternetProtocolAddress($value)
    {
        if (is_null($value) || $value === '') {
            return false;
        }
        if (!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $value)) {
            return false;
        }
        return Validate::checkDecimalNumber(explode(".", $value), array(
            "min" => 0,
            "max" => "255"
        ));
    }

    /**
     * 检测电子邮箱地址是否正确
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkMailAddress($value)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (!preg_match("/^[\w\.\-]+@[\w\-]+\.[a-z]{2,3}(\.[a-z]{2})?$/i", $value)) {
            return false;
        }
        return true;
    }

    /**
     * 检测手机号码是否正确
     *
     * @param mixed $value 待检测值
     * @return boolean
     */
    public static function checkMobileNumber($value)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (!preg_match("/^1\d{10}$/", $value)) {
            return false;
        }
        return true;
    }

    /**
     * 检测值在指定参数范围内
     *
     * @param mixed $value 待检测值
     * @param array $accept 范围
     * @return boolean
     */
    public static function checkAcceptParam($value, $accept)
    {
        if (is_null($value) || $value === '') {
            return true;
        }
        if (is_array($value)) {
            foreach ($value as $val) {
                if (!Validate::checkAcceptParam($val, $accept)) {
                    return false;
                }
            }
        } else {
            if (!in_array($value, $accept)) {
                return false;
            }
        }
        return true;
    }
}
?>