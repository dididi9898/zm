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
$zywldb = $menu_db->get_one(array('name'=>'zyfunds','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'zyfunds',
		'parentid'=>'0',
		'm'=>'zysystem',
		'c'=>'zysystem',
		'a'=>'init',
		'data'=>'',
		'listorder'=>9,
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:充值提现管理
 */
$pid = $menu_db->insert(
	array(
		'name'=>'zyrwc', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zyfunds', //模块
		'c'=>'zyfunds', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单  账号管理
 */
$menu_db->insert(
	array(
		'name'=>'zhmanage', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyfunds', //模块
		'c'=>'zyfunds',//文件 
		'a'=>'zhmanage', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);


/**
 * 添加子菜单  提现列表
 */
$menu_db->insert(
	array(
		'name'=>'zywithdrawcash', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyfunds', //模块
		'c'=>'zyfunds',//文件
		'a'=>'withdrawcash', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	)
);

/**
 * 添加子菜单  充值列表
 */
$menu_db->insert(
	array(
		'name'=>'zyrechargelist', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyfunds', //模块
		'c'=>'zyfunds',//文件
		'a'=>'rechargelist', //方法
		'data'=>'', //附加参数
		'listorder'=>2, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	)
);

/**
 * 添加子菜单  银行信息
 */
$menu_db->insert(
	array(
		'name'=>'zybanklist', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyfunds', //模块
		'c'=>'zyfunds',//文件
		'a'=>'zybanklist', //方法
		'data'=>'', //附加参数
		'listorder'=>4, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	)
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
		'name'=>'zyconfigs', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zyfunds', //模块
		'c'=>'zyfunds',//文件
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
	'zyfunds'=>'资金管理',
	'zyrwc'=>'资金项目管理',
	'zhmanage' =>'账号管理',
	'zyrechargelist' =>'充值列表',
	'zywithdrawcash' =>'提现列表',
	'zybanklist' => '银行信息',
	'zyconfigmenu'=>'配置模块',
	'zyconfig'=>'配置管理',
	'zyconfigs'=>'提现充值配置',
);

?>