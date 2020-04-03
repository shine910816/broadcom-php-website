<?php

/**
 * 独立模型基类
 * @author Kinsama
 * @version 2020-03-24
 */
class InterfaceBase
{

    /**
     * 主执行
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_NONE;
    }

    /**
     * 主检查
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doMainValidate(Controller $controller, User $user, Request $request)
    {
        return VIEW_NONE;
    }

    /**
     * 权限检证
     *
     * @param object $controller Controller对象
     * @param object $user User对象
     * @param object $request Request对象
     */
    public function doAuthValidate(Controller $controller, User $user, Request $request)
    {
        return VIEW_NONE;
    }
}
?>