<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_class('foreground');

class store extends foreground {
	function __construct() {

		$this->get_db = pc_base::load_model('get_model');
		$this->member_db = pc_base::load_model('member_model');
		$this->members_db = pc_base::load_model('member_model');
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		$this->sso_members_db = pc_base::load_model('sso_members_model');
		$this->module_db = pc_base::load_model('module_model');

		$this->_userid = param::get_cookie('_userid');

	}

//=========================== 操作 START

	/**
	 * 店铺审核页
	 */
	public function store_audit()
	{
		$_userid = $this->_userid;
		$memberinfo = $this->member_db->get_one(['userid'=>$_userid]);

		if ($_GET['clear']) {
			$this->member_db->update(['store_audit'=>0,'shopname'=>'','store_logo'=>''],['userid'=>$_userid]);
		}

		if($memberinfo['groupid']==2 && $memberinfo['store_audit']==1){
			//待审核
			include template('zymember', 'store_audit1');
		}elseif($memberinfo['groupid']==2 && $memberinfo['store_audit']==3){
			//驳回
			include template('zymember', 'store_audit2');
		}else{
			//申请店铺
			include template('zymember', 'store_audit');
		}
	}

	/**
	 * 店铺首页
	 */
	public function init()
	{
		$_userid = $this->_userid;
		$memberinfo = $this->member_db->get_one(['userid'=>$_userid]);

		include template('zymember', 'store_init');
	}


//=========================== 操作 END
	


}
?>
