<?php

/**
 * 排课信息列表画面
 * @author Kinsama
 * @version 2020-04-20
 */
class BroadcomCourse_MultiListAction extends ActionBase
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
        // 必要参数检证
        if (!$request->hasParameter("school_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: school_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if (!$request->hasParameter("start_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: start_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if (!$request->hasParameter("end_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: end_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }

        $request->setAttribute("insert_data", $insert_data);
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
        $insert_data = $request->getAttribute("insert_data");
        return $insert_data;
    }
}
?>