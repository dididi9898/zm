-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 05 月 20 日 07:32
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `phpcmsv9_qxc`
--

-- --------------------------------------------------------

--
-- 表的结构 `zy_qxc_lottery_num`
--

CREATE TABLE IF NOT EXISTS `phpcms_qxc_lottery_num` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lotterytime` char(11) NOT NULL COMMENT '开奖时间',
  `addtime` char(11) NOT NULL COMMENT '添加时间',
  `issue` char(11) NOT NULL COMMENT '期号',
  `qian` smallint(8) unsigned NOT NULL COMMENT '仟',
  `bai` smallint(8) unsigned NOT NULL COMMENT '佰',
  `shi` smallint(8) unsigned NOT NULL COMMENT '拾',
  `ge` smallint(8) unsigned NOT NULL COMMENT '个',
  `qiu5` smallint(8) unsigned NOT NULL COMMENT '球5',
  `qiu6` smallint(8) unsigned NOT NULL COMMENT '球6',
  `qiu7` smallint(8) unsigned NOT NULL COMMENT '球7',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='开奖号码表' AUTO_INCREMENT=1 ;