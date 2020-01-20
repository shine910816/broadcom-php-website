/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : broadcom

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2020-01-20 16:15:33
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
