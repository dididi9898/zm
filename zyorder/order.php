<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');
pc_base::load_app_func("EX");
class order extends admin {
	/**
	*构造函数，初始化
	*/
    public static $statusType = ["1"=>"代付款","2"=>"待发货","3"=>"待收货","4"=>"待评价","5"=>"已评价","6"=>"已删除"];
    public static $pay_type = ["1"=>"余额","2"=>"支付宝", "3"=>"微信"];

	public function __construct()
	{
		
		//开启session会话
		session_start();
		//初始化父级的构造函数
		parent::__construct();
		//会员主表
		$this->members_db = pc_base::load_model('members_model');
		//会员附表
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		$this->shop_db = pc_base::load_model('shop_model');
		//订单中心
		$this->order_db = pc_base::load_model('zy_order_model');
		$this->evaluate_set_db = pc_base::load_model('zy_evaluate_set_model');
	    $this->logistics_company_db = pc_base::load_model('zy_logistics_company_model');
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
		$this->config = $this->zyconfig_db->get_one('','url');
		$this->ordergoods_db = pc_base::load_model('zy_order_goods_model');

		//快递公司表
		$this->express_db = pc_base::load_model('zyexpress_model');
		//$this->zy_logistics_company = pc_base::load_model('zy_logistics_company');
        $this->pagesize = 10;

		//引入卓远网络公共函数库
		//require_once 'zywl/functions/global.func.php';
	}

/**
* 菜单===========================================
*/
//订单管理
//	--订单管理
//	--订单管理_商品信息
//	--订单管理_购买者信息
//	--订单管理_物流信息
//	--订单管理_订单发货
//	--订单管理_删除
//	--物流管理
//	--订单管理_添加物流
//	--订单管理_删除
//	--设置关键词
//	--清除缓存
		



/**
* 订单管理===========================================
*/




    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
	/**
	* 订单管理
	*/
	public function order_list(){

        $status = isset($_GET["status"])?intval($_GET["status"]):"";
        $page = isset($_GET["page"])?intval($_GET["page"]):1;
        $start_addtime = isset($_GET["start_addtime"])?strtotime($_GET["start_addtime"]):"";
        $end_addtime = isset($_GET["end_addtime"])?strtotime($_GET["end_addtime"]):"";
		$where = '1';
        if(!empty($status)) $where .= " AND `status` = ".$status." ";
        if(!empty($start_addtime))
            $where .= " AND `add_time` > '$start_addtime' AND "; //优先考虑几天之内的筛选框中内容去查询数据
        if(!empty($end_addtime))
            $where .= " AND `add_time` < '$end_addtime' AND ";
		if($_GET['ordersn']){
			$where .= " and ordersn ='".$_GET['ordersn']."' ";
		}
		if($_GET['pay_type']){
			//1 .余额 
		    //2 .微信
			//3 .支付宝
			$where .= " and pay_type ='".$_GET['pay_type']."' ";
		}

		$order = 'order_id DESC';
        list($info,$count) = $this->members_db->moreTableSelect(
            array("zy_zy_order"=>array("*"),"zy_member"=>array("nickname", "mobile")),
            array( "userid"),
            $where
            , ((string)($page-1)*$this->pagesize).",".$this->pagesize, "B1.order_id DESC","1"
        );
        list($page, $pagenums) = getPage($page, $this->pagesize, $count);
//		$info=$this->order_db->listinfo($where,$order,$page,20); //读取数据库里的字段
//		$pages = $this->order_db->pages;  //分页
		include $this->admin_tpl('order/order_manage'); //和模板对应上
		//include $this->admin_tpl('order_manage'); //和模板对应上
	}

