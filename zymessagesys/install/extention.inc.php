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
		'name'=>'messagesys', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys', //文件
		'a'=>'messagesys',//方法
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
		'name'=>'sms_confing', //菜单名称
		'parentid'=>$pid, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件
		'a'=>'sms_confing', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);


/**
 * 添加子菜单:系统消息管理
 */
$pidss = $menu_db->insert(
	array(
		'name'=>'syssms_platform', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys', //文件
		'a'=>'sms_platform',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
	true //插入菜单之后，是否返回id
);

/**
 * 添加子菜单:消息管理
 */
$one = $menu_db->insert(
	array(
		'name'=>'message_manage', //菜单名称
		'parentid'=>$pidss, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件 
		'a'=>'message_manage', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true//插入菜单之后，是否返回id
	);

/**
 * 添加子菜单:单发消息
 */
$two = $menu_db->insert(
	array(
		'name'=>'message_manage_adddf', //菜单名称
		'parentid'=>$pidss, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件 
		'a'=>'message_manage_adddf', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true//插入菜单之后，是否返回id
	);

/**
 * 添加子菜单:群发消息
 */
$three = $menu_db->insert(
	array(
		'name'=>'message_manage_addqf', //菜单名称
		'parentid'=>$pidss, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件 
		'a'=>'message_manage_addqf', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
		),true//插入菜单之后，是否返回id
	);
	

/**
 * 添加子菜单:短息模块
 */
$pids = $menu_db->insert(
	array(
		'name'=>'sms_platform', //菜单名称
		'parentid'=>$parentid, //添加到后台的主菜单里
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys', //文件
		'a'=>'sms_platform',//方法
		'data'=>'', //附加参数
		'listorder'=>0, //菜单排序
		'display'=>'1'), //显示菜单 1是显示 0是隐藏
	true //插入菜单之后，是否返回id
);

/**
 * 添加子菜单:消费记录
 */
$five = $menu_db->insert(
	array(
		'name'=>'sms_record', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件
		'a'=>'sms_record', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
);

/**
 * 添加子菜单:短信模板
 */
/* $six = $menu_db->insert(
	array(
		'name'=>'sms_model', //菜单名称
		'parentid'=>$pids, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件
		'a'=>'sms_model', //方法
		'data'=>'', //附加参数
		'listorder'=>1, //菜单排序
		'display'=>'1' //显示菜单 1是显示 0是隐藏
	),true//插入菜单之后，是否返回id
); */

/**
 * 添加子菜单:配置模块
 */
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
 * 添加菜单:配置管理
 */
$zywl = $menu_db->get_one(array('name'=>'zyconfig','m'=>'pubconfig','c'=>'pubconfig','a'=>'init'));
if($zywl){
	$sid =$zywl['id'];
}else{
	$sid = $menu_db->insert(
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
 * 添加子菜单:通讯配置
 */
$sids = $menu_db->insert(
	array(
		'name'=>'zymessagesys_configs', //菜单名称
		'parentid'=>$sid, //添加到积分商城。
		'm'=>'zymessagesys', //模块
		'c'=>'messagesys',//文件
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
	'zymessagesysmenu'=>'通讯模块',
	'messagesys'=>'消息模块',
	'syssms_platform'=>'系统消息管理',
	'message_manage'=>'消息管理',
	'message_manage_adddf'=>'单发消息',
	'message_manage_addqf'=>'群发消息',
	'sms_platform'=>'短信模块',
	'sms_confing'=>'平台设置',
	'sms_record'=>'消费记录',
	'sms_model'=>'短信模板',
	'zyconfigmenu'=>'配置模块',
	'zyconfig'=>'配置管理',
	'zymessagesys_configs'=>'通讯配置',
);
	

?>