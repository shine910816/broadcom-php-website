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
        $result[] = array("my_student_list", "我的学员管理");
        //$result[] = array("schedule_list", "校区课表管理");
        $result[] = array("student_list", "校区学员列表");
        $result[] = array("course_list", "一对一排课列表");
        $result[] = array("multi_course_list", "一对多排课列表");
        //$result[] = array("reset_list", "校区返课列表");
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }
}
?>