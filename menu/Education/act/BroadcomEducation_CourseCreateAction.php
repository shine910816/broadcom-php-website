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
            "course_type" => BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
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
            "2" => "选择他人时间",
        );
        $hours_list = explode(",", "1,1.5,2");
        $teacher_list_content = "";
        if ($request->hasParameter("do_create")) {
            // TODO
        } elseif ($request->hasParameter("time_type")) {
            // TODO
            $base_course_info["course_type"] = $request->getParameter("course_type");
            $base_course_info["audition_type"] = $request->getParameter("audition_type");
            $base_course_info["student_id"] = $request->getParameter("student_id");
            $base_course_info["school_id"] = $request->getParameter("school_id");
            $base_course_info["order_item_id"] = $request->getParameter("order_item_id");
            $base_course_info["item_id"] = $request->getParameter("item_id");
            $time_info["time_type"] = $request->getParameter("time_type");
            if ($time_info["time_type"] == "1") {
                $base_course_info["subject_id"] = $request->getParameter("subject_id");
                $base_course_info["teacher_member_id"] = $request->getParameter("teacher_member_id");
                $time_info["start_time"] = $request->getParameter("start_time");
                $time_info["course_hours"] = $request->getParameter("course_hours");
            }
            $order_item_info = Utility::getJsonResponse("?t=35FF8317-9F11-00B5-FEEF-467C7DA37D71&m=" . $user->member()->targetObjectId(), array("order_item_id" => $base_course_info["order_item_id"]));
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
            $base_course_info["course_trans_price"] = $order_item_info["order_item_trans_price"];
            $teacher_list_content = $this->_getTeacherList($controller, $user, $order_item_info);
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
            $teacher_list_content = $this->_getTeacherList($controller, $user, $order_item_info);
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

    private function _getTeacherList(Controller $controller, User $user, $order_item_info)
    {
        $allow_subject_list = array();
        if (!empty($order_item_info)) {
            $allow_subject_list = explode(",", $order_item_info["item_labels"]);
        }
        $post_data = array(
            "school_id" => $user->member()->schoolId(),
            "subject" => "1"
        );
        $respond_teacher_list = Utility::getJsonResponse("?t=C381A56F-A88A-9D03-B33B-52030E5154DD&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($respond_teacher_list)) {
            $respond_teacher_list->setPos(__FILE__, __LINE__);
            return $respond_teacher_list;
        }
Utility::testVariable($respond_teacher_list);
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
Utility::testVariable($request->getAttributes());
        return VIEW_DONE;
    }

    private function _doSelectExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        $member_id = $request->getAttribute("member_id");
        $course_type = $request->getAttribute("course_type");
        $school_id = $request->getAttribute("school_id");
        $student_id = $request->getAttribute("student_id");
        $getting_course_info = $request->getAttribute("getting_course_info");
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        if ($course_type == BroadcomCourseEntity::COURSE_TYPE_CLASS) {
            $schedule_id = $getting_course_info["schedule_id"];
            $schedule_list = $request->getAttribute("schedule_list");
            $order_item_id = $request->getAttribute("order_item_id");
            $item_id = $request->getAttribute("item_id");
            $order_item_info = $request->getAttribute("order_item_info");
            $schedule_refer_array = json_decode(base64_decode($schedule_list[$schedule_id]["schedule_content"]), true);
            foreach ($schedule_refer_array as $schedule_index => $schedule_refer) {
                $insert_data = array();
                $insert_data["course_type"] = $course_type;
                $insert_data["school_id"] = $school_id;
                $insert_data["room_id"] = $schedule_refer["room"];
                $insert_data["teacher_member_id"] = $schedule_refer["teacher"];
                $insert_data["subject_id"] = $schedule_refer["subject"];
                $insert_data["student_id"] = $student_id;
                $insert_data["order_item_id"] = $order_item_id;
                $insert_data["item_id"] = $item_id;
                $insert_data["schedule_id"] = $schedule_id;
                $insert_data["schedule_index"] = $schedule_index;
                $insert_data["course_start_date"] = $schedule_refer["start"];
                $insert_data["course_expire_date"] = $schedule_refer["end"];
                $insert_data["course_hours"] = $schedule_refer["period"];
                $insert_data["assign_member_id"] = $member_id;
                $insert_data["assign_date"] = date("Y-m-d H:i:s");
                $insert_res = BroadcomCourseInfoDBI::insertCourseInfo($insert_data);
                if ($controller->isError($insert_res)) {
                    $insert_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $insert_res;
                }
            }
        } else {
            $order_item_info = $request->getAttribute("order_item_info");
            foreach ($getting_course_info as $course_info) {
                $insert_data = array();
                if ($course_info["refer"] != "0") {
                    $others_course_info = $request->getAttribute("others_course_info");
                    $refer_course_info = $others_course_info[$course_info["refer"]];
                    $insert_data["room_id"] = $refer_course_info["room_id"];
                    $insert_data["teacher_member_id"] = $refer_course_info["teacher_member_id"];
                    $insert_data["subject_id"] = $refer_course_info["subject_id"];
                    $insert_data["course_start_date"] = $refer_course_info["course_start_date"];
                    $insert_data["course_expire_date"] = $refer_course_info["course_expire_date"];
                    $insert_data["course_hours"] = $refer_course_info["course_hours"];
                } else {
                    if ($course_info["start_date"] != "" && $course_info["start_time"] != "") {
                        $start_ts = strtotime($course_info["start_date"] . " " . $course_info["start_time"] . ":00");
                        $end_ts = $start_ts + $course_info["course_hours"] * 60 * 60;
                        $subject_teacher_arr = explode("-", $course_info["subject_teacher"]);
                        $insert_data["room_id"] = $course_info["room_id"];
                        $insert_data["teacher_member_id"] = $subject_teacher_arr[1];
                        $insert_data["subject_id"] = $subject_teacher_arr[0];
                        $insert_data["course_start_date"] = date("Y-m-d H:i:s", $start_ts);
                        $insert_data["course_expire_date"] = date("Y-m-d H:i:s", $end_ts);
                        $insert_data["course_hours"] = $course_info["course_hours"];
                    }
                }
                if (!empty($insert_data)) {
                    $insert_data["course_type"] = $course_type;
                    $insert_data["school_id"] = $school_id;
                    $insert_data["student_id"] = $student_id;
                    $insert_data["assign_member_id"] = $member_id;
                    $insert_data["assign_date"] = date("Y-m-d H:i:s");
                    if ($course_type != BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO && $course_type != BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD) {
                        $order_item_id = $request->getAttribute("order_item_id");
                        $item_id = $request->getAttribute("item_id");
                        $order_item_info = $request->getAttribute("order_item_info");
                        $insert_data["order_item_id"] = $order_item_id;
                        $insert_data["item_id"] = $item_id;
                    }
                    $insert_res = BroadcomCourseInfoDBI::insertCourseInfo($insert_data);
                    if ($controller->isError($insert_res)) {
                        $insert_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $insert_res;
                    }
                }
            }
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=education&act=student_info&student_id=" . $student_id);
        return VIEW_DONE;
    }
}
?>