-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-04-29 04:51:56
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
-- 表的结构 `zy_zyfxmember`
--

CREATE TABLE IF NOT EXISTS `zy_zyfxmember` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '对应主member的ID',
  `addTime` varchar(255) CHARACTER SET utf8mb4 NOT NULL COMMENT '添加时间',
  `updateTime` varchar(255) NOT NULL COMMENT '最后一次上线时间',
  `pid` int(11) DEFAULT '0' COMMENT '上一级的userid,0代表没有上一级',
  `childID` text CHARACTER SET utf8mb4 NOT NULL COMMENT '下级ID，用,分割(对应的是userid)',
  `titleID` int(11) NOT NULL COMMENT '0是默认等级，其他为划分等级',
  `is_buy` int(11) NOT NULL DEFAULT '0' COMMENT '备用参数（按具体情况选择）',
  `money` int(11) NOT NULL DEFAULT '0' COMMENT '分销获得的奖金',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='fx用户表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `zy_zyfxmember`
--



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
