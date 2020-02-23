<?php

/**
 * 数据库操作类-room_info
 * @author Kinsama
 * @version 2020-02-22
 */
class BroadcomRoomInfoDBI
{

    public static function selectRoomList($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM room_info WHERE del_flg = 0 AND school_id = " . $school_id;
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["room_id"]] = $row;
        }
        $result->free();
        return $data;
    }

    public static function selectUsableRoomList($school_id)
    {
        $dbi = Database::getInstance();
        $sql = "SELECT * FROM room_info WHERE del_flg = 0" .
               " AND school_id = " . $school_id .
               " AND usable_flg = 1";
        $result = $dbi->query($sql);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[$row["room_id"]] = $row["room_name"];
        }
        $result->free();
        return $data;
    }

    public static function insertRoom($insert_data)
    {
        $dbi = Database::getInstance();
        $result = $dbi->insert("room_info", $insert_data);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }

    public static function updateRoom($update_data, $room_id)
    {
        $dbi = Database::getInstance();
        $result = $dbi->update("room_info", $update_data, "room_id = " . $room_id);
        if ($dbi->isError($result)) {
            $result->setPos(__FILE__, __LINE__);
            return $result;
        }
        return $result;
    }
}
?>