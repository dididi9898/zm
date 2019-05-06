-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-01-17 05:25:20
-- 服务器版本： 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 表的结构 `zy_zyconfig`
--
CREATE TABLE IF NOT EXISTS `zy_zyconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `config_name` varchar(255) NOT NULL COMMENT '配置名称',
  `model_name` varchar(255) NOT NULL COMMENT '所需模块',
  `item_name` varchar(255) NOT NULL COMMENT '模块项目名',
  `url` varchar(255) NOT NULL COMMENT '地址',
  `api_url` varchar(255) NOT NULL COMMENT 'API地址',
  `explain` text NOT NULL COMMENT '说明',
  `api_explain` text NOT NULL COMMENT 'api说明',
  `key` VARCHAR(255) NOT NULL COMMENT '配置表的关键字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DELETE FROM zy_zyconfig WHERE `item_name`='zyqrcode';

INSERT INTO `zy_zyconfig` (`config_name`, `model_name`, `item_name`, `url`, `api_url`, `explain`, `api_explain`, `key`) VALUES
('二维码显示列表', 'zyqrcode 二维码管理', 'zyqrcode', 'http://pub.300c.cn/index.php?m=zyqrcode&c=api&a=qrcode_api', '域名/index.php?m=zyqrcode&c=api&a=qrcode_api', '一、应用模块：二维码模块      配置来源：互动模块\r\n\r\n二、用途：二维码展示列表	\r\n\r\n三、提供参数：\r\n\r\n 1）请求参数说明：\r\n    project（*项目名称) \r\n\r\n 2）返回格式： json\r\n\r\n 3）请求方式： http  post\r\n', '一、请求参数：\r\n\r\n 1）请求参数说明：\r\n          project（*项目名称) \r\n 2）返回格式： json\r\n 3）请求方式： http post\r\n\r\n\r\n二、返回信息 :\r\n\r\n   返回格式：{"status":"success","code":"200","message":"操作成功","data":[{"id":"1","project":"ywzs","name":"测试模板","url":"http://pub.300c.cn/index.php?m=zymember&c=zymember_api&a=zyfunds_withdrawal","thumb":"http://localhost/zycms/uploadfile/poster/6.jpg","qrcode":"uploadfile/qrcode/1554714022.png"}]}\r\n\r\n\r\n三、返回字段解释：\r\n\r\n   status: 操作成功/操作失败\r\n   code:  操作状态\r\n   message: 提示信息\r\n   data: [ ] 数据组\r\n      data.id: id\r\n      data.project: 项目名称\r\n      data.name: APP名称\r\n      data.url: 安装链接\r\n      data.thumb: 缩略图\r\n      data.qrcode: 二维码图片\r\n\r\n\r\n四、状态信息说明：\r\n\r\n  200：操作成功\r\n  -200： 数据为空\r\n\r\n\r\n\r\n五、实例代码：\r\n\r\n<script type="javascript/text">\r\n$.ajax({\r\n   url:''域名/index.php?m=zyqrcode&c=api&a=qrcode_api'',\r\n   data:{project:ywzs},\r\n   dataType:''json'',\r\n   type:''post'',\r\n   success:function(res){\r\n      {"status":"success","code":"200","message":"操作成功","data":[{"id":"1","project":"ywzs","name":"测试模板","url":"http://pub.300c.cn/index.php?m=zymember&c=zymember_api&a=zyfunds_withdrawal","thumb":"http://localhost/zycms/uploadfile/poster/6.jpg","qrcode":"uploadfile/qrcode/1554714022.png"}]}\r\n});\r\n</script>\r\n', 'zyqrcode1');
