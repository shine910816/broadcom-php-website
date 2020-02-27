<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_CourseCreateAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_create")) {
            $ret = $this->_doCreateExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
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
        $student_id = "";
        $student_info = array();
        $school_id = "";
        $order_item_info = array();
        $course_type = BroadcomCourseEntity::COURSE_TYPE_AUDITION;
        $schedule_list = array();
        $create_able_flg = false;
        $set_course_info = array();
        $others_course_info = array();
        if ($request->hasParameter("order_item_id")) {
            $order_item_id = $request->getParameter("order_item_id");
            $order_item_info = BroadcomOrderDBI::selectOrderItem($order_item_id);
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
            if (empty($order_item_info)) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $student_id = $order_item_info["student_id"];
            $student_info["student_id"] = $student_id;
            $student_info["student_name"] = $order_item_info["student_name"];
            $student_info["grade_name"] = BroadcomStudentEntity::getGradeName($order_item_info["student_entrance_year"]);
            $school_id = $order_item_info["school_id"];
            $item_id = $order_item_info["item_id"];
            $set_course_info = BroadcomCourseInfoDBI::selectCourseInfoByOrderItem($order_item_id);
            if ($controller->isError($set_course_info)) {
                $set_course_info->setPos(__FILE__, __LINE__);
                return $set_course_info;
            }
            if ($order_item_info["item_method"] == BroadcomItemEntity::ITEM_METHOD_CLASS) {
                $course_type = BroadcomCourseEntity::COURSE_TYPE_CLASS;
                if (empty($set_course_info)) {
                    $create_able_flg = true;
                    $schedule_list = BroadcomScheduleInfoDBI::selectPeriodScheduleByItem($school_id, $item_id, date("Y-m-d H:i:s"));
                    if ($controller->isError($schedule_list)) {
                        $schedule_list->setPos(__FILE__, __LINE__);
                        return $schedule_list;
                    }
                }
            } elseif ($order_item_info["item_method"] == BroadcomItemEntity::ITEM_METHOD_1_TO_1) {
                $course_type = BroadcomCourseEntity::COURSE_TYPE_SINGLE;
                $create_able_flg = true;
            } else {
                $course_type = BroadcomCourseEntity::COURSE_TYPE_MULTI;
                $create_able_flg = true;
                $others_course_info = BroadcomCourseInfoDBI::selectMultiCourseInfoByItem($item_id, $student_id);
                if ($controller->isError($others_course_info)) {
                    $others_course_info->setPos(__FILE__, __LINE__);
                    return $others_course_info;
                }
            }
        } else {
            if (!$request->hasParameter("student_id")) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $student_id = $request->getParameter("student_id");
            $student_info_data = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
            if ($controller->isError($student_info_data)) {
                $student_info_data->setPos(__FILE__, __LINE__);
                return $student_info_data;
            }
            if (empty($student_info_data)) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $school_id = $student_info_data["school_id"];
            $student_info["student_id"] = $student_info_data["student_id"];
            $student_info["student_name"] = $student_info_data["student_name"];
            $student_info["grade_name"] = BroadcomStudentEntity::getGradeName($student_info_data["student_entrance_year"]);
            $set_course_info = BroadcomCourseInfoDBI::selectAuditionCourseInfoByStudent($student_id);
            if ($controller->isError($set_course_info)) {
                $set_course_info->setPos(__FILE__, __LINE__);
                return $set_course_info;
            }
            if ($student_info_data["audition_hours"] > 0) {
                $create_able_flg = true;
            }
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("order_item_info", $order_item_info);
        $request->setAttribute("course_type", $course_type);
        $request->setAttribute("create_able_flg", $create_able_flg);
        $request->setAttribute("set_course_info", $set_course_info);
        $request->setAttribute("schedule_list", $schedule_list);
        $request->setAttribute("others_course_info", $others_course_info);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
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
//Utility::testVariable($request->getAttributes());
        return VIEW_DONE;
    }

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>