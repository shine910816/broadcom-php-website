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
                        "student_mobile_number" => $student_info["student_mobile_number"],
                        "student_grade_name" => BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]),
                        "student_level" => $student_level_list[$student_info["student_level"]],
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
        foreach ($result_data as $res_tmp) {
            $total_count += $res_tmp["student_surplus_count"];
            $total_amount += $res_tmp["student_surplus_amount"];
        }
        $request->setAttribute("result_data", $result_data);
        $request->setAttribute("student_count", $student_count);
        $request->setAttribute("total_count", $total_count);
        $request->setAttribute("total_amount", $total_amount);
        return VIEW_DONE;
    }
}
?>