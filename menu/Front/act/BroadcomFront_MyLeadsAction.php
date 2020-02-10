<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 我的意向客户画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_MyLeadsAction extends BroadcomFrontActionBase
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
        $student_info_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($member_id);
        if ($controller->isError($student_info_list)) {
            $student_info_list->setPos(__FILE__, __LINE__);
            return $student_info_list;
        }
        $page_url = "./?menu=" . $request->current_menu . "&act=" . $request->current_act;
        $student_info_list = Utility::getPaginationData($request, $student_info_list, $page_url);
        if ($controller->isError($student_info_list)) {
            $student_info_list->setPos(__FILE__, __LINE__);
            return $student_info_list;
        }
        foreach ($student_info_list as $student_id => $student_info) {
            $student_info_list[$student_id]["grade_name"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
        }
        $member_name_list = BroadcomMemberInfoDBI::selectMemberNameList();
        if ($controller->isError($member_name_list)) {
            $member_name_list->setPos(__FILE__, __LINE__);
            return $member_name_list;
        }
        $request->setAttribute("student_info_list", $student_info_list);
        $request->setAttribute("member_name_list", $member_name_list);
        return VIEW_DONE;
    }
}
?>