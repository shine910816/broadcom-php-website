<?php

/**
 * 核心业绩统计
 * @token FD8BDE31-4601-DA6B-957C-2C76884C58A3
 * @author Kinsama
 * @version 2020-08-31
 */
class BroadcomStats_AchieveAction extends ActionBase
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
        if (!$request->hasParameter("start_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: start_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        if (!$request->hasParameter("end_date")) {
            $err = $controller->raiseError(ERROR_CODE_USER_FALSIFY, "Parameter missed: end_date");
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $school_id = $request->getParameter("school_id");
        $start_date = $request->getParameter("start_date");
        $end_date = $request->getParameter("end_date");
        $post_data = array(
            "school_id" => $school_id,
            "start_date" => $start_date,
            "end_date" => $end_date
        );
        if ($request->hasParameter("member_text")) {
            $post_data["member_text"] = $request->getParameter("member_text");
        }
        $request->setAttribute("post_data", $post_data);
        return VIEW_DONE;
    }

    private function _doDefaultExecute(Controller $controller, User $user, Request $request)
    {
        $post_data = $request->getAttribute("post_data");
        $school_id = $post_data["school_id"];
        $start_date = $post_data["start_date"] . " 00:00:00";
        $end_date = $post_data["end_date"] . " 23:59:59";
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $payment_info = BroadcomPaymentDBI::selectPaymentByDate($school_id, $start_date, $end_date);
        if ($controller->isError($payment_info)) {
            $payment_info->setPos(__FILE__, __LINE__);
            return $payment_info;
        }
        // 统计数据分布
        $achieve_type_list = BroadcomOrderEntity::getAchieveTypeList();
        $stats_item = array(
            "order_count" => 0,         // 签单数
            "order_amount" => 0,        // 签单金额
            "cancel_order_count" => 0,  // 打款退单数
            "cancel_order_amount" => 0, // 打款退单金额
            "total_amount" => 0,        // 实收金额
            "calculate_amount" => 0     // 减退费实收金额
        );
        $achieve_data = array();
        foreach ($achieve_type_list as $achieve_type => $achieve_type_name) {
            $achieve_data[$achieve_type] = $stats_item;
        }
        foreach ($payment_info as $oi_info) {
            if (isset($post_data["member_text"]) && !in_array($oi_info["creator_id"], explode(",", $post_data["member_text"]))) {
                continue;
            }
            $created_date_ts = strtotime($oi_info["created_date"]);
            if ($created_date_ts >= $start_ts && $created_date_ts <= $end_ts) {
                $achieve_data[$oi_info["achieve_type"]]["order_count"]++;
                $achieve_data[$oi_info["achieve_type"]]["order_amount"] += $oi_info["payable_amount"];
            }
            if ($oi_info["order_item_status"] == BroadcomOrderEntity::ORDER_ITEM_STATUS_4) {
                $achieve_data[$oi_info["achieve_type"]]["cancel_order_count"]++;
            }
            foreach ($oi_info["payment_detail"] as $detail_info) {
                if ($detail_info["payment_status"] == BroadcomPaymentEntity::PAYMENT_STATUS_3) {
                    $achieve_data[$oi_info["achieve_type"]]["total_amount"] += $detail_info["payment_amount"];
                }
                if ($detail_info["payment_status"] == BroadcomPaymentEntity::PAYMENT_STATUS_4) {
                    $achieve_data[$oi_info["achieve_type"]]["cancel_order_amount"] -= $detail_info["payment_amount"];
                }
                $achieve_data[$oi_info["achieve_type"]]["calculate_amount"] += $detail_info["payment_amount"];
            }
        }
        // 数据整合
        foreach ($achieve_data as $achieve_type => $achieve_stats_item) {
            $stats_item["order_count"] += $achieve_stats_item["order_count"];
            $stats_item["order_amount"] += $achieve_stats_item["order_amount"];
            $stats_item["cancel_order_count"] += $achieve_stats_item["cancel_order_count"];
            $stats_item["cancel_order_amount"] += $achieve_stats_item["cancel_order_amount"];
            $stats_item["total_amount"] += $achieve_stats_item["total_amount"];
            $stats_item["calculate_amount"] += $achieve_stats_item["calculate_amount"];
        }
        $achieve_type_list["5"] = "合计";
        $achieve_data["5"] = $stats_item;
        $average_amount = 0;
        if ($achieve_data["5"]["order_count"] > 0) {
            $average_amount = round($achieve_data["5"]["total_amount"] / $achieve_data["5"]["order_count"], 2);
        }
        // 消课统计
        $course_stats = BroadcomStatisticsDBI::selectCourseStatsDetail($start_date, $end_date, $school_id);
        if ($controller->isError($course_stats)) {
            $course_stats->setPos(__FILE__, __LINE__);
            return $course_stats;
        }
//Utility::testVAriable($course_stats);
        $course_type_list = BroadcomItemEntity::getItemMethodList();
        $course_type_list["5"] = "试听课";
        $course_type_list["6"] = "赠课";
        $course_data = array();
        foreach ($course_type_list as $course_type => $tmp) {
            $course_data[$course_type] = 0;
        }
        $multi_course_id_list = array();
        if (isset($course_stats[$post_data["school_id"]])) {
            $audition_list = array(
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SOLO,
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_DUO,
                BroadcomCourseEntity::COURSE_TYPE_AUDITION_SQUAD
            );
            foreach ($course_stats[$post_data["school_id"]] as $course_tmp) {
                $course_type = $course_tmp["item_method"];
                if (in_array($course_tmp["course_type"], $audition_list)) {
                    $course_type = "5";
                } elseif ($course_tmp["item_type"] == BroadcomItemEntity::ITEM_TYPE_PRESENT) {
                    $course_type = "6";
                }
                if ($course_tmp["multi_course_id"]) {
                    if (!isset($multi_course_id_list[$course_tmp["multi_course_id"]])) {
                        $multi_course_id_list[$course_tmp["multi_course_id"]] = "1";
                        $course_data[$course_type] += $course_tmp["actual_course_hours"];
                    }
                } else {
                    $course_data[$course_type] += $course_tmp["actual_course_hours"];
                }
            }
        }
        $result = $post_data;
        $result["achieve_type_list"] = $achieve_type_list;
        $result["achieve_data"] = $achieve_data;
        $result["average_amount"] = $average_amount;
        $result["course_type_list"] = $course_type_list;
        $result["course_data"] = $course_data;
        return $result;
    }
}
?>