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
        if ($user->checkPositionAble("education", "my_student_list")) {
            $result[] = array("my_student_list", "我的学员管理");
        }
        if ($user->checkPositionAble("education", "student_list")) {
            $result[] = array("student_list", "校区学员列表");
        }
        if ($user->checkPositionAble("education", "course_list")) {
            $result[] = array("course_list", "一对一排课列表");
        }
        if ($user->checkPositionAble("education", "multi_course_list")) {
            $result[] = array("multi_course_list", "一对多排课列表");
        }
        if ($user->checkPositionAble("education", "reset_list")) {
            $result[] = array("reset_list", "撤销消课申请表");
        }
        $request->setAttribute("left_content", $result);
        return VIEW_DONE;
    }
}
?>
