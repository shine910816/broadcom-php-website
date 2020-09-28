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
        $school_id = $student_info["school_id"];
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
        // 业绩类型详细
        $audition_teacher_list = array();
        $sub_achieve_type_list = BroadcomOrderEntity::getSubAchieveTypeList();
        $student_list = BroadcomStudentInfoDBI::selectLeadsStudentInfo($school_id);
        if ($controller->isError($student_list)) {
            $student_list->setPos(__FILE__, __LINE__);
            return $student_list;
        }
        if (!empty($student_list)) {
            foreach ($student_list as $student_tmp) {
                if ($student_tmp["student_id"] != $student_id) {
                    $sub_achieve_type_list[BroadcomOrderEntity::ACHIEVE_TYPE_2][$student_tmp["student_id"]] = $student_tmp["student_name"];
                }
            }
        }
        $allow_member_position_list = array(
            BroadcomMemberEntity::POSITION_TEACH_MANAGER,
            BroadcomMemberEntity::POSITION_TEACHER
        );
        $teacher_tmp_list = BroadcomMemberPositionDBI::selectMemberPositionListBySchool($school_id, $allow_member_position_list);
        if ($controller->isError($teacher_tmp_list)) {
            $teacher_tmp_list->setPos(__FILE__, __LINE__);
            return $teacher_tmp_list;
        }
        if (!empty($teacher_tmp_list)) {
            foreach ($teacher_tmp_list as $teacher_tmp) {
                $sub_achieve_type_list[BroadcomOrderEntity::ACHIEVE_TYPE_3][$teacher_tmp["member_id"]] = $teacher_tmp["m_name"];
                if ($teacher_tmp["member_position"] == BroadcomMemberEntity::POSITION_TEACHER) {
                    $audition_teacher_list[$teacher_tmp["member_id"]] = $teacher_tmp["m_name"];
                }
            }
        }
        $achieve_member_position_list = array(
            BroadcomMemberEntity::POSITION_HEADMASTER,
            BroadcomMemberEntity::POSITION_ADVISER_MANAGER,
            BroadcomMemberEntity::POSITION_ADVISER,
            BroadcomMemberEntity::POSITION_MARKETING,
            BroadcomMemberEntity::POSITION_ASSIST_MANAGER,
            BroadcomMemberEntity::POSITION_ASSISTANT
        );
        $achieve_tmp_list = BroadcomMemberPositionDBI::selectMemberPositionListBySchool($school_id, $achieve_member_position_list);
        if ($controller->isError($achieve_tmp_list)) {
            $achieve_tmp_list->setPos(__FILE__, __LINE__);
            return $achieve_tmp_list;
        }
        $achieve_member_list = array();
        if (!empty($achieve_tmp_list)) {
            foreach ($achieve_tmp_list as $achieve_tmp) {
                $achieve_member_list[$achieve_tmp["member_id"]] = $achieve_tmp["m_name"];
            }
        }
        $payment_amount = "0";
        $audition_teacher = array();
        $achieve_member = array();
        if ($request->hasParameter("do_create")) {
            $payment_amount = $request->getParameter("payment_amount");
            if (!Validate::checkNotNull($payment_amount) || !Validate::checkDecimalNumber($payment_amount, array("min" => "1", "max" => $total_price))) {
                $request->setError("payment_amount", "请填写的有效付款额");
            }
            if ($request->hasParameter("audition_teacher")) {
                foreach ($request->getParameter("audition_teacher") as $teacher_member_id) {
                    $audition_teacher[$teacher_member_id] = array(
                        "teacher_member_id" => $teacher_member_id
                    );
                }
            }
            if (!$request->hasParameter("achieve_member") || !$request->hasParameter("achieve_ratio")) {
                $request->setError("achieve_member", "请添加业绩所属人");
            } else {
                $getting_achieve_member = $request->getParameter("achieve_member");
                $achieve_ratio = $request->getParameter("achieve_ratio");
                $ratio_total = 0;
                foreach ($achieve_ratio as $ratio_tmp) {
                    $ratio_total += $ratio_tmp;
                }
                if ($ratio_total != 100) {
                    $request->setError("achieve_ratio", "业绩比例合计不为100%");
                } else {
                    foreach ($getting_achieve_member as $idx => $achieve_member_id) {
                        $ratio_total += $ratio_tmp;
                        if (!isset($achieve_member[$achieve_member_id])) {
                            $achieve_member[$achieve_member_id] = array(
                                "member_id" => $achieve_member_id,
                                "achieve_ratio" => 0
                            );
                        }
                        $achieve_member[$achieve_member_id]["achieve_ratio"] += $achieve_ratio[$idx];
                    }
                }
            }
        }
        $request->setAttribute("member_id", $user->member()->id());
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
        $request->setAttribute("achieve_type_list", BroadcomOrderEntity::getAchieveTypeList());
        $request->setAttribute("sub_achieve_type_list", $sub_achieve_type_list);
        $request->setAttribute("audition_teacher_list", $audition_teacher_list);
        $request->setAttribute("achieve_member_list", $achieve_member_list);
        $request->setAttribute("audition_teacher", $audition_teacher);
        $request->setAttribute("achieve_member", $achieve_member);
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
        $member_id = $user->member()->id();
        $student_id = $request->getAttribute("student_id");
        $cart_list = $request->getAttribute("cart_list");
        $cart_item_info = $request->getAttribute("cart_item_info");
        $payable_price_list = $request->getAttribute("payable_price_list");
        $total_price = $request->getAttribute("total_price");
        $payment_amount = $request->getAttribute("payment_amount");
        $achieve_type = $request->getParameter("achieve_type");
        $sub_achieve_type = $request->getParameter("sub_achieve_type");
        $audition_teacher = $request->getAttribute("audition_teacher");
        $achieve_member = $request->getAttribute("achieve_member");
        $school_id = $user->member()->schoolId();
        $dbi = Database::getInstance();
        $begin_res = $dbi->begin();
        if ($controller->isError($begin_res)) {
            $begin_res->setPos(__FILE__, __LINE__);
            return $begin_res;
        }
        $order_number_count = BroadcomOrderDBI::selectOrderCountForCreate();
        if ($controller->isError($order_number_count)) {
            $order_number_count->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_number_count;
        }
        $order_number = "YPBT" . date("Ymd") . sprintf("%04d", $order_number_count + 1);
        $order_insert_data = array();
        $order_insert_data["order_number"] = $order_number;
        $order_insert_data["achieve_type"] = $achieve_type;
        $order_insert_data["sub_achieve_type"] = $sub_achieve_type;
        $order_insert_data["school_id"] = $school_id;
        $order_insert_data["student_id"] = $student_id;
        $order_insert_data["order_payable"] = $total_price;
        $order_insert_data["order_payment"] = $payment_amount;
        $order_insert_data["order_debt"] = $total_price - $payment_amount;
        if ($order_insert_data["order_debt"] == 0) {
            $order_insert_data["order_status"] = BroadcomOrderEntity::ORDER_STATUS_2;
        } else {
            $order_insert_data["order_status"] = BroadcomOrderEntity::ORDER_STATUS_1;
        }
        $order_insert_data["order_examine_flg"] = "0";
        $order_insert_data["order_examiner_id"] = null;
        $order_insert_data["order_examine_date"] = null;
        $order_insert_data["assign_member_id"] = $member_id;
        $order_insert_data["assign_date"] = date("Y-m-d H:i:s");
        $order_id = BroadcomOrderDBI::insertOrder($order_insert_data);
        if ($controller->isError($order_id)) {
            $order_id->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $order_id;
        }
        $payment_insert_data = array();
        $payment_insert_data["student_id"] = $student_id;
        $payment_insert_data["payment_status"] = BroadcomPaymentEntity::PAYMENT_STATUS_1;
        $payment_insert_data["order_id"] = $order_id;
        $payment_insert_data["order_item_id"] = null;
        $payment_insert_data["payment_amount"] = $payment_amount;
        $payment_insert_res = BroadcomPaymentDBI::insertPayment($payment_insert_data);
        if ($controller->isError($payment_insert_res)) {
            $payment_insert_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $payment_insert_res;
        }
        foreach ($cart_list as $main_item_id => $main_item_info) {
            $main_contract_count = BroadcomOrderDBI::selectOrderItemCountForCreate();
            if ($controller->isError($main_contract_count)) {
                $main_contract_count->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $main_contract_count;
            }
            $main_contract_number = "YP" . sprintf("%02d%s%02d%02d%04d",
                $school_id,
                date("Ymd"),
                $cart_item_info[$main_item_id]["item_type"],
                $cart_item_info[$main_item_id]["item_method"],
                $main_contract_count + 1
            );
            $main_order_item_insert_data = array();
            $main_order_item_insert_data["contract_number"] = $main_contract_number;
            $main_order_item_insert_data["achieve_type"] = $achieve_type;
            $main_order_item_insert_data["sub_achieve_type"] = $sub_achieve_type;
            $main_order_item_insert_data["school_id"] = $school_id;
            $main_order_item_insert_data["student_id"] = $student_id;
            $main_order_item_insert_data["order_id"] = $order_id;
            $main_order_item_insert_data["item_id"] = $main_item_id;
            $main_order_item_insert_data["main_order_item_id"] = null;
            $main_order_item_insert_data["order_item_price"] = $cart_item_info[$main_item_id]["item_price"];
            $main_order_item_insert_data["order_item_amount"] = $main_item_info["amount"];
            $main_order_item_insert_data["order_item_discount_type"] = $cart_item_info[$main_item_id]["item_discount_type"];
            $main_order_item_insert_data["order_item_discount_amount"] = $cart_item_info[$main_item_id]["item_discount_amount"];
            $main_order_item_insert_data["order_item_payable_amount"] = $payable_price_list[$main_item_id];
            $main_order_item_insert_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_1;
            $main_order_item_insert_data["assign_member_id"] = $member_id;
            $main_order_item_insert_data["assign_date"] = date("Y-m-d H:i:s");
            $main_order_item_id = BroadcomOrderDBI::insertOrderItem($main_order_item_insert_data);
            if ($controller->isError($main_order_item_id)) {
                $main_order_item_id->setPos(__FILE__, __LINE__);
                $dbi->rollback();
                return $main_order_item_id;
            }
            if (!empty($audition_teacher)) {
                foreach ($audition_teacher as $audition_teacher_insert_data) {
                    $audition_teacher_insert_data["order_item_id"] = $main_order_item_id;
                    $audition_teacher_insert_data["order_id"] = $order_id;
                    $audition_teacher_insert_res = BroadcomOrderDBI::insertOrderItemAudition($audition_teacher_insert_data);
                    if ($controller->isError($audition_teacher_insert_res)) {
                        $audition_teacher_insert_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $audition_teacher_insert_res;
                    }
                }
            }
            foreach ($achieve_member as $achieve_member_insert_data) {
                $achieve_member_insert_data["order_item_id"] = $main_order_item_id;
                $achieve_member_insert_data["order_id"] = $order_id;
                $achieve_member_insert_res = BroadcomOrderDBI::insertOrderItemAchieve($achieve_member_insert_data);
                if ($controller->isError($achieve_member_insert_res)) {
                    $achieve_member_insert_res->setPos(__FILE__, __LINE__);
                    $dbi->rollback();
                    return $achieve_member_insert_res;
                }
            }
            if (isset($main_item_info["present"])) {
                foreach ($main_item_info["present"] as $sub_item_id => $sub_item_amount) {
                    $sub_contract_number = BroadcomOrderDBI::selectPresentOrderItemCountForCreate($main_order_item_id);
                    if ($controller->isError($sub_contract_number)) {
                        $sub_contract_number->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $sub_contract_number;
                    }
                    $sub_order_item_insert_data = array();
                    $sub_order_item_insert_data["contract_number"] = $main_contract_number . "-" . ($sub_contract_number + 1);
                    $sub_order_item_insert_data["achieve_type"] = $achieve_type;
                    $sub_order_item_insert_data["sub_achieve_type"] = $sub_achieve_type;
                    $sub_order_item_insert_data["school_id"] = $school_id;
                    $sub_order_item_insert_data["student_id"] = $student_id;
                    $sub_order_item_insert_data["order_id"] = $order_id;
                    $sub_order_item_insert_data["item_id"] = $sub_item_id;
                    $sub_order_item_insert_data["main_order_item_id"] = $main_order_item_id;
                    $sub_order_item_insert_data["order_item_price"] = $cart_item_info[$sub_item_id]["item_price"];
                    $sub_order_item_insert_data["order_item_amount"] = $sub_item_amount;
                    $sub_order_item_insert_data["order_item_discount_type"] = $cart_item_info[$sub_item_id]["item_discount_type"];
                    $sub_order_item_insert_data["order_item_discount_amount"] = $cart_item_info[$sub_item_id]["item_discount_amount"];
                    $sub_order_item_insert_data["order_item_payable_amount"] = $payable_price_list[$sub_item_id];
                    $sub_order_item_insert_data["order_item_status"] = BroadcomOrderEntity::ORDER_ITEM_STATUS_1;
                    $sub_order_item_insert_data["assign_member_id"] = $member_id;
                    $sub_order_item_insert_data["assign_date"] = date("Y-m-d H:i:s");
                    $sub_insert_res = BroadcomOrderDBI::insertOrderItem($sub_order_item_insert_data);
                    if ($controller->isError($sub_insert_res)) {
                        $sub_insert_res->setPos(__FILE__, __LINE__);
                        $dbi->rollback();
                        return $sub_insert_res;
                    }
                    if (!empty($audition_teacher)) {
                        foreach ($audition_teacher as $audition_teacher_insert_data) {
                            $audition_teacher_insert_data["order_item_id"] = $sub_insert_res;
                            $audition_teacher_insert_data["order_id"] = $order_id;
                            $audition_teacher_insert_res = BroadcomOrderDBI::insertOrderItemAudition($audition_teacher_insert_data);
                            if ($controller->isError($audition_teacher_insert_res)) {
                                $audition_teacher_insert_res->setPos(__FILE__, __LINE__);
                                $dbi->rollback();
                                return $audition_teacher_insert_res;
                            }
                        }
                    }
                    foreach ($achieve_member as $achieve_member_insert_data) {
                        $achieve_member_insert_data["order_item_id"] = $sub_insert_res;
                        $achieve_member_insert_data["order_id"] = $order_id;
                        $achieve_member_insert_res = BroadcomOrderDBI::insertOrderItemAchieve($achieve_member_insert_data);
                        if ($controller->isError($achieve_member_insert_res)) {
                            $achieve_member_insert_res->setPos(__FILE__, __LINE__);
                            $dbi->rollback();
                            return $achieve_member_insert_res;
                        }
                    }
                }
            }
        }
        $delete_res = BroadcomOrderCartDBI::clearOrderCart($student_id);
        if ($controller->isError($delete_res)) {
            $delete_res->setPos(__FILE__, __LINE__);
            $dbi->rollback();
            return $delete_res;
        }
        $commit_res = $dbi->commit();
        if ($controller->isError($commit_res)) {
            $commit_res->setPos(__FILE__, __LINE__);
            return $commit_res;
        }
        $controller->redirect("./?menu=education&act=student_info&student_id=" . $student_id);
        return VIEW_DONE;
    }
}
?>