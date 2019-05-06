-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-04-29 04:52:00
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fx`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_zyfxmoney`
--

CREATE TABLE IF NOT EXISTS `zy_zyfxmoney` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `TXmoney` float NOT NULL DEFAULT '0' COMMENT '已经提现了的金额',
  `WTXmoney` float NOT NULL DEFAULT '0' COMMENT '未提现的金额',
  `moneycount` float NOT NULL DEFAULT '0' COMMENT '金额总数',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='金额表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `zy_zyfxmoney`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
