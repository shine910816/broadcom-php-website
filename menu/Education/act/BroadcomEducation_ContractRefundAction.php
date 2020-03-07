<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-03-07
 */
class BroadcomEducation_ContractRefundAction extends BroadcomEducationActionBase
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
        $member_id = $user->getMemberId();
        if (!$request->hasParameter("order_item_id")) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_item_id = $request->getParameter("order_item_id");
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("order_item_id", $order_item_id);
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