    function showShop()
    {
        $neadArg = ["order_id"=>[true,1]];
        $info = checkArgBcak($neadArg);
        list($data, $count) = $this->members_db->moreTableSelect(
            array("zy_zy_order"=>array("*"), "zy_order_goods"=>array("*")),
            array("order_id"),
            "B2.order_id=".$info["order_id"],"","","0"
        );
		//exit(var_dump($data));
        include $this->admin_tpl("order/shopShow");
    }
    function showAddress()
    {
        $neadArg = ["order_id"=>[true,1]];
        $info = checkArgBcak($neadArg);
        $data = $this->order_db->get_one($info);
        include $this->admin_tpl("order/addressShow");
    }
    function addEX()
    {
        if(isset($_POST["dosubmit"]))
        {
            $neadArg = ["EXid"=>[true, 1], "logistics_order"=>[true, 0], "ordersn"=>[true,0]];
            $info = checkArgBcak($neadArg, "POST");
            $where["ordersn"] = array_pop($info);
            $info["status"] = "3";
            $info["deltime"] = time();
            $this->order_db->update($info, $where);
            showmessage(L('operation_success'),'index.php?m=zymanagement&c=order&a=showOrder', '5', 'deliver');
        }
        else
        {
            $neadArg = ["ordersn"=>[true,0]];
            $info = checkArgBcak($neadArg);
            $data = $this->logistics_company_db->select("1", "*");
            include $this->admin_tpl("order/addOrderEx");
        }
    }
    function EXInfo()
    {
        $EBINfo = pc_base::load_config('EXinfo');
        include $this->admin_tpl("EX/config");
    }
    function changeEXInfo()
    {
        $neadArg = ["EBusinessID"=>[true,0], "AppKey"=>[true,0], "ReqURL"=>[true,0]];
        $info = checkArgBcak($neadArg, "POST");
        set_config($info,'EXinfo');	 //保存进config文件
        showmessage(L('update_success'), HTTP_REFERER, '', 'view');
    }
    function checkEX()
    {
        $neadArg = ["ordersn"=>[true,0]];
        $info = checkArgBcak($neadArg);
        list($orderInfo,$count) = $this->members_db->moreTableSelect(
            array("zy_zy_order"=>array("logistics_order"), "zy_zy_logistics_company"=>array("name,value")),
            array("EXid"),
            $info
        );
        if($orderInfo == null)
            showmessage("没有该订单信息");
        $EBINfo = pc_base::load_config('EXinfo');
        $data = getOrderTracesByJson($EBINfo, $orderInfo["value"],$orderInfo["logistics_order"]);
        $EXinfo = json_decode($data);
        //$EXname = $this->zyexcompany->get_one(array("abbreviation"=>$EXinfo->ShipperCode), "*");
        include $this->admin_tpl("EX/checkEX");
    }
    function dropOrder()
    {
        $neadArg = ["order_id"=>[true,0]];
        $info = checkArgBcak($neadArg);
        $this->ordergoods_db->delete($info);
        $this->order_db->delete($info);
        showmessage(L('operation_success'), 'index.php?m=zyorder&c=order&a=order_list', '5', '');
    }
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
    //*******************************************************************************************************************
	
