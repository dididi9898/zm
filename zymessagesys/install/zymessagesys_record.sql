-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-02-25 02:02:21
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zyshop`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_zymessage_record`
--

CREATE TABLE IF NOT EXISTS `zy_zymessagesys_record` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` smallint(11) unsigned NOT NULL COMMENT '用户id',
  `username` char(40) NOT NULL COMMENT '用户账号',
  `mobile` char(11) NOT NULL COMMENT '用户手机',
  `content` text NOT NULL COMMENT '发送内容',
  `title` varchar(255) NOT NULL COMMENT '发送标题',
  `addtime` char(11) NOT NULL COMMENT '添加时间',
  `nickname` varbinary(120) NOT NULL COMMENT '用户昵称',
  `status` tinyint(1) unsigned NOT NULL COMMENT '1单发、2群发',
  `types` tinyint(1) unsigned NOT NULL COMMENT '1系统消息、2商城消息',
  `url` varchar(255) NOT NULL COMMENT '跳转链接',
  `thumb` varchar(255) NOT NULL DEFAULT 'statics/zymessagesys/images/message_icon.png' COMMENT '缩略图',
  `sendname` varchar(255) NOT NULL DEFAULT '系统消息' COMMENT '发件人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息记录' AUTO_INCREMENT=1 ;
