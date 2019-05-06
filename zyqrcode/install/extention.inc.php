<?php
// +------------------------------------------------------------
// | zyfunds
// +------------------------------------------------------------
// | 卓远网络：CY QQ:185017580 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：20190125
// +------------------------------------------------------------
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */

//先判断有没有卓远网络的大菜单
$zywldbs = $menu_db->get_one(array('name'=>'zyinteractmenu','parentid'=>'0'));
if($zywldbs){
	$parentids =$zywldbs['id'];
}else{
	$parentids = $menu_db->insert(
		array(
			'name'=>'zyinteractmenu',
			'parentid'=>'0',
			'm'=>'zyinteract',
			'c'=>'zyinteract',
			'a'=>'init',
			'data'=>'',
			'listorder'=>9,
			'display'=>'1'
		),
		true
	);
}
/**
 * 添加菜单:配置管理
 */
$zywl = $menu_db->get_one(array('name'=>'zyinteracth_manage','m'=>'pubconfig','c'=>'pubconfig','a'=>'init'));
if($zywl){
	$pids =$zywl['id'];
}else{
	$pids = $menu_db->insert(
		array(
			'name'=>'zyinteracth_manage',
			'parentid'=>$parentids,
			'm'=>'zyinteract',
			'c'=>'zyinteract',
			'a'=>'init',
			'data'=>'',
			'listorder'=>0,
			'display'=>'1'
		),
		true
	);
}

/**
 * 添加子菜单:参考管理
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zyqrcode', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zyqrcode', //模块
		'c'=>'zyqrcode',//文件
		'a'=>'init', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);



$zywldbs = $menu_db->get_one(array('name'=>'zyconfigmenu','parentid'=>'0'));
if($zywldbs){
	$parentids =$zywldbs['id'];
}else{
	$parentids = $menu_db->insert(
		array(
			'name'=>'zyconfigmenu',
			'parentid'=>'0',
			'm'=>'zyconfig',
			'c'=>'config',
			'a'=>'init',
			'data'=>'',
			'listorder'=>9,
			'display'=>'1'
		),
		true
	);
}

/**
 * 添加菜单:参考管理
 */
$zywl = $menu_db->get_one(array('name'=>'zyconfig','m'=>'pubconfig','c'=>'pubconfig','a'=>'init'));
if($zywl){
	$pids =$zywl['id'];
}else{
	$pids = $menu_db->insert(
		array(
			'name'=>'zyconfig',
			'parentid'=>$parentids,
			'm'=>'pubconfig',
			'c'=>'pubconfig',
			'a'=>'init',
			'data'=>'',
			'listorder'=>0,
			'display'=>'1'
		),
		true
	);
}

/**
 * 添加子菜单:参考管理
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zyqrcode_configs', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zyqrcode', //模块
		'c'=>'zyqrcode',//文件
		'a'=>'zyconfig', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);


/**
 * 菜单名称翻译
 */
$language = array(
	'zyinteractmenu'=>'互动模块',
	'zyinteracth_manage'=>'互动管理',
	'zyqrcode'=>'二维码配置',
	'zyconfigmenu'=>'配置模块',
	'zyconfig'=>'配置管理',
	'zyqrcode_configs'=>'二维码配置',
);

?>