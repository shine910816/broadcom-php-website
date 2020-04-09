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
        if ($request->hasParameter("admin_mode")) {
            $ret = $this->_doAdminExecute($controller, $user, $request);
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
        $member_id = $user->member()->id();
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
        $request->setAttribute("educated_list", BroadcomMemberEntity::getEducatedList());
        $request->setAttribute("educated_type_list", BroadcomMemberEntity::getEducatedTypeList());
        $request->setAttribute("married_type_list", BroadcomMemberEntity::getMarriedTypeList());
        $request->setAttribute("contact_relationship_list", BroadcomMemberEntity::getContactRelationshipList());
        return VIEW_DONE;
    }

    private function _doAdminExecute(Controller $controller, User $user, Request $request)
    {
        $controller->redirect("?menu=admin&act=top");
        return VIEW_DONE;
    }
}
?>