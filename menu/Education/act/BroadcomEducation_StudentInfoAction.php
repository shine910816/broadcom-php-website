<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-20
 */
class BroadcomEducation_StudentInfoAction extends BroadcomEducationActionBase
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
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $request->getParameter("student_id");
        $post_data = array(
            "student_id" => $student_id
        );
        $repond_student_info = Utility::getJsonResponse("?t=D2EC2D87-7195-6707-EF12-E55DB18ABF7C&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_student_info)) {
            $repond_student_info->setPos(__FILE__, __LINE__);
            return $repond_student_info;
        }
        $student_info = $repond_student_info["student_info"];
        $history_list = $repond_student_info["history_list"];
        $check_position_option = array(
            BroadcomMemberEntity::POSITION_ADVISER,
            BroadcomMemberEntity::POSITION_MARKETING
        );
        if (in_array($user->member()->position(), $check_position_option) && $student_info["assign_member_id"] != $user->member()->id()) {
            $err = $controller->raiseError(ERROR_CODE_NO_AUTH);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_level_list = BroadcomStudentEntity::getStudentLevelList();
        $student_info["student_grade"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
        $student_info["student_level_name"] = $student_level_list[$student_info["student_level"]];
        $order_list = BroadcomOrderDBI::selectOrderInfoByStudentId($student_id);
        if ($controller->isError($order_list)) {
            $order_list->setPos(__FILE__, __LINE__);
            return $order_list;
        }
        $order_item_list = BroadcomOrderDBI::selectOrderItemByStudent($student_id);
        if ($controller->isError($order_item_list)) {
            $order_item_list->setPos(__FILE__, __LINE__);
            return $order_item_list;
        }
        if (!empty($order_item_list)) {
            foreach ($order_item_list as $order_item_id => $order_item_info) {
                $order_item_list[$order_item_id]["wait_confirm"] = number_format($order_item_info["order_item_arrange"] - $order_item_info["order_item_confirm"], 1) . "小时";
                $order_item_list[$order_item_id]["order_item_remain"] = number_format($order_item_info["order_item_remain"], 1) . "小时";
                $order_item_list[$order_item_id]["order_item_confirm"] = number_format($order_item_info["order_item_confirm"], 1) . "小时";
            }
        }
        $item_list = BroadcomItemInfoDBI::selectItemInfoList();
        if ($controller->isError($item_list)) {
            $item_list->setPos(__FILE__, __LINE__);
            return $item_list;
        }
        $course_list_tmp = BroadcomCourseInfoDBI::selectCourseInfoByStudent($student_id);
        if ($controller->isError($course_list_tmp)) {
            $course_list_tmp->setPos(__FILE__, __LINE__);
            return $course_list_tmp;
        }
        $confirm_flg = "0";
        if ($request->hasParameter("confirm_flg")) {
            $confirm_flg = $request->getParameter("confirm_flg");
        }
        $subject_id = "10";
        if ($request->hasParameter("subject_id")) {
            $subject_id = $request->getParameter("subject_id");
        }
        // 排课信息筛选
        $course_list = array();
        foreach ($course_list_tmp as $course_id => $tmp_course_info) {
            if ($subject_id == "10" || ($subject_id != "10" && $tmp_course_info["subject_id"] == $subject_id)) {
                if ($confirm_flg == "2") {
                    $course_list[$course_id] = $tmp_course_info;
                } elseif ($confirm_flg == "1") {
                    if ($tmp_course_info["confirm_flg"]) {
                        $course_list[$course_id] = $tmp_course_info;
                    }
                } else {
                    if (!$tmp_course_info["confirm_flg"]) {
                        $course_list[$course_id] = $tmp_course_info;
                    }
                }
            }
        }
        $page_url = "./?menu=" . $request->current_menu .
                      "&act=" . $request->current_act .
                      "&student_id=" . $student_id .
                      "&confirm_flg=" . $confirm_flg .
                      "&subject_id=" . $subject_id;
        $course_list = Utility::getPaginationData($request, $course_list, $page_url, 10, "course_filter");
        if ($controller->isError($course_list)) {
            $course_list->setPos(__FILE__, __LINE__);
            return $course_list;
        }
        $school_id = $student_info["school_id"];
        $teacher_info = BroadcomTeacherDBI::selectTeacherInfoList($school_id);
        if ($controller->isError($teacher_info)) {
            $teacher_info->setPos(__FILE__, __LINE__);
            return $teacher_info;
        }
        $back_link = Utility::encodeBackLink("education", "student_info", array(
            "student_id" => $student_id
        ));
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("order_list", $order_list);
        $request->setAttribute("order_item_list", $order_item_list);
        $request->setAttribute("item_list", $item_list);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
        $request->setAttribute("order_status_list", BroadcomOrderEntity::getOrderStatusList());
        $request->setAttribute("order_item_status_list", BroadcomOrderEntity::getOrderItemStatusList());
        $request->setAttribute("course_list", $course_list);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("teacher_info", $teacher_info);
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
        $request->setAttribute("back_link", $back_link);
        $request->setAttribute("history_list", $history_list);
        $request->setAttribute("confirm_flg", $confirm_flg);
        $request->setAttribute("subject_id", $subject_id);
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
        return VIEW_DONE;
    }
}
?>