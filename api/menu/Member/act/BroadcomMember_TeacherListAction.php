<?php

/**
 * 成员列表画面
 * @author Kinsama
 * @version 2020-04-11
 */
class BroadcomMember_TeacherListAction extends ActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("subject")) {
            $ret = $this->_doSubjectExecute($controller, $user, $request);
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
        $school_id = $request->getParameter("school_id");
        $member_list = BroadcomMemberInfoDBI::selectMemberList($school_id, BroadcomMemberEntity::SECTION_3);
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        if (!empty($member_list)) {
            foreach ($member_list as $member_id => $member_info) {
                if (!$this->_screenTeacher($member_info)) {
                    unset($member_list[$member_id]);
                }
            }
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("member_list", $member_list);
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
        $simple_flg = false;
        if ($request->hasParameter("simple")) {
            $simple_flg = true;
        }
        $member_list = $request->getAttribute("member_list");
        $member_result = array();
        if (!empty($member_list)) {
            $position_list = BroadcomMemberEntity::getPositionList();
            foreach ($member_list as $member_id => $member_info) {
                $position_name = $position_list[$member_info["member_position"]];
                if ($simple_flg) {
                    $disp_content = $member_info["m_name"];
                    if ($member_info["member_position"] == BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER) {
                        $disp_content .= "(兼职教师)";
                    }
                    $member_result[$member_id] = $disp_content;
                } else {
                    $covered_mobile_number = Utility::coverMobileNumber($member_info["m_mobile_number"]);
                    $member_info["member_position_name"] = $position_name;
                    $member_info["covered_mobile_number"] = $covered_mobile_number;
                    $member_result[$member_id] = $member_info;
                }
            }
        }
        return array(
            "teacher_list" => $member_result
        );
    }

    private function _doSubjectExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $member_list = $request->getAttribute("member_list");
        $subject_list = BroadcomSubjectEntity::getSubjectList();
        $subject_teacher_list = BroadcomTeacherDBI::selectSchoolTeacherList($school_id);
        if ($controller->isError($subject_teacher_list)) {
            $subject_teacher_list->setPos(__FILE__, __LINE__);
            return $subject_teacher_list;
        }
        $member_result = array();
        foreach (array_keys($subject_list) as $subject_id) {
            if (isset($subject_teacher_list[$subject_id])) {
                foreach ($subject_teacher_list[$subject_id] as $member_id) {
                    $member_result[$subject_id][$member_id] = $member_list[$member_id]["m_name"];
                }
            }
        }
        return array(
            "teacher_list" => $member_result,
            "subject_list" => $subject_list
        );
    }

    private function _screenTeacher($member_info)
    {
        // TODO screen logic
        //if (!is_null($xxx) && $member_info["xxx"] != $xxx) {
        //    return false;
        //}
        return true;
    }
}
?>