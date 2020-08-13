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
        } elseif ($request->hasParameter("do_reset")) {
            $ret = $this->_doResetExecute($controller, $user, $request);
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
        $back_link_text = "";
        if ($request->hasParameter("b")) {
            $back_link_text = $request->getParameter("b");
        }
        $back_link = "./?menu=education&act=course_list";
        if ($back_link_text) {
            $back_link = Utility::decodeBackLink($back_link_text);
        }
        $request->setAttribute("course_id", $course_id);
        $request->setAttribute("multi_course_id", $multi_course_id);
        $request->setAttribute("multi_flg", $multi_flg);
        $request->setAttributes($repond_course_info);
        $request->setAttribute("back_link_text", $back_link_text);
        $request->setAttribute("back_link", $back_link);
        $request->setAttribute("course_reset_reason_list", BroadcomCourseEntity::getCourseResetReasonCodeList());
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
        $back_link_text = $request->getAttribute("back_link_text");
        if ($back_link_text) {
            $redirect_url .= "&b=" . $back_link_text;
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
        $back_link_text = $request->getAttribute("back_link_text");
        $redirect_url = "./?menu=education&act=course_list";
        if ($back_link_text) {
            $redirect_url = Utility::decodeBackLink($back_link_text);
        }
        $controller->redirect($redirect_url);
        return VIEW_NONE;
    }

    private function _doResetExecute(Controller $controller, User $user, Request $request)
    {
        $multi_flg = $request->getAttribute("multi_flg");
        $course_id = $request->getAttribute("course_id");
        $multi_course_id = $request->getAttribute("multi_course_id");
        $base_info = $request->getAttribute("base_info");
        $detail_list = $request->getAttribute("detail_list");
        $school_id = $base_info["school_id"];
        $reset_reason_code = $request->getParameter("reset_reason_code");
        if ($multi_flg) {
            $dbi = Database::getInstance();
            $begin_res = $dbi->begin();
            if ($controller->isError($begin_res)) {
                $begin_res->setPos(__FILE__, __LINE__);
                return $begin_res;
            }
            foreach ($detail_list as $course_id_tmp => $detail_tmp) {
                $insert_data = array();
                $insert_data["course_id"] = $course_id_tmp;
                $insert_data["multi_course_id"] = $multi_course_id;
                $insert_data["school_id"] = $school_id;
                $insert_data["reset_reason_code"] = $reset_reason_code;
                $insert_data["reset_confirm_flg"] = BroadcomCourseEntity::COURSE_RESET_CFM_CODE_0;
                $insert_res = BroadcomCourseInfoDBI::insertCourseReset($insert_data);
                if ($controller->isError($insert_res)) {
                    $insert_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $insert_res;
                }
            }
            $commit_res = $dbi->commit();
            if ($controller->isError($commit_res)) {
                $commit_res->setPos(__FILE__, __LINE__);
                return $commit_res;
            }
        } else {
            $insert_data = array();
            $insert_data["course_id"] = $course_id;
            $insert_data["multi_course_id"] = "";
            $insert_data["school_id"] = $school_id;
            $insert_data["reset_reason_code"] = $reset_reason_code;
            $insert_data["reset_confirm_flg"] = BroadcomCourseEntity::COURSE_RESET_CFM_CODE_0;
            $insert_res = BroadcomCourseInfoDBI::insertCourseReset($insert_data);
            if ($controller->isError($insert_res)) {
                $insert_res->setPos(__FILE__, __LINE__);
                return $insert_res;
            }
        }
        $redirect_url = "./?menu=education&act=course_confirm";
        if ($multi_flg) {
            $redirect_url .= "&multi_course_id=" . $multi_course_id;
        } else {
            $redirect_url .= "&course_id=" . $course_id;
        }
        $back_link_text = $request->getAttribute("back_link_text");
        if ($back_link_text) {
            $redirect_url .= "&b=" . $back_link_text;
        }
        $controller->redirect($redirect_url);
        return VIEW_NONE;
    }
}
?>