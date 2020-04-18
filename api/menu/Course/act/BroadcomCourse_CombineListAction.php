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
        $school_id = $request->getParameter("school_id");
        $start_date = $request->getParameter("start_date");
        $end_date = $request->getParameter("end_date");
        $student_id = $request->getParameter("student_id");
        if ($request->hasParameter("multi")) {
            if (!$request->hasParameter("course_type")) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_type");
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $course_type = $request->getParameter("course_type");
            $audition_type = null;
            $item_id = null;
            if ($course_type == BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO || $course_type == BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD) {
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
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("start_date", $start_date);
        $request->setAttribute("end_date", $end_date);
        $request->setAttribute("student_id", $student_id);
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
                    $course_item["date"] = date("Y-m-d", $course_item["start_ts"]);
                    $course_result[$course_id] = $course_item;
                }
            }
        }
        $json_result = array();
        $json_result["course_list"] = $course_result;
        $select_able_list = array();
        if ($request->hasParameter("time") && $request->hasParameter("hour")) {
            $target_time = $request->getParameter("time");
            $target_hour = $request->getParameter("hour");
            if (!empty($course_result)) {
                foreach ($course_result as $course_id => $course_info) {
                    $date_key = $course_info["date"];
                    if (!isset($select_able_list[$date_key])) {
                        $select_able_list[$date_key] = "1";
                    }
                    $target_start_ts = strtotime($date_key . " " . $target_time . ":00");
                    $target_end_ts = $target_start_ts + $target_hour * 60 * 60;
                    if ($select_able_list[$date_key] && !($target_end_ts <= $course_info["start_ts"] || $target_start_ts >= $course_info["end_ts"])) {
                        $select_able_list[$date_key] = "0";
                    }
                }
            }
            $start_date = $request->getAttribute("start_date");
            $end_date = $request->getAttribute("end_date");
            $date_list = Utility::getWeeklyList($start_date, $end_date);
            foreach ($date_list as $year_key => $year_info) {
                foreach ($year_info as $month_key => $month_info) {
                    foreach ($month_info as $week_idx => $week_info) {
                        foreach ($week_info as $day_idx => $day_info) {
                            if (!empty($day_info)) {
                                if ($day_info["out"]) {
                                    $date_list[$year_key][$month_key][$week_idx][$day_idx]["able"] = "0";
                                } else {
                                    if (isset($select_able_list[$day_info["date"]])) {
                                        $date_list[$year_key][$month_key][$week_idx][$day_idx]["able"] = $select_able_list[$day_info["date"]];
                                    } else {
                                        $date_list[$year_key][$month_key][$week_idx][$day_idx]["able"] = "1";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $json_result["date_list"] = $date_list;
        }
        return $json_result;
    }

    private function _doMultiExecute(Controller $controller, User $user, Request $request)
    {
        $course_list = $request->getAttribute("course_list");
        $student_id = $request->getAttribute("student_id");
        $course_type = $request->getAttribute("course_type");
        $audition_type = $request->getAttribute("audition_type");
        $item_id = $request->getAttribute("item_id");
        $course_result = array();
        $teacher_list = array();
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                $target_flg = false;
                if ($course_info["student_id"] != $student_id && $course_info["course_type"] == $course_type) {
                    if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO || $course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD) {
                        if ($course_info["audition_type"] == $audition_type) {
                            $target_flg = true;
                        }
                    } else {
                        if ($course_info["item_id"] == $item_id) {
                            $target_flg = true;
                        }
                    }
                }
                if ($target_flg) {
                    $course_start_ts = strtotime($course_info["course_start_date"]);
                    $hours_num = sprintf("%.1f", $course_info["course_hours"]);
                    $result_key_array = array(
                        $course_info["course_start_date"],
                        sprintf("%.1f", $course_info["course_hours"]),
                        $course_info["subject_id"],
                        $course_info["teacher_member_id"],
                        $course_info["course_type"]
                    );
                    $teacher_list[$course_info["teacher_member_id"]] = $course_info["teacher_member_name"];
                    $result_key = implode("_", $result_key_array);
                    if (!isset($course_result[$result_key])) {
                        $course_result[$result_key] = array();
                    }
                    $course_result[$result_key][] = $course_info["student_name"] . "-" . $course_info["student_grade_name"];
                }
            }
        }
        $json_result = array();
        if (!empty($course_result)) {
            $subject_list = BroadcomSubjectEntity::getSubjectList();
            foreach ($course_result as $result_key => $student_info) {
                $key_array = explode("_", $result_key);
                $course_type = $key_array[4];
                $student_count = count($student_info);
                $max_count = 15;
                if ($course_type == BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO || $course_type == BroadcomCourseEntity::COURSE_TYPE_DOUBLE) {
                    $max_count = 2;
                } elseif ($course_type == BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD || $course_type == BroadcomCourseEntity::COURSE_TYPE_TRIBLE) {
                    $max_count = 3;
                }
                if ($student_count < $max_count) {
                    $item_result = array();
                    $item_result["course_start_date"] = $key_array[0];
                    $item_result["course_expire_date"] = date("Y-m-d H:i:s", strtotime($key_array[0]) + $key_array[1] * 60 * 60);
                    $item_result["course_hours"] = round($key_array[1], 1);
                    $item_result["teacher_member_id"] = $key_array[3];
                    $item_result["subject_id"] = $key_array[2];
                    $item_result["param"] = Utility::encodeCookieInfo($item_result);
                    $item_result["teacher_member_name"] = $teacher_list[$item_result["teacher_member_id"]];
                    $item_result["subject_name"] = $subject_list[$item_result["subject_id"]];
                    $item_result["student_count"] = $student_count;
                    $item_result["student_names"] = implode(", ", $student_info);
                    $json_result[] = $item_result;
                }
            }
        }
        return array(
            "course_list" => $json_result
        );
    }
}
?>