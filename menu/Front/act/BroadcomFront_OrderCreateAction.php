<?php
require_once SRC_PATH . "/menu/Front/lib/BroadcomFrontActionBase.php";

/**
 * 前台业务画面
 * @author Kinsama
 * @version 2020-02-16
 */
class BroadcomFront_OrderCreateAction extends BroadcomFrontActionBase
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
        } elseif ($request->hasParameter("do_create")) {
            $ret = $this->_doCreateExecute($controller, $user, $request);
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
        $payment_amount = "0";
        if ($request->hasParameter("do_create")) {
            $payment_amount = $request->getParameter("payment_amount");
            if (!Validate::checkNotNull($payment_amount) || !Validate::checkDecimalNumber($payment_amount, array("min" => "1", "max" => $total_price))) {
                $request->setError("payment_amount", "请填写的有效付款额");
            }
        }
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("student_info", $student_info);
        $request->setAttribute("scope_out_flg", $scope_out_flg);
        $request->setAttribute("cart_list", $cart_list);
        $request->setAttribute("cart_item_info", $cart_item_info);
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("item_unit_list", BroadcomItemEntity::getItemUnitList());
        $request->setAttribute("item_discount_type_list", BroadcomItemEntity::getItemDiscountTypeList());
        $request->setAttribute("payable_price_list", $payable_price_list);
        $request->setAttribute("total_price", $total_price);
        $request->setAttribute("payment_amount", $payment_amount);
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

    private function _doCreateExecute(Controller $controller, User $user, Request $request)
    {
Utility::testVariable($request->getAttributes());
        $student_id = $request->getAttribute("student_id");
        $controller->redirect("./?menu=front&act=order_list&student_id=" . $student_id);
        return VIEW_DONE;
    }
}
?>