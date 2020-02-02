<?php

/**
 * 成员登录画面
 * @author Kinsama
 * @version 2020-01-31
 */
class BroadcomMember_LoginAction extends ActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_logout")) {
            $ret = $this->_doLogoutExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_login")) {
            $ret = $this->_doLoginExecute($controller, $user, $request);
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
        $member_id = "0";
        $member_login_name = "";
        if ($request->hasParameter("do_login")) {
            $member_login_name = $request->getParameter("member_login_name");
            $member_login_password = $request->getParameter("member_login_password");
            $login_info = BroadcomMemberLoginDBI::selectMemberLoginByName($member_login_name);
            if ($controller->isError($login_info)) {
                $login_info->setPos(__FILE__, __LINE__);
                return $login_info;
            }
            if (empty($login_info)) {
                $request->setError("member_login_name", "用户名不存在");
            } else {
                $salt_arr = Utility::transSalt($login_info["member_login_salt"]);
                if ($login_info["member_login_password"] != md5($salt_arr["salt1"] . $member_login_password . $salt_arr["salt2"])) {
                    $request->setError("member_login_password", "密码不正确");
                } else {
                    $member_id = $login_info["member_id"];
                }
            }
        }
        $request->setAttribute("member_id", $member_id);
        $request->setAttribute("member_login_name", $member_login_name);
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
        if ($user->isLogin()) {
            $controller->redirect("../");
            return VIEW_NONE;
        }
        return VIEW_DONE;
    }

    /**
     * 执行登录命令
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     * @access private
     */
    private function _doLoginExecute(Controller $controller, User $user, Request $request)
    {
        $member_id = $request->getAttribute("member_id");
        $admin_lvl = "0";
        $login_info = BroadcomMemberLoginDBI::selectMemberLogin($member_id);
        if ($controller->isError($login_info)) {
            $login_info->setPos(__FILE__, __LINE__);
            return $login_info;
        }
        if (empty($login_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if ($login_info["member_level"] == "2") {
            $admin_lvl = "1";
        }
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
        $member_position = "";
        if ($login_info["member_position_level"] == "100") {
            $member_position = "管理员";
        } else {
            $position_level_list = BroadcomMemberEntity::getPositionLevelList();
            if (isset($position_level_list[$login_info["member_position_level"]])) {
                $member_position = $position_level_list[$login_info["member_position_level"]];
            }
        }
        $user->setVariable("member_id", $member_id);
        $user->setVariable("admin_lvl", $admin_lvl);
        $user->setVariable("member_name", $member_info["m_name"]);
        $user->setVariable("member_position", $member_position);
        $user->setVariable("member_position_level", $login_info["member_position_level"]);
        $redirect_url = "../";
        if ($user->hasVariable(REDIRECT_URL)) {
            $redirect_url .= $user->getVariable(REDIRECT_URL);
        }
        $controller->redirect($redirect_url);
        return VIEW_NONE;
    }

    /**
     * 执行登出命令
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     * @access private
     */
    private function _doLogoutExecute(Controller $controller, User $user, Request $request)
    {
        if (!$user->isLogin()) {
            $controller->redirect("../login/");
            return VIEW_NONE;
        }
        $user->setVariable("member_id", "0");
        $user->setVariable("admin_lvl", "0");
        $user->freeVariable("member_name");
        $user->freeVariable("member_position");
        $user->freeVariable("member_position_level");
        $controller->redirect("../login/");
        return VIEW_NONE;
    }

    /**
     * 执行登录错误命令
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     * @access private
     */
    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>