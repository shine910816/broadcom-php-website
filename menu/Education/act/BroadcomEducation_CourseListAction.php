<?php
require_once SRC_PATH . "/menu/Education/lib/BroadcomEducationActionBase.php";

/**
 * 学员教务画面
 * @author Kinsama
 * @version 2020-02-09
 */
class BroadcomEducation_CourseListAction extends BroadcomEducationActionBase
{

    /**
     * 执行主程序
     * @param object $controller Controller对象类
     * @param object $user User对象类
     * @param object $request Request对象类
     */
    public function doMainExecute(Controller $controller, User $user, Request $request)
    {
        if ($request->hasParameter("output")) {
            $ret = $this->_doOutputExecute($controller, $user, $request);
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
        $period_date_info = $this->_getPeriodDate($controller, $user, $request);
        if ($controller->isError($period_date_info)) {
            $period_date_info->setPos(__FILE__, __LINE__);
            return $period_date_info;
        }
        $course_date_from = substr($period_date_info["period_start_date"], 0, 10);
        $course_date_to = substr($period_date_info["period_end_date"], 0, 10);
        $period_type = $period_date_info["period_type"];
        $post_data = array(
            "school_id" => $user->member()->schoolId(),
            "start_date" => $course_date_from,
            "end_date" => $course_date_to,
        );
        $member_position = $user->member()->position();
        $assign_member_list_flg = true;
        $teacher_member_list_flg = true;
        $section_list = BroadcomMemberEntity::getSectionPositionList();
        if (in_array($member_position, $section_list[BroadcomMemberEntity::SECTION_3])) {
            $teacher_member_list_flg = false;
        } elseif (in_array($member_position, $section_list[BroadcomMemberEntity::SECTION_2]) || in_array($member_position, $section_list[BroadcomMemberEntity::SECTION_5])) {
            $assign_member_list_flg = false;
        }
        $student_id = "0";
        if ($request->hasParameter("student_id") && $request->getParameter("student_id")) {
            $student_id = $request->getParameter("student_id");
            $post_data["student_id"] = $student_id;
        }
        $teacher_member_id = "0";
        if (!$teacher_member_list_flg) {
            $teacher_member_id = $user->member()->id();
            $post_data["teacher_member_id"] = $teacher_member_id;
        } else {
            if ($request->hasParameter("teacher_member_id") && $request->getParameter("teacher_member_id")) {
                $teacher_member_id = $request->getParameter("teacher_member_id");
                $post_data["teacher_member_id"] = $teacher_member_id;
            }
        }
        $assign_member_id = "0";
        if (!$assign_member_list_flg) {
            $assign_member_id = $user->member()->id();
            $post_data["assign_member_id"] = $assign_member_id;
        } else {
            if ($request->hasParameter("assign_member_id") && $request->getParameter("assign_member_id")) {
                $assign_member_id = $request->getParameter("assign_member_id");
                $post_data["assign_member_id"] = $assign_member_id;
            }
        }
        $confirm_flg = "1";
        if ($request->hasParameter("confirm_flg")) {
            $confirm_flg = $request->getParameter("confirm_flg");
        }
        if ($confirm_flg != "2") {
            $post_data["confirm_flg"] = $confirm_flg;
        }
        $repond_course_list = Utility::getJsonResponse("?t=D4F1FA27-76D2-3029-4FB9-2FD91B0057B8&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_course_list)) {
            $repond_course_list->setPos(__FILE__, __LINE__);
            return $repond_course_list;
        }
        $course_list = $repond_course_list["course_list"];
        if (!empty($course_list)) {
            foreach ($course_list as $course_id => $course_info) {
                if ($course_info["multi_course_id"]) {
                    unset($course_list[$course_id]);
                }
            }
        }
        $output_param_array = array(
            "menu" => $request->current_menu,
            "act" => $request->current_act,
            "period_type" => $period_type,
            "start_date" => $course_date_from,
            "end_date" => $course_date_to,
            "output" => "1",
            "student_id" => $student_id,
            "teacher_member_id" => $teacher_member_id,
            "assign_member_id" => $assign_member_id,
            "confirm_flg" => $confirm_flg
        );
        $back_link = Utility::encodeBackLink("education", "course_list", array(
            "period_type" => $period_type,
            "start_date" => $course_date_from,
            "end_date" => $course_date_to,
            "student_id" => $student_id,
            "teacher_member_id" => $teacher_member_id,
            "assign_member_id" => $assign_member_id,
            "confirm_flg" => $confirm_flg
        ));
        $request->setAttribute("period_start_date", $course_date_from);
        $request->setAttribute("period_end_date", $course_date_to);
        $request->setAttribute("period_type", $period_type);
        $request->setAttribute("course_list", $course_list);
        $request->setAttribute("assign_member_list_flg", $assign_member_list_flg);
        $request->setAttribute("teacher_member_list_flg", $teacher_member_list_flg);
        $request->setAttribute("student_id", $student_id);
        $request->setAttribute("teacher_member_id", $teacher_member_id);
        $request->setAttribute("assign_member_id", $assign_member_id);
        $request->setAttribute("confirm_flg", $confirm_flg);
        $request->setAttribute("output_url", http_build_query($output_param_array));
        $request->setAttribute("back_link", $back_link);
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
        $course_list = $request->getAttribute("course_list");
        $assign_member_list_flg = $request->getAttribute("assign_member_list_flg");
        $teacher_member_list_flg = $request->getAttribute("teacher_member_list_flg");
        $post_data = array(
            "school_id" => $user->member()->schoolId(),
            "simple" => "1"
        );
        $repond_student_list = Utility::getJsonResponse("?t=9B5BB2E7-F483-24CA-A725-55A304F628DE&m=" . $user->member()->targetObjectId(), $post_data);
        if ($controller->isError($repond_student_list)) {
            $repond_student_list->setPos(__FILE__, __LINE__);
            return $repond_student_list;
        }
        if ($teacher_member_list_flg) {
            $repond_teacher_list = Utility::getJsonResponse("?t=C381A56F-A88A-9D03-B33B-52030E5154DD&m=" . $user->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_teacher_list)) {
                $repond_teacher_list->setPos(__FILE__, __LINE__);
                return $repond_teacher_list;
            }
            $request->setAttribute("teacher_list", $repond_teacher_list["teacher_list"]);
        }
        if ($assign_member_list_flg) {
            $post_data["section"] = array(
                BroadcomMemberEntity::SECTION_1,
                BroadcomMemberEntity::SECTION_2,
                BroadcomMemberEntity::SECTION_5
            );
            $repond_member_list = Utility::getJsonResponse("?t=589049D8-F35C-2E6A-E792-D576E8002A2C&m=" . $user->member()->targetObjectId(), $post_data);
            if ($controller->isError($repond_member_list)) {
                $repond_member_list->setPos(__FILE__, __LINE__);
                return $repond_member_list;
            }
            $request->setAttribute("member_list", $repond_member_list["member_list"]);
        }
        $request->setAttribute("student_list", $repond_student_list["student_list"]);
        return VIEW_DONE;
    }

