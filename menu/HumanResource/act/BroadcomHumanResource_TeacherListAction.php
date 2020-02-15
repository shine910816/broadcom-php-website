<?php
require_once SRC_PATH . "/menu/HumanResource/lib/BroadcomHumanResourceActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-15
 */
class BroadcomHumanResource_TeacherListAction extends BroadcomHumanResourceActionBase
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
        $request->setAttribute("position_list", BroadcomMemberEntity::getPositionList());
        $request->setAttribute("employed_status_list", BroadcomMemberEntity::getEmployedStatusList());
        $request->setAttribute("star_level_list", BroadcomMemberEntity::getStarLevelList());
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
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
        $teacher_list = BroadcomTeacherDBI::selectSchoolTeacherList($school_id);
        if ($controller->isError($teacher_list)) {
            $teacher_list->setPos(__FILE__, __LINE__);
            return $teacher_list;
        }
        $teacher_info_list = BroadcomTeacherDBI::selectTeacherInfoList($school_id);
        if ($controller->isError($teacher_info_list)) {
            $teacher_info_list->setPos(__FILE__, __LINE__);
            return $teacher_info_list;
        }
        $teacher_id_list = array();
        $no_subject_teacher_list = array();
        if (!empty($teacher_list)) {
            foreach ($teacher_list as $subject_id => $teacher_tmp_list) {
                foreach ($teacher_tmp_list as $teacher_member_id) {
                    $teacher_id_list[$teacher_member_id] = $teacher_member_id;
                }
            }
        }
        if (!empty($teacher_info_list)) {
            foreach ($teacher_info_list as $teacher_member_id => $teacher_tmp) {
                if (empty($teacher_id_list) || !in_array($teacher_member_id, $teacher_id_list)) {
                    $no_subject_teacher_list[] = $teacher_member_id;
                }
            }
        }
        $editable_flg = false;
        if ($user->checkPositionAble("human_resource", "teacher_info")) {
            $editable_flg = true;
        }
        $request->setAttribute("teacher_list", $teacher_list);
        $request->setAttribute("teacher_info_list", $teacher_info_list);
        $request->setAttribute("no_subject_teacher_list", $no_subject_teacher_list);
        $request->setAttribute("editable_flg", $editable_flg);
//Utility::testVariable($request->getAttributes());
        return VIEW_DONE;
    }
}
?>