-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-02-10 07:11:41
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zyzyfound`
--
-- 表的结构 `zy_zyfound_bank`   银行信息
--

CREATE TABLE IF NOT EXISTS `zy_zyfound_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank` varchar(255) NOT NULL COMMENT '银行',
  `desc` varchar(255) NOT NULL COMMENT '银行描述',
  `thumb` varchar(255) NOT NULL COMMENT '银行图标',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `zy_zyfound_bank` VALUES ('1', '中国银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo1.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('2', '中国农业银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo2.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('3', '中国建设银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo3.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('4', '中国工商银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo4.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('5', '中国邮政储蓄银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo5.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('6', '交通银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo6.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('7', '招商银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo7.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('8', '兴业银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo8.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('9', '民生银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo9.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('10', '中国广大银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo10.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('11', '华夏银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo11.gif', '1');
INSERT INTO `zy_zyfound_bank` VALUES ('12', '中信银行', '储蓄卡', 'http://pub.300c.cn/statics/funds/images/logo12.gif', '1');

