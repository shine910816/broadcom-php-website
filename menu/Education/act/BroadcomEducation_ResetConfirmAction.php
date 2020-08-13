<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-29
 */
class BroadcomEducation_ResetConfirmAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_cancel")) {
            $ret = $this->_doCancelExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_confirm")) {
            $ret = $this->_doConfirmExecute($controller, $user, $request);
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
        $course_id = "";
        $multi_course_id = "";
        $multi_flg = false;
        if ($request->hasParameter("course_id")) {
            $course_id = $request->getParameter("course_id");
        } elseif ($request->hasParameter("multi_course_id")) {
            $multi_course_id = $request->getParameter("multi_course_id");
            $multi_flg = true;
        } else {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $post_data = array();
        if ($multi_flg) {
            $post_data["multi_course_id"] = $multi_course_id;
        } else {
            $post_data["course_id"] = $course_id;
        }
        $repond_course_info = Utility::getJsonResponse("?t=65118860-60BE-028D-5525-E40E18E58CAA&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_info)) {
            $repond_course_info->setPos(__FILE__, __LINE__);
            return $repond_course_info;
        }
        if (!$repond_course_info["base_info"]["has_reset_flg"]) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter failed: course_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $request->setAttribute("multi_flg", $multi_flg);
        $request->setAttribute("course_id", $course_id);
        $request->setAttribute("multi_course_id", $multi_course_id);
        $request->setAttributes($repond_course_info);
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
        return VIEW_DONE;
    }

    private function _doConfirmExecute(Controller $controller, User $user, Request $request)
    {
        $base_info = $request->getAttribute("base_info");
        $detail_list = $request->getAttribute("detail_list");
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $reset_hours = $base_info["actual_course_hours"];
        foreach ($detail_list as $course_info) {
            if ($base_info["audition_type"]) {
                $student_update_data = array();
                $student_update_data["audition_hours"] = $course_info["audition_hours"] + $reset_hours;
                if ($student_update_data["audition_hours"] > 2) {
                    $student_update_data["audition_hours"] = 2;
                }
                if ($course_info["follow_status"] !== BroadcomStudentEntity::FOLLOW_STATUS_3 && $student_update_data["audition_hours"] >= 2) {
                    $student_update_data["follow_status"] = BroadcomStudentEntity::FOLLOW_STATUS_1;
                }
                $student_update_res = BroadcomStudentInfoDBI::updateStudentInfo($student_update_data, $course_info["student_id"]);
                if ($controller->isError($student_update_res)) {
                    $student_update_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $student_update_res;
                }
            } else {
                $oi_update_data = array();
                $oi_update_data["order_item_remain"] = $course_info["order_item_remain"] + $reset_hours;
                $oi_update_data["order_item_arrange"] = $course_info["order_item_arrange"] - $reset_hours;
                $oi_update_data["order_item_confirm"] = $course_info["order_item_confirm"] - $reset_hours;
                if ($course_info["order_item_status"] == BroadcomOrderEntity::ORDER_ITEM_STATUS_3) {
                    $oi_update_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_2;
                }
                $oi_update_res = BroadcomOrderDBI::updateOrderItem($oi_update_data, $course_info["order_item_id"]);
                if ($controller->isError($oi_update_res)) {
                    $oi_update_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $oi_update_res;
                }
            }
        }
        $course_delete_res = BroadcomCourseInfoDBI::deleteMultiCourseById(array_keys($detail_list));
        if ($controller->isError($course_delete_res)) {
            $course_delete_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $course_delete_res;
        }
        $reset_update_data = array();
        $reset_update_data["reset_confirm_flg"] = "1";
        $reset_update_data["reset_confirm_member_id"] = $user->member()->id();
        $reset_update_data["reset_confirm_date"] = date("Y-m-d H:i:s");
        $reset_update_res = BroadcomCourseInfoDBI::updateCourseReset($reset_update_data, array_keys($detail_list));
        if ($controller->isError($reset_update_res)) {
            $reset_update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $reset_update_res;
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=education&act=reset_list");
        return VIEW_DONE;
    }

    private function _doCancelExecute(Controller $controller, User $user, Request $request)
    {
        $base_info = $request->getAttribute("base_info");
        $detail_list = $request->getAttribute("detail_list");
        $reset_remove_res = BroadcomCourseInfoDBI::removeCourseReset(array_keys($detail_list));
        if ($controller->isError($reset_remove_res)) {
            $reset_remove_res->setPos(__FILE__, __LINE__);
            return $reset_remove_res;
        }
        $controller->redirect("./?menu=education&act=reset_list");
        return VIEW_DONE;
    }
}
?>