<?php
require_once SRC_PATH . "/menu/HumanResource/lib/BroadcomHumanResourceActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-02
 */
class BroadcomHumanResource_ResetPasswordAction extends BroadcomHumanResourceActionBase
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
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $member_id = $request->getParameter("member_id");
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
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("member_info", $member_info);
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
        $member_info = $request->getAttribute("member_info");
        $member_id = $member_info["member_id"];
        $m_mobile_number = $member_info["m_mobile_number"];
        $password_context = substr($m_mobile_number, -6, 6);
        $salt_arr = Utility::transSalt();
        $update_data = array();
        $update_data["member_login_password"] = md5($salt_arr["salt1"] . $password_context . $salt_arr["salt2"]);
        $update_data["member_login_salt"] = $salt_arr["code"];
        $update_res = BroadcomMemberLoginDBI::updateMemberLogin($update_data, $member_id);
        if ($controller->isError($update_res)) {
            $update_res->setPos(__FILE__, __LINE__);
            return $update_res;
        }
        $controller->redirect("?menu=human_resource&act=member_list");
    }
}
?>