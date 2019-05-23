<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');

class index
{
	function __construct() 
	{

		$this->get_db = pc_base::load_model('get_model');
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
		include template('zyim', 'im_talk');
	}



}
?>
