<?php

/**
 * 成员列表画面
 * @author Kinsama
 * @version 2020-04-10
 */
class BroadcomMember_ListAction extends ActionBase
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
        // 必要参数检证
        if (!$request->hasParameter("school_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: school_id");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $request->getParameter("school_id");
        $request->setAttribute("school_id", $school_id);
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
        $school_id = $request->getAttribute("school_id");
        $section_list = null;
        if ($request->hasParameter("section")) {
            $section_list = $request->getParameter("section");
        }
        $member_list = BroadcomMemberInfoDBI::selectMemberList($school_id, $section_list);
        if ($controller->isError($member_list)) {
            $member_list->setPos(__FILE__, __LINE__);
            return $member_list;
        }
        $simple_flg = false;
        if ($request->hasParameter("simple")) {
            $simple_flg = true;
        }
        $member_result = array();
        if (!empty($member_list)) {
            foreach ($member_list as $member_id => $member_info) {
                // TODO Add screen object
                $res_member_info = $this->_screenMember($member_info, $simple_flg);
                if ($res_member_info !== false) {
                    $member_result[$member_id] = $res_member_info;
                }
            }
        }
        return array(
            "member_list" => $member_result
        );
    }

    private function _screenMember($member_info, $simple_flg)
    {
        // TODO screen logic
        //if (!is_null($xxx) && $member_info["xxx"] != $xxx) {
        //    return false;
        //}
        $position_list = BroadcomMemberEntity::getPositionList();
        $position_name = $position_list[$member_info["member_position"]];
        $covered_mobile_number = Utility::coverMobileNumber($member_info["m_mobile_number"]);
        if ($simple_flg) {
            return $member_info["m_name"] . "-" . $position_name;
        }
        $member_info["member_position_name"] = $position_name;
        $member_info["covered_mobile_number"] = $covered_mobile_number;
        return $member_info;
    }
}
?>