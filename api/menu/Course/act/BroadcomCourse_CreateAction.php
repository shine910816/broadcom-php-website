<?php

/**
 * 排课信息创建画面
 * @token 32FDBB8B-A808-4DB5-C2A6-F87D8DD2F5A2
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
        if (!$request->hasParameter("params")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Create failed: params missed");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $param_list = Utility::decodeCookieInfo($request->getParameter("params"));
        // 必要参数检证
        $ness_column_list = array(
            "course_type",
            "audition_type",
            "school_id",
            "teacher_member_id",
            "subject_id",
            "student_id",
            "course_start_date",
            "course_expire_date",
            "course_hours",
            "course_trans_price"
        );
        $insert_data = array();
        foreach ($ness_column_list as $ness_column) {
            if (!isset($param_list[$ness_column])) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: " . $ness_column);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            } else {
                $insert_data[$ness_column] = $param_list[$ness_column];
            }
        }
        $multi_allow_list = array(
            BroadcomCourseEntity::COURSE_TYPE_DOUBLE,
            BroadcomCourseEntity::COURSE_TYPE_TRIBLE,
            BroadcomCourseEntity::COURSE_TYPE_CLASS,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
            BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
        );
        if (in_array($insert_data["course_type"], $multi_allow_list)) {
            $insert_data["multi_course_id"] = md5(sprintf("%s_%s_%s_%s", $insert_data["course_start_date"], $insert_data["course_hours"], $insert_data["subject_id"], $insert_data["teacher_member_id"]));
        } else {
            $insert_data["multi_course_id"] = "";
        }
        if ($request->hasParameter("order_item_id") && $request->hasParameter("item_id")) {
            $insert_data["order_item_id"] = $request->getParameter("order_item_id");
            $insert_data["item_id"] = $request->getParameter("item_id");
        }
        $insert_data["confirm_flg"] = "0";
        $insert_data["assign_member_id"] = $request->member()->id();
        $insert_data["assign_date"] = date("Y-m-d H:i:s");
        $insert_data["operated_by"] = $request->member()->id();
Utility::testVariable($insert_data);
        $student_info = array();
        $order_item_info = array();
        if ($insert_data["audition_type"]) {
            $post_data = array(
                "student_id" => $insert_data["student_id"]
            );
            $repond_student_info = Utility::getJsonResponse("?t=D2EC2D87-7195-6707-EF12-E55DB18ABF7C&m=" . $request->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_student_info)) {
                $repond_student_info->setPos(__FILE__, __LINE__);
                return $repond_student_info;
            }
            $student_info = $repond_student_info["student_info"];
        } else {
            $post_data = array(
                "order_item_id" => $insert_data["order_item_id"]
            );
            $order_item_info = Utility::getJsonResponse("?t=35FF8317-9F11-00B5-FEEF-467C7DA37D71&m=" . $request->member()->targetObjectId(), $post_data);
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
        }
        $request->setAttribute("insert_data", $insert_data);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("order_item_info", $order_item_info);
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
        $student_info = $request->getAttribute("student_info");
        $order_item_info = $request->getAttribute("order_item_info");
        $student_update_data = array();
        $order_update_data = array();
        if (!empty($student_info)) {
            $restore_audition_hours = $student_info["audition_hours"] - $insert_data["course_hours"];
            if ($restore_audition_hours < 0) {
                $restore_audition_hours = 0;
            }
            $student_update_data["audition_hours"] = $restore_audition_hours;
        }
        if (!empty($order_item_info)) {
            $order_update_data["order_item_remain"] = $order_item_info["order_item_remain"] - $insert_data["course_hours"];
            $order_update_data["order_item_arrange"] = $order_item_info["order_item_arrange"] + $insert_data["course_hours"];
            if ($order_update_data["order_item_remain"] < 0) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Create failed: not enough");
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        }
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $insert_res = BroadcomCourseInfoDBI::insertCourseInfo($insert_data);
        if ($controller->isError($insert_res)) {
            $insert_res->setPos(__FILE__, __LINE__);
            return $insert_res;
        }
        if (!empty($student_update_data)) {
            $student_update_res = BroadcomStudentInfoDBI::updateStudentInfo($student_update_data, $insert_data["student_id"]);
            if ($controller->isError($student_update_res)) {
                $student_update_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $student_update_res;
            }
        }
        if (!empty($order_update_data)) {
            $order_update_res = BroadcomOrderDBI::updateOrderItem($order_update_data, $insert_data["order_item_id"]);
            if ($controller->isError($order_update_res)) {
                $order_update_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $order_update_res;
            }
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        return $insert_res;
    }
}
?>