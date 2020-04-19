<?php

/**
 * 排课信息创建画面
 * @author Kinsama
 * @version 2020-04-19
 */
class BroadcomCourse_CreateAction extends ActionBase
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
        if (!$request->editable()) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Create failed: user unauthorized");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        // 必要参数检证
        $ness_column_list = array(
            "course_type",
            "audition_type",
            "school_id",
            "teacher_member_id",
            "subject_id",
            "student_id",
            //"order_item_id",
            //"item_id",
            "course_start_date",
            "course_expire_date",
            "course_hours",
            "course_trans_price",
            //"actual_start_date",
            //"actual_expire_date",
            //"actual_course_hours",
            //"confirm_flg",
            //"confirm_member_id",
            //"confirm_date",
            //"assign_member_id",
            //"assign_date",
            //"operated_by"
        );
        $insert_data = array();
        foreach ($ness_column_list as $ness_column) {
            if (!$request->hasParameter($ness_column)) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: " . $ness_column);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            } else {
                $insert_data[$ness_column] = $request->getParameter($ness_column);
            }
        }
        if ($request->hasParameter("order_item_id") && $request->getParameter("order_item_id")) {
            $insert_data["order_item_id"] = $request->getParameter("order_item_id");
        }
        if ($request->hasParameter("item_id") && $request->getParameter("item_id")) {
            $insert_data["item_id"] = $request->getParameter("item_id");
        }
        $insert_data["confirm_flg"] = "0";
        $insert_data["assign_member_id"] = $request->member()->id();
        $insert_data["assign_date"] = date("Y-m-d H:i:s");
        $insert_data["operated_by"] = $request->member()->id();
        $request->setAttribute("insert_data", $insert_data);
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
        $insert_data = $request->getAttribute("insert_data");
        $audition_allow_list = array(
            BroadcomCourseEntity::COURSE_TYPE_DOUBLE,
            BroadcomCourseEntity::COURSE_TYPE_TRIBLE,
            BroadcomCourseEntity::COURSE_TYPE_CLASS,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
        );
        if (in_array($insert_data["course_type"], $audition_allow_list)) {
            $insert_data["multi_course_id"] = md5(sprintf("%s_%s_%s_%s", $insert_data["course_start_date"], $insert_data["course_hours"], $insert_data["subject_id"], $insert_data["teacher_member_id"]));
        } else {
            $insert_data["multi_course_id"] = "";
        }
        $insert_res = BroadcomCourseInfoDBI::insertCourseInfo($insert_data);
        if ($controller->isError($insert_res)) {
            $insert_res->setPos(__FILE__, __LINE__);
            return $insert_res;
        }
        return $insert_res;
    }
}
?>