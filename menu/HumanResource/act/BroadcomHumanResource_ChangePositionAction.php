<?php
require_once SRC_PATH . "/menu/HumanResource/lib/BroadcomHumanResourceActionBase.php";

/**
 * 岗位变动画面
 * @author Kinsama
 * @version 2020-08-08
 */
class BroadcomHumanResource_ChangePositionAction extends BroadcomHumanResourceActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_submit")) {
            $ret = $this->_doSubmitExecute($controller, $user, $request);
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
        if (!$request->hasParameter("member_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $member_id = $request->getParameter("member_id");
        $school_id = $user->member()->schoolId();
        $menber_list = BroadcomMemberInfoDBI::selectMemberList($school_id);
        if ($controller->isError($menber_list)) {
            $menber_list->setPos(__FILE__, __LINE__);
            return $menber_list;
        }
        if (!isset($menber_list[$member_id])) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $member_info = $menber_list[$member_id];
        $change_position_able_flg = true;
        $change_position_disable_msg = "";
        if ($user->member()->id() == $member_id) {
            $change_position_able_flg = false;
            $change_position_disable_msg = "成员无法对自己进行岗位异动或离职操作。";
        }
        if ($change_position_able_flg) {
            $member_position = $member_info["member_position"];
            $main_position_option = array(
                BroadcomMemberEntity::POSITION_HEADMASTER,        // 校长
                BroadcomMemberEntity::POSITION_ADVISER,           // 课程顾问
                BroadcomMemberEntity::POSITION_MARKETING,         // 市场专员
                BroadcomMemberEntity::POSITION_ASSISTANT          // 学管
            );
            $teacher_position_option = array(
                BroadcomMemberEntity::POSITION_TEACH_MANAGER,     // 教学主管
                BroadcomMemberEntity::POSITION_TEACHER,           // 教师
                BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER // 兼职教师
            );
            if (in_array($member_position, $main_position_option)) {
                $order_check_res = BroadcomOrderDBI::selectOrderInfoForPositionChange($school_id, $member_id);
                if ($controller->isError($order_check_res)) {
                    $order_check_res->setPos(__FILE__, __LINE__);
                    return $order_check_res;
                }
                if ($order_check_res === false || $order_check_res > 0) {
                    $change_position_able_flg = false;
                    $change_position_disable_msg = "该名成员仍持有需要负责的学员/订单/合同，全部分配至其他成员才能完成岗位异动或离职操作。";
                }
            } elseif (in_array($member_position, $teacher_position_option)) {
                $course_check_res = BroadcomCourseInfoDBI::selectCourseInfoForPositionChange($school_id, $member_id);
                if ($controller->isError($course_check_res)) {
                    $course_check_res->setPos(__FILE__, __LINE__);
                    return $course_check_res;
                }
                if ($course_check_res === false || $course_check_res > 0) {
                    $change_position_able_flg = false;
                    $change_position_disable_msg = "该名教师仍持有需要未消除的课程安排，全部消课或删除后才能完成岗位异动或离职操作。";
                }
            }
        }
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("member_info", $member_info);
        $request->setAttribute("change_position_able_flg", $change_position_able_flg);
        $request->setAttribute("change_position_disable_msg", $change_position_disable_msg);
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
        $school_list = BroadcomSchoolInfoDBI::selectSchoolInfoList();
        if ($controller->isError($school_list)) {
            $school_list->setPos(__FILE__, __LINE__);
            return $school_list;
        }
        $request->setAttribute("school_list", $school_list);
        $request->setAttribute("position_list", BroadcomMemberEntity::getPositionList());
        $request->setAttribute("position_level_list", BroadcomMemberEntity::getPositionLevelList());
        $request->setAttribute("employed_status_list", BroadcomMemberEntity::getEmployedStatusList());
        return VIEW_DONE;
    }

    private function _doSubmitExecute(Controller $controller, User $user, Request $request)
    {
        $member_id = $request->getAttribute("member_id");
        $change_position_able_flg = $request->getAttribute("change_position_able_flg");
        if (!$change_position_able_flg) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $update_data = $request->getParameter("m");
        $update_res = BroadcomMemberPositionDBI::updateMemberPosition($update_data, $member_id);
        if ($controller->isError($update_res)) {
            $update_res->setPos(__FILE__, __LINE__);
            return $update_res;
        }
        $controller->redirect("?menu=human_resource&act=member_list");
    }
}
?>