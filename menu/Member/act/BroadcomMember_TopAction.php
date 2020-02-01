<?php
require_once SRC_PATH . "/menu/Member/lib/BroadcomMemberActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-02
 */
class BroadcomMember_TopAction extends BroadcomMemberActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        $ret = $this->_doDefaultExecute($controller, $user, $request);
        if ($controller->isError($ret)) {
            $ret->setPos(__FILE__, __LINE__);
            return $ret;
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
        $member_info = $request->getAttribute("member_info");
//Utility::testVariable($member_info);
        return VIEW_DONE;
    }
}
?>