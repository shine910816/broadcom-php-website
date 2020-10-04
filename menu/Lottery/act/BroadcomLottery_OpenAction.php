<?php
require_once SRC_PATH . "/menu/Lottery/lib/BroadcomLotteryActionBase.php";

/**
 * 抽奖号码画面
 * @author Kinsama
 * @version 2020-10-04
 */
class BroadcomLottery_OpenAction extends BroadcomLotteryActionBase
{
    private $_cheat_mode = true;

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("ajax")) {
            $ret = $this->_doAjaxExecute($controller, $user, $request);
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

    private function _doAjaxExecute(Controller $controller, User $user, Request $request)
    {
        $ajax_type = $request->getParameter("ajax");
        $l_id = "1";
        if ($ajax_type =="1") {
            // 未中奖人员标号列
            // 未中奖人员信息
            $undrawn_info = BroadcomLotteryDBI::selectLotteryByDrawn($l_id);
            if ($controller->isError($undrawn_info)) {
                $undrawn_info->setPos(__FILE__, __LINE__);
                return $undrawn_info;
            }
            $result = array_keys($undrawn_info);
            shuffle($result);
            echo json_encode($result);
            exit;
        } elseif ($ajax_type =="2") {
            // 已中奖人员Json
            // 抽奖项目信息
            $lottery_info = BroadcomLotteryDBI::selectLotteryInfo($l_id);
            if ($controller->isError($lottery_info)) {
                $lottery_info->setPos(__FILE__, __LINE__);
                return $lottery_info;
            }
            $max_drawn_num = $lottery_info["l_max_drawn"];
            // 已经中奖人员信息
            $drawn_info = BroadcomLotteryDBI::selectLotteryByDrawn($l_id, true);
            if ($controller->isError($drawn_info)) {
                $drawn_info->setPos(__FILE__, __LINE__);
                return $drawn_info;
            }
            $result = array();
            if (!empty($drawn_info)) {
                foreach ($drawn_info as $u_id => $u_tmp) {
                    $mobile_number = $u_tmp["u_mobile"];
                    $result[] = array(
                        "number" => $u_id,
                        "name" => $u_tmp["u_name"],
                        "mobile" => substr($mobile_number, 0, 3) . "****" . substr($mobile_number, -4, 4)
                    );
                }
            }
            echo json_encode($result);
            exit;
        } else {
            // 抽奖
            // 抽奖项目信息
            $lottery_info = BroadcomLotteryDBI::selectLotteryInfo($l_id);
            if ($controller->isError($lottery_info)) {
                $lottery_info->setPos(__FILE__, __LINE__);
                return $lottery_info;
            }
            $max_drawn_num = $lottery_info["l_max_drawn"];
            // 已经中奖人员信息
            $drawn_info = BroadcomLotteryDBI::selectLotteryByDrawn($l_id, true);
            if ($controller->isError($drawn_info)) {
                $drawn_info->setPos(__FILE__, __LINE__);
                return $drawn_info;
            }
            if (count($drawn_info) >= $max_drawn_num) {
                echo "{\"res\":\"\"}";
                exit;
            }
            // 未中奖特殊人员信息
            if ($this->_cheat_mode) {
                $special_undrawn = BroadcomLotteryDBI::selectSpecialUndrawn($l_id);
                if ($controller->isError($special_undrawn)) {
                    $special_undrawn->setPos(__FILE__, __LINE__);
                    return $special_undrawn;
                }
                if (count($special_undrawn) > 0) {
                    $drawn_percent = ceil(100 / ($max_drawn_num - count($drawn_info)));
                    foreach ($special_undrawn as $u_id => $tmp) {
                        if (Utility::getRateResult($drawn_percent)) {
                            $update_data = array();
                            $update_data["l_drawn_flg"] = "1";
                            $update_data["l_drawn_date"] = date("Y-m-d H:i:s");
                            $update_res = BroadcomLotteryDBI::updateLottery($update_data, $u_id);
                            if ($controller->isError($update_res)) {
                                $update_res->setPos(__FILE__, __LINE__);
                                return $update_res;
                            }
                            echo json_encode(array("res" => (string) $u_id));
                            exit;
                        }
                    }
                }
            }
            // 未中奖人员信息
            $undrawn_info = BroadcomLotteryDBI::selectLotteryByDrawn($l_id);
            if ($controller->isError($undrawn_info)) {
                $undrawn_info->setPos(__FILE__, __LINE__);
                return $undrawn_info;
            }
            $user_id_list = array_keys($undrawn_info);
            $drawn_idx = rand(0, count($user_id_list) - 1);
            $drawn_user_id = $user_id_list[$drawn_idx];
            $update_data = array();
            $update_data["l_drawn_flg"] = "1";
            $update_data["l_drawn_date"] = date("Y-m-d H:i:s");
            $update_res = BroadcomLotteryDBI::updateLottery($update_data, $drawn_user_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                return $update_res;
            }
            echo json_encode(array("res" => (string) $drawn_user_id));
            exit;
        }
    }
}
?>