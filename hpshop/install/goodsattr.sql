-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-02-21 07:06:58
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zypub`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_goodsattr`
--

CREATE TABLE IF NOT EXISTS `zy_goodsattr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodstypeid` int(11) NOT NULL COMMENT '所属商品类型id',
  `attrname` varchar(255) NOT NULL COMMENT '属性名称',
  `attrval` text NOT NULL COMMENT '属性值',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示（0不显示 1显示）',
  `sort` int(11) NOT NULL DEFAULT '500' COMMENT '排序',
  `attrtype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '属性类型（0输入框 1单选）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
