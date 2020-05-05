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
        $result[] = array("achieve_info", "业绩数据");
        $result[] = array("target_info", "目标进度");
        $result[] = array("income_info", "确认收入");
        $result[] = array("surplus_info", "剩余价值");
        $request->setAttribute("left_content", $result);
        $request->setAttribute("period_select_file", SRC_PATH . "/menu/Data/tpl/BroadcomPeriodSelectView.tpl");
        return VIEW_DONE;
    }

    protected function _getStatisticsPeriod(Controller $controller, User $user, Request $request)
    {
        $period_type = "1";
        if ($request->hasParameter("period_type")) {
            $period_type = $request->getParameter("period_type");
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
        $start_date = $request->getAttribute("period_start_date");
        $end_date = $request->getAttribute("period_end_date");
        $school_id = $request->getAttribute("school_id");
        $member_id_list = $request->getAttribute("member_id_list");
        $teacher_flg = $request->getAttribute("teacher_flg");
        // 统计数据分布
        $achieve_type_list = BroadcomOrderEntity::getAchieveTypeList();
        $stats_item = array(
            "order_count" => 0,
            "order_amount" => 0,
            "cancel_order_count" => 0,
            "cancel_order_amount" => 0,
            "total_amount" => 0,
            "calculate_amount" => 0
        );
        $result_data = array();
        foreach ($achieve_type_list as $achieve_type => $achieve_type_name) {
            $result_data[$achieve_type] = $stats_item;
        }
        $order_item_stats = BroadcomStatisticsDBI::selectOrderItemCount($start_date, $end_date, $school_id, $member_id_list);
        if ($controller->isError($order_item_stats)) {
            $order_item_stats->setPos(__FILE__, __LINE__);
            return $order_item_stats;
        }
        if (!empty($order_item_stats)) {
            foreach ($order_item_stats as $achieve_type => $stats_tmp) {
                $result_data[$achieve_type]["order_count"] += $stats_tmp["order_count"];
                $result_data[$achieve_type]["order_amount"] += $stats_tmp["order_amount"];
                $result_data[$achieve_type]["total_amount"] += $stats_tmp["order_amount"];
                $result_data[$achieve_type]["calculate_amount"] += $stats_tmp["order_amount"];
            }
        }
        $cancel_order_item_stats = BroadcomStatisticsDBI::selectCancelOrderItemCount($start_date, $end_date, $school_id, $member_id_list);
        if ($controller->isError($cancel_order_item_stats)) {
            $cancel_order_item_stats->setPos(__FILE__, __LINE__);
            return $cancel_order_item_stats;
        }
        if (!empty($cancel_order_item_stats)) {
            foreach ($cancel_order_item_stats as $stats_tmp) {
                $result_data[$stats_tmp["achieve_type"]]["cancel_order_count"] += 1;
                $result_data[$stats_tmp["achieve_type"]]["cancel_order_amount"] += round($stats_tmp["order_item_payable_amount"] - $stats_tmp["order_item_trans_price"] * $stats_tmp["order_item_confirm"] * 1.05, 2);
            }
        }
        // 数据整合
        foreach ($result_data as $achieve_type => $achieve_stats_item) {
            $stats_item["order_count"] += $achieve_stats_item["order_count"];
            $stats_item["order_amount"] += $achieve_stats_item["order_amount"];
            $stats_item["cancel_order_count"] += $achieve_stats_item["cancel_order_count"];
            $stats_item["cancel_order_amount"] += $achieve_stats_item["cancel_order_amount"];
            $stats_item["total_amount"] += $achieve_stats_item["total_amount"];
            $stats_item["calculate_amount"] += $achieve_stats_item["calculate_amount"];
        }
        $achieve_type_list["5"] = "合计";
        $result_data["5"] = $stats_item;
        $average_amount = 0;
        if ($result_data["5"]["order_count"] > 0) {
            $average_amount = round($result_data["5"]["order_amount"] / $result_data["5"]["order_count"], 2);
        }
        // 消课统计
        $course_stats = BroadcomStatisticsDBI::selectCourseStatsDetail($start_date, $end_date, $school_id, $member_id_list, $teacher_flg);
        if ($controller->isError($course_stats)) {
            $course_stats->setPos(__FILE__, __LINE__);
            return $course_stats;
        }
Utility::testVariable($course_stats);
        $course_type_list = BroadcomItemEntity::getItemMethodList();
        $course_type_list["5"] = "试听课";
        $course_type_list["6"] = "赠课";
        $course_data = array();
        foreach ($course_type_list as $course_type => $tmp) {
            $course_data[$course_type] = 0;
        }
        $multi_course_id_list = array();
        if (iseet($course_stats[$school_id])) {
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
            }
            if ($course_tmp["multi_course_id"]) {
                if (!isset($multi_course_id_list[$course_tmp["multi_course_id"]])) {
                    $multi_course_id_list[$course_tmp["multi_course_id"]] = "1";
                    $course_data[$course_type]["count"] += $course_tmp["actual_course_hours"];
                }
            } else {
                $course_data[$course_type]["count"] += $course_tmp["actual_course_hours"];
            }
        }
        return array(
            "achieve_type_list" => $achieve_type_list,
            "achieve_data" => $result_data,
            "average_amount" => $average_amount,
            "course_type_list" => $course_type_list,
            "course_data" => $course_data
        );
    }
}
?>