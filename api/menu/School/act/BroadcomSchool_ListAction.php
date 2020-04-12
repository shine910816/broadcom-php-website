<?php

/**
 * 新增意向客户画面
 * @author Kinsama
 * @version 2020-04-12
 */
class BroadcomSchool_ListAction extends ActionBase
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
        $school_list = BroadcomSchoolInfoDBI::selectSchoolInfoList(true);
        if ($controller->isError($school_list)) {
            $school_list->setPos(__FILE__, __LINE__);
            return $school_list;
        }
        if (!empty($school_list)) {
            foreach ($school_list as $school_id => $school_info) {
                if (!$this->_screenSchool($school_info)) {
                    unset($school_list[$school_id]);
                }
            }
        }
        $simple_flg = false;
        if ($request->hasParameter("simple")) {
            $simple_flg = true;
        }
        $school_result = array();
        if (!empty($school_list)) {
            foreach ($school_list as $school_id => $school_info) {
                if ($simple_flg) {
                    $school_result[$school_id] = $school_info["school_name"];
                } else {
                    $school_result[$school_id] = $school_info;
                }
            }
        }
        return array(
            "school_list" => $school_result
        );
    }

    private function _screenSchool($school_info)
    {
        // TODO screen logic
        //if (!is_null($xxx) && $member_info["xxx"] != $xxx) {
        //    return false;
        //}
        return true;
    }
}
?>