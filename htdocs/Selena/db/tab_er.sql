/*
MySQL Data Transfer
Source Host: localhost
Source Database: Selena
Target Host: localhost
Target Database: Selena
Date: 20.02.2011 19:07:25
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tab_er
-- ----------------------------
CREATE TABLE `tab_er` (
  `time` datetime NOT NULL,
  `account` varchar(20) NOT NULL,
  `txn_id` int(13) NOT NULL,
  `sum` double(6,2) NOT NULL,
  `comm` varchar(20) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
