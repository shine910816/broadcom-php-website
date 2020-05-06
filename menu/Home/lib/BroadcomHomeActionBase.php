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
        $school_id = $user->member()->schoolId();
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("member_id_list", null);
        $request->setAttribute("teacher_flg", false);
        $week_date_info = $this->_getStatisticsPeriod($controller, $user, $request);
        if ($controller->isError($week_date_info)) {
            $week_date_info->setPos(__FILE__, __LINE__);
            return $week_date_info;
        }
        $request->setAttributes($week_date_info);
        $week_achieve_info = $this->_getStatsData($controller, $user, $request);
        if ($controller->isError($week_achieve_info)) {
            $week_achieve_info->setPos(__FILE__, __LINE__);
            return $week_achieve_info;
        }
        $week_achieve_count = $week_achieve_info["achieve_data"]["5"]["order_count"];
        $week_achieve_amount = $week_achieve_info["achieve_data"]["5"]["calculate_amount"];
        $week_course_count = array_sum($week_achieve_info["course_data"]);
        $request->setAttribute("period_type_input", "2");
        $month_date_info = $this->_getStatisticsPeriod($controller, $user, $request);
        if ($controller->isError($month_date_info)) {
            $month_date_info->setPos(__FILE__, __LINE__);
            return $month_date_info;
        }
        $request->setAttributes($month_date_info);
        $month_achieve_info = $this->_getStatsData($controller, $user, $request);
        if ($controller->isError($month_achieve_info)) {
            $month_achieve_info->setPos(__FILE__, __LINE__);
            return $month_achieve_info;
        }
        $month_achieve_count = $month_achieve_info["achieve_data"]["5"]["order_count"];
        $month_achieve_amount = $month_achieve_info["achieve_data"]["5"]["calculate_amount"];
        $month_actual_amount = $month_achieve_info["achieve_data"]["5"]["total_amount"];
        $month_course_count = array_sum($month_achieve_info["course_data"]);
        $target_info = BroadcomTargetDBI::selectTarget($school_id, date("Ym"));
        if ($controller->isError($target_info)) {
            $target_info->setPos(__FILE__, __LINE__);
            return $target_info;
        }
        $month_course_percent = "-";
        if (!empty($target_info) && $target_info["course_target"] > 0) {
            $month_course_percent = round($month_course_count / $target_info["course_target"] * 100, 2) . "%";
        }
        return array(
            "week_achieve_count" => $week_achieve_count,
            "week_achieve_amount" => $week_achieve_amount,
            "week_course_count" => $week_course_count,
            "month_achieve_count" => $month_achieve_count,
            "month_achieve_amount" => $month_achieve_amount,
            "month_actual_amount" => $month_actual_amount,
            "month_course_count" => $month_course_count,
            "month_course_percent" => $month_course_percent
        );
    }
}
?>