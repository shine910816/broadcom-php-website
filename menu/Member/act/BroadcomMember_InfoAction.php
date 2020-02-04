<?php
require_once SRC_PATH . "/menu/Member/lib/BroadcomMemberActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-03
 */
class BroadcomMember_InfoAction extends BroadcomMemberActionBase
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
        } elseif ($request->hasParameter("do_change")) {
            $ret = $this->_doChangeExecute($controller, $user, $request);
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
        $member_id = $user->getMemberId();
        $member_info = BroadcomMemberInfoDBI::selectMemberInfo($member_id);
        if ($controller->isError($member_info)) {
            $member_info->setPos(__FILE__, __LINE__);
            return $member_info;
        }
        if (empty($member_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $educated_list = BroadcomMemberEntity::getEducatedList();
        $educated_type_list = BroadcomMemberEntity::getEducatedTypeList();
        $married_type_list = BroadcomMemberEntity::getMarriedTypeList();
        $contact_relationship_list = BroadcomMemberEntity::getContactRelationshipList();
        $update_data = array();
        if ($request->hasParameter("do_change")) {
            $getting_member_info = $request->getParameter("member_info");
            if ($getting_member_info["m_name"] != $member_info["m_name"]) {
                $update_data["m_name"] = $getting_member_info["m_name"];
            }
            if ($getting_member_info["m_birthday"] != $member_info["m_birthday"]) {
                $update_data["m_birthday"] = $getting_member_info["m_birthday"];
            }
            if ($getting_member_info["m_gender"] != $member_info["m_gender"]) {
                $update_data["m_gender"] = $getting_member_info["m_gender"];
            }
            if ($getting_member_info["m_id_code"] != $member_info["m_id_code"]) {
                $update_data["m_id_code"] = $getting_member_info["m_id_code"];
            }
            if ($getting_member_info["m_mobile_number"] != $member_info["m_mobile_number"]) {
                $update_data["m_mobile_number"] = $getting_member_info["m_mobile_number"];
            }
            if ($getting_member_info["m_mail_address"] != $member_info["m_mail_address"]) {
                $update_data["m_mail_address"] = $getting_member_info["m_mail_address"];
            }
            if ($getting_member_info["m_married_type"] != $member_info["m_married_type"]) {
                $update_data["m_married_type"] = $getting_member_info["m_married_type"];
            }
            if ($getting_member_info["m_address"] != $member_info["m_address"]) {
                $update_data["m_address"] = $getting_member_info["m_address"];
            }
            if ($getting_member_info["m_college"] != $member_info["m_college"]) {
                $update_data["m_college"] = $getting_member_info["m_college"];
            }
            if ($getting_member_info["m_major"] != $member_info["m_major"]) {
                $update_data["m_major"] = $getting_member_info["m_major"];
            }
            if ($getting_member_info["m_educated"] != $member_info["m_educated"]) {
                $update_data["m_educated"] = $getting_member_info["m_educated"];
            }
            if ($getting_member_info["m_college_start_date"] != $member_info["m_college_start_date"]) {
                $update_data["m_college_start_date"] = $getting_member_info["m_college_start_date"];
            }
            if ($getting_member_info["m_college_end_date"] != $member_info["m_college_end_date"]) {
                $update_data["m_college_end_date"] = $getting_member_info["m_college_end_date"];
            }
            if ($getting_member_info["m_educated_type"] != $member_info["m_educated_type"]) {
                $update_data["m_educated_type"] = $getting_member_info["m_educated_type"];
            }
            if ($getting_member_info["m_contact_name"] != $member_info["m_contact_name"]) {
                $update_data["m_contact_name"] = $getting_member_info["m_contact_name"];
            }
            if ($getting_member_info["m_contact_relationship"] != $member_info["m_contact_relationship"]) {
                $update_data["m_contact_relationship"] = $getting_member_info["m_contact_relationship"];
            }
            if ($getting_member_info["m_contact_mobile_number"] != $member_info["m_contact_mobile_number"]) {
                $update_data["m_contact_mobile_number"] = $getting_member_info["m_contact_mobile_number"];
            }
        }
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("member_info", $member_info);
        $request->setAttribute("update_data", $update_data);
        $request->setAttribute("educated_list", $educated_list);
        $request->setAttribute("educated_type_list", $educated_type_list);
        $request->setAttribute("married_type_list", $married_type_list);
        $request->setAttribute("contact_relationship_list", $contact_relationship_list);
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

    private function _doChangeExecute(Controller $controller, User $user, Request $request)
    {
        $member_id = $request->getAttribute("member_id");
        $update_data = $request->getAttribute("update_data");
        if (!empty($update_data)) {
            $update_res = BroadcomMemberInfoDBI::updateMemberInfo($update_data, $member_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                return $update_res;
            }
        }
        $controller->redirect("?menu=member&act=top");
        return VIEW_DONE;
    }

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>