<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-29
 */
class BroadcomEducation_CourseConfirmAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_delete")) {
            $ret = $this->_doDeleteExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_confirm")) {
            $ret = $this->_doConfirmExecute($controller, $user, $request);
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
        $course_id = "";
        $multi_course_id = "";
        $multi_flg = false;
        if ($request->hasParameter("course_id")) {
            $course_id = $request->getParameter("course_id");
        } elseif ($request->hasParameter("multi_course_id")) {
            $multi_course_id = $request->getParameter("multi_course_id");
            $multi_flg = true;
        } else {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: course_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $post_data = array();
        if ($multi_flg) {
            $post_data["multi_course_id"] = $multi_course_id;
        } else {
            $post_data["course_id"] = $course_id;
        }
        $repond_course_info = Utility::getJsonResponse("?t=65118860-60BE-028D-5525-E40E18E58CAA&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_info)) {
            $repond_course_info->setPos(__FILE__, __LINE__);
            return $repond_course_info;
        }
        $request->setAttribute("course_id", $course_id);
        $request->setAttribute("multi_course_id", $multi_course_id);
        $request->setAttribute("multi_flg", $multi_flg);
        $request->setAttributes($repond_course_info);
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

    private function _doConfirmExecute(Controller $controller, User $user, Request $request)
    {
        $course_id = $request->getAttribute("course_id");
        $multi_course_id = $request->getAttribute("multi_course_id");
        $multi_flg = $request->getAttribute("multi_flg");
        $post_data = array();
        if ($multi_flg) {
            $post_data["multi_course_id"] = $multi_course_id;
        } else {
            $post_data["course_id"] = $course_id;
        }
        $post_data["confirm"] = "1";
        $repond_course_info = Utility::getJsonResponse("?t=65118860-60BE-028D-5525-E40E18E58CAA&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_info)) {
            $repond_course_info->setPos(__FILE__, __LINE__);
            return $repond_course_info;
        }
        $redirect_url = "./?menu=education&act=course_confirm";
        if ($multi_flg) {
            $redirect_url .= "&multi_course_id=" . $multi_course_id;
        } else {
            $redirect_url .= "&course_id=" . $course_id;
        }
        $controller->redirect($redirect_url);
        return VIEW_DONE;
    }

    private function _doDeleteExecute(Controller $controller, User $user, Request $request)
    {
        $course_id = $request->getAttribute("course_id");
        $multi_course_id = $request->getAttribute("multi_course_id");
        $multi_flg = $request->getAttribute("multi_flg");
        $post_data = array();
        if ($multi_flg) {
            $post_data["multi_course_id"] = $multi_course_id;
        } else {
            $post_data["course_id"] = $course_id;
        }
        $post_data["delete"] = "1";
        $repond_course_info = Utility::getJsonResponse("?t=65118860-60BE-028D-5525-E40E18E58CAA&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_info)) {
            $repond_course_info->setPos(__FILE__, __LINE__);
            return $repond_course_info;
        }
        $redirect_url = "./?menu=education&act=course_confirm";
        if ($multi_flg) {
            $redirect_url .= "&multi_course_id=" . $multi_course_id;
        } else {
            $redirect_url .= "&course_id=" . $course_id;
        }
        $controller->redirect($redirect_url);
        return VIEW_DONE;
    }
}
?>