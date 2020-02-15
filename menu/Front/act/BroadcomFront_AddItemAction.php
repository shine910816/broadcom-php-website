<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 添加课程画面
 * @author Kinsama
 * @version 2020-02-13
 */
class BroadcomFront_AddItemAction extends BroadcomFrontActionBase
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
        } elseif ($request->hasParameter("do_add")) {
            $ret = $this->_doAddExecute($controller, $user, $request);
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
        // 获取基本情报
        if (!$request->hasParameter("student_id")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $student_id = $request->getParameter("student_id");
        $student_info = BroadcomStudentInfoDBI::selectStudentInfo($student_id);
        if ($controller->isError($student_info)) {
            $student_info->setPos(__FILE__, __LINE__);
            return $student_info;
        }
        if (empty($student_info)) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        // 判断是否为高中毕业对象
        $scope_out_flg = false;
        $entrance_year = $student_info["student_entrance_year"];
        $student_grade = BroadcomStudentEntity::getGradeName($entrance_year, true);
        $student_info["student_grade"] = BroadcomStudentEntity::getGradeName($entrance_year);
        $student_level_list = BroadcomStudentEntity::getStudentLevelList();
        $student_info["student_level_name"] = $student_level_list[$student_info["student_level"]];
        if ($student_grade == BroadcomStudentEntity::GRADE_AFTER) {
            $scope_out_flg = true;
        }
        // 判断是否为赠课对象
        $add_present_flg = false;
        $main_item_id = "0";
        $main_item_info = array();
        if ($request->hasParameter("main_item_id")) {
            $add_present_flg = true;
            $main_item_id = $request->getParameter("main_item_id");
            $main_item_info = BroadcomItemInfoDBI::selectItemInfo($main_item_id);
            if ($controller->isError($main_item_info)) {
                $main_item_info->setPos(__FILE__, __LINE__);
                return $main_item_info;
            }
            if (empty($main_item_info)) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            if ($main_item_info["item_sale_status"] == BroadcomItemEntity::ITEM_SALE_OFF) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
        }
        // 获取可售课程
        $item_info_list = array();
        if (!$scope_out_flg) {
            $item_info_list = BroadcomItemInfoDBI::selectSaleAbleItemInfoList($student_grade, $add_present_flg);
            if ($controller->isError($item_info_list)) {
                $item_info_list->setPos(__FILE__, __LINE__);
                return $item_info_list;
            }
        }
        // 获取购物车
        $cart_info = BroadcomOrderCartDBI::selectOrderCartInfoList($student_id);
        if ($controller->isError($cart_info)) {
            $cart_info->setPos(__FILE__, __LINE__);
            return $cart_info;
        }
        if ($add_present_flg && !isset($cart_info[$main_item_id])) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        // 错误选择及对象外时重置购物车
        $delete_item_flg = false;
        if ($scope_out_flg) {
            $delete_item_flg = true;
        } else {
            if (!empty($cart_info)) {
                foreach ($cart_info as $item_info_tmp) {
                    if ($item_info_tmp["item_grade"] != BroadcomItemEntity::ITEM_GRADE_TOTAL && $item_info_tmp["item_grade"] != $student_grade) {
                        $delete_item_flg = true;
                        break;
                    } else {
                        continue;
                    }
                }
            }
        }
        if ($delete_item_flg) {
            $delete_res = BroadcomOrderCartDBI::clearOrderCart($student_id);
            if ($controller->isError($delete_res)) {
                $delete_res->setPos(__FILE__, __LINE__);
                return $delete_res;
            }
        }
        $add_item_id = "0";
        $item_amount = "";
        if ($request->hasParameter("do_add")) {
            if ($scope_out_flg) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            }
            if (!$request->hasParameter("add_item_id")) {
                $request->setError("add_item_id", "请选择一项课程");
            } elseif (!isset($item_info_list[$request->getParameter("add_item_id")])) {
                $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY);
                $err->setPos(__FILE__, __LINE__);
                return $err;
            } else {
                $add_item_id = $request->getParameter("add_item_id");
            }
            $item_amount = $request->getParameter("item_amount");
            if (!Validate::checkNotNull($item_amount) || !Validate::checkNumber($item_amount, array("min" => "1", "max" => "999"))) {
                $request->setError("item_amount", "请有效的购买数量");
            }
        }
        $cart_info = BroadcomOrderCartDBI::selectOrderCartInfoList($student_id);
        if ($controller->isError($cart_info)) {
            $cart_info->setPos(__FILE__, __LINE__);
            return $cart_info;
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("scope_out_flg", $scope_out_flg);
        $request->setAttribute("item_info_list", $item_info_list);
        $request->setAttribute("add_present_flg", $add_present_flg);
        $request->setAttribute("main_item_id", $main_item_id);
        $request->setAttribute("main_item_info", $main_item_info);
        $request->setAttribute("add_item_id", $add_item_id);
        $request->setAttribute("item_amount", $item_amount);
        $request->setAttribute("cart_info", $cart_info);
        $request->setAttribute("cart_count", count($cart_info));
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
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

    private function _doAddExecute(Controller $controller, User $user, Request $request)
    {
        $student_id = $request->getAttribute("student_id");
        $add_item_id = $request->getAttribute("add_item_id");
        $item_amount = $request->getAttribute("item_amount");
        $cart_info = $request->getAttribute("cart_info");
        $add_present_flg = $request->getAttribute("add_present_flg");
        if (isset($cart_info[$add_item_id])) {
            $update_data = array();
            $update_data["item_amount"] = $cart_info[$add_item_id]["item_amount"] + $item_amount;
            $update_res = BroadcomOrderCartDBI::updateOrderCart($update_data, $student_id, $add_item_id);
            if ($controller->isError($update_res)) {
                $update_res->setPos(__FILE__, __LINE__);
                return $update_res;
            }
        } else {
            $insert_data = array();
            $insert_data["student_id"] = $student_id;
            $insert_data["item_id"] = $add_item_id;
            $insert_data["item_amount"] = $item_amount;
            if ($add_present_flg) {
                $insert_data["item_present_flg"] = "1";
                $insert_data["main_item_id"] = $request->getAttribute("main_item_id");
            } else {
                $insert_data["item_present_flg"] = "0";
                $insert_data["main_item_id"] = null;
            }
            $insert_res = BroadcomOrderCartDBI::insertOrderCart($insert_data);
            if ($controller->isError($insert_res)) {
                $insert_res->setPos(__FILE__, __LINE__);
                return $insert_res;
            }
        }
        $redirect_url = "./?menu=front&act=";
        if ($add_present_flg) {
            $redirect_url .= "cart_info";
        } else {
            $redirect_url .= "add_item";
        }
        $redirect_url .= "&student_id=" . $student_id . "&page=" . $request->current_page;
        $controller->redirect($redirect_url);
        return VIEW_DONE;
    }
}
?>