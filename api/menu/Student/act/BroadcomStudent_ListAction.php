<?php

/**
 * 新增意向客户画面
 * @author Kinsama
 * @version 2020-04-11
 */
class BroadcomStudent_ListAction extends ActionBase
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
        if (!$request->hasParameter("school_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: school_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $request->getParameter("school_id");
        $request->setAttribute("school_id", $school_id);
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
        $school_id = $request->getAttribute("school_id");
        $student_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
        if ($controller->isError($student_list)) {
            $student_list->setPos(__FILE__, __LINE__);
            return $student_list;
        }
        if (!empty($student_list)) {
            foreach ($student_list as $student_id => $student_info) {
                if (!$this->_screenStudent($student_info)) {
                    unset($student_list[$student_id]);
                }
            }
        }
        $simple_flg = false;
        if ($request->hasParameter("simple")) {
            $simple_flg = true;
        }
        $student_result = array();
        if (!empty($student_list)) {
            foreach ($student_list as $student_id => $student_info) {
                if ($simple_flg) {
                    $student_result[$student_id] = $student_info["student_name"];
                } else {
                    $covered_mobile_number = Utility::coverMobileNumber($student_info["student_mobile_number"]);
                    $student_info["covered_mobile_number"] = Utility::coverMobileNumber($student_info["student_mobile_number"]);
                    $student_info["student_grade_name"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
                    $student_result[$student_id] = $student_info;
                }
            }
        }
        return array(
            "student_list" => $student_result
        );
    }

    private function _screenStudent($student_info)
    {
        // TODO screen logic
        //if (!is_null($xxx) && $member_info["xxx"] != $xxx) {
        //    return false;
        //}
        return true;
    }
}
?>