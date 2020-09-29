<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-03-17
 */
class BroadcomData_TargetInputAction extends BroadcomDataActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("do_submit")) {
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
        $position_info = BroadcomMemberPositionDBI::selectMemberPosition($user->member()->id());
        if ($controller->isError($position_info)) {
            $position_info->setPos(__FILE__, __LINE__);
            return $position_info;
        }
        if (empty($position_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $position_info["school_id"];
        $current_ts = time();
        $date_list = array();
        for ($i = 0; $i < 12; $i++) {
            $target_date_ts = mktime(0, 0, 0, date("n", $current_ts) + $i, 1, date("Y", $current_ts));
            $date_key = date("Ym", $target_date_ts);
            $date_value = date("Y", $target_date_ts) . "年" . date("n", $target_date_ts) . "月";
            $date_list[$date_key] = $date_value;
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("date_list", $date_list);
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

    private function _doSubmitExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $target_date = $request->getParameter("target_date");
        $front_target = $request->getParameter("front_target");
        $back_target = $request->getParameter("back_target");
        $course_target = $request->getParameter("course_target");
        $insert_data = array();
        $insert_data["school_id"] = $school_id;
        $insert_data["target_date"] = $target_date;
        $insert_data["target_type"] = "2";
        $insert_data["front_target"] = $front_target;
        $insert_data["back_target"] = $back_target;
        $insert_data["total_target"] = $front_target + $back_target;
        $insert_data["course_target"] = $course_target;
        $insert_res = BroadcomTargetDBI::insertTarget($insert_data);
        if ($controller->isError($insert_res)) {
            $insert_res->setPos(__FILE__, __LINE__);
            return $insert_res;
        }
        $controller->redirect("./?menu=data&act=target_info");
        return VIEW_DONE;
    }
}
?>