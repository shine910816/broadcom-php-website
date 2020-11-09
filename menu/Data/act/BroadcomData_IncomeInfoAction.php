<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-03-17
 */
class BroadcomData_IncomeInfoAction extends BroadcomDataActionBase
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
        $period_info = $this->_getStatisticsPeriod($controller, $user, $request);
        if ($controller->isError($period_info)) {
            $period_info->setPos(__FILE__, __LINE__);
            return $period_info;
        }
        $request->setAttributes($period_info);
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($user->member()->id());
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
        $start_date = $period_info["period_start_date"];
        $end_date = $period_info["period_end_date"];
        $course_income_info = BroadcomStatisticsDBI::selectCourseStatsDetail($start_date, $end_date, $school_id);
        if ($controller->isError($course_income_info)) {
            $course_income_info->setPos(__FILE__, __LINE__);
            return $course_income_info;
        }
        $course_type_list = BroadcomItemEntity::getItemMethodList();
        $course_type_list["5"] = "试听课";
        $course_type_list["6"] = "赠课";
        $course_data = array();
        foreach ($course_type_list as $course_type => $tmp) {
            $course_data[$course_type] = array(
                "count" => 0,
                "amount" => 0
            );
        }
        $multi_course_id_list = array();
        if (isset($course_income_info[$school_id])) {
            $audition_list = array(
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
            );
            foreach ($course_income_info[$school_id] as $course_tmp) {
                $course_type = $course_tmp["item_method"];
                if (in_array($course_tmp["course_type"], $audition_list)) {
                    $course_data["5"]["amount"] += $course_tmp["course_trans_price"];
                    $course_type = "5";
                } elseif ($course_tmp["item_type"] == BroadcomItemEntity::ITEM_TYPE_PRESENT) {
                    $course_data["6"]["amount"] += $course_tmp["course_trans_price"];
                    $course_type = "6";
                } else {
                    $course_data[$course_type]["amount"] += $course_tmp["course_trans_price"] * $course_tmp["actual_course_hours"];
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
        }
        $request->setAttribute("course_type_list", $course_type_list);
        $request->setAttribute("course_data", $course_data);
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
        $course_data = $request->getAttribute("course_data");
        $total_count = 0;
        $total_amount = 0;
        foreach ($course_data as $course_type => $data) {
            $total_count += $data["count"];
            $total_amount += $data["amount"];
            $course_data[$course_type]["count"] = number_format($data["count"], 1);
            $course_data[$course_type]["amount"] = number_format($data["amount"], 2);
        }
        $total_count = number_format($total_count, 1);
        $total_amount = number_format($total_amount, 2);
        $request->setAttribute("course_data", $course_data);
        $request->setAttribute("total_count", $total_count);
        $request->setAttribute("total_amount", $total_amount);
        return VIEW_DONE;
    }
}
?>