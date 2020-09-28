<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 成员模块基类
 * @author Kinsama
 * @version 2020-05-05
 */
class BroadcomHomeActionBase extends BroadcomDataActionBase
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
        return VIEW_DONE;
    }

    protected function _getStatistics(Controller $controller, User $user, Request $request)
    {
        // 周间数据统计
        $school_id = $user->member()->schoolId();
        $period_post_data = array();
        $period_post_data["period_type"] = "1";
        $repond_week_period = Utility::getJsonResponse("?t=2B2ECF74-5AD6-3897-AA2B-42567E035029&m=" . $user->member()->targetObjectId(), $period_post_data);
        if ($controller->isError($repond_week_period)) {
            $repond_week_period->setPos(__FILE__, __LINE__);
            return $repond_week_period;
        }
        $week_start_ts = strtotime($repond_week_period["start"] . " 00:00:00");
        $week_end_ts = strtotime($repond_week_period["end"] . " 00:00:00");
        $week_date_text = sprintf("%s月%s日~%s月%s日", date("n", $week_start_ts), date("j", $week_start_ts), date("n", $week_end_ts), date("j", $week_end_ts));
        $stats_post_data = array();
        $stats_post_data["school_id"] = $school_id;
        $stats_post_data["start_date"] = $repond_week_period["start"];
        $stats_post_data["end_date"] = $repond_week_period["end"];
        $repond_week_stats = Utility::getJsonResponse("?t=FD8BDE31-4601-DA6B-957C-2C76884C58A3&m=" . $user->member()->targetObjectId(), $stats_post_data);
        if ($controller->isError($repond_week_stats)) {
            $repond_week_stats->setPos(__FILE__, __LINE__);
            return $repond_week_stats;
        }
        $week_achieve_count = $repond_week_stats["achieve_data"]["5"]["order_count"];
        $week_achieve_amount = $repond_week_stats["achieve_data"]["5"]["order_amount"];
        $week_course_count = 0;
        foreach ($repond_week_stats["course_data"] as $course_type_key => $course_amount) {
            if ($course_type_key != "5") {
                $week_course_count += $course_amount;
            }
        }
        // 月间数据统计
        $period_post_data = array();
        $period_post_data["period_type"] = "2";
        $repond_month_period = Utility::getJsonResponse("?t=2B2ECF74-5AD6-3897-AA2B-42567E035029&m=" . $user->member()->targetObjectId(), $period_post_data);
        if ($controller->isError($repond_month_period)) {
            $repond_month_period->setPos(__FILE__, __LINE__);
            return $repond_month_period;
        }
        $month_start_ts = strtotime($repond_month_period["start"] . " 00:00:00");
        $month_date_text = sprintf("%s年%s月", date("Y", $month_start_ts), date("n", $month_start_ts));
        $stats_post_data = array();
        $stats_post_data["school_id"] = $school_id;
        $stats_post_data["start_date"] = $repond_month_period["start"];
        $stats_post_data["end_date"] = $repond_month_period["end"];
        $repond_month_stats = Utility::getJsonResponse("?t=FD8BDE31-4601-DA6B-957C-2C76884C58A3&m=" . $user->member()->targetObjectId(), $stats_post_data);
        if ($controller->isError($repond_month_stats)) {
            $repond_month_stats->setPos(__FILE__, __LINE__);
            return $repond_month_stats;
        }
        $month_achieve_count = $repond_month_stats["achieve_data"]["5"]["order_count"];
        $month_achieve_amount = $repond_month_stats["achieve_data"]["5"]["order_amount"];
        $month_actual_amount = $repond_month_stats["achieve_data"]["5"]["calculate_amount"];
        $month_course_count = 0;
        foreach ($repond_month_stats["course_data"] as $course_type_key => $course_amount) {
            if ($course_type_key != "5") {
                $month_course_count += $course_amount;
            }
        }
        // 目标获取
        $target_info = BroadcomTargetDBI::selectTarget($school_id, date("Ym"));
        if ($controller->isError($target_info)) {
            $target_info->setPos(__FILE__, __LINE__);
            return $target_info;
        }
        $month_course_percent = "-";
        if (!empty($target_info) && $target_info["course_target"] > 0) {
            $month_course_percent = number_format($month_course_count / $target_info["course_target"] * 100, 2) . "%";
        }
        return array(
            "week_date_text" => $week_date_text,
            "week_achieve_count" => number_format($week_achieve_count),
            "week_achieve_amount" => number_format($week_achieve_amount, 2),
            "week_course_count" => number_format($week_course_count, 1),
            "month_date_text" => $month_date_text,
            "month_achieve_count" => number_format($month_achieve_count),
            "month_achieve_amount" => number_format($month_achieve_amount, 2),
            "month_actual_amount" => number_format($month_actual_amount, 2),
            "month_course_count" => number_format($month_course_count, 1),
            "month_course_percent" => $month_course_percent
        );
    }
}
?>