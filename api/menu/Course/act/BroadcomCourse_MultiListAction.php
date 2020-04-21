<?php

/**
 * 排课信息列表画面
 * @author Kinsama
 * @version 2020-04-20
 */
class BroadcomCourse_MultiListAction extends ActionBase
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
Utility::testVariable($course_list);
        $multi_course_list = array();
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                if ($course_info["multi_course_id"]) {
                    if (!isset($multi_course_list[$course_info["multi_course_id"]])) {
                        $multi_course_list[$course_info["multi_course_id"]] = array(
                        );
                    }
                }
            }
        }
        return $insert_data;
    }
}
?>