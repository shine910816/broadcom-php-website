<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-20
 */
class BroadcomEducation_StudentInfoAction extends BroadcomEducationActionBase
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
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $request->getParameter("student_id");
        $student_info = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
        if ($controller->isError($student_info)) {
            $student_info->setPos(__FILE__, __LINE__);
            return $student_info;
        }
        if (empty($student_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_level_list = BroadcomStudentEntity::getStudentLevelList();
        $student_info["student_grade"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
        $student_info["student_level_name"] = $student_level_list[$student_info["student_level"]];
        $order_list = BroadcomOrderDBI::selectOrderInfoByStudentId($student_id);
        if ($controller->isError($order_list)) {
            $order_list->setPos(__FILE__, __LINE__);
            return $order_list;
        }
        $order_item_list = BroadcomOrderDBI::selectOrderItemByStudent($student_id);
        if ($controller->isError($order_item_list)) {
            $order_item_list->setPos(__FILE__, __LINE__);
            return $order_item_list;
        }
        $item_list = BroadcomItemInfoDBI::selectItemInfoList();
        if ($controller->isError($item_list)) {
            $item_list->setPos(__FILE__, __LINE__);
            return $item_list;
        }
        $course_list = BroadcomCourseInfoDBI::selectCourseInfoByStudent($student_id);
        if ($controller->isError($course_list)) {
            $course_list->setPos(__FILE__, __LINE__);
            return $course_list;
        }
        $school_id = $student_info["school_id"];
        $room_list = BroadcomRoomInfoDBI::selectUsableRoomList($school_id);
        if ($controller->isError($room_list)) {
            $room_list->setPos(__FILE__, __LINE__);
            return $room_list;
        }
        $teacher_info = BroadcomTeacherDBI::selectTeacherInfoList($school_id);
        if ($controller->isError($teacher_info)) {
            $teacher_info->setPos(__FILE__, __LINE__);
            return $teacher_info;
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("order_list", $order_list);
        $request->setAttribute("order_item_list", $order_item_list);
        $request->setAttribute("item_list", $item_list);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
        $request->setAttribute("order_status_list", BroadcomOrderEntity::getOrderStatusList());
        $request->setAttribute("order_item_status_list", BroadcomOrderEntity::getOrderItemStatusList());
        $request->setAttribute("course_list", $course_list);
        $request->setAttribute("course_type_list", BroadcomCourseEntity::getCourseTypeList());
        $request->setAttribute("room_list", $room_list);
        $request->setAttribute("teacher_info", $teacher_info);
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
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
        $back_link = Utility::encodeBackLink("education", "student_info", array(
            "student_id" => $request->getAttribute("student_id")
        ));
        $request->setAttribute("back_link", $back_link);
        return VIEW_DONE;
    }
}
?>