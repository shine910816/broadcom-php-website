<?php
class Authority
{

    private $_allow_list;
    private $_authority_list;

    public function __construct()
    {
        $xml = simplexml_load_file(SRC_PATH . "/api/authority.xml");
        $result = array();
        foreach ($xml->functions->function as $function_info) {
            $menu_act_info = (array) $function_info->attributes();
            $auth_able_array = array();
            foreach ($function_info->position as $pos_tmp) {
                $position = (array) $pos_tmp;
                $result = array(
                    "read" => false,
                    "edit" => false
                );
                if ($position["@attributes"]["readable"] == "1") {
                    $result["read"] = true;
                }
                if ($result["read"] && $position["@attributes"]["editable"] == "1") {
                    $result["edit"] = true;
                }
                $this->_authority_list[$menu_act_info["@attributes"]["menu"]][$menu_act_info["@attributes"]["act"]][$position["@attributes"]["value"]] = $result;
            }
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
            "menu" => "common",
            "act" => "error"
        );
    }

    public function getAuthority($menu, $act, $position)
    {
        if (isset($this->_authority_list[$menu][$act][$position])) {
            return $this->_authority_list[$menu][$act][$position];
        }
        return array(
            "read" => true,
            "edit" => false
        );
    }

    public static function getInstance()
    {
        return new Authority();
    }
}
?>