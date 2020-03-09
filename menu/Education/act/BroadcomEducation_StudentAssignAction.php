<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-03-09
 */
class BroadcomEducation_StudentAssignAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_assign")) {
            $ret = $this->_doAssignExecute($controller, $user, $request);
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
        $member_id = $user->getMemberId();
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($member_id);
        if ($controller->isError($position_info)) {
            $position_info->setPos(__FILE__, __LINE__);
            return $position_info;
        }
        if (empty($position_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $position_info["school_id"];
        $position_list = array(
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,
            BroadcomMemberEntity::POSITION_ASSISTANT
        );
        $assign_able_list = BroadcomMemberPositionDBI::selectMemberPositionListBySchool($school_id, $position_list);
        if ($controller->isError($assign_able_list)) {
            $assign_able_list->setPos(__FILE__, __LINE__);
            return $assign_able_list;
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("assign_able_list", $assign_able_list);
        $request->setAttribute("position_list", BroadcomMemberEntity::getPositionList());
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

    private function _doAssignExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("assign_member_id")) {
            $assign_member_id = $request->getParameter("assign_member_id");
            $update_data = array(
                "assign_member_id" => $assign_member_id,
                "assign_date" => date("Y-m-d H:i:s")
            );
            $student_id = $request->getAttribute("student_id");
            $order_list = BroadcomOrderDBI::selectOrderInfoByStudentId($student_id);
            if ($controller->isError($order_list)) {
                $order_list->setPos(__FILE__, __LINE__);
                return $order_list;
            }
            $order_id_list = array_keys($order_list);
            $order_item_list = BroadcomOrderDBI::selectOrderItemByStudent($student_id);
            if ($controller->isError($order_item_list)) {
                $order_item_list->setPos(__FILE__, __LINE__);
                return $order_item_list;
            }
            $order_item_id_list = array_keys($order_item_list);
            $course_list = BroadcomCourseInfoDBI::selectCourseInfoByStudent($student_id);
            if ($controller->isError($course_list)) {
                $course_list->setPos(__FILE__, __LINE__);
                return $course_list;
            }
            $course_id_list = array_keys($course_list);
            $dbi = Database::getInstance();
            $begin_res = $dbi->begin();
            if ($controller->isError($begin_res)) {
                $begin_res->setPos(__FILE__, __LINE__);
                return $begin_res;
            }
            $update_res = BroadcomStudentInfoDBI::updateStudentInfo($update_data, $student_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $update_res;
            }
            if (!empty($order_id_list)) {
                foreach ($order_id_list as $order_id) {
                    $update_res = BroadcomOrderDBI::updateOrder($update_data, $order_id);
                    if ($controller->isError($update_res)) {
                        $update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $update_res;
                    }
                }
            }
            if (!empty($order_item_id_list)) {
                foreach ($order_item_id_list as $order_item_id) {
                    $update_res = BroadcomOrderDBI::updateOrderItem($update_data, $order_item_id);
                    if ($controller->isError($update_res)) {
                        $update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $update_res;
                    }
                }
            }
            if (!empty($course_id_list)) {
                foreach ($course_id_list as $course_id) {
                    $update_res = BroadcomCourseInfoDBI::updateCourseInfo($update_data, $course_id);
                    if ($controller->isError($update_res)) {
                        $update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $update_res;
                    }
                }
            }
            $commit_res = $dbi->commit();
            if ($controller->isError($commit_res)) {
                $commit_res->setPos(__FILE__, __LINE__);
                return $commit_res;
            }
        }
        $controller->redirect("./?menu=education&act=my_student_list");
        return VIEW_DONE;
    }
}
?>