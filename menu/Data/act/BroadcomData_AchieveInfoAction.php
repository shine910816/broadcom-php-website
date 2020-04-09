<?php
require_once SRC_PATH . "/menu/Data/lib/BroadcomDataActionBase.php";

/**
 * 个人设定画面
 * @author Kinsama
 * @version 2020-03-10
 */
class BroadcomData_AchieveInfoAction extends BroadcomDataActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("ajax")) {
            $ret = $this->_doAjaxExecute($controller, $user, $request);
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
        if ($request->hasParameter("ajax")) {
            $ret = $this->_doAjaxValidate($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } else {
            $ret = $this->_doDefaultValidate($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        }
        return $ret;
    }

    private function _doDefaultValidate(Controller $controller, User $user, Request $request)
    {
        $period_info = $this->_getStatisticsPeriod($controller, $user, $request);
        if ($controller->isError($period_info)) {
            $period_info->setPos(__FILE__, __LINE__);
            return $period_info;
        }
        $request->setAttributes($period_info);
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
        $section_id = "0";
        $member_id = "0";
        if ($request->hasParameter("school_id")) {
            $school_id = $request->getParameter("school_id");
        }
        if ($request->hasParameter("section_id")) {
            $section_id = $request->getParameter("section_id");
        }
        if ($request->hasParameter("member_id")) {
            $member_id = $request->getParameter("member_id");
        }
        $school_list = BroadcomSchoolInfoDBI::selectSchoolInfoList();
        if ($controller->isError($school_list)) {
            $school_list->setPos(__FILE__, __LINE__);
            return $school_list;
        }
        $member_list = BroadcomMemberPositionDBI::selectMemberListBySchoolGroupPosition($school_id);
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        $member_id_list = null;
        $teacher_flg = false;
        if ($member_id != "0") {
            $member_id_list = array($member_id);
            if (isset($member_list["3"]) && in_array($member_id, array_keys($member_list["3"]))) {
                $teacher_flg = true;
            }
        } else {
            if ($section_id != "0") {
                if (isset($member_list[$section_id])) {
                    $member_id_list = array_keys($member_list[$section_id]);
                    if ($section_id == "3") {
                        $teacher_flg = true;
                    }
                }
            }
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("member_id_list", $member_id_list);
        $request->setAttribute("teacher_flg", $teacher_flg);
        $stats_data = $this->_getStatsData($controller, $user, $request);
        if ($controller->isError($stats_data)) {
            $stats_data->setPos(__FILE__, __LINE__);
            return $stats_data;
        }
        $request->setAttribute("param_list", array(
            "school_id" => $school_id,
            "section_id" => $section_id,
            "member_id" => $member_id
        ));
        $request->setAttributes($stats_data);
        $request->setAttribute("school_list", $school_list);
        $request->setAttribute("section_list", BroadcomMemberEntity::getSectionList());
        $request->setAttribute("member_list", $member_list);
        return VIEW_DONE;
    }

    private function _doAjaxValidate(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getParameter("school");
        $section_id = $request->getParameter("section");
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("section_id", $section_id);
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

    private function _doAjaxExecute(Controller $controller, User $user, Request $request)
    {
        $school_id = $request->getAttribute("school_id");
        $section_id = $request->getAttribute("section_id");
        $result = '<option value="0">全部员工</option>';
        if ($section_id != "0") {
            $member_list = BroadcomMemberPositionDBI::selectMemberListBySchoolGroupPosition($school_id);
            if ($controller->isError($member_list)) {
                $member_list->setPos(__FILE__, __LINE__);
            }
            if (isset($member_list[$section_id]) && !empty($member_list[$section_id])) {
                foreach ($member_list[$section_id] as $member_id => $member_name) {
                    $result .= '<option value="' . $member_id . '">' . $member_name . '</option>';
                }
            }
        }
        header("Content-type:text/plain; charset=utf-8");
        echo $result;
        exit;
    }
}
?>