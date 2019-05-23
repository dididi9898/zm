<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');

class index
{
	function __construct() 
	{
		$this->get_db = pc_base::load_model('get_model');
		$this->online_talk_list_db = pc_base::load_model('online_talk_list_model');	//聊天列表
		$this->_userid = param::get_cookie('_userid');
	}


	/**
	* 消息列表
	*/
	public function im_list()
	{
		$_userid = $this->_userid;
		include template('zyim', 'im_list');
	}

	/**
	* 消息内容页
	*/
	public function im_talk()
	{
		$_userid = $this->_userid;
		$where=['records_id'=>$_userid];
		$info=$this->online_talk_list_db->get_one($where,'talk_from_name,talk_from_img,talk_to_name,talk_to_img');
		include template('zyim', 'im_talk');
	}



}
?>
