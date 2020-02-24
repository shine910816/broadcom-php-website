<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-23
 */
class BroadcomEducation_ClassScheduleAction extends BroadcomEducationActionBase
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
        $request->setAttribute("media_channel_list", BroadcomStudentEntity::getMediaChannelList());
        $request->setAttribute("purpose_level_list", BroadcomStudentEntity::getPurposeLevelList());
        $request->setAttribute("follow_status_list", BroadcomStudentEntity::getFollowStatusList());
        $request->setAttribute("student_level_list", BroadcomStudentEntity::getStudentLevelList());
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
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("school_id", $school_id);
Utility::testVariable($request->getAttributes());
        return VIEW_DONE;
    }
}
?>