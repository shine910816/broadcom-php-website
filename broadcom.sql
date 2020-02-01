/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : broadcom

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2020-02-01 19:54:06
*/

SET FOREIGN_KEY_CHECKS=0;

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
INSERT INTO `member_info` VALUES ('100', '陈国欣', '000000199108160000', '13800000000', 'xxxxxx@163.com', '1', '1991-08-16', '0', '天津', '天津外国语大学滨海外事学院', '日语', '2010-09-01', '2014-06-30', '0', '0', null, null, '0', '100', '2020-01-29 20:21:47', '2020-01-29 20:21:47', '0');

-- ----------------------------
-- Table structure for member_login
-- ----------------------------
DROP TABLE IF EXISTS `member_login`;
CREATE TABLE `member_login` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_login_name` varchar(50) DEFAULT NULL,
  `member_login_password` varchar(32) DEFAULT NULL,
  `member_login_salt` varchar(6) DEFAULT NULL,
  `member_level` tinyint(4) NOT NULL,
  `member_position_level` tinyint(4) NOT NULL,
  `operated_by` int(11) NOT NULL,
  `insert_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `del_flg` tinyint(4) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member_login
-- ----------------------------
INSERT INTO `member_login` VALUES ('100', 'chenguoxin', '7858bf06b8f2ab9ccf68aa68c8d04d7e', 'kn88y6', '2', '100', '100', '2020-01-29 20:21:47', '2020-01-29 20:21:47', '0');
