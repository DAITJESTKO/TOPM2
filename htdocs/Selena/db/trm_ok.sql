/*
MySQL Data Transfer
Source Host: localhost
Source Database: Selena
Target Host: localhost
Target Database: Selena
Date: 16.02.2011 12:10:37
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for trm_ok
-- ----------------------------
CREATE TABLE `trm_ok` (
  `time` datetime NOT NULL,
  `account` varchar(20) NOT NULL,
  `txn_id` int(13) NOT NULL,
  `sum` double(6,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
