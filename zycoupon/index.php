<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->qrcode_db = pc_base::load_model('zyqrcode_model');
	}


	/*
	 * 显示信息
	 * */
	public function show_coupon()
	{

		include template('zyqrcode','index');
	}

}
?>