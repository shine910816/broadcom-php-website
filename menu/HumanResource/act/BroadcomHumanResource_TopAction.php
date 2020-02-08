<?php
require_once SRC_PATH . "/menu/HumanResource/lib/BroadcomHumanResourceActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-02-02
 */
class BroadcomHumanResource_TopAction extends BroadcomHumanResourceActionBase
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
        $request->setAttribute("position_list", BroadcomMemberEntity::getPositionList());
        $request->setAttribute("position_level_list", BroadcomMemberEntity::getPositionLevelList());
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
        $member_list = BroadcomMemberLoginDBI::selectMemberList();
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        $editable_flg = false;
        if ($user->checkPositionAble("human_resource", "member_info")) {
            $editable_flg = true;
        }
        $request->setAttribute("member_list", $member_list);
        $request->setAttribute("editable_flg", $editable_flg);
//Utility::testVariable($request->getAttributes());
        return VIEW_DONE;
    }
}
?>