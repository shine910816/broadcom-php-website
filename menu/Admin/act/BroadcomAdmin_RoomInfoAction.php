<?php
require_once SRC_PATH . "/menu/Admin/lib/BroadcomAdminActionBase.php";

/**
 * 后台管理画面
 * @author Kinsama
 * @version 2020-02-22
 */
class BroadcomAdmin_RoomInfoAction extends BroadcomAdminActionBase
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
        if (!$request->hasParameter("school_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $request->getParameter("school_id");
        $school_list = BroadcomSchoolInfoDBI::selectSchoolInfoList();
        if ($controller->isError($school_list)) {
            $school_list->setPos(__FILE__, __LINE__);
            return $school_list;
        }
        if (!isset($school_list[$school_id])) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_name = $school_list[$school_id];
        $room_info_list = BroadcomRoomInfoDBI::selectRoomList($school_id);
        if ($controller->isError($room_info_list)) {
            $room_info_list->setPos(__FILE__, __LINE__);
            return $room_info_list;
        }
        $request->setAttribute("school_id", $school_id);
        $request->setAttribute("school_name", $school_name);
        $request->setAttribute("room_info_list", $room_info_list);
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
        $room_info_list = $request->getAttribute("room_info_list");
        $getting_room_info_list = array();
        if ($request->hasParameter("room_info")) {
            $getting_room_info_list = $request->getParameter("room_info");
        }
        $insert_list = array();
        if ($request->hasParameter("insert_data")) {
            $insert_list = $request->getParameter("insert_data");
        }
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        if (!empty($getting_room_info_list)) {
            foreach ($getting_room_info_list as $room_id => $room_info) {
                $update_data = array();
                if (isset($room_info_list[$room_id])) {
                    if ($room_info["room_name"] != $room_info_list[$room_id]["room_name"]) {
                        $update_data["room_name"] = $room_info["room_name"];
                    }
                    if ($room_info["usable_flg"] != $room_info_list[$room_id]["usable_flg"]) {
                        $update_data["usable_flg"] = $room_info["usable_flg"];
                    }
                }
                if (!empty($update_data)) {
                    $update_res = BroadcomRoomInfoDBI::updateRoom($update_data, $room_id);
                    if ($controller->isError($update_res)) {
                        $update_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $update_res;
                    }
                }
            }
        }
        if (!empty($insert_list)) {
            foreach ($insert_list as $room_name) {
                $insert_data = array();
                $insert_data["school_id"] = $school_id;
                $insert_data["room_name"] = $room_name;
                $insert_data["usable_flg"] = "1";
                $insert_res = BroadcomRoomInfoDBI::insertRoom($insert_data);
                if ($controller->isError($insert_res)) {
                    $insert_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $insert_res;
                }
            }
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=admin&act=room_info&school_id=" . $school_id);
        return VIEW_DONE;
    }
}
?>