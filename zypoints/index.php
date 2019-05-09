<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->_userid = param::get_cookie('_userid');
	}


	public function gift_exchange()
	{
		$_userid = $this->_userid;
		include template('zypoints','gift_exchange');
	}
}
?>