<?php
require_once SRC_PATH . "/menu/Lottery/lib/BroadcomLotteryActionBase.php";

/**
 * 抽奖号码画面
 * @author Kinsama
 * @version 2020-10-04
 */
class BroadcomLottery_NumberAction extends BroadcomLotteryActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        $ret = $this->_doDefaultExecute($controller, $user, $request);
        if ($controller->isError($ret)) {
            $ret->setPos(__FILE__, __LINE__);
            return $ret;
        }
        return $ret;
    }

    /**
     * 执行参数检测
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainValidate(Controller $controller, User $user, Request $request)
    {
        $cookie_info = $this->_getCookie();
        $user_info = BroadcomLotteryDBI::selectLottery($cookie_info["lottery_number"]);
        if ($controller->isError($user_info)) {
            $user_info->setPos(__FILE__, __LINE__);
            return $user_info;
        }
        $request->setAttribute("user_info", $user_info);
        return VIEW_DONE;
    }

    /**
     * 执行默认命令
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     * @access private
     */
    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>