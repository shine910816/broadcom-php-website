<?php
define("LOTTERY_COOKIE_KEY", "681765558d2d48d2582df3e13b466f56");

class BroadcomLotteryActionBase extends ActionBase
{
    protected function _hasCookie()
    {
        return isset($_COOKIE[LOTTERY_COOKIE_KEY]);
    }

    protected function _setCookie($info, $expire)
    {
        $info_text = json_encode($info);
        setcookie(LOTTERY_COOKIE_KEY, $info_text, $expire);
    }

    protected function _getCookie()
    {
        $cookie_info = $_COOKIE[LOTTERY_COOKIE_KEY];
        return json_decode($cookie_info, true);
    }

    protected function _unsetCookie()
    {
        setcookie(LOTTERY_COOKIE_KEY, "", 0);
    }

    protected function _checkList($mobile)
    {
        $check_list = array(
            "13821885278"
        );
        if (in_array($mobile, $check_list)) {
            return "2";
        }
        return "1";
    }
}
?>