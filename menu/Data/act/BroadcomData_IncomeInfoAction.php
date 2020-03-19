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
        if (isset($course_income_info[$school_id])) {
            foreach ($course_income_info[$school_id] as $course_tmp) {
                if ($course_tmp["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION) {
                    $course_data["5"]["amount"] += $course_tmp["course_hours"];
                    $course_data["5"]["count"] += 1;
                } elseif ($course_tmp["item_type"] == BroadcomItemEntity::ITEM_TYPE_PRESENT) {
                    $course_data["6"]["amount"] += $course_tmp["course_hours"];
                    $course_data["6"]["count"] += 1;
                } else {
                    $course_data[$course_tmp["item_method"]]["amount"] += $course_tmp["course_hours"];
                    $course_data[$course_tmp["item_method"]]["count"] += 1;
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
        return VIEW_DONE;
    }
}
?>