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
        $student_id = "";
        $student_info = array();
        $school_id = "";
        $order_item_id = null;
        $order_item_info = array();
        $item_id = null;
        $course_type = BroadcomCourseEntity::COURSE_TYPE_AUDITION;
        $schedule_list = array();
        $create_able_flg = false;
        $set_course_info = array();
        $others_course_info = array();
        $subject_list = BroadcomSubjectEntity::getSubjectList();
        $allow_subject_list = array_keys($subject_list);
        $hint_context = "";
        if ($request->hasParameter("order_item_id")) {
            // TODO 
            //BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
            //BroadcomMemberEntity::POSITION_ASSIST_MANAGER,    // 学管主管
            //BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
            // 以上权限能排正课
            $order_item_id = $request->getParameter("order_item_id");
            $order_item_info = BroadcomOrderDBI::selectOrderItem($order_item_id);
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
            if (empty($order_item_info)) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $student_id = $order_item_info["student_id"];
            $student_info["student_id"] = $student_id;
            $student_info["student_name"] = $order_item_info["student_name"];
            $student_info["grade_name"] = BroadcomStudentEntity::getGradeName($order_item_info["student_entrance_year"]);
            $school_id = $order_item_info["school_id"];
            $item_id = $order_item_info["item_id"];
            $allow_subject_list = explode(",", $order_item_info["item_labels"]);
            $set_course_info = BroadcomCourseInfoDBI::selectCourseInfoByOrderItem($order_item_id);
            if ($controller->isError($set_course_info)) {
                $set_course_info->setPos(__FILE__, __LINE__);
                return $set_course_info;
            }
            if ($order_item_info["item_method"] == BroadcomItemEntity::ITEM_METHOD_CLASS) {
                $course_type = BroadcomCourseEntity::COURSE_TYPE_CLASS;
                if (empty($set_course_info)) {
                    $create_able_flg = true;
                    $schedule_list = BroadcomScheduleInfoDBI::selectPeriodScheduleByItem($school_id, $item_id, date("Y-m-d H:i:s"));
                    if ($controller->isError($schedule_list)) {
                        $schedule_list->setPos(__FILE__, __LINE__);
                        return $schedule_list;
                    }
                }
            } elseif ($order_item_info["item_method"] == BroadcomItemEntity::ITEM_METHOD_1_TO_1) {
                $course_type = BroadcomCourseEntity::COURSE_TYPE_SINGLE;
                $create_able_flg = true;
            } else {
                $course_type = BroadcomCourseEntity::COURSE_TYPE_MULTI;
                $create_able_flg = true;
            }
        } else {
            if (!$request->hasParameter("student_id")) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $student_id = $request->getParameter("student_id");
            $student_info_data = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
            if ($controller->isError($student_info_data)) {
                $student_info_data->setPos(__FILE__, __LINE__);
                return $student_info_data;
            }
            if (empty($student_info_data)) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $school_id = $student_info_data["school_id"];
            $student_info["student_id"] = $student_info_data["student_id"];
            $student_info["student_name"] = $student_info_data["student_name"];
            $student_info["grade_name"] = BroadcomStudentEntity::getGradeName($student_info_data["student_entrance_year"]);
            $set_course_info = BroadcomCourseInfoDBI::selectAuditionCourseInfoByStudent($student_id);
            if ($controller->isError($set_course_info)) {
                $set_course_info->setPos(__FILE__, __LINE__);
                return $set_course_info;
            }
            $create_able_flg = true;
            if ($student_info_data["audition_hours"] <= 0) {
                $hint_context = "该学员已经试听超过2小时";
            }
        }
        if ($course_type == BroadcomCourseEntity::COURSE_TYPE_AUDITION || $course_type == BroadcomCourseEntity::COURSE_TYPE_MULTI) {
            $others_course_info = BroadcomCourseInfoDBI::selectMultiCourseInfoByItem($student_id, date("Y-m-d H:i:s"), $item_id);
            if ($controller->isError($others_course_info)) {
                $others_course_info->setPos(__FILE__, __LINE__);
                return $others_course_info;
            }
        }
        $room_list = BroadcomRoomInfoDBI::selectUsableRoomList($school_id);
        if ($controller->isError($room_list)) {
            $room_list->setPos(__FILE__, __LINE__);
            return $room_list;
        }
        $subject_teacher_info = BroadcomTeacherDBI::selectSchoolTeacherList($school_id);
        if ($controller->isError($subject_teacher_info)) {
            $subject_teacher_info->setPos(__FILE__, __LINE__);
            return $subject_teacher_info;
        }
        $teacher_info = BroadcomTeacherDBI::selectTeacherInfoList($school_id);
        if ($controller->isError($teacher_info)) {
            $teacher_info->setPos(__FILE__, __LINE__);
            return $teacher_info;
        }
        $student_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
        if ($controller->isError($student_list)) {
            $student_list->setPos(__FILE__, __LINE__);
            return $student_list;
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("item_id", $item_id);
        $request->setAttribute("order_item_id", $order_item_id);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("course_type", $course_type);
        $request->setAttribute("create_able_flg", $create_able_flg);
        $request->setAttribute("set_course_info", $set_course_info);
        $request->setAttribute("schedule_list", $schedule_list);
        $request->setAttribute("others_course_info", $others_course_info);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("subject_list", $subject_list);
        $request->setAttribute("allow_subject_list", $allow_subject_list);
        $request->setAttribute("hint_context", $hint_context);
        $request->setAttribute("room_list", $room_list);
        $request->setAttribute("subject_teacher_info", $subject_teacher_info);
        $request->setAttribute("teacher_info", $teacher_info);
        $request->setAttribute("student_list", $student_list);
        if ($request->hasParameter("do_create")) {
            $request->setAttribute("getting_course_info", $request->getParameter("course_info"));
        }
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

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
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
                    if ($course_type != BroadcomCourseEntity::COURSE_TYPE_AUDITION) {
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