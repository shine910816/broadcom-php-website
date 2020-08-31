<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-03-17
 */
class BroadcomData_SurplusInfoAction extends BroadcomDataActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("past")) {
            $ret = $this->_doPastExecute($controller, $user, $request);
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
        if ($request->hasParameter("past")) {
            $current_ts = time();
            $past_date = date("Ym", mktime(0, 0, 0, date("n", $current_ts) - 1, 1, date("Y", $current_ts)));
            if ($request->hasParameter("past_date")) {
                $past_date = $request->getParameter("past_date");
            }
            $past_date_list = array();
            for ($i = 0; $i < 18; $i++) {
                $target_ts = mktime(0, 0, 0, date("n", $current_ts) - 18 + $i, 1, date("Y", $current_ts));
                $p_key = date("Ym", $target_ts);
                $p_val = date("Y", $target_ts) . "年" . date("n", $target_ts) . "月";
                $past_date_list[$p_key] = $p_val;
            }
            if (!Validate::checkAcceptParam($past_date, array_keys($past_date_list))) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $request->setAttribute("past_date", $past_date);
            $request->setAttribute("past_date_list", $past_date_list);
        } else {
            $school_id = $user->member()->schoolId();
            $order_item_info = BroadcomStatisticsDBI::selectOrderItemBySchool($school_id);
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
            $student_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
            if ($controller->isError($student_list)) {
                $student_list->setPos(__FILE__, __LINE__);
                return $student_list;
            }
            $request->setAttribute("order_item_info", $order_item_info);
            $request->setAttribute("student_list", $student_list);
        }
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
        $order_item_info = $request->getAttribute("order_item_info");
        $student_list = $request->getAttribute("student_list");
        $student_level_list = BroadcomStudentEntity::getStudentLevelList();
        $result_data = array();
        if (!empty($order_item_info)) {
            foreach ($order_item_info as $student_id => $student_order_info) {
                if (isset($student_list[$student_id])) {
                    $student_info = $student_list[$student_id];
                    $result_data[$student_id] = array(
                        "student_name" => $student_info["student_name"],
                        "student_mobile_number" => Utility::coverMobileNumber($student_info["student_mobile_number"]),
                        "student_grade_name" => BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]),
                        "student_surplus_count" => 0,
                        "student_surplus_amount" => 0
                    );
                    foreach ($student_order_info as $order_item_info) {
                        $result_data[$student_id]["student_surplus_count"] += $order_item_info["order_item_remain"];
                        $result_data[$student_id]["student_surplus_amount"] += round($order_item_info["order_item_remain"] * $order_item_info["order_item_trans_price"], 2);
                    }
                }
            }
        }
        $student_count = count($result_data);
        $total_count = 0;
        $total_amount = 0;
        foreach ($result_data as $res_key => $res_tmp) {
            $total_count += $res_tmp["student_surplus_count"];
            $total_amount += $res_tmp["student_surplus_amount"];
            $result_data[$res_key]["student_surplus_count"] = number_format($res_tmp["student_surplus_count"], 1);
            $result_data[$res_key]["student_surplus_amount"] = number_format($res_tmp["student_surplus_amount"], 2);
        }
        $total_count = number_format($total_count, 1);
        $total_amount = number_format($total_amount, 2);
        $request->setAttribute("result_data", $result_data);
        $request->setAttribute("student_count", $student_count);
        $request->setAttribute("total_count", $total_count);
        $request->setAttribute("total_amount", $total_amount);
        $request->setAttribute("past_flg", false);
        return VIEW_DONE;
    }

    private function _doPastExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $user->member()->schoolId();
        $past_date = $request->getAttribute("past_date");
        $grade_list = BroadcomStudentEntity::getGradeList();
        $result_data = BroadcomStatisticsDBI::selectPastSurplus($school_id, $past_date);
        if ($controller->isError($result_data)) {
            $result_data->setPos(__FILE__, __LINE__);
            return $result_data;
        }
        $student_count = count($result_data);
        $total_count = 0;
        $total_amount = 0;
        foreach ($result_data as $res_key => $res_tmp) {
            $total_count += $res_tmp["student_surplus_count"];
            $total_amount += $res_tmp["student_surplus_amount"];
            $result_data[$res_key]["student_grade_name"] = $grade_list[$res_tmp["student_grade"]];
            $result_data[$res_key]["student_surplus_count"] = number_format($res_tmp["student_surplus_count"], 1);
            $result_data[$res_key]["student_surplus_amount"] = number_format($res_tmp["student_surplus_amount"], 2);
        }
        $total_count = number_format($total_count, 1);
        $total_amount = number_format($total_amount, 2);
        $request->setAttribute("result_data", $result_data);
        $request->setAttribute("student_count", $student_count);
        $request->setAttribute("total_count", $total_count);
        $request->setAttribute("total_amount", $total_amount);
        $request->setAttribute("past_flg", true);
        return VIEW_DONE;
    }
}
?>