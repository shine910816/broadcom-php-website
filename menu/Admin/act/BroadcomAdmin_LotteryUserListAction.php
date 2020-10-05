<?php
require_once SRC_PATH . "/menu/Admin/lib/BroadcomAdminActionBase.php";

/**
 * 抽奖用户管理
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomAdmin_LotteryUserListAction extends BroadcomAdminActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("reset")) {
            $ret = $this->_doResetExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("change")) {
            $ret = $this->_doChangeExecute($controller, $user, $request);
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
        $l_id = "1";
        $lottery_info = BroadcomLotteryDBI::selectLotteryInfo($l_id);
        if ($controller->isError($lottery_info)) {
            $lottery_info->setPos(__FILE__, __LINE__);
            return $lottery_info;
        }
        $user_list = BroadcomLotteryDBI::selectLotteryList($l_id);
        if ($controller->isError($user_list)) {
            $user_list->setPos(__FILE__, __LINE__);
            return $user_list;
        }
        $result_user_list = array();
        foreach ($user_list as $u_id => $user_info) {
            $user_temp = array();
            $user_temp["u_id"] = $u_id;
            $user_temp["u_name"] = $user_info["u_name"];
            $user_temp["u_mobile"] = Utility::coverMobileNumber($user_info["u_mobile"]);
            $user_temp["u_level"] = $user_info["u_level"];
            $user_temp["l_id"] = $user_info["l_id"];
            $user_temp["l_name"] = $lottery_info["l_name"];
            $user_temp["l_period"] = $user_info["l_period"];
            $user_temp["l_drawn_flg"] = $user_info["l_drawn_flg"];
            $user_temp["l_drawn_date"] = $user_info["l_drawn_date"];
            $result_user_list[$u_id] = $user_temp;
        }
        $request->setAttribute("l_id", $l_id);
        $request->setAttribute("user_list", $result_user_list);
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

    private function _doChangeExecute(Controller $controller, User $user, Request $request)
    {
        $user_id = $request->getParameter("change");
        $user_list = $request->getAttribute("user_list");
        if (isset($user_list[$user_id])) {
            $update_data = array();
            if ($user_list[$user_id]["u_level"] == "1") {
                $update_data["u_level"] = "2";
            } else {
                $update_data["u_level"] = "1";
            }
            $update_res = BroadcomLotteryDBI::updateLottery($update_data, $user_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                return $update_res;
            }
        }
        $controller->redirect("./?menu=admin&act=lottery_user_list");
        return VIEW_DONE;
    }

    private function _doResetExecute(Controller $controller, User $user, Request $request)
    {
        $user_id = $request->getParameter("reset");
        $user_list = $request->getAttribute("user_list");
        if (isset($user_list[$user_id])) {
            $update_data = array();
            $update_data["l_drawn_flg"] = "0";
            $update_data["l_drawn_date"] = null;
            $update_res = BroadcomLotteryDBI::updateLottery($update_data, $user_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                return $update_res;
            }
        }
        $controller->redirect("./?menu=admin&act=lottery_user_list");
        return VIEW_DONE;
    }
}
?>