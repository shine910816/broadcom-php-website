<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_CourseCreateAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_create")) {
            $ret = $this->_doCreateExecute($controller, $user, $request);
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
        $student_id = "";
        $order_item_info = array();
        $course_type = BroadcomCourseEntity::COURSE_TYPE_AUDITION;
        if ($request->hasParameter("order_item_id")) {
            $order_item_id = $request->getParameter("order_item_id");
            $order_item_info = BroadcomOrderDBI::selectOrderItem($order_item_id);
            if ($controller->isError($order_item_info)) {
                $order_item_info->setPos(__FILE__, __LINE__);
                return $order_item_info;
            }
            if (empty($order_item_info)) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $student_id = $order_item_info["student_id"];
Utility::testVariable($order_item_info);
        } else {
            if (!$request->hasParameter("student_id")) {
                $err = $controller->raiseError();
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            $student_id = $request->getParameter("student_id");
        }
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
        return VIEW_DONE;
    }

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>