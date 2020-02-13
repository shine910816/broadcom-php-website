<?php
require_once SRC_PATH . "/menu/Admin/lib/BroadcomAdminActionBase.php";

/**
 * 课程编辑画面
 * @author Kinsama
 * @version 2020-02-10
 */
class BroadcomAdmin_ItemInputAction extends BroadcomAdminActionBase
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
        $request->setAttribute("item_type_list", BroadcomItemEntity::getItemTypeList());
        $request->setAttribute("item_method_list", BroadcomItemEntity::getItemMethodList());
        $request->setAttribute("item_grade_list", BroadcomItemEntity::getItemGradeList());
        $request->setAttribute("subject_list", BroadcomSubjectEntity::getSubjectList());
        $item_info = array(
            "item_name" => "",
            "item_type" => BroadcomItemEntity::ITEM_TYPE_NORMAL,
            "item_method" => BroadcomItemEntity::ITEM_METHOD_CLASS,
            "item_grade" => BroadcomItemEntity::ITEM_GRADE_TOTAL,
            "item_labels" => array(
                BroadcomSubjectEntity::SUBJECT_CHINESE,
                BroadcomSubjectEntity::SUBJECT_MATHS,
                BroadcomSubjectEntity::SUBJECT_ENGLISH,
                BroadcomSubjectEntity::SUBJECT_HISTORY,
                BroadcomSubjectEntity::SUBJECT_POLITICS,
                BroadcomSubjectEntity::SUBJECT_GEOGRAPHY,
                BroadcomSubjectEntity::SUBJECT_PHYSICS,
                BroadcomSubjectEntity::SUBJECT_CHEMISTRY,
                BroadcomSubjectEntity::SUBJECT_BIOLOGY
            ),
            "item_price" => "0",
            "item_unit_amount" => "4",
            "item_unit_hour" => "2",
            "item_desc" => ""
        );
        if ($request->hasParameter("do_create")) {
            $item_info = $request->getParameter("item_info");
            if (!Validate::checkFullNotNull($item_info["item_name"])) {
                $request->setError("item_name", "课程名不能为空");
            }
            if (!isset($item_info["item_labels"])) {
                $item_info["item_labels"] = array();
            }
        }
        $request->setAttribute("item_info", $item_info);
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
        $item_info = $request->getAttribute("item_info");
        if ($item_info["item_method"] != BroadcomItemEntity::ITEM_METHOD_CLASS) {
            $item_info["item_unit"] = BroadcomItemEntity::ITEM_UNIT_HOURS;
            $item_info["item_unit_amount"] = "0";
            $item_info["item_unit_hour"] = "0";
        } else {
            $item_info["item_unit"] = BroadcomItemEntity::ITEM_UNIT_PERIOD;
        }
        $item_info["item_sale_status"] = BroadcomItemEntity::ITEM_SALE_ON;
        if (isset($item_info["item_labels"])) {
            $item_info["item_labels"] = implode(",", $item_info["item_labels"]);
        } else {
            $item_info["item_labels"] = "";
        }
        $insert_res = BroadcomItemInfoDBI::insertSchoolInfo($item_info);
        if ($controller->isError($insert_res)) {
            $insert_res->setPos(__FILE__, __LINE__);
            return $insert_res;
        }
        $controller->redirect("?menu=admin&act=item_list");
        return VIEW_DONE;
    }
}
?>