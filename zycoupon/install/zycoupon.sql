-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2019 年 04 月 29 日 05:45
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `zm`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_zycoupon`
--

CREATE TABLE IF NOT EXISTS `zy_zycoupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `couponname` varchar(100) NOT NULL COMMENT '优惠卷名称',
  `full` decimal(10,0) NOT NULL COMMENT '满',
  `minus` decimal(10,0) NOT NULL COMMENT '减',
  `begintime` int(11) NOT NULL COMMENT '开始时间',
  `endtime` int(11) NOT NULL COMMENT '结束时间',
  `days` int(11) NOT NULL COMMENT '领取后有效天数',
  `limittype` int(11) NOT NULL DEFAULT '0' COMMENT '0 全商品通用 1-99 根据商品类型',
  `totalnum` int(11) NOT NULL DEFAULT '0' COMMENT '优惠卷数量',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 无门槛  1 满减  2叠加满减',
  `usednum` int(11) NOT NULL COMMENT '已使用数量',
  `takenum` int(11) NOT NULL COMMENT '已领取数量',
  `vaild_type` int(11) NOT NULL DEFAULT '1' COMMENT '1 绝对时效 固定时间段xxx-xxx 2 相对时间段 N天有效',
  `status` int(11) NOT NULL COMMENT '状态 1 有效 2 失效 3 已结束',
  `updatetime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
