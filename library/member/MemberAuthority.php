<?php

/**
 * 员工权限包
 * @author Kinsama
 * @version 2020-04-29
 */
class MemberAuthority
{
    private $_position;
    private $_section_list;

    public function __construct($member_position)
    {
        $this->_position = $member_position;
        $this->_section_list = BroadcomMemberEntity::getSectionPositionList();
    }

    /**
     * 是否为校长
     *
     * @return boolean
     */
    public function isMst()
    {
        return in_array($this->_position, $this->_section_list[BroadcomMemberEntity::SECTION_1]);
    }

    /**
     * 是否为教务部
     *
     * @return boolean
     */
    public function isAst()
    {
        return in_array($this->_position, $this->_section_list[BroadcomMemberEntity::SECTION_2]) || in_array($this->_position, $this->_section_list[BroadcomMemberEntity::SECTION_5]);
    }

    /**
     * 是否为教学部
     *
     * @return boolean
     */
    public function isEdu()
    {
        return in_array($this->_position, $this->_section_list[BroadcomMemberEntity::SECTION_3]);
    }

    /**
     * 是否为财务部
     *
     * @return boolean
     */
    public function isHrf()
    {
        return in_array($this->_position, $this->_section_list[BroadcomMemberEntity::SECTION_4]);
    }
}
?>