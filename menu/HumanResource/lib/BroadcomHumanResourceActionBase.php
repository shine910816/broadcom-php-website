<?php

/**
 * 人力资源模块基类
 * @author Kinsama
 * @version 2020-02-06
 */
class BroadcomHumanResourceActionBase extends ActionBase
{

    /**
     * 左边栏
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doLeftContent(Controller $controller, User $user, Request $request)
    {
        $result = array();
        if ($user->checkPositionAble("human_resource", "member_list")) {
            $result[] = array("member_list", "成员列表");
        }
        if ($user->checkPositionAble("human_resource", "teacher_list")) {
            $result[] = array("teacher_list", "教师列表");
        }
        $request->setAttribute("left_content", $result);
        $request->setAttribute("member_info_template_file", SRC_PATH . "/menu/Member/tpl/BroadcomMemberBaseInfoView.tpl");
        return VIEW_DONE;
    }
}
?>