-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-04-29 04:51:52
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
-- 表的结构 `zy_zyfxgradetitle`
--

CREATE TABLE IF NOT EXISTS `zy_zyfxgradetitle` (
  `titleID` int(11) NOT NULL AUTO_INCREMENT,
  `TitleName` varchar(255) NOT NULL,
  `neadMember` int(11) NOT NULL COMMENT '升级到一级需要的人数',
  `gradeAward` int(11) NOT NULL DEFAULT '0' COMMENT '等级奖励',
  PRIMARY KEY (`titleID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='头衔表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `zy_zyfxgradetitle`
--

INSERT INTO `zy_zyfxgradetitle` (`titleID`, `TitleName`, `neadMember`, `gradeAward`) VALUES
(0, '会员', 0, 0),
(1, '村镇', 1, 11),
(2, '街道', 2, 111),
(3, '区', 3, 1111),
(4, '市', 4, 11111),
(5, '省', 5, 22222),
(6, '国', 6, 33333),
(7, '等级7', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
