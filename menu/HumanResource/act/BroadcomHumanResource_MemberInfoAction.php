<?php
require_once SRC_PATH . "/menu/HumanResource/lib/BroadcomHumanResourceActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-02
 */
class BroadcomHumanResource_MemberInfoAction extends BroadcomHumanResourceActionBase
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
        $school_list = BroadcomSchoolInfoDBI::selectSchoolInfoList();
        if ($controller->isError($school_list)) {
            $school_list->setPos(__FILE__, __LINE__);
            return $school_list;
        }
        $request->setAttribute("school_list", $school_list);
        $request->setAttribute("position_list", BroadcomMemberEntity::getPositionList());
        $request->setAttribute("position_level_list", BroadcomMemberEntity::getPositionLevelList());
        $request->setAttribute("educated_list", BroadcomMemberEntity::getEducatedList());
        $request->setAttribute("educated_type_list", BroadcomMemberEntity::getEducatedTypeList());
        $request->setAttribute("married_type_list", BroadcomMemberEntity::getMarriedTypeList());
        $request->setAttribute("contact_relationship_list", BroadcomMemberEntity::getContactRelationshipList());
        $request->setAttribute("employed_status_list", BroadcomMemberEntity::getEmployedStatusList());
        $request->setAttribute("star_level_list", BroadcomMemberEntity::getStarLevelList());
        if ($request->hasParameter("do_submit")) {
            $ret = $this->_doSubmitValidate($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultValidate($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        }
        return VIEW_DONE;
    }

    private function _doDefaultValidate(Controller $controller, User $user, Request $request)
    {
        $edit_mode = false;
        $member_info = array();
        $member_info["m_name"] = "";
        $member_info["m_birthday"] = "1990-01-01";
        $member_info["m_gender"] = "1";
        $member_info["m_id_code"] = "";
        $member_info["m_mobile_number"] = "";
        $member_info["m_mail_address"] = "";
        $member_info["m_married_type"] = "1";
        $member_info["m_address"] = "";
        $member_info["m_college"] = "";
        $member_info["m_major"] = "";
        $member_info["m_educated"] = "3";
        $member_info["m_college_start_date"] = "2009-09-01";
        $member_info["m_college_end_date"] = "2013-06-30";
        $member_info["m_educated_type"] = "1";
        $member_info["m_contact_name"] = "";
        $member_info["m_contact_relationship"] = "6";
        $member_info["m_contact_mobile_number"] = "";
        $member_info["m_licence_number"] = "";
        $member_info["m_primary_star_level"] = BroadcomMemberEntity::STAR_LEVEL_0;
        $member_info["m_junior_star_level"] = BroadcomMemberEntity::STAR_LEVEL_0;
        $member_info["m_senior_star_level"] = BroadcomMemberEntity::STAR_LEVEL_0;
        if ($request->hasParameter("member_id")) {
            $edit_mode = true;
            $member_id = $request->getParameter("member_id");
            $member_info = BroadcomMemberInfoDBI::selectMemberInfo($member_id);
            if ($controller->isError($member_info)) {
                $member_info->setPos(__FILE__, __LINE__);
                return $member_info;
            }
            $request->setAttribute("member_id", $member_id);
        } else {
            $request->setAttribute("member_login_name", "");
            $request->setAttribute("school_id", "");
            $request->setAttribute("member_employed_status", BroadcomMemberEntity::EMPLOYED_STATUS_1);
            $request->setAttribute("member_position", BroadcomMemberEntity::POSITION_TEACHER);
            $request->setAttribute("member_position_level", BroadcomMemberEntity::POSITION_LEVEL_0);
        }
        $request->setAttribute("edit_mode", $edit_mode);
        $request->setAttribute("member_info", $member_info);
        return VIEW_DONE;
    }

    private function _doSubmitValidate(Controller $controller, User $user, Request $request)
    {
        $edit_mode = false;
        $getting_member_info = $request->getParameter("member_info");
        $getting_login_info = array();
        $member_info = array();
        if ($request->hasParameter("member_id")) {
            $edit_mode = true;
            $member_id = $request->getParameter("member_id");
            $member_info = BroadcomMemberInfoDBI::selectMemberInfo($member_id);
            if ($controller->isError($member_info)) {
                $member_info->setPos(__FILE__, __LINE__);
                return $member_info;
            }
            $request->setAttribute("member_id", $member_id);
        } else {
            $member_login_name = $request->getParameter("member_login_name");
            $school_id = $request->getParameter("school_id");
            $member_position = $request->getParameter("member_position");
            $member_employed_status = $request->getParameter("member_employed_status");
            $member_position_level = $request->getParameter("member_position_level");
            if (!Validate::checkNotNull($member_login_name)) {
                $request->setError("member_login_name", "登录名不能为空");
            } else {
                $login_info = BroadcomMemberLoginDBI::selectMemberLoginByName($member_login_name);
                if ($controller->isError($login_info)) {
                    $login_info->setPos(__FILE__, __LINE__);
                    return $login_info;
                }
                if (!empty($login_info)) {
                    $request->setError("member_login_name", "登录名已经被注册");
                }
            }
            $request->setAttribute("member_login_name", $member_login_name);
            $request->setAttribute("school_id", $school_id);
            $request->setAttribute("member_position", $member_position);
            $request->setAttribute("member_employed_status", $member_employed_status);
            $request->setAttribute("member_position_level", $member_position_level);
        }
        $content_data = array();
        if (!$edit_mode || ($edit_mode && $getting_member_info["m_name"] != $member_info["m_name"])) {
            $content_data["m_name"] = $getting_member_info["m_name"];
        }
        if (!$edit_mode || ($getting_member_info["m_birthday"] != $member_info["m_birthday"])) {
            $content_data["m_birthday"] = $getting_member_info["m_birthday"];
        }
        if (!$edit_mode || ($getting_member_info["m_gender"] != $member_info["m_gender"])) {
            $content_data["m_gender"] = $getting_member_info["m_gender"];
        }
        if (!$edit_mode || ($getting_member_info["m_id_code"] != $member_info["m_id_code"])) {
            $content_data["m_id_code"] = $getting_member_info["m_id_code"];
        }
        if (!$edit_mode || ($getting_member_info["m_mobile_number"] != $member_info["m_mobile_number"])) {
            if (!Validate::checkNotNull($getting_member_info["m_mobile_number"]) || !Validate::checkMobileNumber($getting_member_info["m_mobile_number"])) {
                $request->setError("m_mobile_number", "手机号格式不正确");
            } else {
                $content_data["m_mobile_number"] = $getting_member_info["m_mobile_number"];
            }
        }
        if (!$edit_mode || ($getting_member_info["m_mail_address"] != $member_info["m_mail_address"])) {
            if (!Validate::checkMailAddress($getting_member_info["m_mail_address"])) {
                $request->setError("m_mail_address", "邮箱地址格式不正确");
            } else {
                $content_data["m_mail_address"] = $getting_member_info["m_mail_address"];
            }
        }
        if (!$edit_mode || ($getting_member_info["m_married_type"] != $member_info["m_married_type"])) {
            $content_data["m_married_type"] = $getting_member_info["m_married_type"];
        }
        if (!$edit_mode || ($getting_member_info["m_address"] != $member_info["m_address"])) {
            $content_data["m_address"] = $getting_member_info["m_address"];
        }
        if (!$edit_mode || ($getting_member_info["m_college"] != $member_info["m_college"])) {
            $content_data["m_college"] = $getting_member_info["m_college"];
        }
        if (!$edit_mode || ($getting_member_info["m_major"] != $member_info["m_major"])) {
            $content_data["m_major"] = $getting_member_info["m_major"];
        }
        if (!$edit_mode || ($getting_member_info["m_educated"] != $member_info["m_educated"])) {
            $content_data["m_educated"] = $getting_member_info["m_educated"];
        }
        if (!$edit_mode || ($getting_member_info["m_college_start_date"] != $member_info["m_college_start_date"])) {
            $content_data["m_college_start_date"] = $getting_member_info["m_college_start_date"];
        }
        if (!$edit_mode || ($getting_member_info["m_college_end_date"] != $member_info["m_college_end_date"])) {
            $content_data["m_college_end_date"] = $getting_member_info["m_college_end_date"];
        }
        if (!$edit_mode || ($getting_member_info["m_educated_type"] != $member_info["m_educated_type"])) {
            $content_data["m_educated_type"] = $getting_member_info["m_educated_type"];
        }
        if (!$edit_mode || ($getting_member_info["m_contact_name"] != $member_info["m_contact_name"])) {
            $content_data["m_contact_name"] = $getting_member_info["m_contact_name"];
        }
        if (!$edit_mode || ($getting_member_info["m_contact_relationship"] != $member_info["m_contact_relationship"])) {
            $content_data["m_contact_relationship"] = $getting_member_info["m_contact_relationship"];
        }
        if (!$edit_mode || ($getting_member_info["m_contact_mobile_number"] != $member_info["m_contact_mobile_number"])) {
            $content_data["m_contact_mobile_number"] = $getting_member_info["m_contact_mobile_number"];
        }
        if (!$edit_mode) {
            $content_data["m_licence_number"] = $getting_member_info["m_licence_number"];
        }
        if (!$edit_mode) {
            $content_data["m_primary_star_level"] = $getting_member_info["m_primary_star_level"];
        }
        if (!$edit_mode) {
            $content_data["m_junior_star_level"] = $getting_member_info["m_junior_star_level"];
        }
        if (!$edit_mode) {
            $content_data["m_senior_star_level"] = $getting_member_info["m_senior_star_level"];
        }
        $request->setAttribute("edit_mode", $edit_mode);
        $request->setAttribute("member_info", $getting_member_info);
        $request->setAttribute("content_data", $content_data);
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
        $edit_mode = $request->getAttribute("edit_mode");
        if ($edit_mode) {
            $request->setAttribute("page_titles", "修改成员信息");
        } else {
            $request->setAttribute("page_titles", "添加新成员");
        }
        return VIEW_DONE;
    }

    private function _doSubmitExecute(Controller $controller, User $user, Request $request)
    {
        $edit_mode = $request->getAttribute("edit_mode");
        if ($edit_mode) {
            $member_id = $request->getAttribute("member_id");
            $update_data = $request->getAttribute("content_data");
            $update_res = BroadcomMemberInfoDBI::updateMemberInfo($update_data, $member_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                return $update_res;
            }
        } else {
            $member_login_name = $request->getAttribute("member_login_name");
            $school_id = $request->getAttribute("school_id");
            $member_position = $request->getAttribute("member_position");
            $member_position_level = $request->getAttribute("member_position_level");
            $info_insert_data = $request->getAttribute("content_data");
            $password_context = substr($info_insert_data["m_mobile_number"], -6, 6);
            $salt_arr = Utility::transSalt();
            $login_insert_data = array();
            $login_insert_data["member_login_name"] = $member_login_name;
            $login_insert_data["member_login_password"] = md5($salt_arr["salt1"] . $password_context . $salt_arr["salt2"]);
            $login_insert_data["member_login_salt"] = $salt_arr["code"];
            $login_insert_data["member_level"] = "1";
            $position_insert_data = array();
            $position_insert_data["school_id"] = $school_id;
            $position_insert_data["member_position"] = $member_position;
            $position_insert_data["member_position_level"] = $member_position_level;
            $position_insert_data["member_employed_status"] = "1";
            $dbi = Database::getInstance();
            $begin_res = $dbi->begin();
            if ($controller->isError($begin_res)) {
                $begin_res->setPos(__FILE__, __LINE__);
                return $begin_res;
            }
            $member_id = BroadcomMemberLoginDBI::insertMemberLogin($login_insert_data);
            if ($controller->isError($member_id)) {
                $member_id->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $member_id;
            }
            $position_insert_data["member_id"] = $member_id;
            $position_res = BroadcomMemberPositionDBI::insertMemberPosition($position_insert_data);
            if ($controller->isError($position_res)) {
                $position_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $position_res;
            }
            $info_insert_data["member_id"] = $member_id;
            $info_res = BroadcomMemberInfoDBI::insertMemberInfo($info_insert_data);
            if ($controller->isError($info_res)) {
                $info_res->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $info_res;
            }
            $commit_res = $dbi->commit();
            if ($controller->isError($commit_res)) {
                $commit_res->setPos(__FILE__, __LINE__);
                return $commit_res;
            }
        }
        $controller->redirect("?menu=human_resource&act=member_list");
        return VIEW_NONE;
    }

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        $edit_mode = $request->getAttribute("edit_mode");
        if ($edit_mode) {
            $member_info = $request->getAttribute("member_info");
            $request->setAttribute("page_titles", $member_info["m_name"]);
        } else {
            $request->setAttribute("page_titles", "添加新成员");
        }
        return VIEW_DONE;
    }
}
?>