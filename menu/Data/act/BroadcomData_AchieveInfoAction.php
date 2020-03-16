<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-03-10
 */
class BroadcomData_AchieveInfoAction extends BroadcomDataActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("ajax")) {
            $ret = $this->_doAjaxExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
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
        if ($request->hasParameter("ajax")) {
            $ret = $this->_doAjaxValidate($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultValidate($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        }
        return $ret;
    }

    private function _doDefaultValidate(Controller $controller, User $user, Request $request)
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
        $section_id = "0";
        $member_id = "0";
        if ($request->hasParameter("school_id")) {
            $school_id = $request->getParameter("school_id");
        }
        if ($request->hasParameter("section_id")) {
            $section_id = $request->getParameter("section_id");
        }
        if ($request->hasParameter("member_id")) {
            $member_id = $request->getParameter("member_id");
        }
        $school_list = BroadcomSchoolInfoDBI::selectSchoolInfoList();
        if ($controller->isError($school_list)) {
            $school_list->setPos(__FILE__, __LINE__);
            return $school_list;
        }
        $member_list = BroadcomMemberPositionDBI::selectMemberListBySchoolGroupPosition($school_id);
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        $member_id_list = null;
        $teacher_flg = false;
        if ($member_id != "0") {
            $member_id_list = array($member_id);
            if (isset($member_list["3"]) && in_array($member_id, array_keys($member_list["3"]))) {
                $teacher_flg = true;
            }
        } else {
            if ($section_id != "0") {
                if (isset($member_list[$section_id])) {
                    $member_id_list = array_keys($member_list[$section_id]);
                    if ($section_id == "3") {
                        $teacher_flg = true;
                    }
                }
            }
        }
        $period_info = $this->_getStatisticsPeriod($controller, $user, $request);
        if ($controller->isError($period_info)) {
            $period_info->setPos(__FILE__, __LINE__);
            return $period_info;
        }
        $start_date = $period_info["period_start_date"];
        $end_date = $period_info["period_end_date"];
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
        $achieve_type_list["4"] = "合计";
        $result_data["4"] = $stats_item;
        $average_amount = 0;
        if ($result_data["4"]["order_count"] > 0) {
            $average_amount = round($result_data["4"]["order_amount"] / $result_data["4"]["order_count"], 2);
        }
        // 消课统计
        $course_stats = BroadcomStatisticsDBI::selectCourseStats($start_date, $end_date, $school_id, $member_id_list, $teacher_flg);
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
        if (!empty($course_stats)) {
           foreach ($course_stats as $course_tmp) {
                if ($course_tmp["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION) {
                    $course_data["5"] += $course_tmp["course_hours"];
                } elseif ($course_tmp["item_type"] == BroadcomItemEntity::ITEM_TYPE_PRESENT) {
                    $course_data["6"] += $course_tmp["course_hours"];
                } else {
                    $course_data[$course_tmp["item_method"]] += $course_tmp["course_hours"];
                }
           }
        }
        $request->setAttribute("param_list", array(
            "school_id" => $school_id,
            "section_id" => $section_id,
            "member_id" => $member_id
        ));
        $request->setAttribute("achieve_type_list", $achieve_type_list);
        $request->setAttribute("result_data", $result_data);
        $request->setAttribute("average_amount", $average_amount);
        $request->setAttribute("course_type_list", $course_type_list);
        $request->setAttribute("course_data", $course_data);
        $request->setAttribute("school_list", $school_list);
        $request->setAttribute("section_list", BroadcomMemberEntity::getSectionList());
        $request->setAttribute("member_list", $member_list);
        return VIEW_DONE;
    }

    private function _doAjaxValidate(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getParameter("school");
        $section_id = $request->getParameter("section");
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("section_id", $section_id);
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

    private function _doAjaxExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $section_id = $request->getAttribute("section_id");
        $result = '<option value="0">全部员工</option>';
        if ($section_id != "0") {
            $member_list = BroadcomMemberPositionDBI::selectMemberListBySchoolGroupPosition($school_id);
            if ($controller->isError($member_list)) {
                $member_list->setPos(__FILE__, __LINE__);
            }
            if (isset($member_list[$section_id]) && !empty($member_list[$section_id])) {
                foreach ($member_list[$section_id] as $member_id => $member_name) {
                    $result .= '<option value="' . $member_id . '">' . $member_name . '</option>';
                }
            }
        }
        header("Content-type:text/plain; charset=utf-8");
        echo $result;
        exit;
    }
}
?>