<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');


/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */
//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'zymessagesysmenu','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'zymessagesysmenu', 
		'parentid'=>'0', 
		'm'=>'zymessagesys', 
		'c'=>'messagesys', 
		'a'=>'init', 
		'data'=>'', 
		'listorder'=>17,
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:消息模块
 */
$pid = $menu_db->insert(
	array(
		'name'=>'zyim_sys', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zyim', //模块
		'c'=>'zyim', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单:平台设置
 */
$four = $menu_db->insert(
	array(
		'name'=>'zyim_list', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyim', //模块
		'c'=>'zyim',//文件
		'a'=>'zyim_list', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);


/**
 * 菜单名称翻译
 */	
$language = array(
	'zymessagesysmenu'=>'通讯模块',
	'messagesys'=>'消息模块',
	'zyim_sys'=>'聊天系统',
	'zyim_list'=>'聊天消息列表',
);
	

?>