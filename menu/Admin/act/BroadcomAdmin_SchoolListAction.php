<?php
require_once SRC_PATH . "/menu/Admin/lib/BroadcomAdminActionBase.php";

/**
 * 校区管理画面
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomAdmin_SchoolListAction extends BroadcomAdminActionBase
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
        $school_info_list = BroadcomSchoolInfoDBI::selectSchoolInfoList();
        if ($controller->isError($school_info_list)) {
            $school_info_list->setPos(__FILE__, __LINE__);
            return $school_info_list;
        }
        $request->setAttribute("school_info_list", $school_info_list);
        return VIEW_DONE;
    }
}
?>