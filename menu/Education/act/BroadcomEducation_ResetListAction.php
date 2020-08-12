<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_ResetListAction extends BroadcomEducationActionBase
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
        $school_id = $user->member()->schoolId();
        $reset_course_list = BroadcomCourseInfoDBI::selectResetCourseList($school_id);
        if ($controller->isError($reset_course_list)) {
            $reset_course_list->setPos(__FILE__, __LINE__);
            return $reset_course_list;
        }
        $course_type_list = BroadcomCourseEntity::getCourseTypeList();
        $audition_type_list = BroadcomCourseEntity::getAuditionTypeList();
        $subject_list = BroadcomSubjectEntity::getSubjectList();
        $reset_reason_list = BroadcomCourseEntity::getCourseResetReasonCodeList();
        $post_data = array(
            "school_id" => $school_id
        );
        $repond_member_list = Utility::getJsonResponse("?t=589049D8-F35C-2E6A-E792-D576E8002A2C&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_member_list)) {
            $repond_member_list->setPos(__FILE__, __LINE__);
            return $repond_member_list;
        }
        $member_list = $repond_member_list["member_list"];
        $audition_list = array(
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
        );
        $result_data_list = array();
        foreach ($reset_course_list as $course_info) {
            $multi_flg = false;
            $result_key = $course_info["course_id"];
            if ($course_info["multi_course_id"]) {
                $multi_flg = true;
                $result_key = $course_info["multi_course_id"];
            }
            if (in_array($course_info["course_type"], $audition_list)) {
                $course_info["item_name"] = $course_type_list[$course_info["course_type"]];
                $course_info["course_detail_type_name"] = $audition_type_list[$course_info["audition_type"]];
                $course_info["course_type_name"] = "试听课";
            } else {
                $course_info["course_type_name"] = "正课";
                $course_info["course_detail_type_name"] = "学员排课";
            }
            $course_info["teacher_member_name"] = isset($member_list[$course_info["teacher_member_id"]]) ? $member_list[$course_info["teacher_member_id"]]["m_name"] : "";
            $course_info["subject_name"] = $subject_list[$course_info["subject_id"]];
            $course_info["confirm_member_name"] = isset($member_list[$course_info["confirm_member_id"]]) ? $member_list[$course_info["confirm_member_id"]]["m_name"] : "";
            $course_info["reset_reason_name"] = $reset_reason_list[$course_info["reset_reason_code"]];
            $course_info["multi_flg"] = $multi_flg;
            if (!isset($result_data_list[$result_key])) {
                $result_data_list[$result_key] = $course_info;
            }
        }
        $request->setAttribute("reset_list", $result_data_list);
        return VIEW_DONE;
    }
}
?>