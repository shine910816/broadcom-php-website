<?php
require_once SRC_PATH . "/menu/Home/lib/BroadcomHomeActionBase.php";

/**
 * 主画面
 * @author Kinsama
 * @version 2020-02-01
 */
class BroadcomHome_TopAction extends BroadcomHomeActionBase
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
        $stats_data = $this->_getStatistics($controller, $user, $request);
        if ($controller->isError($stats_data)) {
            $stats_data->setPos(__FILE__, __LINE__);
            return $stats_data;
        }
        $request->setAttributes($stats_data);
        return VIEW_DONE;
    }
}
?>