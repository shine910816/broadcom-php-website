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
        $period_start_date = date("Y-m-d", mktime(0, 0, 0, date("n", $current_date_ts), 1, date("Y", $current_date_ts)));
        $period_end_date = date("Y-m-d", mktime(0, 0, -1, date("n", $current_date_ts) + 1, 1, date("Y", $current_date_ts)));
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
        $school_id = $user->member()->schoolId();
        $start_date = $request->getAttribute("period_start_date");
        $end_date = $request->getAttribute("period_end_date");
        // 初始化数据
        $front_actual_amount = 0;
        $back_actual_amount = 0;
        $total_actual_amount = 0;
        $course_actual_amount = 0;
        $post_data = array(
            "school_id" => $school_id,
            "start_date" => $start_date,
            "end_date" => $end_date
        );
        $repond_total_info = Utility::getJsonResponse("?t=FD8BDE31-4601-DA6B-957C-2C76884C58A3&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_total_info)) {
            $repond_total_info->setPos(__FILE__, __LINE__);
            return $repond_total_info;
        }
        $total_actual_amount = $repond_total_info["achieve_data"]["5"]["calculate_amount"];
        foreach ($repond_total_info["course_data"] as $course_type_key => $course_amount) {
            // TODO 试听课不算在统计范围内
            if ($course_type_key != "5") {
                $course_actual_amount += $course_amount;
            }
        }
        $member_list = BroadcomMemberPositionDBI::selectMemberListBySchoolGroupPosition($school_id);
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        if (isset($member_list[BroadcomMemberEntity::SECTION_5])) {
            $post_data["member_text"] = implode(",", array_keys($member_list[BroadcomMemberEntity::SECTION_5]));
            $repond_front_info = Utility::getJsonResponse("?t=FD8BDE31-4601-DA6B-957C-2C76884C58A3&m=" . $user->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_front_info)) {
                $repond_front_info->setPos(__FILE__, __LINE__);
                return $repond_front_info;
            }
            $front_actual_amount = $repond_front_info["achieve_data"]["5"]["calculate_amount"];
        }
        if (isset($member_list[BroadcomMemberEntity::SECTION_2])) {
            $post_data["member_text"] = implode(",", array_keys($member_list[BroadcomMemberEntity::SECTION_2]));
            $repond_back_info = Utility::getJsonResponse("?t=FD8BDE31-4601-DA6B-957C-2C76884C58A3&m=" . $user->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_back_info)) {
                $repond_back_info->setPos(__FILE__, __LINE__);
                return $repond_back_info;
            }
            $back_actual_amount = $repond_back_info["achieve_data"]["5"]["calculate_amount"];
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
            "1" => array(
                "target" => "-",
                "actual" => $front_actual_amount,
                "percent" => "-",
                "amount" => "-",
                "red" => false
            ),
            "2" => array(
                "target" => "-",
                "actual" => $back_actual_amount,
                "percent" => "-",
                "amount" => "-",
                "red" => false
            ),
            "3" => array(
                "target" => "-",
                "actual" => $total_actual_amount,
                "percent" => "-",
                "amount" => "-",
                "red" => false
            ),
            "4" => array(
                "target" => "-",
                "actual" => $course_actual_amount,
                "percent" => "-",
                "amount" => "-",
                "red" => false
            )
        );
        if (!empty($target_info)) {
            if ($target_info["front_target"] > 0) {
                $target_result["1"]["target"] = $target_info["front_target"];
                $target_result["1"]["percent"] = number_format($target_result["1"]["actual"] / $target_result["1"]["target"] * 100, 1) . "%";
                $target_result["1"]["amount"] = $target_result["1"]["actual"] - $target_result["1"]["target"];
                if ($target_result["1"]["amount"] < 0) {
                    $target_result["1"]["red"] = true;
                }
                $target_result["1"]["target"] = number_format($target_result["1"]["target"], 2) . "元";
                $target_result["1"]["amount"] = number_format($target_result["1"]["amount"], 2) . "元";
            }
            if ($target_info["back_target"] > 0) {
                $target_result["2"]["target"] = $target_info["back_target"];
                $target_result["2"]["percent"] = number_format($target_result["2"]["actual"] / $target_result["2"]["target"] * 100, 1) . "%";
                $target_result["2"]["amount"] = $target_result["2"]["actual"] - $target_result["2"]["target"];
                if ($target_result["2"]["amount"] < 0) {
                    $target_result["2"]["red"] = true;
                }
                $target_result["2"]["target"] = number_format($target_result["2"]["target"], 2) . "元";
                $target_result["2"]["amount"] = number_format($target_result["2"]["amount"], 2) . "元";
            }
            if ($target_info["total_target"] > 0) {
                $target_result["3"]["target"] = $target_info["total_target"];
                $target_result["3"]["percent"] = number_format($target_result["3"]["actual"] / $target_result["3"]["target"] * 100, 1) . "%";
                $target_result["3"]["amount"] = $target_result["3"]["actual"] - $target_result["3"]["target"];
                if ($target_result["3"]["amount"] < 0) {
                    $target_result["3"]["red"] = true;
                }
                $target_result["3"]["target"] = number_format($target_result["3"]["target"], 2) . "元";
                $target_result["3"]["amount"] = number_format($target_result["3"]["amount"], 2) . "元";
            }
            if ($target_info["course_target"] > 0) {
                $target_result["4"]["target"] = $target_info["course_target"];
                $target_result["4"]["percent"] = number_format($target_result["4"]["actual"] / $target_result["4"]["target"] * 100, 1) . "%";
                $target_result["4"]["amount"] = $target_result["4"]["actual"] - $target_result["4"]["target"];
                if ($target_result["4"]["amount"] < 0) {
                    $target_result["4"]["red"] = true;
                }
                $target_result["4"]["target"] = number_format($target_result["4"]["target"], 1) . "小时";
                $target_result["4"]["amount"] = number_format($target_result["4"]["amount"], 1) . "小时";
            }
        }
        $target_result["1"]["actual"] = number_format($target_result["1"]["actual"], 2) . "元";
        $target_result["2"]["actual"] = number_format($target_result["2"]["actual"], 2) . "元";
        $target_result["3"]["actual"] = number_format($target_result["3"]["actual"], 2) . "元";
        $target_result["4"]["actual"] = number_format($target_result["4"]["actual"], 1) . "小时";
        $request->setAttribute("target_type_list", $target_type_list);
        $request->setAttribute("target_data", $target_result);
        return VIEW_DONE;
    }
}
?>