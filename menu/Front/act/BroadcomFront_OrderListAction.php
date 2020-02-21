<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 前台业务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_OrderListAction extends BroadcomFrontActionBase
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
        $order_status_list = BroadcomOrderEntity::getOrderStatusList();
        $order_status = BroadcomOrderEntity::ORDER_STATUS_2;
        if ($request->hasParameter("order_status")) {
            $order_status = $request->getParameter("order_status");
            if (!Validate::checkAcceptParam($order_status, array_keys($order_status_list))) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        }
        $order_list = BroadcomOrderDBI::selectOrderListByStatus($order_status);
        if ($controller->isError($order_list)) {
            $order_list->setPos(__FILE__, __LINE__);
            return $order_list;
        }
        $request->setAttribute("order_status", $order_status);
        $request->setAttribute("order_status_list", $order_status_list);
        $request->setAttribute("order_list", $order_list);
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
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($user->getMemberId());
        if ($controller->isError($position_info)) {
            $position_info->setPos(__FILE__, __LINE__);
            return $position_info;
        }
        if (empty($position_info)) {
            $err = $controller->raiseError();
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $position_info["school_id"];
        $student_info_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
        if ($controller->isError($student_info_list)) {
            $student_info_list->setPos(__FILE__, __LINE__);
            return $student_info_list;
        }
        foreach ($student_info_list as $student_id => $student_info) {
            $student_info_list[$student_id]["grade_name"] = BroadcomStudentEntity::getGradeName($student_info["student_entrance_year"]);
        }
        $back_link = Utility::encodeBackLink("front", "order_list", array(
            "order_status" => $request->getAttribute("order_status"),
            "page" => $request->current_page
        ));
        $request->setAttribute("student_info_list", $student_info_list);
        $request->setAttribute("back_link", $back_link);
        return VIEW_DONE;
    }
}
?>