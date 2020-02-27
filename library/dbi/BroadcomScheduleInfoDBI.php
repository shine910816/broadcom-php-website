<?php

/**
 * 数据库操作类-schedule_info
 * @author Kinsama
 * @version 2020-02-23
 */
class BroadcomScheduleInfoDBI
{

    public static function selectPeriodScheduleByItem($school_id, $item_id, $start_date)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM schedule_info WHERE del_flg = 0" .
               " AND school_id = " . $school_id .
               " AND item_id = " . $item_id .
               " AND schedule_start_date >= " . $dbi->quote($start_date);
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["schedule_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectPeriodSchedule($school_id, $expire_date, $before_flg = false)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM schedule_info WHERE del_flg = 0 AND school_id = " . $school_id;
        if ($before_flg) {
            $sql .= " AND schedule_expire_date <= " . $dbi->quote($expire_date);
        } else {
            $sql .= " AND schedule_expire_date >= " . $dbi->quote($expire_date);
        }
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["schedule_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function insertScheduleInfo($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("schedule_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateScheduleInfo($update_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("schedule_info", $update_data, "schedule_id = " . $schedule_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>