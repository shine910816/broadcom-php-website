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
        $cal_post_data = array(
            "period_type" => "1"
        );
        if ($request->hasParameter("period_type")) {
            $cal_post_data["period_type"] = $request->getParameter("period_type");
        }
        if ($cal_post_data["period_type"] == "4") {
            $cal_post_data["start"] = $request->getParameter("start");
            $cal_post_data["end"] = $request->getParameter("end");
        }
        $repond_period = Utility::getJsonResponse("?t=2B2ECF74-5AD6-3897-AA2B-42567E035029&m=" . $user->member()->targetObjectId(), $cal_post_data);
        if ($controller->isError($repond_period)) {
            $repond_period->setPos(__FILE__, __LINE__);
            return $repond_period;
        }
        $start_date = $repond_period["start"];
        $end_date = $repond_period["end"];
        $request->setAttribute("period_type", $cal_post_data["period_type"]);
        $request->setAttribute("period_start_date", $start_date);
        $request->setAttribute("period_end_date", $end_date);
        $school_id = $user->member()->schoolId();
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
        $member_id_list = array();
        $teacher_flg = false;
        if ($member_id != "0") {
            $member_id_list = array($member_id);
        } else {
            if ($section_id != "0") {
                if (isset($member_list[$section_id])) {
                    $member_id_list = array_keys($member_list[$section_id]);
                }
            }
        }
        $stats_post_data = array(
            "school_id" => $school_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
        );
        if (!empty($member_id_list)) {
            $stats_post_data["member_text"] = implode(",", $member_id_list);
        }
        $repond_stats_info = Utility::getJsonResponse("?t=FD8BDE31-4601-DA6B-957C-2C76884C58A3&m=" . $user->member()->targetObjectId(), $stats_post_data);
        if ($controller->isError($repond_stats_info)) {
            $repond_stats_info->setPos(__FILE__, __LINE__);
            return $repond_stats_info;
        }
        $request->setAttribute("param_list", array(
            "school_id" => $school_id,
            "section_id" => $section_id,
            "member_id" => $member_id
        ));
        $request->setAttributes($repond_stats_info);
        $request->setAttribute("school_list", $school_list);
        $request->setAttribute("section_list", BroadcomMemberEntity::getSectionList());
        $request->setAttribute("member_list", $member_list);
        $this->_formatNumber($request);
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

    private function _formatNumber(Request $request)
    {
        $achieve_data = $request->getAttribute("achieve_data");
        $result = array();
        foreach ($achieve_data as $achieve_type => $temp_data) {
            $result[$achieve_type]["order_count"] = number_format($temp_data["order_count"]) . "单";
            $result[$achieve_type]["order_amount"] = number_format($temp_data["order_amount"], 2) . "元";
            $result[$achieve_type]["cancel_order_count"] = number_format($temp_data["cancel_order_count"]) . "单";
            $result[$achieve_type]["cancel_order_amount"] = number_format($temp_data["cancel_order_amount"], 2) . "元";
            $result[$achieve_type]["total_amount"] = number_format($temp_data["total_amount"], 2) . "元";
            $result[$achieve_type]["calculate_amount"] = number_format($temp_data["calculate_amount"], 2) . "元";
        }
        $request->setAttribute("achieve_data", $result);
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