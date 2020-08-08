<?php

/**
 * 成员模块基类
 * @author Kinsama
 * @version 2020-03-10
 */
class BroadcomDataActionBase extends ActionBase
{

    /**
     * 左边栏
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doLeftContent(Controller $controller, User $user, Request $request)
    {
        $result = array();
        if ($user->checkPositionAble("data", "achieve_info")) {
            $result[] = array("achieve_info", "业绩数据");
        }
        if ($user->checkPositionAble("data", "target_info")) {
            $result[] = array("target_info", "目标进度");
        }
        if ($user->checkPositionAble("data", "income_info")) {
            $result[] = array("income_info", "确认收入");
        }
        if ($user->checkPositionAble("data", "surplus_info")) {
            $result[] = array("surplus_info", "剩余价值");
        }
        $request->setAttribute("left_content", $result);
        $request->setAttribute("period_select_file", SRC_PATH . "/menu/Data/tpl/BroadcomPeriodSelectView.tpl");
        return VIEW_DONE;
    }

    protected function _getStatisticsPeriod(Controller $controller, User $user, Request $request)
    {
        $period_type = "1";
        if ($request->hasParameter("period_type")) {
            $period_type = $request->getParameter("period_type");
        } elseif ($request->hasAttribute("period_type_input")) {
            $period_type = $request->getAttribute("period_type_input");
        }
        $period_type_allow_list = array(
            "1" => "本周",
            "2" => "本月",
            "3" => "上月",
            "4" => "自定义"
        );
        if (!Validate::checkAcceptParam($period_type, array_keys($period_type_allow_list))) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $start_date = "";
        $end_date = "";
        if ($period_type == "4") {
            if (!$request->hasParameter("start") || !$request->hasParameter("end")) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $start_date_array = explode("-", $request->getParameter("start"));
            $end_date_array = explode("-", $request->getParameter("end"));
            if (!Validate::checkDate($start_date_array[0], $start_date_array[1], $start_date_array[2]) ||
                !Validate::checkDate($end_date_array[0], $end_date_array[1], $end_date_array[2])) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $start_date_ts = mktime(0, 0, 0, $start_date_array[1], $start_date_array[2], $start_date_array[0]);
            $end_date_ts = mktime(23, 59, 59, $end_date_array[1], $end_date_array[2], $end_date_array[0]);
            if ($start_date_ts > $end_date_ts) {
                $period_type == "1";
            } else {
                $start_date = date("Y-m-d H:i:s", $start_date_ts);
                $end_date = date("Y-m-d H:i:s", $end_date_ts);
            }
        }
        $current_date_ts = time();
        $current_year = date("Y", $current_date_ts);
        $current_month = date("n", $current_date_ts);
        $current_day = date("j", $current_date_ts);
        $current_week = date("N", $current_date_ts);
        if ($period_type == "1") {
            $start_date_ts = mktime(0, 0, 0, $current_month, $current_day - $current_week + 1, $current_year);
            $end_date_ts = mktime(0, 0, -1, $current_month, $current_day - $current_week + 8, $current_year);
            $start_date = date("Y-m-d H:i:s", $start_date_ts);
            $end_date = date("Y-m-d H:i:s", $end_date_ts);
        } elseif ($period_type == "2") {
            $start_date_ts = mktime(0, 0, 0, $current_month, 1, $current_year);
            $end_date_ts = mktime(0, 0, -1, $current_month + 1, 1, $current_year);
            $start_date = date("Y-m-d H:i:s", $start_date_ts);
            $end_date = date("Y-m-d H:i:s", $end_date_ts);
        } elseif ($period_type == "3") {
            $start_date_ts = mktime(0, 0, 0, $current_month - 1, 1, $current_year);
            $end_date_ts = mktime(0, 0, -1, $current_month, 1, $current_year);
            $start_date = date("Y-m-d H:i:s", $start_date_ts);
            $end_date = date("Y-m-d H:i:s", $end_date_ts);
        }
        return array(
            "period_type" => $period_type,
            "period_start_date" => $start_date,
            "period_end_date" => $end_date
        );
    }

    protected function _getStatsData(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $start_date = $request->getAttribute("period_start_date");
        $end_date = $request->getAttribute("period_end_date");
        $member_id_list = $request->getAttribute("member_id_list");
        $post_data = array(
            "school_id" => $school_id,
            "start_date" => $start_date,
            "end_date" => $end_date
        );
        if (!empty($member_id_list)) {
            $post_data["member_text"] = implode(",", $member_id_list);
        }
        $repond_order_list = Utility::getJsonResponse("?t=DD6BE1A4-420A-F46D-E42A-F72CACFB1E09&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_order_list)) {
            $repond_order_list->setPos(__FILE__, __LINE__);
            return $repond_order_list;
        }
        // 统计数据分布
        $achieve_type_list = BroadcomOrderEntity::getAchieveTypeList();
        $stats_item = array(
            "order_count" => 0,         // 签单数
            "order_amount" => 0,        // 签单金额
            "cancel_order_count" => 0,  // 打款退单数
            "cancel_order_amount" => 0, // 打款退单金额
            "total_amount" => 0,        // 实收金额
            "calculate_amount" => 0     // 减退费实收金额
        );
        $achieve_data = array();
        foreach ($achieve_type_list as $achieve_type => $achieve_type_name) {
            $achieve_data[$achieve_type] = $stats_item;
        }
        foreach ($repond_order_list as $order_id => $order_info) {
            $achieve_data[$order_info["achieve_type"]]["order_count"] += count($order_info["order_item"]);
            $achieve_data[$order_info["achieve_type"]]["order_amount"] += $order_info["order_payable"];
            foreach ($order_info["order_item"] as $order_item_id => $order_item_info) {
                if ($order_item_info["order_item_status"] == BroadcomOrderEntity::ORDER_ITEM_STATUS_4) {
                    $achieve_data[$order_info["achieve_type"]]["cancel_order_count"] += 1;
                }
            }
            foreach ($order_info["payment_history"] as $payment_amount) {
                if ($payment_amount > 0) {
                    $achieve_data[$order_info["achieve_type"]]["total_amount"] += $payment_amount;
                } else {
                    $achieve_data[$order_info["achieve_type"]]["cancel_order_amount"] -= $payment_amount;
                }
                $achieve_data[$order_info["achieve_type"]]["calculate_amount"] += $payment_amount;
            }
        }
        // 数据整合
        foreach ($achieve_data as $achieve_type => $achieve_stats_item) {
            $stats_item["order_count"] += $achieve_stats_item["order_count"];
            $stats_item["order_amount"] += $achieve_stats_item["order_amount"];
            $stats_item["cancel_order_count"] += $achieve_stats_item["cancel_order_count"];
            $stats_item["cancel_order_amount"] += $achieve_stats_item["cancel_order_amount"];
            $stats_item["total_amount"] += $achieve_stats_item["total_amount"];
            $stats_item["calculate_amount"] += $achieve_stats_item["calculate_amount"];
        }
        $achieve_type_list["5"] = "合计";
        $achieve_data["5"] = $stats_item;
        $average_amount = 0;
        if ($achieve_data["5"]["order_count"] > 0) {
            $average_amount = round($achieve_data["5"]["total_amount"] / $achieve_data["5"]["order_count"], 2);
        }
        // 消课统计
        $course_stats = BroadcomStatisticsDBI::selectCourseStatsDetail($start_date, $end_date, $school_id);
        if ($controller->isError($course_stats)) {
            $course_stats->setPos(__FILE__, __LINE__);
            return $course_stats;
        }
        $course_type_list = BroadcomItemEntity::getItemMethodList();
        $course_type_list["5"] = "试听课";
        $course_type_list["6"] = "赠课";
        $course_data = array();
        foreach ($course_type_list as $course_type => $tmp) {
            $course_data[$course_type] = 0;
        }
        $multi_course_id_list = array();
        if (isset($course_stats[$school_id])) {
            $audition_list = array(
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
            );
            foreach ($course_stats[$school_id] as $course_tmp) {
                $course_type = $course_tmp["item_method"];
                if (in_array($course_tmp["course_type"], $audition_list)) {
                    $course_type = "5";
                } elseif ($course_tmp["item_type"] == BroadcomItemEntity::ITEM_TYPE_PRESENT) {
                    $course_type = "6";
                }
                if ($course_tmp["multi_course_id"]) {
                    if (!isset($multi_course_id_list[$course_tmp["multi_course_id"]])) {
                        $multi_course_id_list[$course_tmp["multi_course_id"]] = "1";
                        $course_data[$course_type] += $course_tmp["actual_course_hours"];
                    }
                } else {
                    $course_data[$course_type] += $course_tmp["actual_course_hours"];
                }
            }
        }
        return array(
            "achieve_type_list" => $achieve_type_list,
            "achieve_data" => $achieve_data,
            "average_amount" => $average_amount,
            "course_type_list" => $course_type_list,
            "course_data" => $course_data
        );
    }
}
?>
