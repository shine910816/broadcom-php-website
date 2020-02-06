<?php
require_once SRC_PATH . "/menu/Member/lib/BroadcomMemberActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-03
 */
class BroadcomMember_PasswordAction extends BroadcomMemberActionBase
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
        if ($request->hasParameter("do_change")) {
            $member_id = $user->getMemberId();
            $getting_password_info = $request->getParameter("password");
            $login_info = BroadcomMemberLoginDBI::selectMemberLogin($member_id);
            if ($controller->isError($login_info)) {
                $login_info->setPos(__FILE__, __LINE__);
                return $login_info;
            }
            $salt_info = Utility::transSalt($login_info["member_login_salt"]);
            if (md5($salt_info["salt1"] . $getting_password_info["old"] . $salt_info["salt2"]) != $login_info["member_login_password"]) {
                $request->setError("old", "旧密码不正确");
            }
            if (!Validate::checkAlphaNumber($getting_password_info["new"])) {
                $request->setError("new", "密码仅包含大小写英文字母及数字");
            }
            $password_length_option = array(
                "min_length" => 6,
                "max_length" => 20
            );
            if (!Validate::checkLength($getting_password_info["new"], $password_length_option)) {
                $request->setError("new", "密码长度不小于6不大于20");
            }
            if ($getting_password_info["new"] != $getting_password_info["cnf"]) {
                $request->setError("cnf", "密码不一致");
            }
            $request->setAttribute("member_id", $member_id);
            $request->setAttribute("new_password", $getting_password_info["new"]);
        }
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
        $new_password = $request->getAttribute("new_password");
        $salt_info = Utility::transSalt();
        $update_data = array(
            "member_login_password" => md5($salt_info["salt1"] . $new_password . $salt_info["salt2"]),
            "member_login_salt" => $salt_info["code"]
        );
        $update_res = BroadcomMemberLoginDBI::updateMemberLogin($update_data, $member_id);
        if ($controller->isError($update_res)) {
            $update_res->setPos(__FILE__, __LINE__);
            return $update_res;
        }
        $controller->redirect("?menu=member&act=login&do_logout=2");
        return VIEW_DONE;
    }

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>