<?php

/**
 * 日历-时间段选择
 * @token 2B2ECF74-5AD6-3897-AA2B-42567E035029
 * @author Kinsama
 * @version 2020-08-31
 */
class BroadcomCommon_CalendarAction extends ActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        $period_type = $request->getAttribute("period_type");
        $date_format_text = $request->getAttribute("date_format_text");
        $start_date_ts = 0;
        $end_date_ts = 0;
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
            //} else {
            //    $start_date = date("Y-m-d H:i:s", $start_date_ts);
            //    $end_date = date("Y-m-d H:i:s", $end_date_ts);
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
            //$start_date = date("Y-m-d H:i:s", $start_date_ts);
            //$end_date = date("Y-m-d H:i:s", $end_date_ts);
        } elseif ($period_type == "2") {
            $start_date_ts = mktime(0, 0, 0, $current_month, 1, $current_year);
            $end_date_ts = mktime(0, 0, -1, $current_month + 1, 1, $current_year);
            //$start_date = date("Y-m-d H:i:s", $start_date_ts);
            //$end_date = date("Y-m-d H:i:s", $end_date_ts);
        } elseif ($period_type == "3") {
            $start_date_ts = mktime(0, 0, 0, $current_month - 1, 1, $current_year);
            $end_date_ts = mktime(0, 0, -1, $current_month, 1, $current_year);
            //$start_date = date("Y-m-d H:i:s", $start_date_ts);
            //$end_date = date("Y-m-d H:i:s", $end_date_ts);
        }
        return array(
            "start" => date($date_format_text, $start_date_ts),
            "end" => date($date_format_text, $end_date_ts)
        );
    }

    /**
     * 执行参数检测
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainValidate(Controller $controller, User $user, Request $request)
    {
        $period_type = "1";
        if ($request->hasParameter("period_type")) {
            $period_type = $request->getParameter("period_type");
        }
        $period_type_allow_list = explode(",", "1,2,3,4");
        if (!Validate::checkAcceptParam($period_type, $period_type_allow_list)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $date_format_text = "Y-m-d";
        if ($request->hasParameter("time")) {
            $date_format_text .= " H:i:s";
        }
        $request->setAttribute("period_type", $period_type);
        $request->setAttribute("date_format_text", $date_format_text);
        return VIEW_NONE;
    }
}
?>