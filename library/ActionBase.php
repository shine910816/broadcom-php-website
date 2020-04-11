<?php

/**
 * 独立模型基类
 * @author Kinsama
 * @version 2017-01-03
 */
class ActionBase
{

    /**
     * 主执行
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_NONE;
    }

    /**
     * 主检查
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doMainValidate(Controller $controller, User $user, Request $request)
    {
        return VIEW_NONE;
    }

    /**
     * 左边栏
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doLeftContent(Controller $controller, User $user, Request $request)
    {
        return array();
    }

    protected function _getPeriodDate(Controller $controller, User $user, Request $request)
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
            if (!$request->hasParameter("start_date") || !$request->hasParameter("end_date")) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $start_date_array = explode("-", $request->getParameter("start_date"));
            $end_date_array = explode("-", $request->getParameter("end_date"));
            if (!Validate::checkDate($start_date_array[0], $start_date_array[1], $start_date_array[2]) ||
                !Validate::checkDate($end_date_array[0], $end_date_array[1], $end_date_array[2])) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $start_date_ts = mktime(0, 0, 0, $start_date_array[1], $start_date_array[2], $start_date_array[0]);
            $end_date_ts = mktime(23, 59, 59, $end_date_array[1], $end_date_array[2], $end_date_array[0]);
            if ($start_date_ts > $end_date_ts) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
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
}
?>