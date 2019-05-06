<?php
// +------------------------------------------------------------
// | distribution
// +------------------------------------------------------------
// | 卓远网络：CY QQ:185017580 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：20180208
// +------------------------------------------------------------
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */



//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'hpshop','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'hpshop',
		'parentid'=>'0',
		'm'=>'hpshop',
		'c'=>'goods',
		'a'=>'init',
		'data'=>'',
		'listorder'=>9,
		'display'=>'1'
		),
	true
    );
}

/**
 * 添加菜单:订单管理
 */
$pid = $menu_db->insert(
	array(
		'name'=>'goodsmanage', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'hpshop', //模块
		'c'=>'goods', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);

/**
 * 添加子菜单  商品列表
 */
$menu_db->insert(
	array(
		'name'=>'goodslist', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'goodslist', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);


/**
 * 添加子菜单  商品审核
 */
$menu_db->insert(
	array(
		'name'=>'goodsverify', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'goodsverify', //方法
		'data'=>'', //附加参数
		'listorder'=>2, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);


/**
 * 添加子菜单  商品品牌
 */
$menu_db->insert(
	array(
		'name'=>'goodsbrand', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'goodsbrand', //方法
		'data'=>'', //附加参数
		'listorder'=>3, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);

/**
 * 添加子菜单  推荐位管理
 */
$menu_db->insert(
	array(
		'name'=>'goodsposition', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'goodsposition', //方法
		'data'=>'', //附加参数
		'listorder'=>4, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);







/**
 * 添加菜单:分类管理
 */
$pids = $menu_db->insert(
	array(
		'name'=>'sortmanage', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'hpshop', //模块
		'c'=>'goods', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);


/**
 * 添加子菜单  栏目分类
 */
$menu_db->insert(
	array(
		'name'=>'catsort', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'catsort', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		)
	);

/**
 * 添加子菜单  商品类型
 */
$menu_db->insert(
	array(
		'name'=>'goodstype', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件 
		'a'=>'goodstype', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
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
		'name'=>'zyconfigss', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'hpshop', //模块
		'c'=>'goods',//文件
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
	'hpshop'=>'商品管理',
	'goodsmanage'=>'商品管理',
	'goodslist'=>'商品列表',
	'goodsbrand'=>'商品品牌',
	'goodsposition'=>'推荐位管理',
	'goodsverify'=>'商品审核',
	'sortmanage'=>'分类管理',
	'catsort'=>'商品分类',
	'goodstype'=>'商品类型',
	'zyconfig'=>'配置管理',
	'zyconfigss'=>'商品配置',
);

?>