    private function _doOutputExecute(Controller $controller, User $user, Request $request)
    {
        $course_list = $request->getAttribute("course_list");
        $file_context = "学生姓名,电话,教务,合同号,订单所属人,课程名称,课程性质,确认收入,消课状态,年级,排课类型,科目,上课时间,下课时间,消课课时,任课教师,兼职/全职,教师所属校区,消课人,消课时间" . "\n";
        foreach ($course_list as $course_info) {
            $file_cols = array();
            $file_cols[] = $course_info["student_name"];
            $file_cols[] = $course_info["student_mobile_number"];
            $file_cols[] = $course_info["assign_member_name"];
            $file_cols[] = $course_info["contract_number"];
            $file_cols[] = $course_info["order_assign_member_name"];
            $file_cols[] = $course_info["item_name"];
            $file_cols[] = $course_info["course_type_name"];
            $file_cols[] = $course_info["course_trans_price"];
            $file_cols[] = $course_info["confirm_flg"] ? "已消课" : "未消课";
            $file_cols[] = $course_info["student_grade_name"];
            $file_cols[] = $course_info["course_detail_type_name"];
            $file_cols[] = $course_info["subject_name"];
            if ($course_info["confirm_flg"]) {
                $file_cols[] = substr($course_info["actual_start_date"], 0, 16);
                $file_cols[] = substr($course_info["actual_expire_date"], 0, 16);
                $file_cols[] = $course_info["actual_course_hours"];
            } else {
                $file_cols[] = substr($course_info["course_start_date"], 0, 16);
                $file_cols[] = substr($course_info["course_expire_date"], 0, 16);
                $file_cols[] = $course_info["course_hours"];
            }
            $file_cols[] = $course_info["teacher_member_name"];
            if ($course_info["teacher_member_name"] == BroadcomMemberEntity::POSITION_CONCURRENT_TEACHER) {
                $file_cols[] = "兼职";
            } else {
                $file_cols[] = "全职";
            }
            $file_cols[] = $course_info["teacher_school_name"];
            $file_cols[] = $course_info["confirm_member_name"];
            if ($course_info["confirm_flg"]) {
                $file_cols[] = substr($course_info["confirm_date"], 0, 16);
            } else {
                $file_cols[] = "";
            }
            $file_context .= implode(",", $file_cols) . "\n";
        }
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . date("排课列表_Y-m-d_H:i:s") . ".csv");
        header("Cache-Control: max-age=0");
        echo iconv("UTF-8", "GB2312", $file_context);
        exit;
    }
}
?>