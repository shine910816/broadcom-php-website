<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_ScheduleCreateAction extends BroadcomEducationActionBase
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
        } elseif ($request->hasParameter("do_select")) {
            $ret = $this->_doChooseExecute($controller, $user, $request);
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
        $item_id = "";
        $ready_flg = false;
        if ($request->hasParameter("do_create")) {
            $school_id = $request->getParameter("school_id");
            $item_id = $request->getParameter("item_id");
            $course_info = $request->getParameter("course_info");
            $item_info = BroadcomItemInfoDBI::selectItemInfo($item_id);
            if ($controller->isError($item_info)) {
                $item_info->setPos(__FILE__, __LINE__);
                return $item_info;
            }
            if (empty($item_info)) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            // TODO check
            $request->setAttribute("school_id", $school_id);
            $request->setAttribute("item_info", $item_info);
            $request->setAttribute("course_info", $course_info);
        } elseif ($request->hasParameter("do_select")) {
            $item_id = $request->getParameter("selected_item_id");
            $ready_flg = true;
            $item_info = BroadcomItemInfoDBI::selectItemInfo($item_id);
            if ($controller->isError($item_info)) {
                $item_info->setPos(__FILE__, __LINE__);
                return $item_info;
            }
            if (empty($item_info)) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $member_id = $user->getMemberId();
            $position_info = BroadcomMemberPositionDBI::selectMemberPosition($member_id);
            if ($controller->isError($position_info)) {
                $position_info->setPos(__FILE__, __LINE__);
                return $position_info;
            }
            if (empty($position_info)) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $school_id = $position_info["school_id"];
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
            $allow_subject_array = explode(",", $item_info["item_labels"]);
            $subject_list = BroadcomSubjectEntity::getSubjectList();
            $teacher_list = array();
            foreach ($subject_teacher_info as $subject_id => $teacher_list_array) {
                if (in_array($subject_id, $allow_subject_array)) {
                    foreach ($teacher_list_array as $teacher_member_id) {
                        if (isset($teacher_info[$teacher_member_id])) {
                            $teacher_list[$teacher_member_id] = $teacher_info[$teacher_member_id]["m_name"];
                            if ($teacher_info[$teacher_member_id]["member_position"] == BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER) {
                                $teacher_list[$teacher_member_id] .= "(兼职)";
                            }
                        }
                    }
                }
            }
            $request->setAttribute("school_id", $school_id);
            $request->setAttribute("item_info", $item_info);
            $request->setAttribute("room_list", $room_list);
            $request->setAttribute("teacher_list", $teacher_list);
            $request->setAttribute("allow_subject_array", $allow_subject_array);
            $request->setAttribute("subject_list", $subject_list);
            $request->setAttribute("today_date", date("Y-m-d"));
        } else {
            $class_item_list = BroadcomItemInfoDBI::selectClassItemList();
            if ($controller->isError($class_item_list)) {
                $class_item_list->setPos(__FILE__, __LINE__);
                return $class_item_list;
            }
            $request->setAttribute("class_item_list", $class_item_list);
        }
        $request->setAttribute("item_id", $item_id);
        $request->setAttribute("ready_flg", $ready_flg);
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

    private function _doChooseExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $item_info = $request->getAttribute("item_info");
        $course_info = $request->getAttribute("course_info");
        $insert_data = array();
        $insert_data["school_id"] = $school_id;
        $insert_data["item_id"] = $item_info["item_id"];
        $insert_data["item_unit_amount"] = $item_info["item_unit_amount"];
        $insert_data["item_unit_hour"] = $item_info["item_unit_hour"];
        $schedule_content = array();
        foreach ($course_info as $course_idx => $course_item) {
            $start_date_ts = strtotime($course_item["start_date"] . " " . $course_item["start_time"] . ":00");
            $end_date_ts = $start_date_ts + $item_info["item_unit_hour"] * 60 * 60;
            $schedule_content_item = array();
            $schedule_content_item["start"] = date("Y-m-d H:i:s", $start_date_ts);
            $schedule_content_item["end"] = date("Y-m-d H:i:s", $end_date_ts);
            $schedule_content_item["period"] = round($item_info["item_unit_hour"], 1);
            $schedule_content_item["room"] = (int) $course_item["room_id"];
            $schedule_content_item["teacher"] = (int) $course_item["teacher_member_id"];
            $schedule_content_item["subject"] = (int) $course_item["subject_id"];
            $schedule_content_item["confirm"] = false;
            $schedule_content[$course_idx] = $schedule_content_item;
        }
        $start_idx = 1;
        $end_idx = count($schedule_content);
        $insert_data["schedule_content"] = base64_encode(json_encode($schedule_content));
        $insert_data["schedule_start_date"] =  $schedule_content[$start_idx]["start"];
        $insert_data["schedule_expire_date"] = $schedule_content[$end_idx]["end"];
        $insert_res = BroadcomScheduleInfoDBI::insertScheduleInfo($insert_data);
        if ($controller->isError($insert_res)) {
            $insert_res->setPos(__FILE__, __LINE__);
            return $insert_res;
        }
        $controller->redirect("./?menu=education&act=schedule_list");
        return VIEW_DONE;
    }
}
?>