<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-29
 */
class BroadcomEducation_CourseConfirmAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_reset")) {
            $ret = $this->_doResetExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_confirm")) {
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
        if (!$request->hasParameter("course_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $course_id = $request->getParameter("course_id");
        $member_id = $user->getMemberId();
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($member_id);
        if ($controller->isError($position_info)) {
            $position_info->setPos(__FILE__, __LINE__);
            return $position_info;
        }
        if (empty($position_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $position_info["school_id"];
        $teacher_flg = false;
        $teacher_position_list = array(
            BroadcomMemberEntity::POSITION_TEACH_MANAGER,
            BroadcomMemberEntity::POSITION_TEACHER,
            BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER
        );
        if (in_array($position_info["member_position"], $teacher_position_list)) {
            $teacher_flg = true;
        }
        $course_info = BroadcomCourseInfoDBI::selectCourseInfo($course_id);
        if ($controller->isError($course_info)) {
            $course_info->setPos(__FILE__, __LINE__);
            return $course_info;
        }
        if (empty($course_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if ($teacher_flg) {
            if ($member_id != $course_info["teacher_member_id"]) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        } else {
            if ($member_id != $course_info["assign_member_id"] && $position_info["member_position"] != BroadcomMemberEntity::POSITION_HEADMASTER) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        }
        $student_info = BroadcomStudentInfoDBI::selectStudentInfo($course_info["student_id"]);
        if ($controller->isError($student_info)) {
            $student_info->setPos(__FILE__, __LINE__);
            return $student_info;
        }
        if (empty($student_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $order_item_info = array();
        if ($course_info["course_type"] != BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO && $course_info["course_type"] != BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD) {
            $order_item_info = BroadcomOrderDBI::selectOrderItem($course_info["order_item_id"]);
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
            if (empty($order_item_info)) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        }
        $class_course_list = array();
        if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_CLASS) {
            $class_course_list = BroadcomCourseInfoDBI::selectClassCourseInfoBySchedule($course_info["schedule_id"], $course_info["schedule_index"]);
            if ($controller->isError($class_course_list)) {
                $class_course_list->setPos(__FILE__, __LINE__);
                return $class_course_list;
            }
        }
        $confirm_able_flg = false;
        $reset_able_flg = false;
        if ($course_info["course_type"] != BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO &&
            $course_info["course_type"] != BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD &&
            $course_info["course_type"] != BroadcomCourseEntity::COURSE_TYPE_CLASS &&
            $course_info["confirm_flg"] && !$course_info["reset_flg"] && !$teacher_flg) {
            $reset_able_flg = true;
        }
        // TODO auth
        $ready_cnf_flg = true;
        if ($course_info["confirm_flg"]) {
            $ready_cnf_flg = false;
        } else {
            if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_CLASS) {
                foreach ($class_course_list as $class_course_info) {
                    if ($class_course_info["order_item_remain"] <= 0) {
                        $ready_cnf_flg = false;
                        break;
                    } else {
                        continue;
                    }
                }
            } elseif ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION) {
                if ($student_info["audition_hours"] <= 0) {
                    // TODO 无试听时长时不能消试听课？
                    $ready_cnf_flg = false;
                }
            } else {
                if ($order_item_info["order_item_remain"] <= 0) {
                    $ready_cnf_flg = false;
                }
            }
        }
        if ($ready_cnf_flg) {
            if ($user->isAdmin()) {
                $confirm_able_flg = true;
            } elseif ($position_info["member_position"] == BroadcomMemberEntity::POSITION_HEADMASTER) {
                $confirm_from_date_ts = strtotime($course_info["course_expire_date"]);
                $confirm_to_date_ts = mktime(0, 0, -1, date("n", $confirm_from_date_ts), date("j", $confirm_from_date_ts) + 1, date("Y", $confirm_from_date_ts));
                $current_ts = time();
                if ($current_ts > $confirm_to_date_ts) {
                    $confirm_able_flg = true;
                }
            } else {
                $confirm_from_date_ts = strtotime($course_info["course_expire_date"]);
                $confirm_to_date_ts = mktime(0, 0, -1, date("n", $confirm_from_date_ts), date("j", $confirm_from_date_ts) + 1, date("Y", $confirm_from_date_ts));
                $current_ts = time();
                if ($current_ts > $confirm_from_date_ts && $current_ts < $confirm_to_date_ts) {
                    if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_CLASS) {
                        if ($teacher_flg) {
                            $confirm_able_flg = true;
                        }
                    } else {
                        $confirm_able_flg = true;
                    }
                }
            }
        }
        $room_list = BroadcomRoomInfoDBI::selectUsableRoomList($school_id);
        if ($controller->isError($room_list)) {
            $room_list->setPos(__FILE__, __LINE__);
            return $room_list;
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
        $item_list = BroadcomItemInfoDBI::selectItemInfoList();
        if ($controller->isError($item_list)) {
            $item_list->setPos(__FILE__, __LINE__);
            return $item_list;
        }
        $request->setAttribute("course_id", $course_id);
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("teacher_flg", $teacher_flg);
        $request->setAttribute("course_info", $course_info);
        $request->setAttribute("confirm_able_flg", $confirm_able_flg);
        $request->setAttribute("reset_able_flg", $reset_able_flg);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
        $request->setAttribute("room_list", $room_list);
        $request->setAttribute("teacher_info", $teacher_info);
        $request->setAttribute("student_list", $student_list);
        $request->setAttribute("item_list", $item_list);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("class_course_list", $class_course_list);
        $request->setAttribute("reset_reason_list", BroadcomCourseEntity::getCourseResetReasonCodeList());
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

    private function _doConfirmExecute(Controller $controller, User $user, Request $request)
    {
        $confirm_able_flg = $request->getAttribute("confirm_able_flg");
        if ($confirm_able_flg) {
            $course_id = $request->getAttribute("course_id");
            $member_id = $request->getAttribute("member_id");
            $course_info = $request->getAttribute("course_info");
            $student_info = $request->getAttribute("student_info");
            $order_item_info = $request->getAttribute("order_item_info");
            $class_course_list = $request->getAttribute("class_course_list");
            $dbi = Database::getInstance();
            $begin_res = $dbi->begin();
            if ($controller->isError($begin_res)) {
                $begin_res->setPos(__FILE__, __LINE__);
                return $begin_res;
            }
            if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_CLASS) {
                $actual_start_date = $course_info["course_start_date"];
                $actual_expire_date = $course_info["course_expire_date"];
                $actual_course_hours = $course_info["course_hours"];
                foreach ($class_course_list as $class_course_info) {
                    $course_update_data = array();
                    $course_update_data["actual_start_date"] = $actual_start_date;
                    $course_update_data["actual_expire_date"] = $actual_expire_date;
                    $course_update_data["actual_course_hours"] = $actual_course_hours;
                    $course_update_data["course_trans_price"] = round($order_item_info["order_item_trans_price"] * $actual_course_hours, 2);
                    $course_update_data["confirm_flg"] = "1";
                    $course_update_data["confirm_member_id"] = $member_id;
                    $course_update_data["confirm_date"] = date("Y-m-d H:i:s");
                    $course_update_res = BroadcomCourseInfoDBI::updateCourseInfo($course_update_data, $class_course_info["course_id"]);
                    if ($controller->isError($course_update_res)) {
                        $course_update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $course_update_res;
                    }
                    $order_item_update_data = array();
                    $order_item_update_data["order_item_remain"] = $class_course_info["order_item_remain"] - $actual_course_hours;
                    $order_item_update_data["order_item_confirm"] = $class_course_info["order_item_confirm"] + $actual_course_hours;
                    if ($order_item_update_data["order_item_remain"] <= 0) {
                        $order_item_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_3;
                        $order_item_update_data["order_item_remain"] = 0;
                    }
                    $order_item_update_res = BroadcomOrderDBI::updateOrderItem($order_item_update_data, $class_course_info["order_item_id"]);
                    if ($controller->isError($order_item_update_res)) {
                        $order_item_update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $order_item_update_res;
                    }
                }
            } else {
                $actual_course_hours = $request->getParameter("actual_course_hours");
                $start_date_ts = strtotime($course_info["course_start_date"]);
                $start_end_ts = $start_date_ts + $actual_course_hours * 60 * 60;
                $course_update_data = array();
                $course_update_data["actual_start_date"] = date("Y-m-d H:i:s", $start_date_ts);
                $course_update_data["actual_expire_date"] = date("Y-m-d H:i:s", $start_end_ts);
                $course_update_data["actual_course_hours"] = $actual_course_hours;
                if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION) {
                    $student_update_data = array();
                    if ($student_info["audition_hours"] > 0) {
                        $student_update_data["audition_hours"] = $student_info["audition_hours"] - $actual_course_hours;
                        if ($student_update_data["audition_hours"] < 0) {
                            $student_update_data["audition_hours"] = 0;
                        }
                        if ($student_info["follow_status"] != BroadcomStudentEntity::FOLLOW_STATUS_3) {
                            $student_update_data["follow_status"] = BroadcomStudentEntity::FOLLOW_STATUS_2;
                        }
                    }
                    if (!empty($student_update_data)) {
                        $student_update_res = BroadcomStudentInfoDBI::updateStudentInfo($student_update_data, $course_info["student_id"]);
                        if ($controller->isError($student_update_res)) {
                            $student_update_res->setPos(__FILE__, __LINE__);
                            $dbi->rollback();
                            return $student_update_res;
                        }
                    }
                } else {
                    $course_update_data["course_trans_price"] = round($order_item_info["order_item_trans_price"] * $actual_course_hours, 2);
                    $order_item_update_data = array();
                    $order_item_update_data["order_item_remain"] = $order_item_info["order_item_remain"] - $actual_course_hours;
                    $order_item_update_data["order_item_confirm"] = $order_item_info["order_item_confirm"] + $actual_course_hours;
                    if ($order_item_update_data["order_item_remain"] <= 0) {
                        $order_item_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_3;
                        $order_item_update_data["order_item_remain"] = 0;
                    }
                    $order_item_update_res = BroadcomOrderDBI::updateOrderItem($order_item_update_data, $order_item_info["order_item_id"]);
                    if ($controller->isError($order_item_update_res)) {
                        $order_item_update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $order_item_update_res;
                    }
                }
                $course_update_data["confirm_flg"] = "1";
                $course_update_data["confirm_member_id"] = $member_id;
                $course_update_data["confirm_date"] = date("Y-m-d H:i:s");
                $course_update_res = BroadcomCourseInfoDBI::updateCourseInfo($course_update_data, $course_id);
                if ($controller->isError($course_update_res)) {
                    $course_update_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $course_update_res;
                }
            }
            $commit_res = $dbi->commit();
            if ($controller->isError($commit_res)) {
                $commit_res->setPos(__FILE__, __LINE__);
                return $commit_res;
            }
        }
        $controller->redirect("./?menu=education&act=course_list");
        return VIEW_DONE;
    }

    private function _doResetExecute(Controller $controller, User $user, Request $request)
    {
        $reset_able_flg = $request->getAttribute("reset_able_flg");
        if ($reset_able_flg) {
            $member_id = $request->getAttribute("member_id");
            $course_id = $request->getAttribute("course_id");
            $reset_flg = $request->getParameter("reset_flg");
            $course_update_data = array();
            $course_update_data["reset_flg"] = $reset_flg;
            $course_update_data["reset_member_id"] = $member_id;
            $course_update_data["reset_date"] = date("Y-m-d H:i:s");
            $course_update_res = BroadcomCourseInfoDBI::updateCourseInfo($course_update_data, $course_id);
            if ($controller->isError($course_update_res)) {
                $course_update_res->setPos(__FILE__, __LINE__);
                return $course_update_res;
            }
        }
        $controller->redirect("./?menu=education&act=course_list");
        return VIEW_DONE;
    }
}
?>