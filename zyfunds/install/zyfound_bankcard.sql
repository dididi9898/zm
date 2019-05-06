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
-- 表的结构 `zy_zyfound_bankcard`  账户管理
--

CREATE TABLE IF NOT EXISTS `zy_zyfound_bankcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` SMALLINT(2) NOT NULL COMMENT '用户ID',
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `nickname` varchar(100) NOT NULL COMMENT '用户昵称',
  `phone` varchar(100) NOT NULL COMMENT '手机号',
  `tid` SMALLINT(2) NOT NULL COMMENT '账号类型:支付宝|微信|银行卡',
  `tname` varchar(255) NOT NULL COMMENT '手机号',
  `account` varchar(255) NOT NULL COMMENT '支付账号',
  `accountname` varchar(255) NOT NULL COMMENT '支付账号名称',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `is_first` tinyint(2) NOT NULL COMMENT '是否默认账户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
