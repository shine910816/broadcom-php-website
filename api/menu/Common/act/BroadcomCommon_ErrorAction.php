<?php

/**
 * 错误API画面
 * @author Kinsama
 * @version 2020-04-08
 */
class BroadcomCommon_ErrorAction extends ActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    /**
     * 执行参数检测
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainValidate(Controller $controller, User $user, Request $request)
    {
        $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Target failed: " . $_GET["t"]);
        $err->setPos(__FILE__, __LINE__);
        return $err;
    }
}
?>