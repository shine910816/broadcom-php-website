<?php

/**
 * 排课信息列表画面
 * @author Kinsama
 * @version 2020-04-20
 */
class BroadcomCourse_MultiListAction extends ActionBase
{
    private $_screen_student_list = array();
    private $_screen_assign_list = array();

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
        if (!$request->hasParameter("start_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: start_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if (!$request->hasParameter("end_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: end_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $post_data = array(
            "school_id" => $request->getParameter("school_id"),
            "start_date" => $request->getParameter("start_date"),
            "end_date" => $request->getParameter("end_date"),
        );
        if ($request->hasParameter("teacher_member_id")) {
            $post_data["teacher_member_id"] = $request->getParameter("teacher_member_id");
        }
        if ($request->hasParameter("confirm_flg")) {
            $post_data["confirm_flg"] = $request->getParameter("confirm_flg");
        }
        $repond_course_list = Utility::getJsonResponse("?t=D4F1FA27-76D2-3029-4FB9-2FD91B0057B8&m=" . $request->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_list)) {
            $repond_course_list->setPos(__FILE__, __LINE__);
            return $repond_course_list;
        }
        $student_id = null;
        $assign_member_id = null;
        if ($request->hasParameter("student_id")) {
            $student_id = $request->getParameter("student_id");
        }
        if ($request->hasParameter("assign_member_id")) {
            $assign_member_id = $request->getParameter("assign_member_id");
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("assign_member_id", $assign_member_id);
        $request->setAttribute("course_list", $repond_course_list["course_list"]);
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
        $course_list = $request->getAttribute("course_list");
        $student_id = $request->getAttribute("student_id");
        $assign_member_id = $request->getAttribute("assign_member_id");
        $multi_course_list = array();
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                if ($course_info["multi_course_id"]) {
                    if (!isset($multi_course_list[$course_info["multi_course_id"]])) {
                        $multi_course_list[$course_info["multi_course_id"]] = array(
                            "course_type" => $course_info["course_type"],
                            "audition_type" => $course_info["audition_type"],
                            "course_type_name" => $course_info["course_type_name"],
                            "course_detail_type_name" => $course_info["course_detail_type_name"],
                            "multi_course_id" => $course_info["multi_course_id"],
                            "school_id" => $course_info["school_id"],
                            "assign_member_id" => $course_info["assign_member_id"],
                            "assign_member_name" => $course_info["assign_member_name"],
                            "item_id" => $course_info["item_id"],
                            "item_name" => $course_info["item_name"],
                            "confirm_flg" => $course_info["confirm_flg"],
                            "confirm_member_id" => $course_info["confirm_member_id"],
                            "confirm_member_name" => $course_info["confirm_member_name"],
                            "confirm_date" => $course_info["confirm_date"],
                            "course_start_date" => $course_info["course_start_date"],
                            "course_expire_date" => $course_info["course_expire_date"],
                            "course_hours" => $course_info["course_hours"],
                            "actual_start_date" => $course_info["actual_start_date"],
                            "actual_expire_date" => $course_info["actual_expire_date"],
                            "actual_course_hours" => $course_info["actual_course_hours"],
                            "teacher_member_id" => $course_info["teacher_member_id"],
                            "teacher_member_name" => $course_info["teacher_member_name"],
                            "teacher_position" => $course_info["teacher_position"],
                            "teacher_school_name" => $course_info["teacher_school_name"],
                            "subject_id" => $course_info["subject_id"],
                            "subject_name" => $course_info["subject_name"],
                            "course_info" => array()
                        );
                    }
                    $course_item = array(
                        "course_id" => $course_info["course_id"],
                        "student_id" => $course_info["student_id"],
                        "student_name" => $course_info["student_name"],
                        "student_mobile_number" => $course_info["student_mobile_number"],
                        "student_grade_name" => $course_info["student_grade_name"],
                        "assign_member_id" => $course_info["assign_member_id"],
                        "assign_member_name" => $course_info["assign_member_name"],
                        "order_item_id" => $course_info["order_item_id"],
                        "contract_number" => $course_info["contract_number"],
                        "order_assign_member_id" => $course_info["order_assign_member_id"],
                        "order_assign_member_name" => $course_info["order_assign_member_name"],
                        "course_trans_price" => $course_info["course_trans_price"]
                    );
                    $multi_course_list[$course_info["multi_course_id"]]["course_info"][] = $course_item;
                    $this->_screen_student_list[$course_info["multi_course_id"]][] = $course_info["student_id"];
                    $this->_screen_assign_list[$course_info["multi_course_id"]][] = $course_info["order_assign_member_id"];
                }
            }
        }
        if (!empty($multi_course_list)) {
            foreach ($multi_course_list as $multi_course_id => $course_info_tmp) {
                if (!$this->_screenCourse($multi_course_id, $student_id, $assign_member_id)) {
                    unset($multi_course_list[$multi_course_id]);
                }
            }
        }
        return array(
            "course_list" => $multi_course_list
        );
    }

    private function _screenCourse($multi_course_id, $student_id, $assign_member_id)
    {
        if (!is_null($student_id) && !in_array($student_id, $this->_screen_student_list[$multi_course_id])) {
            return false;
        }
        if (!is_null($assign_member_id) && !in_array($assign_member_id, $this->_screen_assign_list[$multi_course_id])) {
            return false;
        }
        return true;
    }
}
?>