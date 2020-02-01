<?php

/**
 * 成员模块基类
 * @author Kinsama
 * @version 2020-02-02
 */
class BroadcomMemberActionBase extends ActionBase
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
        $result[] = array("top", "个人设定");
        $result[] = array("info", "修改个人信息");
        $result[] = array("password", "修改登录密码");
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }
}
?>