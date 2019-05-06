-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 11 月 30 日 05:25
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `phpcmsv9`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_sms_xf_record`
--

CREATE TABLE IF NOT EXISTS `zy_sms_xf_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin` char(30) NOT NULL COMMENT '管理员',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `reception` char(11) NOT NULL COMMENT '接收人',
  `ip` char(15) NOT NULL,
  `sendtime` int(11) NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='消费记录' AUTO_INCREMENT=1 ;