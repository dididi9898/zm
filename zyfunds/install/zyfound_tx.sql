-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-02-09 08:03:17
-- 服务器版本： 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zycms`

-- 表的结构 `zy_zyfound_tx_record` 提现管理
--

CREATE TABLE `zy_zyfound_tx_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_sn` varchar(100) NOT NULL COMMENT '提现订单号',
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `nickname` varchar(100) NOT NULL COMMENT '用户昵称',
  `phone` varchar(100) NOT NULL COMMENT '手机号码',
  `type` tinyint(4) NOT NULL COMMENT '提现类型',
  `account` varchar(255) NOT NULL COMMENT '提现账号',
  `accountname` varchar(255) NOT NULL COMMENT '提现账号名称',
  `amount` float(11,2) NOT NULL COMMENT '提现金额',
  `reason` VARCHAR(255) COMMENT '退回原因',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现状态',
  `addtime` int(11) NOT NULL COMMENT '提现时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
