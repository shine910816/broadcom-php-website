<?php
/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-08-21
 */
class BroadcomHistoryDBI
{
    public static function selectStudentHistory($student_id)
    {
        $dbi = Database::getInstance();
        $result = self::_selectHistory(BroadcomHistoryEntity::HISTORY_TYPE_STUDENT, $student_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function insertStudentHistory($student_id, $data_list)
    {
        $dbi = Database::getInstance();
        $result = self::_insertHistory(BroadcomHistoryEntity::HISTORY_TYPE_STUDENT, $student_id, $data_list);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    private static function _selectHistory($history_type, $temp_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT history_id,"
               " group_sign," .
               " update_name," .
               " update_old_content," .
               " update_new_content," .
               " operated_by," .
               " insert_date" .
               " FROM history_info" .
               " WHERE del_flg = 0" .
               " AND history_type = " . BroadcomHistoryEntity::HISTORY_TYPE_STUDENT;
        switch ($history_type) {
            case BroadcomHistoryEntity::HISTORY_TYPE_STUDENT:
                $sql .= " AND student_id = " . $temp_id;
                break;
            case BroadcomHistoryEntity::HISTORY_TYPE_ORDER:
                $sql .= " AND order_id = " . $temp_id;
                break;
            case BroadcomHistoryEntity::HISTORY_TYPE_ORDER_ITEM:
                $sql .= " AND order_item_id = " . $temp_id;
                break;
            case BroadcomHistoryEntity::HISTORY_TYPE_COURSE:
                $sql .= " AND course_id = " . $temp_id;
                break;
            default:
            case BroadcomHistoryEntity::HISTORY_TYPE_MEMBER:
                $sql .= " AND member_id = " . $temp_id;
                break;
        }
        $sql .= " ORDER BY history_id ASC";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            if (!isset($data[$row["group_sign"]])) {
                $data[$row["group_sign"]] = array(
                    "created_id" => $row["operated_by"],
                    "created_date" => $row["insert_date"],
                    "history_count" => 0,
                    "history_detail" => array()
                );
            }
            $data[$row["group_sign"]]["history_detail"][$row["history_id"]] = array(
                "name" => $row["update_name"],
                "old" => $row["update_old_content"],
                "new" => $row["update_new_content"]
            );
            $data[$row["group_sign"]]["history_count"]++;
        }
        $result->free();
        return $data;
    }

    private static function _insertHistory($history_type, $temp_id, $data_list)
    {
        $dbi = Database::getInstance();
        $group_sign = md5("member" . time());
        $main_key = "member_id";
        switch ($history_type) {
            case BroadcomHistoryEntity::HISTORY_TYPE_STUDENT:
                $group_sign = md5("student" . time());
                $main_key = "student_id";
                break;
            case BroadcomHistoryEntity::HISTORY_TYPE_ORDER:
                $group_sign = md5("order" . time());
                $main_key = "order_id";
                break;
            case BroadcomHistoryEntity::HISTORY_TYPE_ORDER_ITEM:
                $group_sign = md5("orderitem" . time());
                $main_key = "order_item_id";
                break;
            case BroadcomHistoryEntity::HISTORY_TYPE_COURSE:
                $group_sign = md5("course" . time());
                $main_key = "course_id";
                break;
            default:
            case BroadcomHistoryEntity::HISTORY_TYPE_MEMBER:
                break;
        }
        foreach ($data_list as $data_item) {
            $insert_data = array();
            $insert_data["history_type"] = $history_type;
            $insert_data[$main_key] = $temp_id;
            $insert_data["group_sign"] = $group_sign;
            $insert_data["update_name"] = $data_item["name"];
            $insert_data["update_old_content"] = $data_item["old"];
            $insert_data["update_new_content"] = $data_item["new"];
            $result = $dbi->insert("history_info", $insert_data);
            if ($dbi->isError($result)) {
                $result->setPos(__FILE__, __LINE__);
                return $result;
            }
        }
        return $result;
    }
}
?>