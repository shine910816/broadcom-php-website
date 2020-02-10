<?php

/**
 * 公用方法类
 * @author Kinsama
 * @version 2017-02-20
 */
class Utility
{

    /**
     * 获取文件格式文件名
     *
     * @param string $name 参数格式文件名
     * @return string 文件格式文件名
     */
    public static function getFileFormatName($name)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }

    /**
     * 获取参数格式文件名
     *
     * @param string $name 文件格式文件名
     * @return string 参数格式文件名
     */
    public static function getParamFormatName($name)
    {
        $result = "";
        for ($i = 0; $i < strlen($name); $i++) {
            $str = substr($name, $i, 1);
            if (preg_match('/^[a-z0-9]$/', $str)) {
                $result .= $str;
            } else {
                $result .= "_" . strtolower($str);
            }
        }
        return ltrim($result, "_");
    }

    /**
     * 为字符串或数组添加索引标识
     *
     * @param string or array $value 字符串或数组
     * @param boolean $db_flg 数据库表名或字段名Flag
     * @return string or array
     */
    public static function quoteString($value, $db_flg = false)
    {
        if (is_array($value)) {
            foreach ($value as $arr_key => $arr_val) {
                $value[$arr_key] = self::quoteString($arr_val, $db_flg);
            }
        } else {
            if ($db_flg) {
                $value = '`' . $value . '`';
            } else {
                // if (strpos('"', $value) === false) {
                // $value = str_replace('"', '\\"', $value);
                // }
                $value = '"' . $value . '"';
            }
        }
        return $value;
    }

    /**
     * 按概率返回结果
     *
     * @param float $rate 概率(百分值)
     * @return boolean 符合概率范围内返回true，否则返回false
     */
    public static function getRateResult($rate)
    {
        $base_rate = floor($rate * 100);
        $rand_num = rand(1, 10000);
        if ($rand_num > $base_rate) {
            return false;
        }
        return true;
    }

    /**
     * 获取随机字符列
     *
     * @param int $length 长度
     * @param boolean $upper_flg 大写字母Flag
     * @return string
     */
    public static function getRandomString($length = 6, $upper_flg = false)
    {
        $number_list = "0123456789";
        $lower_list = "abcdefghijklmnopqrstuvwxyz";
        $upper_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $string_list = $number_list . $lower_list;
        if ($upper_flg) {
            $string_list .= $upper_list;
        }
        $random_string = "";
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($string_list) - 1);
            $random_string .= substr($string_list, $index, 1);
        }
        return $random_string;
    }

    /**
     * 获取随机验证码
     *
     * @param int $length
     * @return string
     */
    public static function getNumberCode($length = 6)
    {
        $number_list = "0123456789";
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($number_list) - 1);
            $result .= substr($number_list, $index, 1);
        }
        return $result;
    }

    public static function transSalt($code = null)
    {
        if (is_null($code)) {
            $code = self::getRandomString();
        }
        $md5_code = md5($code);
        $salt1 = substr($md5_code, 0, 16);
        $salt2 = substr($md5_code, 16, 16);
        return array(
            "code" => $code,
            "salt1" => $salt1,
            "salt2" => $salt2
        );
    }

    public static function sendToPhone($phone, $code, $template)
    {
        if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
            return true;
        }
        require_once SRC_PATH . "/driver/aliyun-dysms-php-sdk/Message.php";
        $result = Message::sendSms($phone, $code, $template);
        return $result->Code == "OK";
    }

    public static function sendToMail($mail_address, $title, $content)
    {
        if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
            return true;
        }
        require_once SRC_PATH . "/library/Mailer.php";
        $mailer = Mailer::getInstance();
        return $mailer->send($mail_address, $title, $content);
    }

    /**
     * 数字转文字
     *
     * @param int $number 数字
     * @param boolean $upper_flg 大写汉字判断
     * @return string
     */
    public static function getTransNumberInstance()
    {
        require_once SRC_PATH . "/library/Number.php";
        return new Number();
    }

    /**
     * 分页页面数据分割
     *
     * @param object $request Request对象
     * @param array $data 页面数据
     * @param string $url_page 页码链接URL
     * @param int $per_page 每页最大显示条目数
     * @return array or Error Object
     */
    public static function getPaginationData(Request $request, $data, $url_page, $per_page = DISPLAY_NUMBER_PER_PAGE)
    {
        $max_page = ceil(count($data) / $per_page);
        if ($max_page < 1) {
            $request->setAttribute("url_page", $url_page);
            $request->setAttribute("max_page", 1);
            return array();
        }
        $request->setAttribute("url_page", $url_page);
        $request->setAttribute("max_page", $max_page);
        $data_tmp = array_chunk($data, $per_page, true);
        if (!isset($data_tmp[$request->current_page - 1])) {
            $err = Error::getInstance();
            $err->raiseError(ERROR_CODE_USER_FALSIFY, "最大页码为" . $max_page . "，页码参数被窜改为" . $request->current_page);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        return $data_tmp[$request->current_page - 1];
    }

    public static function transContext($str)
    {
        $i_flg = false;
        $b_flg = false;
        $result = "";
        for ($i = 0; $i < mb_strlen($str, "utf-8"); $i++) {
            $word = mb_substr($str, $i, 1, "utf-8");
            if ($word == "_") {
                if ($i_flg) {
                    $result .= "</i>";
                    $i_flg = false;
                } else {
                    $result .= "<i>";
                    $i_flg = true;
                }
            } elseif ($word == "*") {
                if ($b_flg) {
                    $result .= "</b>";
                    $b_flg = false;
                } else {
                    $result .= "<b>";
                    $b_flg = true;
                }
            } else {
                $result .= $word;
            }
        }
        return $result;
    }

    public static function getPasswordSecurityLevel($password)
    {
        $ref_text = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()`~-_=+[{]}\\|;:'\",<.>/?";
        $ref = array();
        for ($j = 0; $j < strlen($ref_text); $j++) {
            $ref[] = substr($ref_text, $j, 1);
        }
        $length = mb_strlen($password, "utf-8");
        $password_arr = array();
        for ($i = 0; $i < $length; $i++) {
            $password_arr[] = mb_substr($password, $i, 1, "utf-8");
        }
        $result = 0;
        foreach ($password_arr as $password_item) {
            if (!in_array($password_item, $ref)) {
                return 0;
            }
            $result++;
        }
        return $result;
    }

    public static function encodeCookieInfo($param)
    {
        if (!is_array($param)) {
            $param = array(
                $param
            );
        }
        foreach ($param as $key => $val) {
            $param[$key] = urlencode($val);
        }
        $result = base64_encode(json_encode($param));
        $result = rtrim($result, "=");
        $result = str_replace("+", "-", $result);
        $result = str_replace("/", "_", $result);
        return $result;
    }

    public static function decodeCookieInfo($param)
    {
        $param = str_replace("-", "+", $param);
        $param = str_replace("_", "/", $param);
        $result = json_decode(base64_decode($param), true);
        foreach ($result as $key => $val) {
            $result[$key] = urldecode($val);
        }
        return $result;
    }

    public static function transJson($json_path)
    {
        $json_header = get_headers($json_path);
        if (strpos($json_header[0], "200 OK") === false) {
            $err = Error::getInstance();
            $err->raiseError(ERROR_CODE_THIRD_ERROR_FALSIFY, "地址无效: " . $json_path);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        $json_array = json_decode(file_get_contents($json_path), true);
        if (empty($json_array)) {
            $err = Error::getInstance();
            $err->raiseError(ERROR_CODE_THIRD_ERROR_FALSIFY, "JSON内容无效: " . $json_path);
            $err->setPos(__FILE__, __LINE__);
            return $err;
        }
        return $json_array;
    }

    public static function getBirthInfo($custom_birth)
    {
        $result = array();
        $birthday = new DateTime($custom_birth);
        $month_day = $birthday->format("nd");
        $now_month_day = date("nd");
        $result["age"] = date("Y") - $birthday->format("Y");
        if ($now_month_day < $month_day) {
            $result["age"] -= 1;
        }
        if ($month_day >= 321 && $month_day <= 419) {
            $result["con"] = "白羊座&#9800;";
        }
        if ($month_day >= 420 && $month_day <= 520) {
            $result["con"] = "金牛座&#9801;";
        }
        if ($month_day >= 521 && $month_day <= 621) {
            $result["con"] = "双子座&#9802;";
        }
        if ($month_day >= 622 && $month_day <= 722) {
            $result["con"] = "巨蟹座&#9803;";
        }
        if ($month_day >= 723 && $month_day <= 822) {
            $result["con"] = "狮子座&#9804;";
        }
        if ($month_day >= 823 && $month_day <= 922) {
            $result["con"] = "处女座&#9805;";
        }
        if ($month_day >= 923 && $month_day <= 1023) {
            $result["con"] = "天秤座&#9806;";
        }
        if ($month_day >= 1024 && $month_day <= 1122) {
            $result["con"] = "天蝎座&#9807;";
        }
        if ($month_day >= 1123 && $month_day <= 1221) {
            $result["con"] = "射手座&#9808;";
        }
        if ($month_day >= 1222 || $month_day <= 119) {
            $result["con"] = "摩羯座&#9809;";
        }
        if ($month_day >= 120 && $month_day <= 218) {
            $result["con"] = "水瓶座&#9810;";
        }
        if ($month_day >= 219 && $month_day <= 320) {
            $result["con"] = "双鱼座&#9811;";
        }
        return $result;
    }

    public static function getDateWeek($date_str)
    {
        return date("oW", strtotime($date_str));
        //$date_ts = strtotime($date_str);
        //$year = date("Y", $date_ts);
        //$month = date("n", $date_ts);
        //$week = date("W", $date_ts);
        //if ($week == "01" && $month == "12") {
        //    $year++;
        //}
        //if ($week == "53" && $month == "1") {
        //    $year--;
        //}
        //return $year . $week;
    }

    public static function getWeeklyList($param_year)
    {
        $days_max = 365;
        if (date("L", strtotime($param_year . "-01-01 00:00:00")) == "1") {
            $days_max = 366;
        }
        $result = array(
            "week" => array(),
            "day" => array()
        );
        for ($i = -6; $i <= $days_max + 7; $i++) {
            $current_time = mktime(0, 0, 0, 1, $i, $param_year);
            $column_key = date("oW", $current_time);
            $column_value = date("Ymd", $current_time);
            $result["week"][$column_key][] = $column_value;
            $result["day"][$column_value] = $column_key;
        }
        return $result;
    }

    public static function getContentTypeList()
    {
        return array(
            "001" => "application/x-001",
            "301" => "application/x-301",
            "323" => "text/h323",
            "906" => "application/x-906",
            "907" => "drawing/907",
            "a11" => "application/x-a11",
            "acp" => "audio/x-mei-aac",
            "ai" => "application/postscript",
            "aif" => "audio/aiff",
            "aifc" => "audio/aiff",
            "aiff" => "audio/aiff",
            "anv" => "application/x-anv",
            "apk" => "application/vnd.android.package-archive",
            "asa" => "text/asa",
            "asf" => "video/x-ms-asf",
            "asp" => "text/asp",
            "asx" => "video/x-ms-asf",
            "au" => "audio/basic",
            "avi" => "video/avi",
            "awf" => "application/vnd.adobe.workflow",
            "biz" => "text/xml",
            "bmp" => "application/x-bmp",
            "bot" => "application/x-bot",
            "c4t" => "application/x-c4t",
            "c90" => "application/x-c90",
            "cal" => "application/x-cals",
            "cat" => "application/vnd.ms-pki.seccat",
            "cdf" => "application/x-netcdf",
            "cdr" => "application/x-cdr",
            "cel" => "application/x-cel",
            "cer" => "application/x-x509-ca-cert",
            "cg4" => "application/x-g4",
            "cgm" => "application/x-cgm",
            "cit" => "application/x-cit",
            "class" => "java/*",
            "cml" => "text/xml",
            "cmp" => "application/x-cmp",
            "cmx" => "application/x-cmx",
            "cot" => "application/x-cot",
            "crl" => "application/pkix-crl",
            "crt" => "application/x-x509-ca-cert",
            "csi" => "application/x-csi",
            "css" => "text/css",
            "cut" => "application/x-cut",
            "dbf" => "application/x-dbf",
            "dbm" => "application/x-dbm",
            "dbx" => "application/x-dbx",
            "dcd" => "text/xml",
            "dcx" => "application/x-dcx",
            "der" => "application/x-x509-ca-cert",
            "dgn" => "application/x-dgn",
            "dib" => "application/x-dib",
            "dll" => "application/x-msdownload",
            "doc" => "application/msword",
            "dot" => "application/msword",
            "drw" => "application/x-drw",
            "dtd" => "text/xml",
            "dwf" => "application/x-dwf",
            "dwg" => "application/x-dwg",
            "dxb" => "application/x-dxb",
            "dxf" => "application/x-dxf",
            "edn" => "application/vnd.adobe.edn",
            "emf" => "application/x-emf",
            "eml" => "message/rfc822",
            "ent" => "text/xml",
            "epi" => "application/x-epi",
            "eps" => "application/postscript",
            "etd" => "application/x-ebx",
            "exe" => "application/x-msdownload",
            "fax" => "image/fax",
            "fdf" => "application/vnd.fdf",
            "fif" => "application/fractals",
            "fo" => "text/xml",
            "frm" => "application/x-frm",
            "g4" => "application/x-g4",
            "gbr" => "application/x-gbr",
            "gif" => "image/gif",
            "gl2" => "application/x-gl2",
            "gp4" => "application/x-gp4",
            "hgl" => "application/x-hgl",
            "hmr" => "application/x-hmr",
            "hpg" => "application/x-hpgl",
            "hpl" => "application/x-hpl",
            "hqx" => "application/mac-binhex40",
            "hrf" => "application/x-hrf",
            "hta" => "application/hta",
            "htc" => "text/x-component",
            "htm" => "text/html",
            "html" => "text/html",
            "htt" => "text/webviewhtml",
            "htx" => "text/html",
            "icb" => "application/x-icb",
            "ico" => "application/x-ico",
            "iff" => "application/x-iff",
            "ig4" => "application/x-g4",
            "igs" => "application/x-igs",
            "iii" => "application/x-iphone",
            "img" => "application/x-img",
            "ins" => "application/x-internet-signup",
            "ipa" => "application/vnd.iphone",
            "isp" => "application/x-internet-signup",
            "ivf" => "video/x-ivf",
            "java" => "java/*",
            "jfif" => "image/jpeg",
            "jpe" => "application/x-jpe",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "js" => "application/x-javascript",
            "jsp" => "text/html",
            "la1" => "audio/x-liquid-file",
            "lar" => "application/x-laplayer-reg",
            "latex" => "application/x-latex",
            "lavs" => "audio/x-liquid-secure",
            "lbm" => "application/x-lbm",
            "lmsff" => "audio/x-la-lms",
            "ls" => "application/x-javascript",
            "ltr" => "application/x-ltr",
            "m1v" => "video/x-mpeg",
            "m2v" => "video/x-mpeg",
            "m3u" => "audio/mpegurl",
            "m4e" => "video/mpeg4",
            "mac" => "application/x-mac",
            "man" => "application/x-troff-man",
            "math" => "text/xml",
            "mdb" => "application/x-mdb",
            "mfp" => "application/x-shockwave-flash",
            "mht" => "message/rfc822",
            "mhtml" => "message/rfc822",
            "mi" => "application/x-mi",
            "mid" => "audio/mid",
            "midi" => "audio/mid",
            "mil" => "application/x-mil",
            "mml" => "text/xml",
            "mnd" => "audio/x-musicnet-download",
            "mns" => "audio/x-musicnet-stream",
            "mocha" => "application/x-javascript",
            "movie" => "video/x-sgi-movie",
            "mp1" => "audio/mp1",
            "mp2" => "audio/mp2",
            "mp2v" => "video/mpeg",
            "mp3" => "audio/mp3",
            "mp4" => "video/mpeg4",
            "mpa" => "video/x-mpg",
            "mpd" => "application/vnd.ms-project",
            "mpe" => "video/x-mpeg",
            "mpeg" => "video/mpg",
            "mpg" => "video/mpg",
            "mpga" => "audio/rn-mpeg",
            "mpp" => "application/vnd.ms-project",
            "mps" => "video/x-mpeg",
            "mpt" => "application/vnd.ms-project",
            "mpv" => "video/mpg",
            "mpv2" => "video/mpeg",
            "mpw" => "application/vnd.ms-project",
            "mpx" => "application/vnd.ms-project",
            "mtx" => "text/xml",
            "mxp" => "application/x-mmxp",
            "net" => "image/pnetvue",
            "nrf" => "application/x-nrf",
            "nws" => "message/rfc822",
            "odc" => "text/x-ms-odc",
            "out" => "application/x-out",
            "p10" => "application/pkcs10",
            "p12" => "application/x-pkcs12",
            "p7b" => "application/x-pkcs7-certificates",
            "p7c" => "application/pkcs7-mime",
            "p7m" => "application/pkcs7-mime",
            "p7r" => "application/x-pkcs7-certreqresp",
            "p7s" => "application/pkcs7-signature",
            "pc5" => "application/x-pc5",
            "pci" => "application/x-pci",
            "pcl" => "application/x-pcl",
            "pcx" => "application/x-pcx",
            "pdf" => "application/pdf",
            "pdx" => "application/vnd.adobe.pdx",
            "pfx" => "application/x-pkcs12",
            "pgl" => "application/x-pgl",
            "pic" => "application/x-pic",
            "pko" => "application/vnd.ms-pki.pko",
            "pl" => "application/x-perl",
            "plg" => "text/html",
            "pls" => "audio/scpls",
            "plt" => "application/x-plt",
            "png" => "image/png",
            "pot" => "application/vnd.ms-powerpoint",
            "ppa" => "application/vnd.ms-powerpoint",
            "ppm" => "application/x-ppm",
            "pps" => "application/vnd.ms-powerpoint",
            "ppt" => "application/vnd.ms-powerpoint",
            "pr" => "application/x-pr",
            "prf" => "application/pics-rules",
            "prn" => "application/x-prn",
            "prt" => "application/x-prt",
            "ps" => "application/x-ps",
            "ptn" => "application/x-ptn",
            "pwz" => "application/vnd.ms-powerpoint",
            "r3t" => "text/vnd.rn-realtext3d",
            "ra" => "audio/vnd.rn-realaudio",
            "ram" => "audio/x-pn-realaudio",
            "ras" => "application/x-ras",
            "rat" => "application/rat-file",
            "rdf" => "text/xml",
            "rec" => "application/vnd.rn-recording",
            "red" => "application/x-red",
            "rgb" => "application/x-rgb",
            "rjs" => "application/vnd.rn-realsystem-rjs",
            "rjt" => "application/vnd.rn-realsystem-rjt",
            "rlc" => "application/x-rlc",
            "rle" => "application/x-rle",
            "rm" => "application/vnd.rn-realmedia",
            "rmf" => "application/vnd.adobe.rmf",
            "rmi" => "audio/mid",
            "rmj" => "application/vnd.rn-realsystem-rmj",
            "rmm" => "audio/x-pn-realaudio",
            "rmp" => "application/vnd.rn-rn_music_package",
            "rms" => "application/vnd.rn-realmedia-secure",
            "rmvb" => "application/vnd.rn-realmedia-vbr",
            "rmx" => "application/vnd.rn-realsystem-rmx",
            "rnx" => "application/vnd.rn-realplayer",
            "rp" => "image/vnd.rn-realpix",
            "rpm" => "audio/x-pn-realaudio-plugin",
            "rsml" => "application/vnd.rn-rsml",
            "rt" => "text/vnd.rn-realtext",
            "rtf" => "application/x-rtf",
            "rv" => "video/vnd.rn-realvideo",
            "sam" => "application/x-sam",
            "sat" => "application/x-sat",
            "sdp" => "application/sdp",
            "sdw" => "application/x-sdw",
            "sis" => "application/vnd.symbian.install",
            "sisx" => "application/vnd.symbian.install",
            "sit" => "application/x-stuffit",
            "slb" => "application/x-slb",
            "sld" => "application/x-sld",
            "slk" => "drawing/x-slk",
            "smi" => "application/smil",
            "smil" => "application/smil",
            "smk" => "application/x-smk",
            "snd" => "audio/basic",
            "sol" => "text/plain",
            "sor" => "text/plain",
            "spc" => "application/x-pkcs7-certificates",
            "spl" => "application/futuresplash",
            "spp" => "text/xml",
            "ssm" => "application/streamingmedia",
            "sst" => "application/vnd.ms-pki.certstore",
            "stl" => "application/vnd.ms-pki.stl",
            "stm" => "text/html",
            "sty" => "application/x-sty",
            "svg" => "text/xml",
            "swf" => "application/x-shockwave-flash",
            "tdf" => "application/x-tdf",
            "tg4" => "application/x-tg4",
            "tga" => "application/x-tga",
            "tif" => "application/x-tif",
            "tiff" => "image/tiff",
            "tld" => "text/xml",
            "top" => "drawing/x-top",
            "torrent" => "application/x-bittorrent",
            "tsd" => "text/xml",
            "txt" => "text/plain",
            "uin" => "application/x-icq",
            "uls" => "text/iuls",
            "vcf" => "text/x-vcard",
            "vda" => "application/x-vda",
            "vdx" => "application/vnd.visio",
            "vml" => "text/xml",
            "vpg" => "application/x-vpeg005",
            "vsd" => "application/vnd.visio",
            "vss" => "application/vnd.visio",
            "vst" => "application/x-vst",
            "vsw" => "application/vnd.visio",
            "vsx" => "application/vnd.visio",
            "vtx" => "application/vnd.visio",
            "vxml" => "text/xml",
            "wav" => "audio/wav",
            "wax" => "audio/x-ms-wax",
            "wb1" => "application/x-wb1",
            "wb2" => "application/x-wb2",
            "wb3" => "application/x-wb3",
            "wbmp" => "image/vnd.wap.wbmp",
            "wiz" => "application/msword",
            "wk3" => "application/x-wk3",
            "wk4" => "application/x-wk4",
            "wkq" => "application/x-wkq",
            "wks" => "application/x-wks",
            "wm" => "video/x-ms-wm",
            "wma" => "audio/x-ms-wma",
            "wmd" => "application/x-ms-wmd",
            "wmf" => "application/x-wmf",
            "wml" => "text/vnd.wap.wml",
            "wmv" => "video/x-ms-wmv",
            "wmx" => "video/x-ms-wmx",
            "wmz" => "application/x-ms-wmz",
            "wp6" => "application/x-wp6",
            "wpd" => "application/x-wpd",
            "wpg" => "application/x-wpg",
            "wpl" => "application/vnd.ms-wpl",
            "wq1" => "application/x-wq1",
            "wr1" => "application/x-wr1",
            "wri" => "application/x-wri",
            "wrk" => "application/x-wrk",
            "ws" => "application/x-ws",
            "ws2" => "application/x-ws",
            "wsc" => "text/scriptlet",
            "wsdl" => "text/xml",
            "wvx" => "video/x-ms-wvx",
            "x_b" => "application/x-x_b",
            "x_t" => "application/x-x_t",
            "xap" => "application/x-silverlight-app",
            "xdp" => "application/vnd.adobe.xdp",
            "xdr" => "text/xml",
            "xfd" => "application/vnd.adobe.xfd",
            "xfdf" => "application/vnd.adobe.xfdf",
            "xhtml" => "text/html",
            "xls" => "application/vnd.ms-excel",
            "xlw" => "application/x-xlw",
            "xml" => "text/xml",
            "xpl" => "audio/scpls",
            "xq" => "text/xml",
            "xql" => "text/xml",
            "xquery" => "text/xml",
            "xsd" => "text/xml",
            "xsl" => "text/xml",
            "xslt" => "text/xml",
            "xwd" => "application/x-xwd"
        );
    }

    /**
     * 测试变量
     *
     * @param mixed $data 变量
     * @param boolean $disp_flg 表示形式
     */
    public static function testVariable($data, $disp_flg = false)
    {
        if ($disp_flg) {
            header("Content-Type:text/html; charset=utf-8");
            var_dump($data);
        } else {
            header("Content-Type:text/plain; charset=utf-8");
            print_r($data);
        }
        exit();
    }
}
?>