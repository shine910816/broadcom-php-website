<?php

/**
 * 排课信息及消课删除画面
 * @author Kinsama
 * @version 2020-04-28
 */
class BroadcomCourse_InfoAction extends ActionBase
{
    private $_confirm_able = false;
    private $_delete_able = false;

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("delete")) {
            $ret = $this->_doDeleteExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("confirm")) {
            $ret = $this->_doConfirmExecute($controller, $user, $request);
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
        $multi_flg = false;
        $course_id = "";
        $multi_course_id = "";
        $course_list = array();
        if ($request->hasParameter("course_id")) {
            $course_id = $request->getParameter("course_id");
        } elseif ($request->hasParameter("multi_course_id")) {
            $multi_course_id = $request->getParameter("multi_course_id");
            $multi_flg = true;
        } else {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if ($multi_flg) {
            $course_list = BroadcomCourseInfoDBI::selectCourseInfo($multi_course_id, $multi_flg);
            if ($controller->isError($course_list)) {
                $course_list->setPos(__FILE__, __LINE__);
                return $course_list;
            }
        } else {
            $course_list = BroadcomCourseInfoDBI::selectCourseInfo($course_id);
            if ($controller->isError($course_list)) {
                $course_list->setPos(__FILE__, __LINE__);
                return $course_list;
            }
        }
        if (count($course_list) == 1 && !is_null($course_list[0]["multi_course_id"])) {
            $course_id = $course_list[0]["multi_course_id"];
            $multi_flg = true;
            $course_list = BroadcomCourseInfoDBI::selectCourseInfo($course_id, $multi_flg);
            if ($controller->isError($course_list)) {
                $course_list->setPos(__FILE__, __LINE__);
                return $course_list;
            }
        }
        if (empty($course_list)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $base_course_info = array();
        $base_course_info["course_type"] = $course_list[0]["course_type"];
        $base_course_info["audition_type"] = $course_list[0]["audition_type"];
        $base_course_info["multi_course_id"] = $course_list[0]["multi_course_id"];
        $base_course_info["school_id"] = $course_list[0]["school_id"];
        $base_course_info["teacher_member_id"] = $course_list[0]["teacher_member_id"];
        $base_course_info["subject_id"] = $course_list[0]["subject_id"];
        $base_course_info["course_start_date"] = $course_list[0]["course_start_date"];
        $base_course_info["course_expire_date"] = $course_list[0]["course_expire_date"];
        $base_course_info["course_hours"] = $course_list[0]["course_hours"];
        $base_course_info["actual_start_date"] = $course_list[0]["actual_start_date"];
        $base_course_info["actual_expire_date"] = $course_list[0]["actual_expire_date"];
        $base_course_info["actual_course_hours"] = $course_list[0]["actual_course_hours"];
        $base_course_info["confirm_flg"] = $course_list[0]["confirm_flg"];
        $base_course_info["confirm_member_id"] = $course_list[0]["confirm_member_id"];
        $base_course_info["confirm_date"] = $course_list[0]["confirm_date"];
        $base_course_info["course_detail"] = array();
        foreach ($course_list as $course_item) {
            $course_tmp = array();
            $course_tmp["course_id"] = $course_item["course_id"];
            $course_tmp["student_id"] = $course_item["student_id"];
            $course_tmp["student_name"] = $course_item["student_name"];
            $course_tmp["student_mobile_number"] = $course_item["student_mobile_number"];
            $course_tmp["student_entrance_year"] = $course_item["student_entrance_year"];
            $course_tmp["audition_hours"] = $course_item["audition_hours"];
            $course_tmp["order_item_id"] = $course_item["order_item_id"];
            $course_tmp["contract_number"] = $course_item["contract_number"];
            $course_tmp["order_item_status"] = $course_item["order_item_status"];
            $course_tmp["order_item_confirm"] = $course_item["order_item_confirm"];
            $course_tmp["order_item_remain"] = $course_item["order_item_remain"];
            $course_tmp["order_assign_member_id"] = $course_item["order_assign_member_id"];
            $course_tmp["item_id"] = $course_item["item_id"];
            $course_tmp["item_name"] = $course_item["item_name"];
            $course_tmp["assign_member_id"] = $course_item["assign_member_id"];
            $course_tmp["assign_date"] = $course_item["assign_date"];
            $course_tmp["operated_by"] = $course_item["operated_by"];
            $course_tmp["insert_date"] = $course_item["insert_date"];
            $base_course_info["course_detail"][$course_item["course_id"]] = $course_tmp;
        }


Utility::testVariable($base_course_info);
        $request->setAttribute("insert_data", $insert_data);
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
    }

    private function _doConfirmExecute(Controller $controller, User $user, Request $request)
    {
        if (!$request->editable()) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Create failed: user unauthorized");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
    }

    private function _doDeleteExecute(Controller $controller, User $user, Request $request)
    {
        if (!$request->editable()) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Create failed: user unauthorized");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
    }
}
?>