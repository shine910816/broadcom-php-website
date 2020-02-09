<?php

/**
 * 前台业务基类
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFrontActionBase extends ActionBase
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
        $result[] = array("my_leads", "我的意向客户");
        $result[] = array("school_leads", "校区意向客户");
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }
}
?>