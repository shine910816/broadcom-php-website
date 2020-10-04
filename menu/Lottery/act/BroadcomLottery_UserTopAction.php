<?php
require_once SRC_PATH . "/menu/Lottery/lib/BroadcomLotteryActionBase.php";

/**
 * 抽奖用户画面
 * @author Kinsama
 * @version 2020-10-04
 */
class BroadcomLottery_UserTopAction extends BroadcomLotteryActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        }elseif ($request->hasParameter("do_register")) {
            $ret = $this->_doRegisterExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
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
        if ($this->_hasCookie()) {
            $controller->redirect("./lottery/number");
        }
        $current_ts = time();
        $overtime_ts = mktime(0, 0, 0, date("n", $current_ts), date("j", $current_ts) + 1, date("Y", $current_ts));
        $create_flg = false;
        $user_mobile = "";
        $user_name = "";
        if ($request->hasParameter("do_register")) {
            $user_mobile = $request->getParameter("user_mobile");
            $user_name = $request->getParameter("user_name");
            if (!Validate::checkMobileNumber($user_mobile)) {
                $request->setError("user_mobile", "请填写一个有效的手机号码");
                $request->setAttribute("user_mobile", $user_mobile);
                $request->setAttribute("user_name", $user_name);
                return VIEW_DONE;
            }
            $user_info = BroadcomLotteryDBI::selectLotteryInfoByMobile("1", $user_mobile);
            if ($controller->isError($user_info)) {
                $user_info->setPos(__FILE__, __LINE);
                return $user_info;
            }
            if (empty($user_info)) {
                $create_flg = true;
                $request->setAttribute("overtime_ts", $overtime_ts);
            } else {
                $cookie_info = array();
                $cookie_info["lottery_number"] = $user_info["u_id"];
                $cookie_info["user_name"] = $user_info["u_name"];
                $cookie_info["user_mobile"] = $user_info["u_mobile"];
                $request->setAttribute("cookie_info", $cookie_info);
            }
        }
        $request->setAttribute("create_flg", $create_flg);
        $request->setAttribute("user_mobile", $user_mobile);
        $request->setAttribute("user_name", $user_name);
        $request->setAttribute("overtime_ts", $overtime_ts);
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

    private function _doRegisterExecute(Controller $controller, User $user, Request $request)
    {
        $create_flg = $request->getAttribute("create_flg");
        $cookie_info = array();
        $overtime_ts = $request->getAttribute("overtime_ts");
        if ($create_flg) {
            $user_mobile = $request->getAttribute("user_mobile");
            $user_name = $request->getAttribute("user_name");
            $overtime_ts = $request->getAttribute("overtime_ts");
            $insert_data = array();
            $insert_data["u_name"] = $user_name;
            $insert_data["u_mobile"] = $user_mobile;
            $insert_data["u_level"] = $this->_checkList($user_mobile);
            // TODO 指定的抽奖项目
            $insert_data["l_id"] = "1";
            $insert_data["l_period"] = date("Y-m-d H:i:s", $overtime_ts);
            $insert_res = BroadcomLotteryDBI::insertLottery($insert_data);
            if ($controller->isError($insert_res)) {
                $insert_res->setPos(__FILE__, __LINE);
                return $insert_res;
            }
            $cookie_info["lottery_number"] = $insert_res;
            $cookie_info["user_name"] = $insert_data["u_name"];
            $cookie_info["user_mobile"] = $insert_data["u_mobile"];
        } else {
            $cookie_info = $request->getAttribute("cookie_info");
        }
        $this->_setCookie($cookie_info, $overtime_ts);
        $controller->redirect("./lottery/number");
        return VIEW_DONE;
    }

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>