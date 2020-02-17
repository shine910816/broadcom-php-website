<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 已选择课程画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomFront_CartInfoAction extends BroadcomFrontActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("delete_item_id")) {
            $ret = $this->_doDeleteExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->isError()) {
            $ret = $this->_doErrorExecute($controller, $user, $request);
            if ($controller->isError($ret)) {
                $ret->setPos(__FILE__, __LINE__);
                return $ret;
            }
        } elseif ($request->hasParameter("do_change")) {
            $ret = $this->_doChangeExecute($controller, $user, $request);
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
        // 获取购物车
        $cart_list = BroadcomOrderCartDBI::selectOrderCartList($student_id);
        if ($controller->isError($cart_list)) {
            $cart_list->setPos(__FILE__, __LINE__);
            return $cart_list;
        }
        $cart_item_info = BroadcomOrderCartDBI::selectOrderCartInfoList($student_id);
        if ($controller->isError($cart_item_info)) {
            $cart_item_info->setPos(__FILE__, __LINE__);
            return $cart_item_info;
        }
        $getting_cart_info = array();
        if ($request->hasParameter("do_change")) {
            $getting_cart_info = $request->getParameter("cart_info");
            foreach ($getting_cart_info as $item_id => $cart_item_info_tmp) {
                if (!Validate::checkNotNull($cart_item_info_tmp["item_amount"]) || !Validate::checkNumber($cart_item_info_tmp["item_amount"], array("min" => "1", "max" => "999"))) {
                    $request->setError("item_amount", "请填写有效的购买数量");
                }
                if ($cart_item_info[$item_id]["item_unit"] == BroadcomItemEntity::ITEM_UNIT_PERIOD && $cart_item_info_tmp["item_amount"] > 1) {
                    $request->setError("item_amount_period", "班课每单购买数量最大为1");
                }
                if (!Validate::checkNotNull($cart_item_info_tmp["item_discount_amount"]) || !Validate::checkDecimalNumber($cart_item_info_tmp["item_discount_amount"])) {
                    $request->setError("item_discount_amount", "请填写有效的优惠额度");
                }
            }
        }
        $total_price = 0;
        $payable_price_list = array();
        foreach ($cart_item_info as $item_id => $cart_item_info_tmp) {
            $item_total_price = $this->_getTotalPrice(
                $cart_item_info_tmp["item_price"],
                $cart_item_info_tmp["item_amount"],
                $cart_item_info_tmp["item_discount_type"],
                $cart_item_info_tmp["item_discount_amount"]
            );
            $payable_price_list[$item_id] = $item_total_price;
            $total_price += $item_total_price;
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("scope_out_flg", $scope_out_flg);
        $request->setAttribute("cart_list", $cart_list);
        $request->setAttribute("cart_item_info", $cart_item_info);
        $request->setAttribute("getting_cart_info", $getting_cart_info);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
        $request->setAttribute("item_discount_type_list", BroadcomItemEntity::getItemDiscountTypeList());
        $request->setAttribute("payable_price_list", $payable_price_list);
        $request->setAttribute("total_price", $total_price);
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

    private function _doChangeExecute(Controller $controller, User $user, Request $request)
    {
        $student_id = $request->getAttribute("student_id");
        $cart_item_info = $request->getAttribute("cart_item_info");
        $getting_cart_info = $request->getAttribute("getting_cart_info");
        $update_list = array();
        foreach ($getting_cart_info as $item_id => $cart_info) {
            if ($cart_item_info[$item_id]["item_amount"] != $cart_info["item_amount"]) {
                $update_list[$item_id]["item_amount"] = $cart_info["item_amount"];
            }
            if ($cart_item_info[$item_id]["item_discount_amount"] != $cart_info["item_discount_amount"]) {
                $update_list[$item_id]["item_discount_amount"] = $cart_info["item_discount_amount"];
            }
            if ($cart_item_info[$item_id]["item_discount_type"] != $cart_info["item_discount_type"]) {
                $update_list[$item_id]["item_discount_type"] = $cart_info["item_discount_type"];
                if ($cart_info["item_discount_type"] == BroadcomItemEntity::ITEM_DISCOUNT_TYPE_NONE) {
                    $update_list[$item_id]["item_discount_amount"] = "0";
                }
            }
        }
        if (!empty($update_list)) {
            $dbi = Database::getInstance();
            $begin_res = $dbi->begin();
            if ($controller->isError($begin_res)) {
                $begin_res->setPos(__FILE__, __LINE__);
                return $begin_res;
            }
            foreach ($update_list as $item_id => $update_data) {
                $update_res = BroadcomOrderCartDBI::updateOrderCart($update_data, $student_id, $item_id);
                if ($controller->isError($update_res)) {
                    $update_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $update_res;
                }
            }
            $commit_res = $dbi->commit();
            if ($controller->isError($commit_res)) {
                $commit_res->setPos(__FILE__, __LINE__);
                return $commit_res;
            }
        }
        $controller->redirect("./?menu=front&act=cart_info&student_id=" . $student_id);
        return VIEW_DONE;
    }

    private function _doDeleteExecute(Controller $controller, User $user, Request $request)
    {
        $student_id = $request->getAttribute("student_id");
        $cart_item_info = $request->getAttribute("cart_item_info");
        $delete_item_id = $request->getParameter("delete_item_id");
        $delete_item_list = array();
        foreach ($cart_item_info as $item_info_tmp) {
            if ($delete_item_id == $item_info_tmp["item_id"] || $delete_item_id == $item_info_tmp["main_item_id"]) {
                $delete_item_list[] = $item_info_tmp["item_id"];
            }
        }
        if (!empty($delete_item_list)) {
            $delete_res = BroadcomOrderCartDBI::clearOrderCart($student_id, $delete_item_list);
            if ($controller->isError($delete_res)) {
                $delete_res->setPos(__FILE__, __LINE__);
                return $delete_res;
            }
        }
        $controller->redirect("./?menu=front&act=cart_info&student_id=" . $student_id);
        return VIEW_DONE;
    }
}
?>