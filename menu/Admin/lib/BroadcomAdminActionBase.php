<?php

/**
 * 后台管理基类
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomAdminActionBase extends ActionBase
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
        $result[] = array("school_list", "校区管理");
        $result[] = array("item_list", "课程管理");
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }
}
?>