<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_CourseListAction extends BroadcomEducationActionBase
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
        $current_date = date("Ymd");
        if ($request->hasParameter("date")) {
            $current_date = $request->getParameter("date");
        }
        $current_year = substr($current_date, 0, 4);
        $current_month = substr($current_date, 4, 2);
        $current_date_ts = mktime(0, 0, 0, $current_month, 1, $current_year);
        $course_date_from = date("Y-m-d H:i:s", $current_date_ts);
        $course_date_to = date("Y-m-d H:i:s", mktime(0, 0, -1, $current_month + 1, 1, $current_year));
        $prev_date = date("Ym", mktime(0, 0, 0, $current_month - 1, 1, $current_year));
        $next_date = date("Ym", mktime(0, 0, 0, $current_month + 1, 1, $current_year));
        $current_date_text = date("Y", $current_date_ts) . "年" . date("n", $current_date_ts) . "月";
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
        $course_list = BroadcomCourseInfoDBI::selectCourseInfoBySchool($school_id, $course_date_from, $course_date_to);
        if ($controller->isError($course_list)) {
            $course_list->setPos(__FILE__, __LINE__);
            return $course_list;
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
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("course_list", $course_list);
        $request->setAttribute("prev_date", $prev_date);
        $request->setAttribute("next_date", $next_date);
        $request->setAttribute("current_date_text", $current_date_text);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
        $request->setAttribute("room_list", $room_list);
        $request->setAttribute("teacher_info", $teacher_info);
        $request->setAttribute("student_list", $student_list);
        $request->setAttribute("item_list", $item_list);
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