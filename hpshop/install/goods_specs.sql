-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-02-21 07:15:50
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
-- 表的结构 `zy_goods_specs`
--

CREATE TABLE IF NOT EXISTS `zy_goods_specs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '店铺id',
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `specid` varchar(255) NOT NULL COMMENT '组合',
  `specids` varchar(255) NOT NULL COMMENT '组合参数',
  `makerprice` decimal(11,2) NOT NULL COMMENT '市场价',
  `specprice` decimal(11,2) NOT NULL COMMENT '本店价',
  `specstock` int(11) NOT NULL COMMENT '库存',
  `salenum` int(11) NOT NULL COMMENT '销量',
  `status` TINYINT(1) NOT NULL COMMENT '是否启用（1启用 0禁用）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
