-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-04-29 04:51:46
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
-- 表的结构 `zy_zyfxconfig`
--

CREATE TABLE IF NOT EXISTS `zy_zyfxconfig` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增',
  `tier` int(11) NOT NULL DEFAULT '1' COMMENT '分销层级',
  `awardType` int(11) NOT NULL DEFAULT '1' COMMENT '1:固定金额，2:商品百分比',
  `awardNumber` text NOT NULL COMMENT '奖励额度（按等级不同，额度不同，JSON格式存放）',
  `gradeTitleID` int(11) NOT NULL DEFAULT '0' COMMENT '0:代表统一头衔',
  `upGradeConditions` text NOT NULL COMMENT '升级条件（一般按下级人数进行限制,在头衔表中也有这个字段，暂时不知道用哪个好）',
  `gradeTitleType` int(11) NOT NULL DEFAULT '1' COMMENT '1:关闭等级2：开启等级',
  `gradeNumber` int(11) NOT NULL DEFAULT '7' COMMENT '分成的等级数',
  `TXcharge` smallint(6) NOT NULL COMMENT '提现手续费',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='分销配置表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `zy_zyfxconfig`
--

INSERT INTO `zy_zyfxconfig` (`ID`, `tier`, `awardType`, `awardNumber`, `gradeTitleID`, `upGradeConditions`, `gradeTitleType`, `gradeNumber`, `TXcharge`) VALUES
(1, 3, 1, '{"1":"10","2":"20","3":"22 "}', 0, '0', 1, 6, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
