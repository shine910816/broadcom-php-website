<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员信息编辑画面
 * @author Kinsama
 * @version 2020-08-13
 */
class BroadcomEducation_StudentEditAction extends BroadcomEducationActionBase
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
        } elseif ($request->hasParameter("do_submit")) {
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
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: student_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $request->getParameter("student_id");
        $post_data = array(
            "student_id" => $student_id
        );
        $repond_student_info = Utility::getJsonResponse("?t=D2EC2D87-7195-6707-EF12-E55DB18ABF7C&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_student_info)) {
            $repond_student_info->setPos(__FILE__, __LINE__);
            return $repond_student_info;
        }
        $base_student_info = $repond_student_info["student_info"];
        $grade_list = BroadcomStudentEntity::getGradeList();
        $media_channel_list = BroadcomStudentEntity::getMediaChannelList();
        $purpose_level_list = BroadcomStudentEntity::getPurposeLevelList();
        $relatives_type_list = BroadcomStudentEntity::getRelativesTypeList();
        $follow_status_list = BroadcomStudentEntity::getFollowStatusList();
        $column_name_list = BroadcomStudentEntity::getColumnNames();
        $student_update_data = array();
        $student_update_history_data = array();
        $student_info = $base_student_info;
        if ($request->hasParameter("do_submit")) {
            $student_info = $request->getParameter("student_info");
            foreach ($student_info as $student_key => $student_value) {
                if ($student_value != $base_student_info[$student_key]) {
                    switch ($student_key) {
                        case "student_name":
                            if (!Validate::checkFullNotNull($student_value)) {
                                $request->setError("student_name", "学员姓名不能为空");
                            } else {
                                $student_update_data["student_name"] = $student_value;
                                $student_update_history_data[] = array(
                                    "name" => $column_name_list["student_name"],
                                    "old" => $base_student_info[$student_key],
                                    "new" => $student_value
                                );
                            }
                            break;
                        case "student_gender":
                            $student_update_data["student_gender"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["student_gender"],
                                "old" => $base_student_info[$student_key] ? "男" : "女",
                                "new" => $student_value ? "男" : "女"
                            );
                            break;
                        case "student_entrance_year":
                            $student_update_data["student_entrance_year"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => "年级",
                                "old" => BroadcomStudentEntity::getGradeName($base_student_info[$student_key]),
                                "new" => BroadcomStudentEntity::getGradeName($student_value)
                            );
                            break;
                        case "student_mobile_number":
                            if (!Validate::checkNotNull($student_value) || !Validate::checkMobileNumber($student_value)) {
                                $request->setError("student_mobile_number", "手机号格式不正确");
                            } else {
                                $check_res = BroadcomStudentInfoDBI::selectStudentMobileNumber($student_value);
                                if ($controller->isError($check_res)) {
                                    $check_res->setPos(__FILE__, __LINE__);
                                    return $check_res;
                                }
                                if ($check_res > 0) {
                                    $request->setError("student_mobile_number", "手机号已被注册");
                                } else {
                                    $student_update_data["student_mobile_number"] = $student_value;
                                    $student_update_history_data[] = array(
                                        "name" => $column_name_list["student_mobile_number"],
                                        "old" => Utility::coverMobileNumber($base_student_info[$student_key]),
                                        "new" => Utility::coverMobileNumber($student_value)
                                    );
                                }
                            }
                            break;
                        case "media_channel_code":
                            $student_update_data["media_channel_code"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["media_channel_code"],
                                "old" => $media_channel_list[$base_student_info[$student_key]],
                                "new" => $media_channel_list[$student_value]
                            );
                            break;
                        case "student_school_name":
                            if (!Validate::checkFullNotNull($student_value)) {
                                $request->setError("student_school_name", "在读学校不能为空");
                            } else {
                                $student_update_data["student_school_name"] = $student_value;
                                $student_update_history_data[] = array(
                                    "name" => $column_name_list["student_school_name"],
                                    "old" => $base_student_info[$student_key],
                                    "new" => $student_value
                                );
                            }
                            break;
                        case "student_address":
                            $student_update_data["student_address"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["student_address"],
                                "old" => $base_student_info[$student_key],
                                "new" => $student_value
                            );
                            break;
                        case "purpose_level":
                            $student_update_data["purpose_level"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["purpose_level"],
                                "old" => $purpose_level_list[$base_student_info[$student_key]],
                                "new" => $purpose_level_list[$student_value]
                            );
                            break;
                        case "student_relatives_type":
                            $student_update_data["student_relatives_type"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["student_relatives_type"],
                                "old" => $relatives_type_list[$base_student_info[$student_key]],
                                "new" => $relatives_type_list[$student_value]
                            );
                            break;
                        case "student_relatives_name":
                            $student_update_data["student_relatives_name"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["student_relatives_name"],
                                "old" => $base_student_info[$student_key],
                                "new" => $student_value
                            );
                            break;
                        case "student_relatives_mobile_number":
                            $student_update_data["student_relatives_mobile_number"] = $student_value;
                            $student_update_history_data[] = array(
                                "name" => $column_name_list["student_relatives_mobile_number"],
                                "old" => $base_student_info[$student_key],
                                "new" => $student_value
                            );
                            break;
                    }
                }
            }
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("base_student_info", $base_student_info);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("student_update_data", $student_update_data);
        $request->setAttribute("student_update_history_data", $student_update_history_data);
        $request->setAttribute("grade_list", $grade_list);
        $request->setAttribute("media_channel_list", $media_channel_list);
        $request->setAttribute("purpose_level_list", $purpose_level_list);
        $request->setAttribute("relatives_type_list", $relatives_type_list);
        $request->setAttribute("follow_status_list", $follow_status_list);
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

    private function _doSubmitExecute(Controller $controller, User $user, Request $request)
    {
        $student_id = $request->getAttribute("student_id");
        $student_update_data = $request->getAttribute("student_update_data");
        $student_update_history_data = $request->getAttribute("student_update_history_data");
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $update_res = BroadcomStudentInfoDBI::updateStudentInfo($student_update_data, $student_id);
        if ($controller->isError($update_res)) {
            $update_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $update_res;
        }
        $history_res = BroadcomHistoryDBI::insertStudentHistory($student_id, $student_update_history_data);
        if ($controller->isError($history_res)) {
            $history_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $history_res;
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=education&act=student_info&student_id=" . $student_id);
        return VIEW_DONE;
    }
}
?>