	/**
	* 订单管理_删除
	*/
	public function order_manage_del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->order_db->delete(array('order_id'=>$id));
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
				$result=$this->order_db->delete(array('order_id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的信息',HTTP_REFERER);
		}
	}
	


	/**
	* 订单管理_用户信息
	*/
	public function order_manage_userinfo(){
		$show_header = false;		//去掉最上面的线条		
		$id = $_GET['id'];
		$info = $this->order_db->get_one(array('order_id' =>$id));
		include $this->admin_tpl('order_manage_userinfo');  //和模板对应上
		
	}
	
	
	
	/**
	* 订单管理_商品信息
	*/
	public function order_manage_shopinfo(){
    
		$info = $this->ordergoods_db->select(array('order_id'=>$_GET['id']));
		include $this->admin_tpl('order_manage_shopinfo');  //和模板对应上

	}


	/**
	* 订单管理_订单发货_添加物流
	*/
	public function order_manage_ddfh(){	
		
		if($_POST['dosubmit']){
			$wuliu = $this->express_db->get_one(array('code'=>$_POST['shippercode']));
			$info = $this->order_db->update(array('shipper_name' =>$wuliu['company'],'shipper_code' =>$wuliu['code'],'logistics_order' =>$_POST['logistics_order'],'fhtime'=>time(),'status'=>'3'),array('order_id'=>$_POST['id']));
			//发送模板消息‘订单发货通知’	start
			
			/**
			 * 微信模板消息_订单发货通知
			 * @param  [type] $touser [用户openid]
			 * @param  [type] $url 	[跳转的链接地址]
			 * @param  [type] $name    [收货人姓名]
			 * @param  [type] $mobile  [收货人手机号]
			 * @param  [type] $kd_gs  [快递公司]
			 * @param  [type] $kd_order  [快递单号]
			 * @param  [type] $order_sn  [订单号]
			 * @return [type]         [description]
			 */
			 //发送模板消息‘订单支付状态通知’	end
			
			if($info){
				showmessage(L('update_success'), HTTP_REFERER);
			}else{
				showmessage(L("operation_failure"),HTTP_REFERER);
			}		
		}else{
			$orderid = $_GET['id'];
			$info = $this->express_db->get_one(array('id' =>$orderid));
			$infok = $this->express_db->select();
			include $this->admin_tpl('order_manage_ddfh');  //和模板对应上
		}
	}
	
		/**
	* 订单管理_查看物流信息
	*/
	public function order_manage_wlxx(){	

			$id = $_GET['id'];
		    $order =  $this->order_db->get_one(array('order_id'=>$id));
		    $KdApi = pc_base::load_app_class('KdApiSearch');
			$KdApi = new KdApiSearch();
	
			$logisticResult=$KdApi->getOrderTracesByJson($order['shipper_code'],$order['logistics_order']);
			$logisticResult = json_decode($logisticResult,true);
			include $this->admin_tpl('order_manage_wlxx');  //和模板对应上
	
	}

    /**
	* 物流管理
	*/
	public function logistics_company(){
		$where = '1';
		$name=isset($_GET['name'])?$_GET['name']:'';


		if(!empty($name)){
			$where .= " and name like '%$name%'  ";
		}

		$order = 'EXid ASC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		
    
		$info=$this->logistics_company_db->listinfo($where,$order,$page); //读取数据库里的字段 第四个参数 为每页多少条信息
		$pages = $this->logistics_company_db->pages;  //分页

		include $this->admin_tpl('order_logistics');  //和模板对应上
	}
/**
	* 物流管理_删除
	*/
	public function order_logistics_del(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->logistics_company_db->delete(array('id'=>$id));
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
				$result=$this->zyorder_kuaidi_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的信息',HTTP_REFERER);
		}
	}
	/**
	* 物流管理_添加
	*/
	public function order_logistics_add(){
		if($_POST['dosubmit']){
			$info = $this->logistics_company_db->insert(array('name' =>trim($_POST['name']),'value' =>trim($_POST['value'])));

			if($info){
				showmessage('添加成功', HTTP_REFERER);
			}else{
				showmessage('添加失败', HTTP_REFERER);
			}		
		}else{
			include $this->admin_tpl('order_logistics_add');  //和模板对应上
		}
	}

	
	//评价
	public function evaluate_set(){
			$big_menu = array

		(

		    	'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zymessagesys&c=messagesys&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'

		);

		$where = '1';
		$name=isset($_GET['name'])?$_GET['name']:'';


		if(!empty($name)){
			$where .= " and name like '%$name%'  ";
		}

		$order = 'id ASC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		
    
		$info=$this->evaluate_set_db->listinfo($where,$order,$page); //读取数据库里的字段 第四个参数 为每页多少条信息
		$pages = $this->evaluate_set_db->pages;  //分页

		include $this->admin_tpl('evaluate_set');  //和模板对应上
	}

    public function evaluate_set_add(){

	    if($_POST){
	    	$name = $_POST['name'];
	    	$value = $_POST['value'];
	    	$data = [
                 "name"=>$name,
                 "value"=>$value
	    	];
            $result  = $this->evaluate_set_db->insert($data);
            if($result){
				showmessage('添加成功', HTTP_REFERER);
			}else{
				showmessage('添加失败', HTTP_REFERER);
			}	
	    }else{
		include $this->admin_tpl('evaluate_set_add');  //和模板对应上
	    }
	}
	
	public function evaluate_set_del(){
		if($_POST){
			foreach($_POST['id'] as $id){
		        $this->evaluate_set_db->delete(array('id'=>$id));
			}
			showmessage("删除成功",HTTP_REFERER);
		}
	}

	public function config()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyorder&c=order&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		
		$where = ['item_name'=>'zyorder'];
		$order = 'id DESC';
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
			$zyconfig_num = $this->zyconfig_db->count(['item_name'=>'zyorder']);
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'url'=>$_POST['url'],
				'item_name'=>'zyorder',
				'key'=>'zyorder'.($zyconfig_num+1),
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


}
?>