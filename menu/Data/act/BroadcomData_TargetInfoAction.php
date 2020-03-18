<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-03-17
 */
class BroadcomData_TargetInfoAction extends BroadcomDataActionBase
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
        $current_date = date("Ym");
        if ($request->hasParameter("date")) {
            $current_date = $request->getParameter("date");
        }
        $current_date_ts = mktime(0, 0, 0, substr($current_date, 4, 2), 1, substr($current_date, 0, 4));
        $date_text = date("Y", $current_date_ts) . "年" . date("n", $current_date_ts) . "月";
        $date_prev = date("Ym", mktime(0, 0, 0, date("n", $current_date_ts) - 1, 1, date("Y", $current_date_ts)));
        $date_next = date("Ym", mktime(0, 0, 0, date("n", $current_date_ts) + 1, 1, date("Y", $current_date_ts)));
        $period_start_date = date("Y-m-d H:i:s", mktime(0, 0, 0, date("n", $current_date_ts), 1, date("Y", $current_date_ts)));
        $period_end_date = date("Y-m-d H:i:s", mktime(0, 0, -1, date("n", $current_date_ts) + 1, 1, date("Y", $current_date_ts)));
        $request->setAttribute("target_date", $current_date);
        $request->setAttribute("date_text", $date_text);
        $request->setAttribute("date_prev", $date_prev);
        $request->setAttribute("date_next", $date_next);
        $request->setAttribute("period_start_date", $period_start_date);
        $request->setAttribute("period_end_date", $period_end_date);
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
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($user->getMemberId());
        if ($controller->isError($position_info)) {
            $position_info->setPos(__FILE__, __LINE__);
            return $position_info;
        }
        if (empty($position_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $position_info["school_id"];
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("teacher_flg", false);
        $member_list = BroadcomMemberPositionDBI::selectMemberListBySchoolGroupPosition($school_id);
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        $member_id_list = null;
        $request->setAttribute("member_id_list", $member_id_list);
        $total_stats = $this->_getStatsData($controller, $user, $request);
        if ($controller->isError($total_stats)) {
            $total_stats->setPos(__FILE__, __LINE__);
            return $total_stats;
        }
        if (isset($member_list["1"])) {
            $request->setAttribute("member_id_list", array_keys($member_list["1"]));
            $front_stats = $this->_getStatsData($controller, $user, $request);
            if ($controller->isError($front_stats)) {
                $front_stats->setPos(__FILE__, __LINE__);
                return $front_stats;
            }
        } else {
            $front_stats = $total_stats;
        }
        if (isset($member_list["2"])) {
            $request->setAttribute("member_id_list", array_keys($member_list["2"]));
            $back_stats = $this->_getStatsData($controller, $user, $request);
            if ($controller->isError($back_stats)) {
                $back_stats->setPos(__FILE__, __LINE__);
                return $back_stats;
            }
        } else {
            $back_stats = $total_stats;
        }
        $front_actual_amount = $front_stats["achieve_data"]["4"]["calculate_amount"];
        $back_actual_amount = $back_stats["achieve_data"]["4"]["calculate_amount"];
        $total_actual_amount = $total_stats["achieve_data"]["4"]["calculate_amount"];
        $course_actual_amount = 0;
        foreach ($total_stats["course_data"] as $course_amount) {
            $course_actual_amount += $course_amount;
        }
        $target_date = $request->getAttribute("target_date");
        $target_info = BroadcomTargetDBI::selectTarget($school_id, $target_date);
        if ($controller->isError($target_info)) {
            $target_info->setPos(__FILE__, __LINE__);
            return $target_info;
        }
        $target_item = array(
            "target" => "-",
            "actual" => "0",
            "percent" => "-",
            "amount" => "-"
        );
        $target_type_list = array(
            "1" => "营销实收",
            "2" => "学管实收",
            "3" => "合计实收",
            "4" => "消课课时"
        );
        $target_result = array(
            "1" => $target_item,
            "2" => $target_item,
            "3" => $target_item,
            "4" => $target_item
        );
        $target_result["1"]["actual"] = $front_actual_amount;
        $target_result["2"]["actual"] = $back_actual_amount;
        $target_result["3"]["actual"] = $total_actual_amount;
        $target_result["4"]["actual"] = $course_actual_amount;
        if (!empty($target_info)) {
            if ($target_info["front_target"] > 0) {
                $target_result["1"]["target"] = $target_info["front_target"];
                $target_result["1"]["percent"] = sprintf("%.1f", $target_result["1"]["actual"] / $target_result["1"]["target"] * 100) . "%";
                $target_result["1"]["amount"] = $target_result["1"]["actual"] - $target_result["1"]["target"];
            }
            if ($target_info["back_target"] > 0) {
                $target_result["2"]["target"] = $target_info["back_target"];
                $target_result["2"]["percent"] = sprintf("%.1f", $target_result["2"]["actual"] / $target_result["2"]["target"] * 100) . "%";
                $target_result["2"]["amount"] = $target_result["2"]["actual"] - $target_result["2"]["target"];
            }
            if ($target_info["total_target"] > 0) {
                $target_result["3"]["target"] = $target_info["total_target"];
                $target_result["3"]["percent"] = sprintf("%.1f", $target_result["3"]["actual"] / $target_result["3"]["target"] * 100) . "%";
                $target_result["3"]["amount"] = $target_result["3"]["actual"] - $target_result["3"]["target"];
            }
            if ($target_info["course_target"] > 0) {
                $target_result["4"]["target"] = $target_info["course_target"];
                $target_result["4"]["percent"] = sprintf("%.1f", $target_result["4"]["actual"] / $target_result["4"]["target"] * 100) . "%";
                $target_result["4"]["amount"] = $target_result["4"]["actual"] - $target_result["4"]["target"];
            }
        }
        $request->setAttribute("target_type_list", $target_type_list);
        $request->setAttribute("target_data", $target_result);
        return VIEW_DONE;
    }
}
?>