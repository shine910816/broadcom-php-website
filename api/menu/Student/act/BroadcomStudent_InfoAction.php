<?php

/**
 * 学员信息画面
 * @author Kinsama
 * @version 2020-04-16
 */
class BroadcomStudent_InfoAction extends ActionBase
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
        // 必要参数检证
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: student_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $request->getParameter("student_id");
        $student_info = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
        if ($controller->isError($student_info)) {
            $student_info->setPos(__FILE__, __LINE__);
            return $student_info;
        }
        if (empty($student_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter invalid: student_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $request->setAttribute("student_info", $student_info);
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
        $student_info = $request->getAttribute("student_info");
        $student_result = array();
        $student_result["student_id"] = $student_info["student_id"];
        $student_result["student_name"] = $student_info["student_name"];
        $student_result["student_mobile_number"] = $student_info["student_mobile_number"];
        $student_result["covered_mobile_number"] = Utility::coverMobileNumber($student_info["student_mobile_number"]);
        $student_result["student_entrance_year"] = $student_info["student_entrance_year"];
        $student_result["student_grade_name"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
        $student_result["student_gender"] = $student_info["student_gender"];
        $student_result["school_id"] = $student_info["school_id"];
        $student_result["student_school_name"] = $student_info["student_school_name"];
        $student_result["student_address"] = $student_info["student_address"];
        $student_result["student_level"] = $student_info["student_level"];
        $student_result["media_channel_code"] = $student_info["media_channel_code"];
        $student_result["media_channel_code_2"] = $student_info["media_channel_code_2"];
        $student_result["purpose_level"] = $student_info["purpose_level"];
        $student_result["follow_status"] = $student_info["follow_status"];
        $student_result["audition_hours"] = round($student_info["audition_hours"], 1);
        $student_result["inread_status"] = $student_info["inread_status"];
        $student_result["assign_member_id"] = $student_info["assign_member_id"];
        $student_result["assign_date"] = $student_info["assign_date"];
        $student_result["operated_by"] = $student_info["operated_by"];
        $student_result["insert_date"] = $student_info["insert_date"];
        return array(
            "student_info" => $student_result
        );
    }
}
?>