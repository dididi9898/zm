<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class goods extends admin {
	function __construct() {
		parent::__construct();
		$this->get_db = pc_base::load_model('get_model');
		$this->admin = pc_base::load_model('admin_model');
		$this->member_db = pc_base::load_model('member_model');
		$this->member_detail_db = pc_base::load_model('member_detail_model');

		//商品表
		$this->goods_db = pc_base::load_model('goods_model');
		//品牌表
		$this->brand_db = pc_base::load_model('brand_model');
		//推荐位表
		$this->position_db = pc_base::load_model('goodsposition_model');
		//分类栏目表
		$this->goodscat_db = pc_base::load_model('goodscat_model');
		//商品类型表
		$this->goodstype_db = pc_base::load_model('goodstype_model');
		//商品推荐位表
		$this->positem_db = pc_base::load_model('goodspositem_model');

		//商品类型属性表
		$this->goodsattr_db = pc_base::load_model('goodsattr_model');
		//商品规格组合表
		$this->goods_specs_db = pc_base::load_model('goods_specs_model');
		//商品属性表
		$this->goods_attr_db = pc_base::load_model('goods_attr_model');


		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
		$this->config = $this->zyconfig_db->get_one('','url');
		
	}


	/**
	* 订单列表
	*/
	public function goodslist(){
		
		$where = ' isok = 1 ';
		
		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and id =".$_GET['q'];
				}
				elseif($_GET['type'] == 2){
					$where .= " and goods_name like '%".$_GET['q']."%' ";
				}	
			}
		}

		if($_GET['status']){
			$where .= " and on_sale = ".$_GET['status'];
		}

		if($_GET['start_addtime']){
			$start_addtime=$_GET['start_addtime'];
			$where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";   
		}
		if($_GET['end_addtime']){
			$end_addtime=$_GET['end_addtime'];
			$where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
		} 
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->goods_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, $pagesize = 20);
		$pages = $this->goods_db->pages;
		//dump($pages,true);

		$cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
		$cinfo = getcatinfo($cinfo,1);
		$binfo = $this->brand_db->select('1','id,brandname','',$order = 'sort ASC, id DESC');
		$binfo = getcatinfo($binfo,2);
		$tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
		$tinfo = getcatinfo($tinfo,3);
		// dump($tinfo,true);
		//添加商品
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=goodsadd\', title:\'添加商品\', width:\'1200\', height:\'700\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加商品');
		


		include $this->admin_tpl('goodslist');
	}	


	/**
	* 添加商品
	*/
	public function goodsadd(){

		if($_POST['dosubmit']){
			// dump($_POST,true);
			if($_POST['goodsimg_url']){
				$goodsimg=[];
				$count=count($_POST['goodsimg_url']);
				for ($i=0; $i <$count ; $i++) { 
					$goodsimg[]=[
						'url'=>$_POST['goodsimg_url'][$i],
						'alt'=>$_POST['goodsimg_alt'][$i],
					];
				}
			}
			
			$awardNumber = json_encode($_POST["awardNumber"]);
			$trialAwardNumber = json_encode($_POST["trialAwardNumber"]);
			$goodsimg = array2string($goodsimg);
        	$data=[
        		'shopid' => 1,
        		'goods_name'=>$_POST['gname'],
        		'summary'=>$_POST['summary'],
        		'thumb'=>$_POST['thumb'],
        		'album'=>$goodsimg,
        		'content'=>$_POST['content'],
        		'on_sale'=>$_POST['status'],
        		'market_price'=>$_POST['mprice'],
        		'shop_price'=>$_POST['sprice'],
        		'stock'=>$_POST['stock'],
        		'catid' => $_POST['cid'],
				'brand_id' => $_POST['bid'],
				'type_id' => $_POST['tid'],
				'addtime'=>time(),
				'point_mode'=>$_POST["point_mode"],
				'point_value'=>$_POST["point_value"],
				'point_sy_value'=>$_POST["point_sy_value"],
				'trialAwardNumber'=>$trialAwardNumber,
				'awardNumber'=>$awardNumber,
            ];
        	
        	$results=$this->goods_db->insert($data,true);
        	if(isset($_POST['pos'])){
        		foreach ($_POST['pos'] as $kp => $vp) {
        			$resultss=$this->positem_db->insert(['pos_id'=>$vp,'goodsid'=>$results]);
        		}
        	}

        	if(isset($_POST['goodsspec'])){
        		$stock = 0;
        		$len = count($_POST['goodsspec']);
        		$sql= "insert into phpcms_goods_specs ( `shopid`, `goodsid`, `specid`, `specids`, `makerprice`, `specprice`, `specstock`, `status`) values";
				for($i=0;$i<$len;$i++){
					$sql.="(1, ".$results.", '".$_POST['goodsspec'][$i]['key']."', '".$_POST['goodsspec'][$i]['val']."', '', '".$_POST['goodsspec'][$i]['bprice']."', '".$_POST['goodsspec'][$i]['stock']."', '".$_POST['goodsspec'][$i]['open']."'),";
					$stock += $_POST['goodsspec'][$i]['stock'];
				};

				$this->goods_db->update(['stock'=>$stock,'isspec'=>1],['id'=>$results]);
				$sql = substr($sql,0,strlen($sql)-1);
				$this->goods_specs_db->query($sql);
        	}

        	if(isset($_POST['goods_attr'])){

        		$sqls= "insert into phpcms_goods_attr ( `shopid`, `goodsid`, `attrid`, `val`) values";
				foreach ($_POST['goods_attr'] as $k => $v) {
					$sqls.="(1, ".$results.", ".$k.", '".$v."'),";
				}
				$sqls = substr($sqls,0,strlen($sqls)-1);
				$this->goods_attr_db->query($sqls);
        	}

        	showmessage(L('添加商品成功'), '?m=hpshop&c=goods&a=goodsadds');

		}else{
			$cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
			$cinfo = catetree($cinfo);
			$binfo = $this->brand_db->select('1','id,brandname','',$order = 'sort ASC, id DESC');
			$tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
			$pinfo = $this->position_db->select('1','id,posname','',$order = 'sort ASC, id ASC');
			// dump($binfo,true);
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");

			$upload_number = '10';
			$upload_allowext = 'gif|jpg|jpeg|png|bmp'; 
			$isselectimage = '0';
			$authkeys = upload_key("$upload_number,$upload_allowext,$isselectimage");

			$allowuploadnum = '10';
			$alowuploadexts = '';
			$allowbrowser = 1;
			$authkeyss = upload_key("$allowuploadnum,$alowuploadexts,$allowbrowser");

			include $this->admin_tpl('goodsadd');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function goodsadds(){
		showmessage(L('operation_success'), '', '', 'add');
	}



	/**
	 * 检查商品名称重复性
	 * @param 
	 */
	// public function checkbrand_ajax() {
	// 	$bname = isset($_GET['bname']) && trim($_GET['bname']) ? trim($_GET['bname']) : exit(0);
	// 	if(CHARSET != 'utf-8') {
	// 		$bname = iconv('utf-8', CHARSET, $bname);
	// 		$bname = addslashes($bname);
	// 	}
	// 	if ($r = $this->brand_db->get_one(array('brandname'=>$bname))){
	// 		if($r['id']==$_GET['id']){
	// 			exit();
	// 		}else{
	// 			exit('FALSE');
	// 		}
	// 	} else {
	// 		exit();
	// 	}		
	// }


	/**
	* 商品_删除
	*/
	public function goodsdel(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->goods_db->delete(array('id'=>$id));
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
				$result=$this->goods_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}


	/**
	* 编辑商品
	*/
	public function goodsedit(){

		if($_POST['dosubmit']){
			$id=$_POST['id'];
			if($_POST['goodsimg_url']){
				$goodsimg=[];
				$count=count($_POST['goodsimg_url']);
				for ($i=0; $i <$count ; $i++) { 
					$goodsimg[]=[
						'url'=>$_POST['goodsimg_url'][$i],
						'alt'=>$_POST['goodsimg_alt'][$i],
					];
				}
			}
            $awardNumber = json_encode($_POST["awardNumber"]);
            $trialAwardNumber = json_encode($_POST["trialAwardNumber"]);
			$goodsimg = array2string($goodsimg);
        	$data=[
        		'goods_name'=>$_POST['gname'],
        		'summary'=>$_POST['summary'],
        		'thumb'=>$_POST['thumb'],
        		'album'=>$goodsimg,
        		'content'=>$_POST['content'],
        		'on_sale'=>$_POST['status'],
        		'market_price'=>$_POST['mprice'],
        		'shop_price'=>$_POST['sprice'],
        		'catid' => $_POST['cid'],
				'brand_id' => $_POST['bid'],
				'type_id' => $_POST['tid'],
				'stock'=>$_POST['stock'],
                'point_mode'=>$_POST["point_mode"],
                'point_value'=>$_POST["point_value"],
                'point_sy_value'=>$_POST["point_sy_value"],
                'trialAwardNumber'=>$trialAwardNumber,
                'awardNumber'=>$awardNumber,
        	];

        	
        	if(isset($_POST['pos'])){
        		$results = $this->positem_db->delete(['goodsid'=>$id]);
        		foreach ($_POST['pos'] as $kp => $vp) {
        			$resultss=$this->positem_db->insert(['pos_id'=>$vp,'goodsid'=>$id]);
        		}
        	}else{
        		$results = $this->positem_db->delete(['goodsid'=>$id]);
        	}
        	$results=$this->goods_db->update($data,array('id'=>$id));

        	if(isset($_POST['goodsspecs'])){
        		$stock = 0;
        		$sql= "replace into phpcms_goods_specs (`id`, `shopid`, `goodsid`, `specid`, `specids`, `makerprice`, `specprice`, `specstock`, `status`) values";
				
				foreach ($_POST['goodsspecs'] as $k => $v) {
					$sql.="(".$k.", 1, ".$id.", '".$v['key']."', '".$v['val']."', '', '".$v['bprice']."', '".$v['stock']."', '".$v['open']."'),";
					$stock += $v['stock'];
				}
				$this->goods_db->update(['stock'=>$stock],['id'=>$id]);
				$sql = substr($sql,0,strlen($sql)-1);
				$this->goods_specs_db->query($sql);
        	}

        	if(isset($_POST['goods_attrs'])){

        		$sqls= "replace into phpcms_goods_attr (`id`, `shopid`, `goodsid`, `attrid`, `val`) values";
				foreach ($_POST['goods_attrs'] as $k => $v) {
					$sqls.="(".$k.", 1, ".$id.", ".$v['aid'].", '".$v['val']."'),";
				}
				$sqls = substr($sqls,0,strlen($sqls)-1);
				$this->goods_attr_db->query($sqls);
        	}

        	if(isset($_POST['goodsspec'])){
        		$this->goods_specs_db->delete(['goodsid'=>$id]);
        		$stock = 0;
        		$len = count($_POST['goodsspec']);
        		$sql= "insert into phpcms_goods_specs ( `shopid`, `goodsid`, `specid`, `specids`, `makerprice`, `specprice`, `specstock`, `status`) values";
				for($i=0;$i<$len;$i++){
					$sql.="(1, ".$id.", '".$_POST['goodsspec'][$i]['key']."', '".$_POST['goodsspec'][$i]['val']."', '', '".$_POST['goodsspec'][$i]['bprice']."', '".$_POST['goodsspec'][$i]['stock']."', '".$_POST['goodsspec'][$i]['open']."'),";
					$stock += $_POST['goodsspec'][$i]['stock'];
				};
				$this->goods_db->update(['stock'=>$stock,'isspec'=>1],['id'=>$id]);
				$sql = substr($sql,0,strlen($sql)-1);
				$this->goods_specs_db->query($sql);				
        	}

        	if( !isset($_POST['goodsspec']) && !isset($_POST['goodsspecs']) ){
        		$this->goods_db->update(['isspec'=>0],['id'=>$id]);
        	}

        	if(isset($_POST['goods_attr'])){
				$this->goods_attr_db->delete(['goodsid'=>$id]);
        		$sqls= "insert into phpcms_goods_attr ( `shopid`, `goodsid`, `attrid`, `val`) values";
				foreach ($_POST['goods_attr'] as $k => $v) {
					$sqls.="(1, ".$id.", ".$k.", '".$v."'),";
				}
				$sqls = substr($sqls,0,strlen($sqls)-1);
				$this->goods_attr_db->query($sqls);

        	}

        	showmessage(L('修改商品信息成功'), '?m=hpshop&c=goods&a=goodsedits');
			
		}else{
			$info = $this->goods_db->get_one(array('id'=>$_GET['id']));
			$info["awardNumber"] = json_decode($info["awardNumber"], true);
			$info["trialAwardNumber"] = json_decode($info["trialAwardNumber"], true);
			$gattr = $this->goodsattr_db->select(array('goodstypeid'=>$info['type_id'],'attrtype'=>1));

			// $num = count($gattr);
			// $gattr = $this->newattr($gattr,$num);
			//$gattrs = $this->goods_attr_db->select(array('goodsid'=>$_GET['id']));
			//dump($gattr,true);
			$where= 'a.attrid = b.id and a.goodsid = '.$_GET['id'];
		    $sql = 'SELECT a.id, a.val, a.attrid, b.attrname FROM phpcms_goods_attr a,phpcms_goodsattr b WHERE '.$where.' ORDER BY a.id ASC';
		    $page = intval($_GET['page']);
		    $gattrs = $this->get_db->multi_listinfo($sql,$page,999999);
		    //dump($gattrs,true);
			if($info['isspec'] == 1){
				$gspec = $this->goods_specs_db->select(array('goodsid'=>$_GET['id']));
			}
			
			// $narr = [];
			// foreach ($gspec as $ks => $vs) {
			// 	$narr[$vs['specid']] = $vs;
			// }


			$alinfo = string2array($info['album']);					
			$cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
			$cinfo = catetree($cinfo);
			$binfo = $this->brand_db->select('1','id,brandname','',$order = 'sort ASC, id DESC');
			$tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
			$pinfo = $this->position_db->select('1','id,posname','',$order = 'sort ASC, id ASC');
			$posinfo =$this->positem_db->select(['goodsid'=>$_GET['id']],'pos_id');
			$posarr = [];
			foreach ($posinfo as $key => $value) {
				$posarr[] = $value['pos_id'];
			}


			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");

			$upload_number = '10';
			$upload_allowext = 'gif|jpg|jpeg|png|bmp'; 
			$isselectimage = '0';
			$authkeys = upload_key("$upload_number,$upload_allowext,$isselectimage");

			$allowuploadnum = '10';
			$alowuploadexts = '';
			$allowbrowser = 1;
			$authkeyss = upload_key("$allowuploadnum,$alowuploadexts,$allowbrowser");
			include $this->admin_tpl('goodsedit');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function goodsedits(){
		showmessage(L('operation_success'), '', '', 'edit');
	}



	/**
	* 订单列表
	*/
	public function goodsverify(){
		
		$where = ' isok = 2 ';
		if($_GET['level']){
			$where = ' isok = 3 ';
		}
		
		if($_GET['type']){
			if($_GET['q']){
				if($_GET['type'] == 1){
					$where .= " and id =".$_GET['q'];
				}
				elseif($_GET['type'] == 2){
					$where .= " and goods_name like '%".$_GET['q']."%' ";
				}	
			}
		}

		if($_GET['status']){
			$where .= " and on_sale = ".$_GET['status'];
		}

		if($_GET['start_addtime']){
			$start_addtime=$_GET['start_addtime'];
			$where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";   
		}
		if($_GET['end_addtime']){
			$end_addtime=$_GET['end_addtime'];
			$where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
		} 
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->goods_db->listinfo($where, $order = 'addtime DESC,id DESC', $page, $pagesize = 20);
		$pages = $this->goods_db->pages;

		$cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
		$cinfo = getcatinfo($cinfo,1);
		$binfo = $this->brand_db->select('1','id,brandname','',$order = 'sort ASC, id DESC');
		$binfo = getcatinfo($binfo,2);
		$tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
		$tinfo = getcatinfo($tinfo,3);
			
		include $this->admin_tpl('goodsverify');
	}	


	/**
	* 查看商品
	*/
	public function goodsview(){

		if($_POST['dosubmit']){
			showmessage(L('operation_success'), '', '', 'view');			
		}else{
			$info = $this->goods_db->get_one(array('id'=>$_GET['id']));
			
			$gattr = $this->goodsattr_db->select(array('goodstypeid'=>$info['type_id'],'attrtype'=>1));

			$where= 'a.attrid = b.id and a.goodsid = '.$_GET['id'];
		    $sql = 'SELECT a.id, a.val, a.attrid, b.attrname FROM phpcms_goods_attr a,phpcms_goodsattr b WHERE '.$where.' ORDER BY a.id ASC';
		    $page = intval($_GET['page']);
		    $gattrs = $this->get_db->multi_listinfo($sql,$page,999999);

			$gspec = $this->goods_specs_db->select(array('goodsid'=>$_GET['id']));

			$alinfo = string2array($info['album']);					
			$cinfo = $this->goodscat_db->select('1','id,cate_name,pid','',$order = 'sort ASC,id ASC');
			$cinfo = catetree($cinfo);
			$binfo = $this->brand_db->select('1','id,brandname','',$order = 'sort ASC, id DESC');
			$tinfo = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
			$pinfo = $this->position_db->select('1','id,posname','',$order = 'sort ASC, id ASC');
			$posinfo =$this->positem_db->select(['goodsid'=>$_GET['id']],'pos_id');
			$posarr = [];
			foreach ($posinfo as $key => $value) {
				$posarr[] = $value['pos_id'];
			}

			include $this->admin_tpl('goodsview');
		}
	}


	/**
	* 审核商品通过
	*/
	public function goodsagree(){
		//操作单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->goods_db->update(array('isok'=>1),array('id'=>$id));
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//批量操作；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $ids) {
				$result=$this->goods_db->update(array('isok'=>1),array('id'=>$ids));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择什么
		if( empty($_POST['id'])){
			showmessage('请选择要操作的记录',HTTP_REFERER);
		}
	}


	/**
	* 驳回商品通过
	*/
	public function goodsdisagree(){
		//操作单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->goods_db->update(array('isok'=>3),array('id'=>$id));
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//批量操作；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $ids) {
				$result=$this->goods_db->update(array('isok'=>3),array('id'=>$ids));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择什么
		if( empty($_POST['id'])){
			showmessage('请选择要操作的记录',HTTP_REFERER);
		}
	}






	/**
	* 商品品牌
	*/
	public function goodsbrand(){
		
		$where = ' 1 ';
		if($_GET['q']){
			$where .= " and brandname like '%".$_GET['q']."%' ";
		}

		if($_GET['status']){
			$where .= " and status = ".$_GET['status'];
		}

		// if($_GET['start_addtime']){
		// 	$start_addtime=$_GET['start_addtime'];
		// 	$where .= " and addtime >= '".strtotime($_GET['start_addtime'])."'";   
		// }
		// if($_GET['end_addtime']){
		// 	$end_addtime=$_GET['end_addtime'];
		// 	$where .= " and addtime <= '".strtotime($_GET['end_addtime'])."'";
		// } 
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->brand_db->listinfo($where, $order = 'sort ASC, id DESC', $page, $pagesize = 20);
		$pages = $this->brand_db->pages;

		//添加品牌
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=brandadd\', title:\'添加品牌\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加品牌');
			
		include $this->admin_tpl('brand');
	}


	/**
     * 品牌排序
     */
    public function brandlistorder() {
    	// dump($_POST,true);
        if(isset($_POST['listorders'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->brand_db->update(array('sort'=>$listorder),array('id'=>$id));
            }
            showmessage(L('operation_success'),'?m=hpshop&c=goods&a=goodsbrand');
        } else {
            showmessage(L('operation_failure'),'?m=hpshop&c=goods&a=goodsbrand');
        }
    }

	/**
	* 添加品牌
	*/
	public function brandadd(){

		if($_POST['dosubmit']){
			// dump($_POST,true);
        	$data=[
        		'brandname'=>$_POST['bname'],
        		'brandimg'=>$_POST['thumb'],
        		'status'=>$_POST['status'],
        	];
        	
        	$results=$this->brand_db->insert($data);

        	showmessage(L('添加品牌成功'), '?m=hpshop&c=goods&a=brandadds');

		}else{
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('brandadd');
		}
	}


	/**
	* 添加中间跳转
	*/
	public function brandadds(){
		showmessage(L('operation_success'), '', '', 'add');
	}


	/**
	* 编辑品牌
	*/
	public function brandedit(){

		if($_POST['dosubmit']){
			$id=$_POST['id'];
			$data=[
        		'brandname'=>$_POST['bname'],
        		'brandimg'=>$_POST['thumb'],
        		'status'=>$_POST['status'],
        	];
        	
        	$results=$this->brand_db->update($data,array('id'=>$id));

        	showmessage(L('修改品牌信息成功'), '?m=hpshop&c=goods&a=brandedits');
			
		}else{
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");					
			$info = $this->brand_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('brandedit');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function brandedits(){
		showmessage(L('operation_success'), '', '', 'edit');
	}

	/**
	 * 检查品牌重复性
	 * @param 
	 */
	public function checkbrand_ajax() {
		$bname = isset($_GET['bname']) && trim($_GET['bname']) ? trim($_GET['bname']) : exit(0);
		if(CHARSET != 'utf-8') {
			$bname = iconv('utf-8', CHARSET, $bname);
			$bname = addslashes($bname);
		}
		if ($r = $this->brand_db->get_one(array('brandname'=>$bname))){
			if($r['id']==$_GET['id']){
				exit();
			}else{
				exit('FALSE');
			}
		} else {
			exit();
		}		
	}	


	/**
	* 品牌_删除
	*/
	public function branddel(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->brand_db->delete(array('id'=>$id));
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
				$result=$this->brand_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}







	/**
	* 商品推荐位
	*/
	public function goodsposition(){
		
		$where = ' 1 ';
		if($_GET['q']){
			$where .= " and posname like '%".$_GET['q']."%' ";
		}
		
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->position_db->listinfo($where, $order = 'sort ASC, id DESC', $page, $pagesize = 20);
		$pages = $this->position_db->pages;

		//添加品牌
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=positionadd\', title:\'添加推荐位\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加推荐位');
			
		include $this->admin_tpl('position');
	}


	/**
	* 添加推荐位
	*/
	public function positionadd(){

		if($_POST['dosubmit']){
			// dump($_POST,true);
        	$data=[
        		'posname'=>$_POST['pname'],
        	];
        	
        	$results=$this->position_db->insert($data);

        	showmessage(L('添加推荐位成功'), '?m=hpshop&c=goods&a=positionadds');

		}else{
			
			include $this->admin_tpl('positionadd');
		}
	}


	/**
	* 添加中间跳转
	*/
	public function positionadds(){
		showmessage(L('operation_success'), '', '', 'add');
	}


	/**
	* 编辑推荐位
	*/
	public function positionedit(){

		if($_POST['dosubmit']){
			$id=$_POST['id'];
			$data=[
        		'posname'=>$_POST['pname'],
        	];
        	
        	$results=$this->position_db->update($data,array('id'=>$id));

        	showmessage(L('修改推荐位信息成功'), '?m=hpshop&c=goods&a=positionedits');
			
		}else{				
			$info = $this->position_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('positionedit');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function positionedits(){
		showmessage(L('operation_success'), '', '', 'edit');
	}


	/**
     * 推荐位排序
     */
    public function positionlistorder() {
    	// dump($_POST,true);
        if(isset($_POST['listorders'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->position_db->update(array('sort'=>$listorder),array('id'=>$id));
            }
            showmessage(L('operation_success'),'?m=hpshop&c=goods&a=goodsposition');
        } else {
            showmessage(L('operation_failure'),'?m=hpshop&c=goods&a=goodsposition');
        }
    }



	/**
	 * 检查推荐位重复性
	 * @param 
	 */
	public function checkposition_ajax() {
		$pname = isset($_GET['pname']) && trim($_GET['pname']) ? trim($_GET['pname']) : exit(0);
		if(CHARSET != 'utf-8') {
			$pname = iconv('utf-8', CHARSET, $pname);
			$pname = addslashes($pname);
		}
		if ($r = $this->position_db->get_one(array('posname'=>$pname))){
			if($r['id']==$_GET['id']){
				exit();
			}else{
				exit('FALSE');
			}
		} else {
			exit();
		}		
	}	


	/**
	* 品牌_删除
	*/
	public function positiondel(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->position_db->delete(array('id'=>$id));
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
				$result=$this->position_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	}













	/**
	* 商品分类列表
	*/
	public function catsort(){
		$where=' 1 ';
		$infos = $this->goodscat_db->select($where,'*','',$order = 'sort ASC,id ASC');
		$infos = catetree($infos);

		$upload_allowext = 'jpg|jpeg|gif|png|bmp';
		$isselectimage = '1';
		$images_width = '';
		$images_height = '';
		$watermark = '0';
		$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
		//添加栏目
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=catadd\', title:\'添加商品分类\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加商品分类');
		include $this->admin_tpl('catlist');
	}


	/**
	* 添加分类栏目
	*/
	public function catadd(){

		if($_POST['dosubmit']){
        	$data=[
        		'pid'=>$_POST['pid'],
        		'cate_name'=>$_POST['cname'],
        		'cate_img'=>$_POST['thumb'],
        		'isshow'=>$_POST['status'],
        		'description'=>$_POST['desc'],
        	];
        	
        	$results=$this->goodscat_db->insert($data);

        	showmessage(L('添加栏目成功'), '?m=hpshop&c=goods&a=catadds');

		}else{
			$info = $this->goodscat_db->select($where,'*','',$order = 'id ASC, sort ASC');
			$info = catetree($info);
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('catadd');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function catadds(){
		showmessage(L('operation_success'), '', '', 'add');
	}


	/**
     * 分类栏目排序
     */
    public function catlistorder() {
    	// dump($_POST,true);
        if(isset($_POST['listorders'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->goodscat_db->update(array('sort'=>$listorder),array('id'=>$id));
            }
            showmessage(L('operation_success'),'?m=hpshop&c=goods&a=catsort');
        } else {
            showmessage(L('operation_failure'),'?m=hpshop&c=goods&a=catsort');
        }
    }

    /**
	 * 检查分类名称重复性
	 * @param 
	 */
	public function checkcat_ajax() {
		$cname = isset($_GET['cname']) && trim($_GET['cname']) ? trim($_GET['cname']) : exit(0);
		if(CHARSET != 'utf-8') {
			$cname = iconv('utf-8', CHARSET, $cname);
			$cname = addslashes($cname);
		}
		if ($r = $this->goodscat_db->get_one(array('cate_name'=>$cname))){
			if($r['id']==$_GET['id']){
				exit();
			}else{
				exit('FALSE');
			}	
		} else {
			exit();
		}		
	}	


	/**
	* 编辑商品分类
	*/
	public function catedit(){

		if($_POST['dosubmit']){
			$id=$_POST['id'];
			$data=[
        		'pid'=>$_POST['pid'],
        		'cate_name'=>$_POST['cname'],
        		'cate_img'=>$_POST['thumb'],
        		'isshow'=>$_POST['status'],
        		'description'=>$_POST['desc'],
        	];
        	
        	$results=$this->goodscat_db->update($data,array('id'=>$id));
        	showmessage(L('修改商品分类信息成功'), '?m=hpshop&c=goods&a=catedits');
			
		}else{					
			$info = $this->goodscat_db->get_one(array('id'=>$_GET['id']));
			$infos = $this->goodscat_db->select($where,'*','',$order = 'id ASC, sort ASC');
			$infos = catetree($infos);
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('catedit');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function catedits(){
		showmessage(L('operation_success'), '', '', 'edit');
	}

	/**
	* 商品分类_删除
	*/
	public function catdel(){
		//删除单个
		$id=intval($_GET['id']);
		pdel($id);
		showmessage('删除成功',HTTP_REFERER);
	}





	/**
	* 商品类型
	*/
	public function goodstype(){
		
		$where = ' 1 ';
		if($_GET['q']){
			$where .= " and type_name like '%".$_GET['q']."%' ";
		}

		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->goodstype_db->listinfo($where, $order = 'id ASC', $page, $pagesize = 20);
		$pages = $this->goodstype_db->pages;

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=typeadd\', title:\'添加商品类型\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加商品类型');
			
		include $this->admin_tpl('goodstype');
	}


	/**
	* 添加商品类型
	*/
	public function typeadd(){

		if($_POST['dosubmit']){
			// dump($_POST,true);
        	$data = [
        		'type_name'=>$_POST['tname'],
        	];
        	
        	$id = $this->goodstype_db->insert($data,true);

        	$datas = [
        		'goodstypeid' => $id,
        		'attrname' => '颜色',
        		'attrval' => '红,黄,蓝',
        		'isshow' => 0,
        		'attrtype' => 1,
        		'sort' => 0
        	];
        	$this->goodsattr_db->insert($datas);


        	showmessage(L('添加商品类型成功'), '?m=hpshop&c=goods&a=typeadds');

		}else{
			
			include $this->admin_tpl('typeadd');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function typeadds(){
		showmessage(L('operation_success'), '', '', 'add');
	}


	/**
	 * 检查类型名称重复性
	 * @param 
	 */
	public function checktype_ajax() {
		$tname = isset($_GET['tname']) && trim($_GET['tname']) ? trim($_GET['tname']) : exit(0);
		if(CHARSET != 'utf-8') {
			$tname = iconv('utf-8', CHARSET, $tname);
			$tname = addslashes($tname);
		}
		if ($r = $this->goodstype_db->get_one(array('type_name'=>$tname))){
			if($r['id']==$_GET['id']){
				exit();
			}else{
				exit('FALSE');
			}	
		} else {
			exit();
		}		
	}	

	/**
	* 编辑商品分类
	*/
	public function typeedit(){

		if($_POST['dosubmit']){
			$id=$_POST['id'];
			$data=[
        		'type_name'=>$_POST['tname'],
        	];
        	
        	$results=$this->goodstype_db->update($data,array('id'=>$id));

        	showmessage(L('修改商品分类信息成功'), '?m=hpshop&c=goods&a=typeedits');
			
		}else{					
			$info = $this->goodstype_db->get_one(array('id'=>$_GET['id']));
			include $this->admin_tpl('typeedit');
		}
	}


	/**
	* 添加中间跳转
	*/
	public function typeedits(){
		showmessage(L('operation_success'), '', '', 'edit');
	}



	/**
	* 商品类型_删除
	*/
	public function typedel(){
		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$num = $this->goods_db->count(array('type_id'=>$id));
			if($num > 0){
				showmessage(L('该属性有商品正在使用，无法删除'),HTTP_REFERER);
			}else{
				$result=$this->goodstype_db->delete(array('id'=>$id));
				if ($result) {
					showmessage(L('operation_success'),HTTP_REFERER);
				}else {
					showmessage(L("operation_failure"),HTTP_REFERER);
				}
			}
			
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $pid) {
				$result=$this->goodstype_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	} 



	/**
	* 商品类型属性列表
	*/
	public function typeattr(){
		$tinfo = $this->goodstype_db->get_one(array('id'=>$_GET['id']));
		$infos = $this->goodsattr_db->select(array('goodstypeid'=>$_GET['id']),'*','','sort ASC');

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=typeattradd&id='.$_GET['id'].'\', title:\'添加新属性\', width:\'800\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加新属性');
			
		include $this->admin_tpl('typeattr');
	}


	/**
	* 添加属性
	*/
	public function typeattradd(){

		if($_POST['dosubmit']){

			if($_POST['value']){
				$value=str_replace('，', ',', $_POST['value']);
			}else{
				$value='';
			}
			
        	$data = [
        		'goodstypeid' => $_POST['id'],
        		'attrname' => $_POST['aname'],
        		'attrval' => $value,
        		'isshow' => $_POST['show'],
        		'attrtype' => $_POST['status'],
        	];
        	$this->goodsattr_db->insert($data);

        	showmessage(L('添加属性成功'), '?m=hpshop&c=goods&a=typeattradds');

		}else{
			
			include $this->admin_tpl('typeattradd');
		}
	}

	/**
	* 添加中间跳转
	*/
	public function typeattradds(){
		showmessage(L('operation_success'), '', '', 'add');
	}


	/**
	* 编辑属性
	*/
	public function typeattredit(){

		if($_POST['dosubmit']){
			if($_POST['value']){
				$value=str_replace('，', ',', $_POST['value']);
			}else{
				$value='';
			}
			
        	$data = [
        		'attrname' => $_POST['aname'],
        		'attrval' => $value,
        		'isshow' => $_POST['show'],
        		'attrtype' => $_POST['status'],
        	];
        	$this->goodsattr_db->update($data,array('id'=>$_POST['id']));

        	showmessage(L('修改属性信息成功'), '?m=hpshop&c=goods&a=typeattredits');
			
		}else{
			$info = $this->goodsattr_db->get_one(array('id'=>$_GET['id']));					
			
			include $this->admin_tpl('typeattredit');
		}
	}


	/**
	* 添加中间跳转
	*/
	public function typeattredits(){
		showmessage(L('operation_success'), '', '', 'edit');
	}


	/**
	* 商品属性_删除
	*/
	public function typeattrdel(){

		//删除单个
		$id=intval($_GET['id']);
		if($id){
			$result=$this->goodsattr_db->delete(array('id'=>$id));
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
				$result=$this->goodsattr_db->delete(array('id'=>$pid));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if( empty($_POST['id'])){
			showmessage('请选择要删除的记录',HTTP_REFERER);
		}
	} 

	/**
     * 属性排序
     */
    public function goodsattrlistorder() {
    	// dump($_POST,true);
        if(isset($_POST['listorders'])) {
            foreach($_POST['listorders'] as $id => $listorder) {
                $this->goodsattr_db->update(array('sort'=>$listorder),array('id'=>$id));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        } else {
            showmessage(L('operation_failure'),HTTP_REFERER);
        }
    }


	/**
	 * 获取属性信息
	 * @param 
	 */
	public function getattr() {
		// $id=$_POST['tid'];
		// $tinfo = $this->goodstype_db->get_one(array('id'=>$id));
		// $info = string2array($tinfo['type_content']);
		// sort($info);
		// echo json_encode($info);

		$id=$_POST['tid'];
		$info = $this->goodsattr_db->select(array('goodstypeid'=>$id,'attrtype'=>1,'isshow'=>1),'attrval,attrname','','sort ASC');
		$infos = $this->goodsattr_db->select(array('goodstypeid'=>$id,'attrtype'=>0,'isshow'=>1),'*','','sort DESC,id DESC');
		$num = count($info);
		$ninfo = $this->newattr($info,$num);
		$data = [
			'attr' => $infos,
			'spec' => $ninfo,
			'specname' => $info
		];
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}	


	/**
	 * 获取属性搭配信息
	 * @param $arr 原数组
	 * @param $num 属性个数
	 * @param $time 运行次数
	 * @param $data 所需数据
	 */
	public function newattr($arr,$num,$time=0,$data=[]) {
		if($num == 0){
			return '0';
		}
		$sarr = explode(',',$arr[$time]['attrval']);
		
		if( empty($data) ){
			foreach ($sarr as $k => $v) {
			
				$data[] = [
					$time => $v,
					'keys' => $k+1,
					'vals' => $v
				];
			}
		}else{
			$narr = [];
			// foreach ($sarr as $k => $v) {
			// 	$lsarr = $data;

			// 	foreach ($data as $ks => $vs) {

			// 		$lsarr[$ks][$time] = $v;
			// 		$lsarr[$ks]['key'] .= '-'.$k;
			// 		$lsarr[$ks]['val'] .= $v;
			// 		$narr[] = $lsarr[$ks];
			// 	}
				
			// }


			foreach ($data as $ks => $vs) {
				
				foreach ($sarr as $k => $v) {
					$lsarr = $data;
					$lsarr[$ks][$time] = $v;
					$lsarr[$ks]['keys'] .= '-'.($k+1);
					$lsarr[$ks]['vals'] .= ','.$v;
					$narr[] = $lsarr[$ks];
				}
				
			}
			$data = $narr;
		}
		

		$num--;
		$time++;
		if ( $num > 0 ) {
			$data = $this->newattr($arr,$num,$time,$data);
		}

		return $data;
		
	}	







	/*
	 * 配置模块
	 * */
	public function zyconfig()
	{
		$big_menu = array
		(
			'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=hpshop&c=goods&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()	{window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
		);
		$order = 'id DESC';
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$info=$this->zyconfig_db->listinfo('item_name = "zyshop"',$order,$page,9999);
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
			$num = $this->zyconfig_db->count(['item_name'=>'zyshop']);
			$car=array
			(
				'config_name'=>$_POST['config_name'],
				'model_name'=>$_POST['model_name'],
				'item_name'=>'zyshop',
				'url'=>$_POST['url'],
				'key'=>'zyshop'.($num+1),
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












