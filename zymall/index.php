<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
require_once 'classes/phpqrcode/QRcode.class.php';
class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->qrcode_db = pc_base::load_model('zyqrcode_model');
	}


	/*
	 * 显示信息
	 * */
	public function index_show()
	{
	    if($_GET['obj']){
	        $project=$_GET['obj'];
	        //echo $project;
        }
	    //echo $this->strget(APP_PATH.'uploadfile/qrcode/1552286253.png');
        //echo $this->scerweima1('https://www.baidu.com');
		//echo $this->update_qrcode('http://www.baidu.com','uploadfile/qrcode/1552286253.png');//调用查看结果
		include template('zyqrcode','index');
	}

}
?>