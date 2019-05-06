-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 05 月 11 日 15:50
-- 服务器版本: 5.5.25
-- PHP 版本: 5.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `300c`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_brand`
--

CREATE TABLE IF NOT EXISTS `zy_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandname` varchar(60) NOT NULL COMMENT '品牌名称',
  `brandimg` varchar(100) NOT NULL COMMENT '品牌logo',
  `sort` smallint(6) NOT NULL DEFAULT '500' COMMENT '品牌排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:启用 2：关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
