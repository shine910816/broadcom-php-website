<?php

/**
 * 排课信息列表画面(排课检证用组合条件)
 * @author Kinsama
 * @version 2020-04-14
 */
class BroadcomCourse_CombineListAction extends ActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("multi")) {
            $ret = $this->_doMultiExecute($controller, $user, $request);
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
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: student_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if ($request->hasParameter("multi")) {
            if (!$request->hasParameter("course_type")) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_type");
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $course_type = $request->getParameter("course_type");
            $audition_type = null;
            $item_id = null;
            if ($course_type == BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD) {
                if (!$request->hasParameter("audition_type")) {
                    $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: audition_type");
                    $err->setPos(__FILE__, __LINE__);
                    return $err;
                } else {
                    $audition_type = $request->getParameter("audition_type");
                }
            } else {
                if (!$request->hasParameter("item_id")) {
                    $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: item_id");
                    $err->setPos(__FILE__, __LINE__);
                    return $err;
                } else {
                    $item_id = $request->getParameter("item_id");
                }
            }
            $request->setAttribute("course_type", $course_type);
            $request->setAttribute("audition_type", $audition_type);
            $request->setAttribute("item_id", $item_id);
        } else {
            if (!$request->hasParameter("teacher_member_id")) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: teacher_member_id");
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $request->setAttribute("teacher_member_id", $request->getParameter("teacher_member_id"));
        }
        $school_id = $request->getParameter("school_id");
        $start_date = $request->getParameter("start_date");
        $end_date = $request->getParameter("end_date");
        $post_data = array(
            "school_id" => $school_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "confirm_flg" => "0"
        );
        $repond_course_list = Utility::getJsonResponse("?t=D4F1FA27-76D2-3029-4FB9-2FD91B0057B8&m=" . $request->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_list)) {
            $repond_course_list->setPos(__FILE__, __LINE__);
            return $repond_course_list;
        }
        $request->setAttribute("student_id", $request->getParameter("student_id"));
        $request->setAttribute("course_list", $repond_course_list["course_list"]);
        return VIEW_DONE;
    }

    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        $course_list = $request->getAttribute("course_list");
        $student_id = $request->getAttribute("student_id");
        $teacher_member_id = $request->getAttribute("teacher_member_id");
        $course_result = array();
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                if ($course_info["student_id"] == $student_id || $course_info["teacher_member_id"] == $teacher_member_id) {
                    $course_item = array();
                    $course_item["student_id"] = $course_info["student_id"];
                    $course_item["teacher_member_id"] = $course_info["teacher_member_id"];
                    $course_item["start_ts"] = strtotime($course_info["course_start_date"]);
                    $course_item["end_ts"] = strtotime($course_info["course_expire_date"]);
                    $course_item["date"] = date("Ymd", $course_item["start_ts"]);
                    $course_result[$course_id] = $course_item;
                }
            }
        }
        return array(
            "course_list" => $course_result
        );
    }

    private function _doMultiExecute(Controller $controller, User $user, Request $request)
    {
        $course_list = $request->getAttribute("course_list");
        $student_id = $request->getAttribute("student_id");
        $course_type = $request->getAttribute("course_type");
        $audition_type = $request->getAttribute("audition_type");
        $item_id = $request->getAttribute("item_id");
        $course_result = array();
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                if ($course_info["student_id"] != $student_id) {
                    // TODO
                }
            }
        }
    }
}
?>