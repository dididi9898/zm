-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-02-10 02:29:23
-- 服务器版本： 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zyfound`
--
-- 表的结构 `zy_zyfound_pay_record` 充值管理
--

CREATE TABLE `zy_zyfound_pay_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_sn` varchar(255) NOT NULL COMMENT '交易订单号',
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `nickname` varchar(100) NOT NULL COMMENT '用户昵称',
  `phone` varchar(100) NOT NULL COMMENT '手机',
  `type` tinyint(4) NOT NULL COMMENT '交易类型',
  `amount` float(11,2) NOT NULL COMMENT '交易金额',
  `addtime` int(11) NOT NULL COMMENT '交易时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

