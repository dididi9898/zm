-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-04-28 07:40:09
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tq`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_zyshop`
--

CREATE TABLE IF NOT EXISTS `zy_zyshop` (
  `shopID` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(255) NOT NULL DEFAULT '500' COMMENT '商品排序',
  `typeID` int(11) NOT NULL COMMENT '对应的商品类型',
  `price` float(8,2) DEFAULT '0.00' COMMENT '价格',
  `shopname` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '商品名',
  `addtime` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '添加时间',
  `sketch` text CHARACTER SET utf8mb4 COMMENT '简述',
  `putaway` int(11) NOT NULL DEFAULT '0' COMMENT '是否上架',
  `thumb` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '缩略图地址',
  `content` text CHARACTER SET utf8mb4 COMMENT '内容',
  `repertory` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `specification` text CHARACTER SET utf8mb4 COMMENT '商品规格',
  `unspecification` text CHARACTER SET utf8mb4 COMMENT '没有处理过的specification',
  `shoppicture` text CHARACTER SET utf8mb4 NOT NULL COMMENT '商品轮播图',
  `infopicture` text CHARACTER SET utf8mb4 NOT NULL COMMENT '详细图片',
  PRIMARY KEY (`shopID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
