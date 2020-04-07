<?php

/**
 * 数据库控制器
 * @author Kinsama
 * @version 2016-12-30
 */
class Database
{

    /**
     * 数据库连接标识
     *
     * @access private
     */
    private $con;

    /**
     * 数据库文字编码
     *
     * @access private
     */
    private $charset = 'utf8';

    /**
     * 初始化
     */
    public function __construct()
    {
        // 连接数据库
        require_once SRC_PATH . "/config/ConnectInfo.php";
        $connect_info = new ConnectInfo();
        $this->con = mysqli_connect(
            $connect_info->host(),
            $connect_info->user(),
            $connect_info->password(),
            $connect_info->databaseName(),
            $connect_info->port()
        );
        if (!$this->con) {
            exit("Not connect to database !");
        }
        // 设置文字编码
        mysqli_query($this->con, 'set names ' . $this->charset);
        return;
    }

    /**
     * 插入数据
     *
     * @param string $tableName 表名
     * @param array $insertData 数据
     * @return boolean
     */
    public function insert($tableName, $insertData)
    {
        if (empty($tableName) || !is_array($insertData)) {
            $error = Error::getInstance();
            $error->raiseError(ERROR_CODE_DATABASE_PARAM);
            $error->setPos(__FILE__, __LINE__);
            return $error;
        }
        $now_date = date("Y-m-d H:i:s");
        $operated_by = "0";
        if (!isset($insertData["operated_by"]) && isset($_SESSION["member_id"])) {
            $operated_by = $_SESSION["member_id"];
        }
        $insertData['operated_by'] = $operated_by;
        $insertData['insert_date'] = $now_date;
        $insertData['update_date'] = $now_date;
        $insertData['del_flg'] = 0;
        foreach ($insertData as $data_key => $data_value) {
            if ($data_value === null) {
                $insertData[$data_key] = "NULL";
            } else {
                $insertData[$data_key] = $this->quote($data_value);
            }
        }
        $sql_key = implode(", ", $this->quote(array_keys($insertData), true));
        $sql_val = implode(", ", $insertData);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s);", $this->quote($tableName, true), $sql_key, $sql_val);
        return $this->_query($sql);
    }

    /**
     * 修改数据
     *
     * @param string $tableName 表名
     * @param array $updateData 数据
     * @param string $where 条件
     * @return boolean
     */
    public function update($tableName, $updateData, $where)
    {
        if ($tableName == null || !is_array($updateData) || $where == null) {
            $error = Error::getInstance();
            $error->raiseError(ERROR_CODE_DATABASE_PARAM);
            $error->setPos(__FILE__, __LINE__);
            return $error;
        }
        $updateData['update_date'] = date("Y-m-d H:i:s");
        $update = array();
        foreach ($updateData as $dk => $dv) {
            $update[] = sprintf('%s = %s', $this->quote($dk, true), ($dv === null) ? "NULL" : $this->quote($dv));
        }
        $update_list = implode(", ", $update);
        $sql = sprintf("UPDATE %s SET %s WHERE %s;", $this->quote($tableName, true), $update_list, $where);
        return $this->_query($sql);
    }

    /**
     * 删除数据
     *
     * @param string $tableName 表名
     * @param string $where 条件
     * @return boolean
     */
    public function delete($tableName, $where)
    {
        if ($tableName == null || $where == null) {
            $error = Error::getInstance();
            $error->raiseError(ERROR_CODE_DATABASE_PARAM);
            $error->setPos(__FILE__, __LINE__);
            return $error;
        }
        $sql = sprintf("DELETE FROM %s WHERE %s;", $this->quote($tableName, true), $where);
        return $this->_query($sql);
    }

    /**
     * 查询数据
     *
     * @param string $sql SQL语句
     * @return mixed
     */
    public function query($sql)
    {
        if ($sql == null) {
            return false;
        }
        return $this->_query($sql);
    }

    /**
     * 开始事件
     *
     * @return mixed
     */
    public function begin()
    {
        return $this->_query("BEGIN;");
    }

    /**
     * 终止事件
     *
     * @return mixed
     */
    public function rollback()
    {
        return $this->_query("ROLLBACK;");
    }

    /**
     * 提交事件
     *
     * @return mixed
     */
    public function commit()
    {
        return $this->_query("COMMIT;");
    }

    /**
     * 为字符串或数组添加索引标识
     *
     * @param string or array $value 字符串或数组
     * @param boolean $db_flg 数据库表名或字段名Flag
     * @return string or array
     */
    public function quote($value, $db_flg = false)
    {
        return Utility::quoteString($value, $db_flg);
    }

    /**
     * 判断对象是否为错误对象
     *
     * @param object $obj 判断对象
     * @return boolean
     */
    public function isError($obj)
    {
        return Error::isError($obj);
    }

    /**
     * 查询符合条件的条目数
     *
     * @param string $tableName 表名
     * @param string $where 条件
     * @return boolean
     */
    public function check($tableName, $where)
    {
        if ($where == null || $tableName == null) {
            return false;
        }
        $sql = sprintf("SELECT COUNT(*) FROM `%s` WHERE %s", $tableName, $where);
        $result = $this->_query($sql);
        $data = $result->fetch_row();
        return $data[0];
    }

    /**
     * 执行并记录SQL语句
     *
     * @access private
     * @param string $sql SQL语句
     * @return object boolean
     */
    private function _query($sql)
    {
        $sql = trim($sql, ' ');
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $error = Error::getInstance();
            $error->raiseError(ERROR_CODE_DATABASE_RESULT, mysqli_error($this->con) . ", the sql text: " . $sql);
            return $error;
        }
        $do_main = substr($sql, 0, 6);
        switch ($do_main) {
            case "INSERT":
                $this->_logSqlText($sql, true);
                $tmp_res = mysqli_query($this->con, "SELECT LAST_INSERT_ID()");
                $res = $tmp_res->fetch_row();
                return $res[0];
            case "UPDATE":
            case "DELETE":
                $this->_logSqlText($sql, true);
                return mysqli_affected_rows($this->con);
            case "BEGIN;":
            case "ROLLBA":
            case "COMMIT":
                $this->_logSqlText($sql, true);
                return $result;
            default:
                if (TEST_MODE_FLAG) {
                    $this->_logSqlText($sql);
                }
                return $result;
        }
    }

    /**
     * 记录SQL语句
     *
     * @access private
     * @param string $sql SQL语句
     */
    private function _logSqlText($sql, $log_flg = false)
    {
        $file_path = SRC_PATH . "/temp/log";
        if (!file_exists($file_path)) {
            mkdir($file_path, 0777, true);
        }
        if ($log_flg) {
            $file_name = "/" . date("oW") . ".sql";
            $fo = fopen($file_path . $file_name, "a");
        } else {
            $fo = fopen($file_path . "/sqltext.log", "a");
        }
        $text = $sql . "\n";
        fwrite($fo, $text);
        fclose($fo);
        return;
    }

    /**
     * 获取数据库链接
     *
     * @return object
     */
    public static function getInstance()
    {
        return new Database();
    }
}
?>