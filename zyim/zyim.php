<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');
pc_base::load_app_func('admin', 'set_config', 0);	//加载应用类方法

class zyim extends admin
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
		//消息
		$this->online_talk_list_db = pc_base::load_model('online_talk_list_model');	//聊天列表
		$this->online_talk_record_db = pc_base::load_model('online_talk_record_model');	//聊天记录

	}




	/**
	 * 聊天消息-列表
	 */
	public function zyim_list()
	{
		//$where = ['item_name'=>'zymessagesys'];
		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->online_talk_list_db->listinfo('',$order,$page,20);
		$pages = $this->online_talk_list_db->pages;

		include $this->admin_tpl('zyim_list');
	}




	/*
	 * 通讯配置-添加
	 * */
	public function talk()
	{
		if($_POST['dosubmit'])
		{

		}
		else
		{
			$record_id=$_GET['records_id'];
			$where=['records_id'=>$record_id];
			$infos=$this->online_talk_record_db->select($where);
			$info=$this->online_talk_list_db->get_one($where);
			include $this->admin_tpl('talk');
		}
	}


	/**
	 * 删除
	 * @return [type] [description]
	 */
	public function del()
	{
		if(intval($_GET['id']))
		{
			$result=$this->online_talk_list_db->delete(array('id'=>$_GET['id']));
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
				$result=$this->online_talk_list_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])) {
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}


//======================配置模块-配置管理-通讯配置（别人需要的） END


}
?>