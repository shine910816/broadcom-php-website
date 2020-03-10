<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-29
 */
class BroadcomEducation_ResetConfirmAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_cancel")) {
            $ret = $this->_doCancelExecute($controller, $user, $request);
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
        $course_info = BroadcomCourseInfoDBI::selectCourseInfo($course_id);;
        if ($controller->isError($course_info)) {
            $course_info->setPos(__FILE__, __LINE__);
            return $course_info;
        }
        if (empty($course_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if ($course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_AUDITION ||
            $course_info["course_type"] == BroadcomCourseEntity::COURSE_TYPE_CLASS ||
            !$course_info["confirm_flg"] || !$course_info["reset_flg"]) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
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
        $request->setAttribute("course_info", $course_info);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
        $request->setAttribute("room_list", $room_list);
        $request->setAttribute("teacher_info", $teacher_info);
        $request->setAttribute("student_list", $student_list);
        $request->setAttribute("item_list", $item_list);
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
        $course_id = $request->getAttribute("course_id");
        $member_id = $request->getAttribute("member_id");
        $course_info = $request->getAttribute("course_info");
        $order_item_info = $request->getAttribute("order_item_info");
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $order_item_update_data = array();
        $order_item_update_data["order_item_remain"] = $order_item_info["order_item_remain"] + $course_info["actual_course_hours"];
        $order_item_update_data["order_item_confirm"] = $order_item_info["order_item_confirm"] - $course_info["actual_course_hours"];
        if ($order_item_update_data["order_item_status"] == BroadcomOrderEntity::ORDER_ITEM_STATUS_3) {
            $order_item_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_2;
        }
        $order_item_update_res = BroadcomOrderDBI::updateOrderItem($order_item_update_data, $order_item_info["order_item_id"]);
        if ($controller->isError($order_item_update_res)) {
            $order_item_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_item_update_res;
        }
        $course_update_data = array();
        $course_update_data["reset_examine_flg"] = "1";
        $course_update_data["reset_examine_member_id"] = $member_id;
        $course_update_data["reset_examine_date"] = date("Y-m-d H:i:s");
        $course_update_res = BroadcomCourseInfoDBI::updateCourseInfo($course_update_data, $course_id);
        if ($controller->isError($course_update_res)) {
            $course_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $course_update_res;
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=education&act=course_list");
        return VIEW_DONE;
    }

    private function _doCancelExecute(Controller $controller, User $user, Request $request)
    {
        $course_id = $request->getAttribute("course_id");
        $member_id = $request->getAttribute("member_id");
        $course_update_data = array();
        $course_update_data["reset_flg"] = "0";
        $course_update_data["reset_examine_member_id"] = $member_id;
        $course_update_data["reset_examine_date"] = date("Y-m-d H:i:s");
        $course_update_res = BroadcomCourseInfoDBI::updateCourseInfo($course_update_data, $course_id);
        if ($controller->isError($course_update_res)) {
            $course_update_res->setPos(__FILE__, __LINE__);
            return $course_update_res;
        }
        $controller->redirect("./?menu=education&act=course_list");
        return VIEW_DONE;
    }
}
?>