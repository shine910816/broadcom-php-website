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
        $request->setAttribute("educated_list", BroadcomMemberEntity::getEducatedList());
        $request->setAttribute("educated_type_list", BroadcomMemberEntity::getEducatedTypeList());
        $request->setAttribute("married_type_list", BroadcomMemberEntity::getMarriedTypeList());
        $request->setAttribute("contact_relationship_list", BroadcomMemberEntity::getContactRelationshipList());
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
        if ($request->hasParameter("member_id")) {
            $edit_mode = true;
        } else {
            $login_info = array();
            $login_info["member_login_name"] = "";
            $login_info["member_position_level"] = BroadcomMemberEntity::POSITION_LEVEL_TEACHER;
            $login_info["member_employed_status"] = "1";
        }
        $request->setAttribute("edit_mode", $edit_mode);
        $request->setAttribute("member_info", $member_info);
        return VIEW_DONE;
    }

    private function _doSubmitValidate(Controller $controller, User $user, Request $request)
    {
        $getting_member_info = $request->getParameter("member_info");
Utility::testVariable($getting_member_info);
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

    private function _doSubmitExecute(Controller $controller, User $user, Request $request)
    {
        //$member_id = $request->getAttribute("member_id");
        //$update_data = $request->getAttribute("update_data");
        //if (!empty($update_data)) {
        //    $update_res = BroadcomMemberInfoDBI::updateMemberInfo($update_data, $member_id);
        //    if ($controller->isError($update_res)) {
        //        $update_res->setPos(__FILE__, __LINE__);
        //        return $update_res;
        //    }
        //}
        //$controller->redirect("?menu=member&act=top");
        return VIEW_NONE;
    }

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_NONE;
    }
}
?>