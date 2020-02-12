<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 新增意向客户画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_CreateLeadsAction extends BroadcomFrontActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_create")) {
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
        $student_info = array();
        $student_info["student_name"] = "";
        $student_info["student_mobile_number"] = "";
        $student_info["student_entrance_year"] = BroadcomStudentEntity::getAdjustedYear();
        $student_info["student_gender"] = "1";
        $student_info["student_school_name"] = "";
        $student_info["student_address"] = "";
        $student_info["student_relatives_type"] = BroadcomStudentEntity::RELATIVES_TYPE_1;
        $student_info["student_relatives_name"] = "";
        $student_info["student_relatives_mobile_number"] = "";
        $student_info["media_channel_code"] = BroadcomStudentEntity::MEDIA_CHANNEL_1_1;
        $student_info["purpose_level"] = BroadcomStudentEntity::PURPOSE_LEVEL_HIGH;
        if ($request->hasParameter("do_create")) {
            $student_info = $request->getParameter("student_info");
            if (!Validate::checkFullNotNull($student_info["student_name"])) {
                $request->setError("student_name", "学员姓名不能为空");
            }
            if (!Validate::checkNotNull($student_info["student_mobile_number"]) || !Validate::checkMobileNumber($student_info["student_mobile_number"])) {
                $request->setError("student_mobile_number", "手机号格式不正确");
            } else {
                $check_res = BroadcomStudentInfoDBI::selectStudentMobileNumber($student_info["student_mobile_number"]);
                if ($controller->isError($check_res)) {
                    $check_res->setPos(__FILE__, __LINE__);
                    return $check_res;
                }
                if ($check_res > 0) {
                    $request->setError("student_mobile_number", "手机号已被注册");
                }
            }
        }
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("grade_list", BroadcomStudentEntity::getGradeList());
        $request->setAttribute("media_channel_list", BroadcomStudentEntity::getMediaChannelList());
        $request->setAttribute("purpose_level_list", BroadcomStudentEntity::getPurposeLevelList());
        $request->setAttribute("relatives_type_list", BroadcomStudentEntity::getRelativesTypeList());
        $request->setAttribute("follow_status_list", BroadcomStudentEntity::getFollowStatusList());
        $request->setAttribute("adjust_year", BroadcomStudentEntity::getAdjustedYear() + 1);
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

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        $student_info = $request->getAttribute("student_info");
        $member_id = $user->getMemberId();
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($member_id);
        if ($controller->isError($position_info)) {
            $position_info->setPos(__FILE__, __LINE__);
            return $position_info;
        }
        if (empty($position_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $position_info["school_id"];
        $student_info["member_id"] = $member_id;
        $student_info["school_id"] = $school_id;
        $student_info["follow_status"] = BroadcomStudentEntity::FOLLOW_STATUS_1;
        $student_info["student_level"] = BroadcomStudentEntity::STUDENT_LEVEL_NONE;
        $student_info["audition_hours"] = "2";
        $student_info["accept_date"] = date("Y-m-d H:i:s");
        $insert_res = BroadcomStudentInfoDBI::insertSchoolInfo($student_info);
        if ($controller->isError($insert_res)) {
            $insert_res->setPos(__FILE__, __LINE__);
            return $insert_res;
        }
        $controller->redirect("./?menu=front&act=my_leads");
        return VIEW_DONE;
    }
}
?>