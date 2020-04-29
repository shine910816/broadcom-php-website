<?php

/**
 * 排课信息及消课删除画面
 * @author Kinsama
 * @version 2020-04-28
 */
class BroadcomCourse_InfoAction extends ActionBase
{
    private $_multi_flg = false;
    private $_assign_member_list = array();
    private $_confirm_able = true;
    private $_delete_able = true;

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
Utility::testVariable($ret);
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
        $course_list = array();
        if ($request->hasParameter("course_id")) {
            $course_list = BroadcomCourseInfoDBI::selectCourseInfo($request->getParameter("course_id"));
            if ($controller->isError($course_list)) {
                $course_list->setPos(__FILE__, __LINE__);
                return $course_list;
            }
            if (!empty($course_list) && !is_null($course_list[0]["multi_course_id"])) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter invalid: this course is not single");
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        } elseif ($request->hasParameter("multi_course_id")) {
            $this->_multi_flg = true;
            $course_list = BroadcomCourseInfoDBI::selectCourseInfo($request->getParameter("multi_course_id"), true);
            if ($controller->isError($course_list)) {
                $course_list->setPos(__FILE__, __LINE__);
                return $course_list;
            }
            if (empty($course_list)) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter invalid: multi_course_id");
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        } else {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $subject_list = BroadcomSubjectEntity::getSubjectList();
        $post_data = array(
            "school_id" => $request->member()->schoolId()
        );
        $repond_member_list = Utility::getJsonResponse("?t=589049D8-F35C-2E6A-E792-D576E8002A2C&m=" . $request->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_member_list)) {
            $repond_member_list->setPos(__FILE__, __LINE__);
            return $repond_member_list;
        }
        $member_list = $repond_member_list["member_list"];
        $base_course_info = array();
        $base_course_info["course_type"] = $course_list[0]["course_type"];
        $base_course_info["audition_type"] = $course_list[0]["audition_type"];
        $base_course_info["course_type_name"] = "";
        $base_course_info["course_detail_type_name"] = "";
        $base_course_info["multi_course_id"] = $course_list[0]["multi_course_id"];
        $base_course_info["school_id"] = $course_list[0]["school_id"];
        $base_course_info["item_id"] = $course_list[0]["item_id"];
        $base_course_info["item_name"] = $course_list[0]["item_name"];
        $base_course_info["teacher_member_id"] = $course_list[0]["teacher_member_id"];
        $base_course_info["teacher_member_name"] = isset($member_list[$base_course_info["teacher_member_id"]]) ? $member_list[$base_course_info["teacher_member_id"]]["m_name"] : "";
        $base_course_info["subject_id"] = $course_list[0]["subject_id"];
        $base_course_info["subject_name"] = $subject_list[$course_list[0]["subject_id"]];
        $base_course_info["course_start_date"] = $course_list[0]["course_start_date"];
        $base_course_info["course_expire_date"] = $course_list[0]["course_expire_date"];
        $base_course_info["course_hours"] = round($course_list[0]["course_hours"], 1);
        $base_course_info["actual_start_date"] = $course_list[0]["actual_start_date"];
        $base_course_info["actual_expire_date"] = $course_list[0]["actual_expire_date"];
        $base_course_info["actual_course_hours"] = is_null($course_list[0]["actual_course_hours"]) ? null : round($course_list[0]["actual_course_hours"], 1);
        $base_course_info["confirm_flg"] = $course_list[0]["confirm_flg"] ? true : false;
        $base_course_info["confirm_member_id"] = $course_list[0]["confirm_member_id"];
        $base_course_info["confirm_member_name"] = isset($member_list[$base_course_info["confirm_member_id"]]) ? $member_list[$base_course_info["confirm_member_id"]]["m_name"] : "";
        $base_course_info["confirm_date"] = $course_list[0]["confirm_date"];
        $audition_list = array(
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
        );
        if (in_array($base_course_info["course_type"], $audition_list)) {
            $course_type_list = BroadcomCourseEntity::getCourseTypeList();
            $audition_type_list = BroadcomCourseEntity::getAuditionTypeList();
            $base_course_info["item_name"] = $course_type_list[$base_course_info["course_type"]];
            $base_course_info["course_detail_type_name"] = $audition_type_list[$base_course_info["audition_type"]];
            $base_course_info["course_type_name"] = "试听课";
        } else {
            $base_course_info["course_type_name"] = "正课";
            $base_course_info["course_detail_type_name"] = "学员排课";
        }
        $course_detail = array();
        foreach ($course_list as $course_item) {
            $course_tmp = array();
            $course_tmp["course_id"] = $course_item["course_id"];
            $course_tmp["student_id"] = $course_item["student_id"];
            $course_tmp["student_name"] = $course_item["student_name"];
            $course_tmp["student_mobile_number"] = Utility::coverMobileNumber($course_item["student_mobile_number"]);
            $course_tmp["student_grade_name"] = BroadcomStudentEntity::getGradeName($course_item["student_entrance_year"]);
            $course_tmp["audition_hours"] = round($course_item["audition_hours"], 1);
            $course_tmp["assign_member_id"] = $course_item["assign_member_id"];
            $this->_assign_member_list[$course_tmp["assign_member_id"]] = $course_tmp["assign_member_id"];
            $course_tmp["assign_member_name"] = isset($member_list[$course_tmp["assign_member_id"]]) ? $member_list[$course_tmp["assign_member_id"]]["m_name"] : "";
            $course_tmp["assign_date"] = $course_item["assign_date"];
            $course_tmp["order_item_id"] = $course_item["order_item_id"];
            $course_tmp["contract_number"] = $course_item["contract_number"];
            $course_tmp["order_item_status"] = $course_item["order_item_status"];
            $course_tmp["order_item_confirm"] = round($course_item["order_item_confirm"], 1);
            $course_tmp["order_item_remain"] = round($course_item["order_item_remain"], 1);
            $course_tmp["order_assign_member_id"] = $course_item["order_assign_member_id"];
            $course_tmp["order_assign_member_name"] = isset($member_list[$course_tmp["order_assign_member_id"]]) ? $member_list[$course_tmp["order_assign_member_id"]]["m_name"] : "";
            $course_detail[$course_item["course_id"]] = $course_tmp;
        }
        // 已消课判断
        if ($base_course_info["confirm_flg"]) {
            $this->_confirm_able = false;
        }
        // 权限判断
        if ($this->_confirm_able && !$request->isAdmin() & !$request->member()->auth()->isMst()) {
            if ($request->member()->auth()->isAst()) {
                if (!in_array($request->member()->id(), $this->_assign_member_list)) {
                    $this->_confirm_able = false;
                }
            } elseif ($request->member()->auth()->isEdu()) {
                if ($request->member()->id() != $base_course_info["teacher_member_id"]) {
                    $this->_confirm_able = false;
                }
            } elseif ($request->member()->auth()->isHrf()) {
                $this->_confirm_able = false;
            }
        }
        // 时间判断
        $expire_ts = strtotime($base_course_info["course_expire_date"]);
        $finish_ts = mktime(0, 0, 0, date("n", $expire_ts), date("j", $expire_ts) + 1, date("Y"));
        $current_ts = time();
        // TODO for test
        $current_ts = strtotime("2020-04-15 23:30:00");
        if ($this->_confirm_able) {
            if ($request->isAdmin() || $request->member()->auth()->isMst()) {
                if ($current_ts < $expire_ts) {
                    $this->_confirm_able = false;
                }
            } else {
                if ($current_ts < $expire_ts || $current_ts >= $finish_ts) {
                    $this->_confirm_able = false;
                }
            }
        }
        // 可删除时间判断
        //if ($this->_confirm_able) {
            if ($current_ts >= $finish_ts) {
                $this->_delete_able = false;
            }
        //} else {
        //    $this->_delete_able = false;
        //}

        $request->setAttribute("base_course_info", $base_course_info);
        $request->setAttribute("course_detail_list", $course_detail);
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
        $base_course_info = $request->getAttribute("base_course_info");
        $base_course_info["delete_able"] = $this->_delete_able;
        $base_course_info["confirm_able"] = $this->_confirm_able;
        $course_detail_list = $request->getAttribute("course_detail_list");
        return array(
            "base_info" => $base_course_info,
            "detail_list" => $course_detail_list
        );
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