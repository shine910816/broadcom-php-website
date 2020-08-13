<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员信息编辑画面
 * @author Kinsama
 * @version 2020-08-13
 */
class BroadcomEducation_StudentEditAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_submit")) {
            $ret = $this->_doSubmitExecute($controller, $user, $request);
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
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: student_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $request->getParameter("student_id");
        $post_data = array(
            "student_id" => $student_id
        );
        $repond_student_info = Utility::getJsonResponse("?t=D2EC2D87-7195-6707-EF12-E55DB18ABF7C&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_student_info)) {
            $repond_student_info->setPos(__FILE__, __LINE__);
            return $repond_student_info;
        }
        $base_student_info = $repond_student_info["student_info"];
        $student_update_data = array();
        $student_update_history_data = array();
        $student_info = $base_student_info;
        if ($request->hasParameter("do_submit")) {
            $student_info = $request->getParameter("student_info");
            foreach ($student_info as $student_key => $student_value) {
                if ($student_value != $base_student_info[$student_key]) {
                    
                }
            }
        }
Utility::testVariable($base_student_info);
        $request->setAttribute("base_student_info", $base_student_info);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("student_update_data", $student_update_data);
        $request->setAttribute("student_update_history_data", $student_update_history_data);
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

    private function _doErrorExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }

    private function _doSubmitExecute(Controller $controller, User $user, Request $request)
    {
        return VIEW_DONE;
    }
}
?>