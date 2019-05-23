<?php
// +------------------------------------------------------------
// | 卓远网络1.0
// +------------------------------------------------------------
// | 卓远网络：YYY QQ:185017580 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：20171010
// +------------------------------------------------------------


defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');


/**
 * 添加父级菜单:后台添加一个卓远商城菜单
 */



//先判断有没有卓远网络的大菜单
$zywldb = $menu_db->get_one(array('name'=>'zywlmenu','parentid'=>'0'));
if($zywldb){
	$parentid =$zywldb['id'];
}else{
	$parentid = $menu_db->insert(
	array(
		'name'=>'zywlmenu',
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
 * 添加菜单:excel导入导出
 */
$pid = $menu_db->insert(
	array(
		'name'=>'excel', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zyexcel', //模块
		'c'=>'excel', //文件
		'a'=>'init',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
		true //插入菜单之后，是否返回id
	);


/**
 * 添加子菜单：excel导入
 */
$piddr=$menu_db->insert(
	array(
		'name'=>'excel_drlist', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyexcel', //模块
		'c'=>'excel',//文件
		'a'=>'excel_drlist', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true
	);
/**
 * 添加子菜单：excel导入_测试数据
 */
$menu_db->insert(
	array(
		'name'=>'excel_dr_ceshi', //菜单名称
		'parentid'=>$piddr, //添加到积分商城。
		'm'=>'zyexcel', //模块
		'c'=>'excel',//文件
		'a'=>'excel_dr_ceshi', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'0' //显示菜单 1是显示 0是隐藏
		)
	);


/**
 * 添加子菜单：excel导出
 */
$piddc=$menu_db->insert(
	array(
		'name'=>'excel_dclist', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zyexcel', //模块
		'c'=>'excel',//文件
		'a'=>'excel_dclist', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true
	);
/**
 * 添加子菜单：excel导出_测试数据
 */
$menu_db->insert(
	array(
		'name'=>'excel_dc_ceshi', //菜单名称
		'parentid'=>$piddr, //添加到积分商城。
		'm'=>'zyexcel', //模块
		'c'=>'excel',//文件
		'a'=>'excel_dc_ceshi', //方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'0' //显示菜单 1是显示 0是隐藏
		)
	);






/**
 * 菜单名称翻译
 */
$language = array(
	'zywlmenu'=>'卓远网络',
	'excel'=>'excel导入导出',
	'excel_drlist' =>'excel导入',
	'excel_dr_ceshi' =>'excel导入测试',
	'excel_dclist' =>'excel导出',
	'excel_dc_ceshi' =>'excel导出测试',
);





/*卓远网络
	商城管理
		列表*/



?>