<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_CourseCreateAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_create")) {
            $ret = $this->_doCreateExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("time_type")) {
            $ret = $this->_doSelectExecute($controller, $user, $request);
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
        $base_course_info = array(
            "course_type" => BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD,
            "audition_type" => "0",
            "student_id" => "0",
            "school_id" => "0",
            "subject_id" => "0",
            "teacher_member_id" => "0",
            "order_item_id" => "",
            "item_id" => "",
            "course_trans_price" => "0"
        );
        $student_info = array();
        $order_item_info = array();
        $audition_flg = false;
        $time_info = array(
            "time_type" => "1",
            "start_time" => "08:00",
            "course_hours" => "2"
        );
        $time_type_list = array(
            "1" => "自定义时间",
            "2" => "其他学员时间",
        );
        $hours_list = explode(",", "1,1.5,2");
        $teacher_list_content = "";
        if ($request->hasParameter("do_create")) {
            if (!$request->hasParameter("base_data") && !$request->hasParameter("time_data")) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $base_data = Utility::decodeCookieInfo($request->getParameter("base_data"));
            $time_data = Utility::decodeCookieInfo($request->getParameter("time_data"));
            $selected_date = array();
            $selected_info = array();
            if ($request->hasParameter("selected_date")) {
                $selected_date = $request->getParameter("selected_date");
            }
            if ($request->hasParameter("selected_info")) {
                $selected_info = $request->getParameter("selected_info");
            }
            $request->setAttribute("base_data", $base_data);
            $request->setAttribute("time_data", $time_data);
            $request->setAttribute("selected_date", $selected_date);
            $request->setAttribute("selected_info", $selected_info);
            return VIEW_DONE;
        } elseif ($request->hasParameter("time_type")) {
            $time_info["time_type"] = $request->getParameter("time_type");
            $time_info["start_time"] = $request->getParameter("start_time");
            $time_info["course_hours"] = $request->getParameter("course_hours");
            $base_course_info["course_type"] = $request->getParameter("course_type");
            $base_course_info["audition_type"] = $request->getParameter("audition_type");
            $base_course_info["student_id"] = $request->getParameter("student_id");
            $base_course_info["school_id"] = $request->getParameter("school_id");
            $base_course_info["order_item_id"] = $request->getParameter("order_item_id");
            $base_course_info["item_id"] = $request->getParameter("item_id");
            if (!$base_course_info["order_item_id"]) {
                $audition_flg = true;
            } else {
                $order_item_info = Utility::getJsonResponse("?t=35FF8317-9F11-00B5-FEEF-467C7DA37D71&m=" . $user->member()->targetObjectId(), array("order_item_id" => $base_course_info["order_item_id"]));
                if ($controller->isError($order_item_info)) {
                    $order_item_info->setPos(__FILE__, __LINE__);
                    return $order_item_info;
                }
                $base_course_info["course_trans_price"] = $order_item_info["order_item_trans_price"];
            }
            $subject_teacher = explode("_", $request->getParameter("subject_teacher"));
            $base_course_info["subject_id"] = $subject_teacher[0];
            $base_course_info["teacher_member_id"] = $subject_teacher[1];
            $teacher_list_content = $this->_getTeacherList($controller, $user, $order_item_info, $base_course_info);
            if ($controller->isError($teacher_list_content)) {
                $teacher_list_content->setPos(__FILE__, __LINE__);
                return $teacher_list_content;
            }
        } else {
            if ($request->hasParameter("order_item_id") && $request->getParameter("order_item_id")) {
                $order_item_id = $request->getParameter("order_item_id");
                $order_item_info = Utility::getJsonResponse("?t=35FF8317-9F11-00B5-FEEF-467C7DA37D71&m=" . $user->member()->targetObjectId(), array("order_item_id" => $order_item_id));
                if ($controller->isError($order_item_info)) {
                    $order_item_info->setPos(__FILE__, __LINE__);
                    return $order_item_info;
                }
                $base_course_info["student_id"] = $order_item_info["student_id"];
                $base_course_info["school_id"] = $order_item_info["school_id"];
                $base_course_info["order_item_id"] = $order_item_info["order_item_id"];
                $base_course_info["item_id"] = $order_item_info["item_id"];
                $base_course_info["course_trans_price"] = $order_item_info["order_item_trans_price"];
                switch ($order_item_info["item_method"]) {
                    case BroadcomItemEntity::ITEM_METHOD_1_TO_1:
                        $base_course_info["course_type"] = BroadcomCourseEntity::COURSE_TYPE_SINGLE;
                        break;
                    case BroadcomItemEntity::ITEM_METHOD_1_TO_2:
                        $base_course_info["course_type"] = BroadcomCourseEntity::COURSE_TYPE_DOUBLE;
                        break;
                    case BroadcomItemEntity::ITEM_METHOD_1_TO_3:
                        $base_course_info["course_type"] = BroadcomCourseEntity::COURSE_TYPE_TRIBLE;
                        break;
                    case BroadcomItemEntity::ITEM_METHOD_CLASS:
                        $base_course_info["course_type"] = BroadcomCourseEntity::COURSE_TYPE_CLASS;
                        break;
                }
            } else {
                if (!$request->hasParameter("student_id")) {
                    $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                    $err->setPos(__FILE__, __LINE__);
                    return $err;
                }
                $base_course_info["student_id"] = $request->getParameter("student_id");
                $audition_flg = true;
                $base_course_info["audition_type"] = BroadcomCourseEntity::AUDITION_TYPE_1;
            }
            $teacher_list_content = $this->_getTeacherList($controller, $user, $order_item_info, $base_course_info);
            if ($controller->isError($teacher_list_content)) {
                $teacher_list_content->setPos(__FILE__, __LINE__);
                return $teacher_list_content;
            }
        }
        $repond_student_info = Utility::getJsonResponse("?t=D2EC2D87-7195-6707-EF12-E55DB18ABF7C&m=" . $user->member()->targetObjectId(), array("student_id" => $base_course_info["student_id"]));
        if ($controller->isError($repond_student_info)) {
            $repond_student_info->setPos(__FILE__, __LINE__);
            return $repond_student_info;
        }
        $student_info = $repond_student_info["student_info"];
        $base_course_info["school_id"] = $student_info["school_id"];
        $multi_flg = false;
        $multi_student_course_type_list = array(
            BroadcomCourseEntity::COURSE_TYPE_DOUBLE,
            BroadcomCourseEntity::COURSE_TYPE_TRIBLE,
            BroadcomCourseEntity::COURSE_TYPE_CLASS,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
        );
        if (in_array($base_course_info["course_type"], $multi_student_course_type_list)) {
            $multi_flg = true;
        }
        if ($audition_flg) {
            $course_type_list = array(
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO => "一对一",
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO => "一对二",
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD => "一对三"
            );
            $audition_type_list = BroadcomCourseEntity::getAuditionTypeList();
            $request->setAttribute("course_type_list", $course_type_list);
            $request->setAttribute("audition_type_list", $audition_type_list);
        }
        $request->setAttribute("base_course_info", $base_course_info);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("audition_flg", $audition_flg);
        $request->setAttribute("multi_flg", $multi_flg);
        $request->setAttribute("time_info", $time_info);
        $request->setAttribute("time_type_list", $time_type_list);
        $request->setAttribute("hours_list", $hours_list);
        $request->setAttribute("teacher_list_content", $teacher_list_content);
        return VIEW_DONE;
    }

    private function _getTeacherList(Controller $controller, User $user, $order_item_info, $base_course_info)
    {
        $post_data = array(
            "school_id" => $user->member()->schoolId(),
            "subject" => "1"
        );
        $respond_teacher_list = Utility::getJsonResponse("?t=C381A56F-A88A-9D03-B33B-52030E5154DD&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($respond_teacher_list)) {
            $respond_teacher_list->setPos(__FILE__, __LINE__);
            return $respond_teacher_list;
        }
        $teacher_list = $respond_teacher_list["teacher_list"];
        $subject_list = $respond_teacher_list["subject_list"];
        $allow_subject_list = array_keys($subject_list);
        if (!empty($order_item_info)) {
            $allow_subject_list = explode(",", $order_item_info["item_labels"]);
        }
        $result = "";
        foreach ($teacher_list as $subject_id => $teacher_list_tmp) {
            if (!empty($allow_subject_list) && in_array($subject_id, $allow_subject_list)) {
                $result .= '<optgroup label="' . $subject_list[$subject_id] . '">';
                foreach ($teacher_list_tmp as $teacher_member_id => $teacher_name) {
                    $result .= '<option value="' . $subject_id . "_" . $teacher_member_id . '"';
                    if ($subject_id == $base_course_info["subject_id"] && $teacher_member_id == $base_course_info["teacher_member_id"]) {
                        $result .= "selected";
                    }
                    $result .= ">" . $teacher_name . "</option>";
                }
                $result .= "</optgroup>";
            }
        }
        return $result;
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

    private function _doSelectExecute(Controller $controller, User $user, Request $request)
    {
        $base_course_info = $request->getAttribute("base_course_info");
        $student_info = $request->getAttribute("student_info");
        $order_item_info = $request->getAttribute("order_item_info");
        $time_info = $request->getAttribute("time_info");
        $audition_flg = $request->getAttribute("audition_flg");
        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d", time() + 24 * 60 * 60 * 90);
        $post_data = array(
            "school_id" => $base_course_info["school_id"],
            "start_date" => $start_date,
            "end_date" => $end_date,
            "student_id" => $base_course_info["student_id"]
        );
        if ($time_info["time_type"] == "1") {
            $post_data["teacher_member_id"] = $base_course_info["teacher_member_id"];
            $post_data["time"] = $time_info["start_time"];
            $post_data["hour"] = $time_info["course_hours"];
            $respond_calendar_list = Utility::getJsonResponse("?t=1A48D380-3B3F-A5AD-F4A6-23F86DC2BFC1&m=" . $user->member()->targetObjectId(), $post_data);
            if ($controller->isError($respond_calendar_list)) {
                $respond_calendar_list->setPos(__FILE__, __LINE__);
                return $respond_calendar_list;
            }
            $request->setAttribute("course_list", $respond_calendar_list["date_list"]);
        } else {
            $post_data["multi"] = "1";
            $post_data["course_type"] = $base_course_info["course_type"];
            if ($audition_flg) {
                $post_data["audition_type"] = $base_course_info["audition_type"];
            } else {
                $post_data["item_id"] = $base_course_info["item_id"];
            }
            $respond_course_list = Utility::getJsonResponse("?t=1A48D380-3B3F-A5AD-F4A6-23F86DC2BFC1&m=" . $user->member()->targetObjectId(), $post_data);
            if ($controller->isError($respond_course_list)) {
                $respond_course_list->setPos(__FILE__, __LINE__);
                return $respond_course_list;
            }
            $request->setAttribute("course_list", $respond_course_list["course_list"]);
        }
        $request->setAttribute("base_data", Utility::encodeCookieInfo($base_course_info));
        $request->setAttribute("time_data", Utility::encodeCookieInfo($time_info));
        return VIEW_DONE;
    }

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        $base_data = $request->getAttribute("base_data");
        $time_data = $request->getAttribute("time_data");
        $selected_date = $request->getAttribute("selected_date");
        $selected_info = $request->getAttribute("selected_info");
        $insert_list = array();
        if ($time_data["time_type"] == "1") {
            if (!empty($selected_date)) {
                foreach ($selected_date as $start_date) {
                    $insert_data = $base_data;
                    $course_start_date = $start_date . " " . $time_data["start_time"] . ":00";
                    $course_expire_date = date("Y-m-d H:i:s", strtotime($course_start_date) + $time_data["course_hours"] * 60 * 60);
                    $insert_data["course_start_date"] = $course_start_date;
                    $insert_data["course_expire_date"] = $course_expire_date;
                    $insert_data["course_hours"] = $time_data["course_hours"];
                    $insert_list[] = $insert_data;
                }
            }
        } else {
            if (!empty($selected_info)) {
                foreach ($selected_info as $encode_course_info) {
                    $course_info = Utility::decodeCookieInfo($encode_course_info);
                    $insert_data = $base_data;
                    $insert_data["course_start_date"] = $course_info["course_start_date"];
                    $insert_data["course_expire_date"] = $course_info["course_expire_date"];
                    $insert_data["course_hours"] = $course_info["course_hours"];
                    $insert_data["teacher_member_id"] = $course_info["teacher_member_id"];
                    $insert_data["subject_id"] = $course_info["subject_id"];
                    $insert_list[] = $insert_data;
                }
            }
        }
        if (!empty($insert_list)) {
            foreach ($insert_list as $insert_data) {
                $respond_course_id = Utility::getJsonResponse("?t=32FDBB8B-A808-4DB5-C2A6-F87D8DD2F5A2&m=" . $user->member()->targetObjectId(), $insert_data);
                if ($controller->isError($respond_course_id)) {
                    $respond_course_id->setPos(__FILE__, __LINE__);
                    return $respond_course_id;
                }
            }
        }
        $controller->redirect("./?menu=education&act=student_info&student_id=" . $base_data["student_id"]);
        return VIEW_DONE;
    }
}
?>