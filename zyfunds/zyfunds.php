<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class zyfunds extends admin {
	function __construct() {
		parent::__construct();
		$this->get_db = pc_base::load_model('get_model');
		$this->zyfound_bankcard_db = pc_base::load_model('zyfound_bankcard_model');
		$this->zyfound_pay_db = pc_base::load_model('zyfound_pay_model');
		$this->zyfound_tx_db = pc_base::load_model('zyfound_tx_model');
		$this->zyfound_bank_db = pc_base::load_model('zyfound_bank_model');
		$this->zyfound_account_db = pc_base::load_model('zyfound_account_model');
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
	}

	/*
	 * 银行卡信息
	 * */
	public function zybanklist()
	{
		$page = empty($_GET['page'])?1:intval($_GET['page']);
		$info = $this->zyfound_bank_db->listinfo('','id asc',$page,10);
		$pages = $this->zyfound_bank_db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyfunds&c=zyfunds&a=zybanklistadd\', title: \'添加银行\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加银行');
		include $this->admin_tpl('zybanklist');
	}

	/*
	 * 添加银行卡信息
	 * */
	public function zybanklistadd()
	{
		if(isset($_POST['dosubmit'])){
			$data['bank'] = $_POST['bank'];
			$data['desc'] = $_POST['desc'];
			$data['thumb'] = $_POST['thumb'];
			$data['status'] = $_POST['status'];
			if(empty($_POST['status'])){
				$data['status'] = 0;
			}
			
			if($this->zyfound_bank_db->insert($data)){
				showmessage('操作成功','index.php?m=zyfunds&c=zyfunds&a=zybanklistadds');
			}else{
				showmessage('操作失败','index.php?m=zyfunds&c=zyfunds&a=zybanklistadds');
			}
		}else{
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('zybanklistadd');
		}
	}

	public function zybanklistadds(){
		showmessage('已操作','','','add');
	}

	/*
	 * 添加银行卡信息
	 * */
	public function zybanklistedit()
	{
		if(isset($_POST['dosubmit'])){
			$data['bank'] = $_POST['bank'];
			$data['desc'] = $_POST['desc'];
			$data['thumb'] = $_POST['thumb'];
			$data['status'] = $_POST['status'];
			if(empty($_POST['status'])){
				$data['status'] = 0;
			}
			if($this->zyfound_bank_db->update($data,array('id'=>$_POST['id']))){
				showmessage('操作成功','index.php?m=zyfunds&c=zyfunds&a=zybanklistedits');
			}else{
				showmessage('操作失败','index.php?m=zyfunds&c=zyfunds&a=zybanklistedits');
			}
		}else{
			$info = $this->zyfound_bank_db->get_one(array('id'=>$_GET['id']));
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('zybanklistedit');
		}
	}

	public function zybanklistedits(){
		showmessage('已操作','','','edit');
	}
	
	/*
	 * 删除银行卡信息
	 * */
	public function zybanklistdel(){
		$id = intval($_GET['id']);
		if($id){
			$result=$this->zyfound_bank_db->delete(array('id'=>$id));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $id) {
				$result=$this->zyfound_bank_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])){
			showmessage('请选择要删除的订单',HTTP_REFERER);
		}
	}
	
	/*
	 * 支付类型
	 * */
	public function zypaylist()
	{
		include $this->admin_tpl('zypaylist');
	}
	
	/**
	 * 账号管理
	 */
	public function zhmanage()
	{
		$where = 1;
		if($_GET['type']==1){
			$where .= " and `username` like '%{$_GET['q']}%'";
		}elseif($_GET['type']==2){
			$where .= " and `phone` like '%{$_GET['q']}%'";
		}elseif($_GET['type']==3){
			$where .= " and `account` like '%{$_GET['q']}%'";
		}

		$page = empty($_GET['page'])?1:intval($_GET['page']);
		$infos = $this->zyfound_bankcard_db->listinfo($where,'id desc',$page,20);
		$pages = $this->zyfound_bankcard_db->pages;
		include $this->admin_tpl('zhmanage');
	}

	/**
	 * 账号删除
	 */
	public function zhmanagedel()
	{
		if(intval($_GET['id']))
		{
			if($this->zyfound_bankcard_db->delete(array('id'=>$_GET['id'])))
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
				$result=$this->zyfound_bankcard_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])) {
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}

	/**
	 * 提现列表
	 */
	function withdrawcash(){
		$where = 1;
		if(!empty($_GET['types'])){
			if(intval($_GET['types'])==1){
				$where .= ' and `trade_sn` like "%{$_GET["q"]}%"';
			}elseif(intval($_GET['types'])==2){
				$where .= ' and `phone` like "%{$_GET["q"]}%"';
			}
		}

		if(!empty($_GET['status'])){
			if($_GET['status'] == 1){
				$where .= " and `status`=0";
			}elseif($_GET['status'] == 2){
				$where .= " and `status`=1";
			}elseif($_GET['status'] == 3){
				$where .= " and `status`=2";
			}
		}

		if(!empty($_GET['start_addtime'])){
			$where .= " and `addtime` >= '".strtotime($_GET['start_addtime'])."'";
		}
		if(!empty($_GET['end_addtime'])){
			$where .= " and `addtime` <= '".strtotime($_GET['end_addtime'])."'";
		}

		if(!empty($_GET['s'])){
			$where .= " and `amount` >= {$_GET['s']}";
		}
		if(!empty($_GET['l'])){
			$where .= " and `amount` <= {$_GET['l']}";
		}

		$order = 'id DESC';
		$page = empty($_GET['page']) ? 1: intval($_GET['page']);
		$info = $this->zyfound_tx_db->listinfo($where,$order,$page,10); //读取数据库里的字段
		$pages = $this->zyfound_tx_db->pages;  //分页
		include $this->admin_tpl('withdrawcash');
	}

	/**
	 * 驳回理由
	 */
	public function withdrawcashedit(){
		if(isset($_POST['dosubmit'])) {
			$data['reason'] = $_POST['reason'];
			$data['status'] = 2;
			$map['id'] = $_POST['id'];
			$url = $this->zyconfig_db->get_one(array('key'=>'zymember2'));

			if (!empty($url['url'])) {
				$userid = $this->zyfound_tx_db->get_one(array('id'=>$_POST['id']));
				$params = array('userid'=>$userid['userid'],'module'=>'zyfunds','describe'=>'资金退回','amount'=>$userid['amount']);
				$paramstring = http_build_query($params);
				$content = $this->juhecurl($url['url'],$paramstring);
				$res = json_decode($content,true);
				
				if($res['code']==200){
					if($this->zyfound_tx_db->update($data,$map)){
						showmessage('操作成功','index.php?m=zyfunds&c=zyfunds&a=withdrawcashedits');
					}else{
						showmessage('操作失败','index.php?m=zyfunds&c=zyfunds&a=withdrawcashedits');
					}
				}
			}
		}
		include $this->admin_tpl("withdrawcashedit");
	}

	public function withdrawcashedits(){
		showmessage("操作中间",'','','edit');
	}

	/**
	 * 驳回理由
	 */
	public function withdrawcashview(){
		if(isset($_POST['dosubmit'])){
			showmessage('查阅完毕','','','view');
		}else{
			$info = $this->zyfound_tx_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl("withdrawcashview");
		}
	}


	/**
	 * 审核提现
	 */
	public function repass(){
		$id = $_GET['id'];
		$this->zyfound_tx_db->update(array('status'=>'0'),array('id'=>$_GET['id']));
		showmessage("审核通过",HTTP_REFERER);
	}

	/**
	 * 充值管理_充值记录
	 */
	public function rechargelist(){
		$where = '1';
		if(!empty($_GET['type'])&&$_GET['type']==1){
			$where .= " AND `trade_sn` LIKE '%{$_GET['q']}%' ";
		}
		if(!empty($_GET['type'])&&$_GET['type']==2){
			$where .= " AND `phone` LIKE '%{$_GET['q']}%' ";
		}
		if(!empty($_GET['start_addtime'])){
			$where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";
		}
		if(!empty($_GET['end_addtime'])){
			$where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
		}
		$page = empty($_GET['page']) ? 1: $_GET['page'];
		$infos = $this->zyfound_pay_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, 20);
		$pages = $this->zyfound_pay_db->pages;
		include $this->admin_tpl('rechargelist');
	}

	/**
	 * 充值记录_删除
	 */
	public function recharge_del(){
		$id = intval($_GET['id']);
		if($id){
			$result=$this->zyfound_pay_db->delete(array('id'=>$id));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $id) {
				$result=$this->zyfound_pay_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])){
			showmessage('请选择要删除的订单',HTTP_REFERER);
		}
	}

	/*
	 * 配置模块
	 * */
	public function zyconfig()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyfunds&c=zyfunds&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		$order = 'id DESC';
		$where = array("item_name"=>'zyfunds');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->zyconfig_db->listinfo($where,$order,$page,20);
		$pages = $this->zyconfig_db->pages;

		include $this->admin_tpl('zyconfig');
	}
	
	/*
	 * 添加配置
	 * */
	public function configadd()
	{

		if($_POST['dosubmit'])
		{
			if(empty($_POST['config_name']))
			{
				showmessage('请输入项目名',HTTP_REFERER);
			}
			$num = $this->zyconfig_db->count(array('item_name'=>"zyfunds"))+1;
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'item_name'=>"zyfunds",
				"key"=>"zyfunds".$num,
				'url'=>$_POST['url'],
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

	/**
	 * 请求接口返回内容
	 * @param  string $url [请求的URL地址]
	 * @param  string $params [请求的参数]
	 * @param  int $ipost [是否采用POST形式]
	 * @return  string
	 */
	public function juhecurl($url,$params=false,$ispost=0){
		$httpInfo = array();
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
		curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		if( $ispost )
		{
			curl_setopt( $ch , CURLOPT_POST , true );
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
			curl_setopt( $ch , CURLOPT_URL , $url );
		}
		else
		{
			if($params){
				curl_setopt( $ch , CURLOPT_URL , $url.'&'.$params );
			}else{
				curl_setopt( $ch , CURLOPT_URL , $url);
			}
		}
		$response = curl_exec( $ch );

		if ($response === FALSE) {
			//echo "cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
		$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
		curl_close( $ch );
		return $response;
	}
}









