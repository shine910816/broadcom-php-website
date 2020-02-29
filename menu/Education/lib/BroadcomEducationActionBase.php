<?php

/**
 * 学员教务基类
 * @author Kinsama
 * @version 2020-02-18
 */
class BroadcomEducationActionBase extends ActionBase
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
        $result[] = array("student_list", "学员管理");
        $result[] = array("schedule_list", "课表管理");
        $result[] = array("course_list", "排课列表");
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }
}
?>