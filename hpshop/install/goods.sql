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
-- 表的结构 `zy_goods`
--

CREATE TABLE IF NOT EXISTS `zy_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopid` int(11) NOT NULL COMMENT '店铺ID',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `summary` text NOT NULL COMMENT '商品简述',
  `thumb` varchar(100) NOT NULL COMMENT '商品主图',
  `album` text NOT NULL COMMENT '商品相册',
  `content` text NOT NULL COMMENT '商品内容信息',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价',
  `on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架 1：上架 2：下架',
  `stock` int(11) NOT NULL DEFAULT '999' COMMENT '库存',
  `salesnum` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `catid` mediumint(9) NOT NULL COMMENT '所属栏目',
  `brand_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '所属品牌',
  `type_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '所属类型',
  `isok` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品审核（1.正常 2.待审核 3.退稿）',
  `isspec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有规格数据（1有 0无）',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
