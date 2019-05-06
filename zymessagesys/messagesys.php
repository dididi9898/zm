<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');
pc_base::load_app_func('admin', 'set_config', 0);	//加载应用类方法

class messagesys extends admin
{
	/**
	*构造函数，初始化
	*/
	public function __construct()
	{
		//开启session会话
		session_start();
		//初始化父级的构造函数
		parent::__construct();
		//引入数据表
		$this->get_db = pc_base::load_model('get_model');
		//配置模块表
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
		//消息
		$this->zymessagesys_record_db = pc_base::load_model('zymessagesys_record_model');
		$this->sms_report_db = pc_base::load_model('sms_report_model');	//短信记录
		$this->sms_xf_record_db = pc_base::load_model('sms_xf_record_model');	//短信消费记录

	}




//======================配置模块-配置管理-通讯配置（别人需要的） START
	/**
	 * 通讯配置-列表
	 */
	public function zyconfig()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymessagesys&c=messagesys&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		$where = ['item_name'=>'zymessagesys'];
		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->zyconfig_db->listinfo($where,$order,$page,20);
		$pages = $this->zyconfig_db->pages;

		include $this->admin_tpl('zyconfig');
	}




	/*
	 * 通讯配置-添加
	 * */
	public function configadd()
	{

		if($_POST['dosubmit'])
		{
			if(empty($_POST['config_name']))
			{
				showmessage('请输入项目名',HTTP_REFERER);
			}
			$zyconfig_num = $this->zyconfig_db->count(['item_name'=>'zymessagesys'])+1;
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'url'=>$_POST['url'],
				'item_name'=>'zymessagesys',
				'key'=>'zymessagesys'.$zyconfig_num,
			);

			$this->zyconfig_db->insert($car); //修改
			showmessage(L('operation_success'), '', '', 'add');
		}
		else
		{
			$into=$this->module_db->select();
			include $this->admin_tpl('zyconfigadd');
		}
	}

	/**
	 * 编辑配置界面
	 * @return [type] [description]
	 */
	public function configedit()
	{
		if(isset($_POST['dosubmit']))
		{
			$car=array
			(
				'url'=>$_POST['url'],
				'model_name'=>$_POST['model_name'],
			);
			$this->zyconfig_db->update($car, array('id'=>$_POST['id'])); //修改
			showmessage('操作完成','','','edit');
		}
		else
		{
			if(!$_GET['id'])
			{
				showmessage('id不能为空',HTTP_REFERER);
			}
			$into=$this->module_db->select();
			$info =$this->zyconfig_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('zyconfigshow');
		}
	}

	/**
	 * 编辑文档界面
	 * @return [type] [description]
	 */
	public function configeditD()
	{
		if(isset($_POST['dosubmit']))
		{
			$car=array
			(
				'api_url'=>$_POST['api_url'],
				'explain'=>$_POST['explain'],
				'api_explain'=>$_POST['api_explain'],
			);
			$this->zyconfig_db->update($car, array('id'=>$_GET['id'])); //修改
			showmessage('操作完成','','','show');
		}
		else
		{
			if(!$_GET['id'])
			{
				showmessage('id不能为空',HTTP_REFERER);
			}
			$info =$this->zyconfig_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('zyconfigdoc');
		}
	}

	/**
	 * 删除配置
	 * @return [type] [description]
	 */
	public function configdel()
	{
		if(intval($_GET['id']))
		{
			$result=$this->zyconfig_db->delete(array('id'=>$_GET['id']));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id']))
		{
			foreach($_POST['id'] as $id) {
				$result=$this->zyconfig_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])) {
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}


//======================配置模块-配置管理-通讯配置（别人需要的） END




	/**
	* 通讯管理_消息管理
	*/
	public function message_manage()
	{		
		$where= 'types=1';
		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and username like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 2){
					$where .= " and userid ='".$_GET['q']."'";
				}
				if($_GET['type'] == 3){
					$where .= " and nickname like '%".$_GET['q']."%' ";
				}
				if($_GET['type'] == 4){
					$where .= " and mobile like '%".$_GET['q']."%' ";

				}
			}
		}
		if($_GET['type2']){
			if($_GET['q2']){
				if($_GET['type2'] == 1){
					$where .= " and title like '%".$_GET['q2']."%' ";
				}
			}
		}
		if($_GET['status']){
			if($_GET['status'] == 1){
				$where .= " and status=1"; 
			}else if($_GET['status'] == 2){
				$where .= " and status=2";
			}
		}
		
		if($_GET['start_addtime']){
			$where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";
		}
		if($_GET['end_addtime']){
			$where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
		}

		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->zymessagesys_record_db->listinfo($where,$order,$page); //读取数据库里的字段
		$pages = $this->zymessagesys_record_db->pages;  //分页
		
		include $this->admin_tpl('message_manage'); //和模板对应上
	}


	/**
	* 通讯管理_消息管理_删除
	*/
	public function message_manage_del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->zymessagesys_record_db->delete(array('id'=>$id));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $pid) {
				$result=$this->zymessagesys_record_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的信息',HTTP_REFERER);
		}
	}

	/**
	* 通讯管理_消息管理_单发
	*/
	public function message_manage_adddf()
	{
		//$config = $this->zymessage_config_db->get_one('id=1');

		if($_POST['dosubmit']){
			$userid = $_POST['userid'];
			
			//==================	获取其他接口-接口 START
			$config = $this->zyconfig_db->get_one(array('key'=>'zymember1'),"url");
			$curl = [
				'userid'=>$userid,
					'field'=>"userid,nickname,mobile,username",
				];
			$memberinfo = _crul_post($config['url'],$curl);
			$memberinfo=json_decode($memberinfo,true);
			if($memberinfo['status']=="error"){
				showmessage($memberinfo['message'],HTTP_REFERER);
			}
			//==================	获取其他接口-接口 END		
				
			$admin_username = param::get_cookie('admin_username');
			//系统消息
			$arr = array(
				'userid'=>$memberinfo['data']['userid'],
				'username'=>$memberinfo['data']['username'],
				'mobile'=>$memberinfo['data']['mobile'],
				'nickname'=>$memberinfo['data']['nickname'],
				'content'=>	$_POST['content'],
				'title'	=>	$_POST['title'],
				'url'	=>	$_POST['url'],
				'addtime'=>	time(),
				'status'=>	'1',
				'types'	=>	'1',
				'sendname'	=>	'后台 '.$admin_username,
			);
			$this->zymessagesys_record_db->insert($arr);
			showmessage("发送成功",HTTP_REFERER);
		}else{
			$show_header = false;		//去掉最上面的线条
			include $this->admin_tpl('message_manage_adddf'); //和模板对应上
		}
	}


	/**
	* 通讯管理_消息管理_群发
	*/
	public function message_manage_addqf()
	{	
		//$config = $this->zymessage_config_db->get_one('id=1');
		if($_POST['dosubmit']){

			$groupid = $_POST['vip_type'];
			//==================	获取其他接口-接口 START
			$config = $this->zyconfig_db->get_one(array('key'=>'zymessagesys8'),"url");
			$curl = [
				'groupid'=>$groupid
			];
			$memberinfo = _crul_post($config['url'],$curl);
			$memberinfo=json_decode($memberinfo,true);
			if($memberinfo['status']=="error"){
				showmessage($memberinfo['message'],HTTP_REFERER);
			}
			$memberinfo = $memberinfo['data'];
			//==================	获取其他接口-接口 END		


			$admin_username = param::get_cookie('admin_username');
			for($i=0;$i<count($memberinfo);$i++){
				/*系统消息*/
				$arr = array(
					'userid'=>$memberinfo[$i]['userid'],
					'username'=>$memberinfo[$i]['username'],
					'mobile'=>$memberinfo[$i]['mobile'],
					'nickname'=>$memberinfo[$i]['nickname'],
					'content'=>	$_POST['content'],
					'title'	=>	$_POST['title'],
					'url'	=>	$_POST['url'],
					'addtime'=>	time(),
					'status'=>	'2',
					'types'	=>	'1',
					'sendname'	=>	'后台 '.$admin_username,
				);
				$this->zymessagesys_record_db->insert($arr);
			}
			
			showmessage("发送成功",HTTP_REFERER);
		}else{
			$show_header = false;		//去掉最上面的线条

			//==================	获取其他接口-接口 START
				$config = $this->zyconfig_db->get_one(array('key'=>'zymember8'),"url");
				$memberinfo = _crul_get($config['url']);
				$memberinfo=json_decode($memberinfo,true);
			//==================	获取其他接口-接口 END		

			include $this->admin_tpl('message_manage_addqf'); //和模板对应上
		}
	}

	/**
	* 短信模块_消费记录
	*/
	public function sms_record()
	{
		$where = '1';
		$order = 'id DESC';//DESC倒叙 //ASC正序
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->sms_xf_record_db->listinfo($where,$order,$page); //读取数据库里的字段  5个数据一页
		$pages = $this->sms_xf_record_db->pages;  		//分页
		$count = $this->sms_xf_record_db->select();		//获取数据总数
		$count = count($count);		//获取数据总数

		include $this->admin_tpl('sms_record');
	}


	//短信模块_平台设置
	public function sms_record_del()
	{
		if(!$_GET['id'])
		{
			showmessage('id不能为空',HTTP_REFERER);
		}
		$this->sms_xf_record_db->delete(array('id'=>$_GET['id']));  //删除id
		showmessage('删除成功',HTTP_REFERER);//返回当前页面
	}

	/**
	* 短信模块_平台设置
	*/
	public function sms_confing()
	{
		if($_POST['dosubmit']){
			set_config($_POST['setconfig'],'zysystem');	 //保存进config文件
			showmessage("操作成功",HTTP_REFERER);
		}else{
			$show_validator = true;
			//这里是获取ZY系统配置的信息
			$setconfig = pc_base::load_config('zysystem');	
			extract($setconfig);
	
			include $this->admin_tpl('sms_confing');
		}
	}



}
?>