<?php

/**
 * 排课信息列表画面
 * @author Kinsama
 * @version 2020-04-08
 */
class BroadcomCourse_ListAction extends ActionBase
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
        $school_id = $request->getParameter("school_id");
        $course_start_date = $request->getParameter("start_date") . " 00:00:00";
        $course_expire_date = $request->getParameter("end_date") . " 23:59:59";
        $course_list = BroadcomCourseInfoDBI::selectCourseInfoBySchool($school_id, $course_start_date, $course_expire_date);
        if ($controller->isError($course_list)) {
            $course_list->setPos(__FILE__, __LINE__);
            return $course_list;
        }
        $student_id = null;
        $teacher_member_id = null;
        $assign_member_id = null;
        $confirm_flg = null;
        if ($request->hasParameter("student_id")) {
            $student_id = $request->getParameter("student_id");
        }
        if ($request->hasParameter("teacher_member_id")) {
            $teacher_member_id = $request->getParameter("teacher_member_id");
        }
        if ($request->hasParameter("assign_member_id")) {
            $assign_member_id = $request->getParameter("assign_member_id");
        }
        if ($request->hasParameter("confirm_flg")) {
            $confirm_flg = $request->getParameter("confirm_flg");
        }
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                if (!$this->_screenCourse($course_info, $student_id, $teacher_member_id, $assign_member_id, $confirm_flg)) {
                    unset($course_list[$course_id]);
                }
            }
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("course_list", $course_list);
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
        $course_list = $request->getAttribute("course_list");
        if (!empty($course_list)) {
            $post_data = array(
                "simple" => "1"
            );
            $repond_school_list = Utility::getJsonResponse("?t=B7AA9B79-622B-A31B-FD5C-28B79AFCDF34&m=" . $request->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_school_list)) {
                $repond_school_list->setPos(__FILE__, __LINE__);
                return $repond_school_list;
            }
            $school_list = $repond_school_list["school_list"];
            $post_data = array(
                "school_id" => $school_id
            );
            $repond_student_list = Utility::getJsonResponse("?t=9B5BB2E7-F483-24CA-A725-55A304F628DE&m=" . $request->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_student_list)) {
                $repond_student_list->setPos(__FILE__, __LINE__);
                return $repond_student_list;
            }
            $student_list = $repond_student_list["student_list"];
            $post_data["section"] = array(
                BroadcomMemberEntity::SECTION_1,
                BroadcomMemberEntity::SECTION_2,
                BroadcomMemberEntity::SECTION_3
            );
            $repond_member_list = Utility::getJsonResponse("?t=589049D8-F35C-2E6A-E792-D576E8002A2C&m=" . $request->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_member_list)) {
                $repond_member_list->setPos(__FILE__, __LINE__);
                return $repond_member_list;
            }
            $member_list = $repond_member_list["member_list"];
            $subject_list = BroadcomSubjectEntity::getSubjectList();
            foreach ($course_list as $course_id => $course_info) {
                if (isset($student_list[$course_info["student_id"]])) {
                    $course_list[$course_id]["student_name"] = $student_list[$course_info["student_id"]]["student_name"];
                    $course_list[$course_id]["student_mobile_number"] = $student_list[$course_info["student_id"]]["covered_mobile_number"];
                    $course_list[$course_id]["student_grade_name"] = $student_list[$course_info["student_id"]]["student_grade_name"];
                } else {
                    $course_list[$course_id]["student_name"] = "Undefined";
                    $course_list[$course_id]["student_mobile_number"] = "Undefined";
                    $course_list[$course_id]["student_grade_name"] = "Undefined";
                }
                if (isset($member_list[$course_info["assign_member_id"]])) {
                    $course_list[$course_id]["assign_member_name"] = $member_list[$course_info["assign_member_id"]]["m_name"];
                }
                if (isset($member_list[$course_info["order_assign_member_id"]])) {
                    $course_list[$course_id]["order_assign_member_name"] = $member_list[$course_info["order_assign_member_id"]]["m_name"];
                } else {
                    $course_list[$course_id]["order_assign_member_name"] = "";
                }
                if (isset($member_list[$course_info["teacher_member_id"]])) {
                    $course_list[$course_id]["teacher_member_name"] = $member_list[$course_info["teacher_member_id"]]["m_name"];
                    $course_list[$course_id]["teacher_position"] = $member_list[$course_info["teacher_member_id"]]["member_position"];
                    $course_list[$course_id]["teacher_school_name"] = $school_list[$member_list[$course_info["teacher_member_id"]]["school_id"]] . "校区";
                }
                if ($course_info["confirm_flg"] && isset($member_list[$course_info["confirm_member_id"]])) {
                    $course_list[$course_id]["confirm_member_name"] = $member_list[$course_info["confirm_member_id"]]["m_name"];
                } else {
                    $course_list[$course_id]["confirm_member_name"] = "";
                }
                if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO || $course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD) {
                    $course_list[$course_id]["course_type_name"] = "试听课";
                    $audition_type_list = BroadcomCourseEntity::getAuditionTypeList();
                    $course_list[$course_id]["course_detail_type_name"] = $audition_type_list[$course_info["audition_type"]];
                } else {
                    $course_list[$course_id]["course_type_name"] = "正课";
                    $course_list[$course_id]["course_detail_type_name"] = "学员排课";
                }
                $course_list[$course_id]["subject_name"] = $subject_list[$course_info["subject_id"]];
            }
        }
        return array(
            "course_list" => $course_list
        );
    }

    private function _screenCourse($course_info, $student_id = null, $teacher_member_id = null, $assign_member_id = null, $confirm_flg = null)
    {
        if (!is_null($student_id) && $course_info["student_id"] != $student_id) {
            return false;
        }
        if (!is_null($teacher_member_id) && $course_info["teacher_member_id"] != $teacher_member_id) {
            return false;
        }
        if (!is_null($assign_member_id) && $course_info["assign_member_id"] != $assign_member_id) {
            return false;
        }
        if (!is_null($confirm_flg) && $course_info["confirm_flg"] != $confirm_flg) {
            return false;
        }
        return true;
    }
}
?>