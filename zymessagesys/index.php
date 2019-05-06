<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');

class index
{
	function __construct() 
	{

		$this->get_db = pc_base::load_model('get_model');
	}


	/**
	* 消息列表
	*/
	public function message_list()
	{
		include template('zymessagesys', 'message_list');
	}

	/**
	* 消息内容页
	*/
	public function message_show()
	{
		include template('zymessagesys', 'message_show');
	}



}
?>
