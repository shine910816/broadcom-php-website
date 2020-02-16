<?php
require_once SRC_PATH . "/menu/HumanResource/lib/BroadcomHumanResourceActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-02
 */
class BroadcomHumanResource_TeacherInfoAction extends BroadcomHumanResourceActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_change")) {
            $ret = $this->_doChangeExecute($controller, $user, $request);
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
        if (!$request->hasParameter("teacher_member_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $teacher_member_id = $request->getParameter("teacher_member_id");
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
        $teacher_info = BroadcomTeacherDBI::selectTeacherInfoList($school_id);
        if ($controller->isError($teacher_info)) {
            $teacher_info->setPos(__FILE__, __LINE__);
            return $teacher_info;
        }
        if (!isset($teacher_info[$teacher_member_id])) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $teacher_member_info = $teacher_info[$teacher_member_id];
        $teacher_name = $teacher_member_info["m_name"];
        $teacher_subject_list = BroadcomTeacherDBI::selectSchoolTeacherSubjectList($school_id, $teacher_member_id);
        if ($controller->isError($teacher_subject_list)) {
            $teacher_subject_list->setPos(__FILE__, __LINE__);
            return $teacher_subject_list;
        }
        if ($request->hasParameter("do_change")) {
            $getting_teacher_member_info = $request->getParameter("teacher_member_info");
            $getting_teacher_subject_list = array();
            if (!$request->hasParameter("teacher_subject_list")) {
                $request->setError("subject_list", "至少选择一门学科");
            } else {
                $getting_teacher_subject_list = $request->getParameter("teacher_subject_list");
            }
            if (count($getting_teacher_subject_list) > 2) {
                $request->setError("subject_list", "最多选择两门学科");
            }
            $request->setAttribute("getting_teacher_member_info", $getting_teacher_member_info);
            $request->setAttribute("getting_teacher_subject_list", $getting_teacher_subject_list);
        }
        $request->setAttribute("teacher_member_id", $teacher_member_id);
        $request->setAttribute("teacher_member_info", $teacher_member_info);
        $request->setAttribute("teacher_name", $teacher_name);
        $request->setAttribute("teacher_subject_list", $teacher_subject_list);
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
        $request->setAttribute("star_level_list", BroadcomMemberEntity::getStarLevelList());
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

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        $getting_teacher_member_info = $request->getAttribute("getting_teacher_member_info");
        $getting_teacher_subject_list = $request->getAttribute("getting_teacher_subject_list");
        $request->setAttribute("teacher_member_info", $getting_teacher_member_info);
        $request->setAttribute("teacher_subject_list", $getting_teacher_subject_list);
        return VIEW_DONE;
    }

    private function _doChangeExecute(Controller $controller, User $user, Request $request)
    {
        $getting_teacher_member_info = $request->getAttribute("getting_teacher_member_info");
        $getting_teacher_subject_list = $request->getAttribute("getting_teacher_subject_list");
        $teacher_member_info = $request->getAttribute("teacher_member_info");
        $teacher_member_id = $teacher_member_info["member_id"];
        $school_id = $teacher_member_info["school_id"];
        $update_data = array();
        if ($getting_teacher_member_info["m_licence_number"] != $teacher_member_info["m_licence_number"]) {
            $update_data["m_licence_number"] = $getting_teacher_member_info["m_licence_number"];
        }
        if ($getting_teacher_member_info["m_primary_star_level"] != $teacher_member_info["m_primary_star_level"]) {
            $update_data["m_primary_star_level"] = $getting_teacher_member_info["m_primary_star_level"];
        }
        if ($getting_teacher_member_info["m_junior_star_level"] != $teacher_member_info["m_junior_star_level"]) {
            $update_data["m_junior_star_level"] = $getting_teacher_member_info["m_junior_star_level"];
        }
        if ($getting_teacher_member_info["m_senior_star_level"] != $teacher_member_info["m_senior_star_level"]) {
            $update_data["m_senior_star_level"] = $getting_teacher_member_info["m_senior_star_level"];
        }
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        if (!empty($update_data)) {
            $update_res = BroadcomMemberInfoDBI::updateMemberInfo($update_data, $teacher_member_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $update_res;
            }
        }
        $delete_res = BroadcomTeacherDBI::removeTeacher($school_id, $teacher_member_id);
        if ($controller->isError($delete_res)) {
            $delete_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $delete_res;
        }
        foreach ($getting_teacher_subject_list as $subject_id) {
            $insert_data = array();
            $insert_data["school_id"] = $school_id;
            $insert_data["member_id"] = $teacher_member_id;
            $insert_data["subject_id"] = $subject_id;
            $insert_res = BroadcomTeacherDBI::insertTeacher($insert_data);
            if ($controller->isError($insert_res)) {
                $insert_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $insert_res;
            }
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("?menu=human_resource&act=teacher_list");
        return VIEW_DONE;
    }
}
?>