/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : broadcom

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2020-01-21 18:36:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for custom_info
-- ----------------------------
DROP TABLE IF EXISTS `custom_info`;
CREATE TABLE `custom_info` (
  `custom_id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `del_flg` tinyint(4) NOT NULL,
  PRIMARY KEY (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of custom_info
-- ----------------------------

-- ----------------------------
-- Table structure for custom_login
-- ----------------------------
DROP TABLE IF EXISTS `custom_login`;
CREATE TABLE `custom_login` (
  `custom_id` int(11) NOT NULL AUTO_INCREMENT,
  `insert_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `del_flg` tinyint(4) NOT NULL,
  PRIMARY KEY (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of custom_login
-- ----------------------------

-- ----------------------------
-- Table structure for member_info
-- ----------------------------
DROP TABLE IF EXISTS `member_info`;
CREATE TABLE `member_info` (
  `member_id` int(11) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `m_id_code` varchar(18) DEFAULT NULL,
  `m_mobile_number` varchar(11) DEFAULT NULL,
  `m_mail_address` varchar(100) DEFAULT NULL,
  `m_gender` tinyint(4) NOT NULL,
  `m_birthday` date NOT NULL,
  `m_married_flg` tinyint(4) NOT NULL,
  `m_address` varchar(20) DEFAULT NULL,
  `m_college` varchar(50) DEFAULT NULL,
  `m_major` varchar(50) DEFAULT NULL,
  `m_college_start_date` date NOT NULL,
  `m_college_end_date` date NOT NULL,
  `m_educated` tinyint(4) NOT NULL,
  `m_educated_type` tinyint(4) NOT NULL,
  `m_contact_name` varchar(20) DEFAULT NULL,
  `m_contact_mobile_number` varchar(11) DEFAULT NULL,
  `m_contact_relationship` tinyint(4) NOT NULL,
  `operated_by` int(11) NOT NULL,
  `insert_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `del_flg` tinyint(4) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member_info
-- ----------------------------
