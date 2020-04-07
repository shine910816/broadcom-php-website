<?php
class Authority
{

    private $_allow_list;
    private $_authority_list;

    public function __construct()
    {
        $xml = simplexml_load_file(SRC_PATH . "/config/authority.xml");
        $result = array();
        foreach ($xml->functions->function as $function_info) {
            $menu_act_info = (array) $function_info->attributes();
            $auth_able_array = array();
            foreach ($function_info->position as $pos_tmp) {
                $position = (array) $pos_tmp;
                //if ($position["@attributes"]["value"] == $member_position) {
                //    $auth_able_array["read"] = $position["@attributes"]["readable"];
                //    $auth_able_array["edit"] = $position["@attributes"]["editable"];
                //    break;
                //} else {
                //    continue;
                //}
                $result = array(
                    "read" => false,
                    "edit" => false,
                );
                if ($position["@attributes"]["readable"] == "1") {
                    $result["read"] = true;
                }
                if ($result["read"] && $position["@attributes"]["editable"] == "1") {
                    $result["edit"] = true;
                }
                $this->_authority_list[$menu_act_info["@attributes"]["menu"]][$menu_act_info["@attributes"]["act"]][$position["@attributes"]["value"]] = $result;
            }
            //if ($api_flg) {
            //    $result[$menu_act_info["@attributes"]["token"]] = $auth_able_array;
            //} else {
            //    $result[$menu_act_info["@attributes"]["menu"]][$menu_act_info["@attributes"]["act"]] = $auth_able_array;
            //}
            $this->_allow_list[$menu_act_info["@attributes"]["token"]] = array(
                "menu" => $menu_act_info["@attributes"]["menu"],
                "act" => $menu_act_info["@attributes"]["act"]
            );
        }
    }

    public function getTokenMenuAct($token)
    {
        if (isset($this->_allow_list[$token])) {
            return $this->_allow_list[$token];
        }
        return array(
            "menu" => SYSTEM_DEFAULT_MENU,
            "act" => SYSTEM_DEFAULT_ACT
        );
    }

    public static function getInstance()
    {
        return new Authority();
    }
}
?>