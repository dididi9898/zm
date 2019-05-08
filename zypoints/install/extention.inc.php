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
$zymall = $menu_db->get_one(array('name'=>'zymall','parentid'=>'0'));
if($zymall){
	$parentids =$zymall['id'];
}else{
	$parentids = $menu_db->insert(
		array(
			'name'=>'zymall',
			'parentid'=>'0',
			'm'=>'zymall',
			'c'=>'zymall',
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

$pids = $menu_db->insert(
	array(
		'name'=>'zypoints_manage',
		'parentid'=>$parentids,
		'm'=>'zypoints',
		'c'=>'zypoints',
		'a'=>'init',
		'data'=>'',
		'listorder'=>0,
		'display'=>'1'
	),
	true
);

/**
 * 添加子菜单:参考管理
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zypoints', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zypoints', //模块
		'c'=>'zypoints',//文件
		'a'=>'init', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);
/**
 * 添加子菜单:参考管理
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zypoints_user', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zypoints', //模块
		'c'=>'zypoints',//文件
		'a'=>'init_rec', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);

/**
 * 添加子菜单:参考管理
 */
$userid = $menu_db->insert(
	array(
		'name'=>'zypoint_setting', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zypoints', //模块
		'c'=>'zypoints',//文件
		'a'=>'zypoint_setting', //方法
		'data'=>'', //附加参数
		'listorder'=>2, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);
/**
 * 菜单名称翻译
 */
$language = array(
	'zymall'=>'商城模块',
	'zypoints_manage'=>'积分模块',
	'zypoints'=>'积分物品管理',
	'zypoints_user'=>'积分兑换管理',
	'zypoint_setting'=>'积分设置',
);

?>