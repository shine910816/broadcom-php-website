<?php
class Authority
{
    public function _construct()
    {
        $xml = simplexml_load_file(SRC_PATH . "/config/authority.xml");
        $result = array();
        foreach ($xml->functions->function as $function_info) {
            $menu_act_info = (array) $function_info->attributes();
            $auth_able_array = array();
            foreach ($function_info->position as $pos_tmp) {
                $position = (array) $pos_tmp;
                if ($position["@attributes"]["value"] == $member_position) {
                    $auth_able_array["read"] = $position["@attributes"]["readable"];
                    $auth_able_array["edit"] = $position["@attributes"]["editable"];
                    break;
                } else {
                    continue;
                }
            }
            if ($api_flg) {
                $result[$menu_act_info["@attributes"]["token"]] = $auth_able_array;
            } else {
                $result[$menu_act_info["@attributes"]["menu"]][$menu_act_info["@attributes"]["act"]] = $auth_able_array;
            }
        }
    }

    public static function getInstance()
    {
        return new Authority();
    }
}
?>