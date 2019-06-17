<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_func('EX');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class zyorder_api{

	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->order_db = pc_base::load_model('zy_order_model');
		$this->logistics_db = pc_base::load_model('zy_logistics_model');
		$this->evaluate_set_db = pc_base::load_model('zy_evaluate_set_model');
		$this->evaluate_db = pc_base::load_model('zy_evaluate_model');
		$this->order_comment = pc_base::load_model('order_comment_model');
		//订单商品表
		$this->ordergoods_db = pc_base::load_model('zy_order_goods_model');
        $this->goods_db = pc_base::load_model('goods_model');
        $this->goods_specs_db = pc_base::load_model('goods_specs_model');
        $this->order_aftersale = pc_base::load_model('zy_order_aftersale_model');
		//分销用户表
		$this->zyfxmember_db = pc_base::load_model("zyfxmember_model");
		$this->zycoupon_db = pc_base::load_model('zycoupon_model');
		$this->zycoupon_user_db = pc_base::load_model('zycoupon_user_model');
        $this->order_aftersale = pc_base::load_model('zy_order_aftersale_model');
	}
    /**
     * CURL方式的GET传值
     * @param  [type] $url  [GET传值的URL]
     * @return [type]       [description]
     */
     function _crul_get($url){
	    $oCurl = curl_init();  
	    if(stripos($url,"https://")!==FALSE){  
	    	 curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);  
		     curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);  
		     curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1  
	   }  
	   curl_setopt($oCurl, CURLOPT_URL, $url);  
	   curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );  
	   $sContent = curl_exec($oCurl);  
	   $aStatus = curl_getinfo($oCurl);  
	   curl_close($oCurl);  
	   if(intval($aStatus["http_code"])==200){  
		   return $sContent;  
	   }else{  
	       return false;  
	   }  
}  





     /**
       * CURL方式的POST传值
       * @param  [type] $url  [POST传值的URL]
       * @param  [type] $data [POST传值的参数]
       * @return [type]       [description]
     */
     function _crul_post($url,$data){

       //初始化curl		
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, $url); 
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //post提交方式
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
       //要求结果为字符串且输出到屏幕上
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       //运行curl
       $result = curl_exec($curl);

       //返回结果	    
       if (curl_errno($curl)) {
          return 'Errno'.curl_error($curl);
       }
       curl_close($curl);
       return $result;
      }

	/**
	 *单个订单商品详情(用户版，此处用户与商家相同)
	 */
	public function one_goods_info($ischeck = 1,$order_goods_id = 0){

		if ( $_POST['order_goods_id'] ) {
			$id = $_POST['order_goods_id'];//订单id
		} else {
			$id = $order_goods_id;//订单id
		}
		//$info = $this->ordergoods_db->select(1);
		if ( empty($id) ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( $ischeck == 1 ) {

			$_userid = param::get_cookie('_userid');
			$userid = $_POST['uid'];//用户id，APP端必须传
			//$oid = $_POST['oid'];
			//非APP端直接用$_userid
			if($_userid){
				$uid = $_userid;
			}else{
				$uid = $userid;
			}

			if ( !$uid ) {
				$result = [
					'status' => 'error',
					'code' => 0,
					'message' => '请先登录！',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
		}

		$info = $this->ordergoods_db->select(['id'=>$id],'id,order_id,goods_id,goods_name,goods_num,final_price,goods_price,specid,specid_name,is_comment,goods_img');
		if ( $ischeck != 1 ) {
			return $info;
			exit(0);
		}

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
		$jg = stripslashes($jg);

		exit($jg);
	}

	/**
     *单个订单商品详情(用户版，此处用户与商家相同)
     */
	public function uordgoodsinfo($ischeck = 1,$orderid = 0, $status){

		if ( $_POST['oid'] ) {
			$oid = $_POST['oid'];//订单id
		} else {
			$oid = $orderid;//订单id
		}
		//$info = $this->ordergoods_db->select(1);
		if ( empty($oid) ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( $ischeck == 1 ) {

			$_userid = param::get_cookie('_userid');
			$userid = $_POST['uid'];//用户id，APP端必须传
			//$oid = $_POST['oid'];
			//非APP端直接用$_userid
			if($_userid){
				$uid = $_userid;
			}else{
				$uid = $userid;
			}

			if ( !$uid ) {
				$result = [
					'status' => 'error',
					'code' => 0,
					'message' => '请先登录！',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}

			$count = $this->order_db->count(['order_id'=>$oid,'userid'=>$uid]);
			if ( $count == 0 ) {
				$result = [
					'status' => 'error',
					'code' => -2,
					'message' => '非法访问',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
		}
        if($status == "6")
            list($info, $count) = $this->ordergoods_db->moreTableSelect(array("zy_order_goods"=>array("id","order_id","goods_id","goods_name","goods_num","final_price","goods_price","specid","specid_name","is_comment","goods_img"),"zy_order_aftersale"=>array("isDeal"))
            ,array("id"), ['order_id'=>$oid,"isAfterSale"=>2], "", "", 1
            );
		else
            list($info, $count) = $this->ordergoods_db->moreTableSelect(array("zy_order_goods"=>array("id","order_id","goods_id","goods_name","goods_num","final_price","goods_price","specid","specid_name","is_comment","goods_img"),"zy_order_aftersale"=>array("isDeal"))
                ,array("id"), ['order_id'=>$oid], "", "", 1
            );
		if ( $ischeck != 1 ) {
			return $info;
			exit(0);
		}

		if (isset($_POST['eva'])) {
			
		}	
		
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);	
		

	}	


    public function getevaluateset(){
		$evaluate_sets = $this->evaluate_set_db->select(1);
		$this->caozuo_success($evaluate_sets);
	}

	/**
     *单个订单商品详情(店铺版，此处只有商家可用，适用店铺订单)
     */
	public function sordgoodsinfo(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];//用户id，APP端必须传
		$oid = $_POST['oid'];//订单id
		//非APP端直接用$_userid
		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}

		if ( !$uid ) {
			$result = [
				'status' => 'error',
				'code' => 0,
				'message' => '请先登录！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( empty($oid) ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$count = $this->order_db->count(['order_id'=>$oid,'shopid'=>$uid]);
		if ( $count == 0 ) {
			$result = [
				'status' => 'error',
				'code' => -2,
				'message' => '非法访问',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		
		$info = $this->ordergoods_db->select(['order_id'=>$oid],'goods_id,goods_name,goods_num,final_price,goods_price,specid_name,is_comment,goods_img');	
			
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
		]; 
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);	
		

	}	




	/**
	* 获取订单列表（店铺用户通用）
	*/
	public function order_list(){
		//用户id和用户名
		//$_userid = $_GET['userid'];
		$page = $_GET['page'];
		$pagesize = $_GET['pagesize'];

		$_userid = param::get_cookie('_userid');
		$userid = $_GET['userid'];//用户id，APP端必须传
		//非APP端直接用$_userid
		if($userid){
			$uid = $userid;
		}else{
			$uid = $_userid;
		}
		if($page == null){
            $page = 1;
		}
		if($pagesize == null){
			$pagesize = 10;
		}
		if($uid==null){
			$this->empty_userid();
		}
		$where = ' userid = '.$uid;
		if($_GET['status']==1){
			$where.= ' AND status=1';
		}else if($_GET['status']==2){
			$where.= ' AND (status=2 OR status=11 OR status=10) ';
		}else if($_GET['status']==3){
			$where.= ' AND status=3';
		}else if($_GET['status']==4){
			$where.= ' AND (status=4 OR status=10)';
		}else if($_GET['status']==5){
			$where.= ' AND (status=7 OR status=12)';
		}else if($_GET['status']==6){
			$where.= ' AND (status=8 OR status=9 OR status=10)';
		}else{
			$where.= ' AND status<>8 AND status<>9 AND 1';
		}
		if($_GET['try_status']==0){
			$where.= ' AND try_status=0';
		}else if($_GET['try_status']==1){
			$where.= ' AND try_status=1';
		}

		$sql = 'SELECT storeid from phpcms_zy_order WHERE userid = '.$_userid.' GROUP BY storeid';
		$sqlrs = $this->order_db->query($sql);
		$sqlres = $this->order_db->fetch_array($sqlrs);
		$idarr = '';
        foreach ($sqlres as $key => $value) {
        	if ( empty($idarr) ) {
        		$idarr = $value['storeid'];
        	}else{
        		$idarr .= ','.$value['storeid'];
        	}
        } 

		$token_url= APP_PATH.'index.php?m=zymember&c=zymember_api&a=zyshop_nickname';
        $data = array ('ids' => $idarr);
        $content = http_build_query($data);
        $content_length = strlen($content);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                "Content-type: application/x-www-form-urlencoded\r\n" .
                "Content-length: $content_length\r\n",
                'content' => $content
            )
        );
		$token = json_decode(file_get_contents($token_url,false,stream_context_create($options)));
	    $rs =  json_decode(json_encode($token),true);
	    $snamarr = [];
	    foreach ($rs['data'] as $ks => $vs) {
        	$snamarr[$vs['userid']] = $vs;
        }

        $where.=' AND status <= 12 ';
		$order = ' order_id DESC ';
		//$page = $pageindex ? $pageindex : '1';
		$orders = $this->order_db->listinfo($where,$order,$page,$pagesize); //读取数据库里的字段
		$totalcount = $this->order_db->count($where);
		foreach ($orders as $k => $v) {
			$goodsinfo = $this->uordgoodsinfo(0,$v['order_id'], $_GET['status']);
			if($v["status"] == "1" && $v["try_status"] != '1')
            {
                if(time() - $v["addtime"] > 3600)
                {
                    $specidGoods = [];
                    $notSpecidGoods = [];
                    $result = $this->order_db->update(array('status'=>6),array('order_id'=>$v["order_id"]));
                    $order_info = $this->order_db->get_one(array('order_id'=>$v["order_id"]));
                    $this->coupon_cancel($order_info['couponid']);
                    $goodsInfo = $this->ordergoods_db->select(array("order_id"=>$v["order_id"]), "id,goods_id, is_count, specid, goods_num");
                    foreach($goodsInfo as $ki=>$vi)
                    {
                        if($vi["specid"] && $vi["is_count"] == 1)
                            $specidGoods[] = $vi;
                        elseif($vi["is_count"] == 1)
                            $notSpecidGoods[] = $vi;
                    }
                    foreach($specidGoods as $ki=>$vi)
                        $this->goods_specs_db->update(array("specstock"=>"+=".$vi["goods_num"]), array("goodsid"=>$vi["goods_id"], "specid"=>$vi["specid"]));

                    foreach($notSpecidGoods as $ki=>$vi)
                        $this->goods_db->update(array("stock"=>"+=".$vi["goods_num"]), array("id"=>$vi["goods_id"]));
                    unset($orders[$k]);
                    continue;
                }
            }
			$orders[$k]['goodsinfo'] = $goodsinfo;
			$orders[$k]["id"] = $v["order_id"];
			$orders[$k]['storename'] = $snamarr[$v['storeid']]['shopname'];

 		}

		// $orderss = $this->order_db->select($where);
		// $totalcount = count($orderss);
		//查询商品信息
		$totalpage = ceil($totalcount/$pagesize);
		$data = [
			"status"=> 'success',
			"code"=>1,
			"message"=>'操作成功',
			"data"=>$orders,
			'page' => [
				'pagesize'=>$pagesize,
				'totalpage'=>$totalpage,
				'totalnum' => $totalcount
			]
			// "pages"=>$pages,
			// "pagesize"=>$pagesize,
			// "pageindex"=>$pageindex,
			// "totalpage"=>$totalpage,
			// "totalcount"=>$totalcount
		];
 		echo json_encode($data);
	}

	/**
	 * 获取分销订单列表（店铺用户通用）
	 */
	public function fx_order_list(){
		$_userid = param::get_cookie('_userid');
		$userid = $_GET['userid'];//用户id，APP端必须传
		//非APP端直接用$_userid
		if($userid){
			$uid = $userid;
		}else{
			$uid = $_userid;
		}
		if($uid==null){
			$this->empty_userid();
		}
		$where = ' userid = '.$uid;
		$where.= ' AND ((try_status=0 and (`status` in (2,3,4,5))) OR ( try_status=1 and (`status` in (4,5))))';

		$sql = 'SELECT * from phpcms_zy_order WHERE '.$where.'order by addtime desc';
		$sqlrs = $this->order_db->query($sql);
		$sqlres = $this->order_db->fetch_array($sqlrs);

		if(!$sqlres){
			$data = [
				"status"=> 'error',
				"code"=>-1,
				"message"=>'该用户还没有下过单',
			];
			exit(json_encode($data));
		}else{
			foreach($sqlres as $k=>$v){
				$sqlres[$k]['addtime']= date("Y-m-d", $v['addtime']);
				$sqlres[$k]['fx_money']=$this->show_fx_money($_userid,$userid,$v['fx_money']);
			}
		}

		$data = [
			"status"=> 'success',
			"code"=>1,
			"message"=>'操作成功',
			"data"=>$sqlres,
		];
		echo json_encode($data);
	}
    function changeDeal()
    {
        $neadArg = ["isDeal"=>[true, 0], "commentid"=>[true, 1],];
        $info = checkArgBcak($neadArg, "POST");
        $info["isDeal"] = $info["isDeal"] == "同意"? 2:3;
        $where["afterSaleid"] = array_pop($info);
        if($info["isDeal"] == 2)
        {
            list($orderData, $count) = $this->ordergoods_db->moreTableSelect(array("zy_order_goods"=>array("*"),"zy_order_aftersale"=>array("*"),"zy_zy_order"=>array("*") ), array("id", "order_id"), "B2.`afterSaleid`=".$where["afterSaleid"],"", "", 0);

            if($orderData["pay_type"] == '1' && $orderData["number"] != 0)
            {
                $html = APP_PATH."index.php?m=zypay&c=ali&a=wap_refund";
                $curl = [
                'WIDout_trade_no'=>$orderData["ordersn"],
                'WIDtrade_no'=>$orderData["aliTradeNo"],
//                'WIDrefund_amount'=>0.01 ,
                'WIDrefund_amount'=>$orderData["goods_price"]*$orderData["number"],
                "WIDout_request_no"=>time() + mt_rand(100,999),

                ];
                $sms_verify = _crul_post($html,$curl);
                $sms_verify=json_decode($sms_verify,true);
            }

        }
        $this->order_aftersale->update($info, $where);
        returnAjaxData("1", "成功");
    }
	/**
	 * 获取分销订单列表（店铺用户通用）
	 */
	public function fx_good_list(){
		$page = $_GET['page'];
		$pagesize = $_GET['pagesize'];

		$_userid = param::get_cookie('_userid');
		$userid = $_GET['userid'];//用户id，APP端必须传
		//非APP端直接用$_userid
		if($userid){
			$uid = $userid;
		}else{
			$uid = $_userid;
		}
		if($page == null){
			$page = 1;
		}
		if($pagesize == null){
			$pagesize = 10;
		}
		if($uid==null){
			$this->empty_userid();
		}

		$children=$this->find_children($uid);

		if(is_array($children)){
			$childrenid='(0';
			//$is_first=true;
			for($i=1; $i<= 3; $i++) {
				for($j=0; $j< count($children[$i-1]); $j++) {
					if($children[$i-1][$j]!=null) {
						$childrenid .= ',' . $children[$i - 1][$j];
					}
				}
			}
			$childrenid.=')';
		}else{
			$data = [
				"status"=> 'success',
				"code"=>-200,
				"message"=>'没有下级成员',
			];
			exit(json_encode($data));
		}

		$where = ' userid in '.$childrenid;
		$infos = $this->order_db->select($where,'order_id,userid,addtime','','addtime DESC'); //读取数据库里的字段
		if($infos) {
			$orders_id='(';
			$is_first=true;
			foreach ($infos as $item) {
				if ($is_first) {
					$orders_id .= $item['order_id'];
					$is_first = false;
				} else {
					$orders_id .= ',' . $item['order_id'];
				}
			}
			$orders_id.=')';
			$where = ' B1.order_id in '.$orders_id;
			$order = ' addtime DESC ';
			$data =
			list($info, $totalcount) = $this->ordergoods_db->moreTableSelect(array("zy_zy_order"=>array("order_id","userid","addtime"),"zy_order_goods"=>array("goods_name","goods_fxmoney","specid_name"), "zy_member"=>ARRAY("username","nickname","mobile")),array("order_id","userid"),$where,((string)($page-1)*$pagesize).",".$pagesize
				,$order,1); //读取数据库里的字段
			//$info = $this->ordergoods_db->listinfo($where,$order,$page,$pagesize,'','','','','goods_name'); //读取数据库里的字段
			//$totalcount = $this->ordergoods_db->count($where);
			$totalpage = ceil($totalcount/$pagesize);
			foreach($info as $k=> $v) {
				$info[$k]['goods_fxmoney'] = $this->show_fx_money($uid, $v['userid'], $v['goods_fxmoney']);
				$info[$k]['addtime']=date("Y-m-d H:i:s", $v['addtime']);;
			}
		}


		$data = [
			"status"=> 'success',
			"code"=>1,
			"message"=>'操作成功',
			"data"=>$info,
			'page' => [
				'pagesize'=>$pagesize,
				'totalpage'=>$totalpage,
				'totalnum' => $totalcount
			]
		];
		echo json_encode($data);
	}


	//店铺订单列表
	public function order_list_shop(){
		$_storeid = $_GET['storeid'];
		$pageindex = $_GET['pageindex'];
		$pagesize = $_GET['pagesize'];
		if($pageindex == null){
			$pageindex = 1;
		}
		if($pagesize == null){
			$pagesize = 10;
		}
		if($_userid==null){
			exit($this->show_error("no userid"));
		}
		$where = 'storeid='.$_storeid;
		if($_GET['status']==1){
			$where.= ' AND status=1';
		}else if($_GET['status']==2){
			$where.= ' AND status=2';
		}else if($_GET['status']==3){
			$where.= ' AND status=3';
		}else if($_GET['status']==4){
			$where.= ' AND status=4';
		}else{
			$where.= ' AND 1';
		}
		$where.=' AND status < 6 ';
		$order = 'id DESC';
		$page = $pageindex ? $pageindex : '1';
		$orders=$this->order_db->listinfo($where,$order,$page,$pagesize); //读取数据库里的字段
		$totalcount = $this->order_db->count($where);
		foreach ($orders as $k => $v) {
			$goodsinfo = $this->uordgoodsinfo(0,$v['id']);
			$orders[$k]['goodsinfo'] = $goodsinfo;
		}
		// $orderss = $this->order_db->select($where);
		// $totalcount = count($orderss);
		//查询商品信息
		$totalpage = ceil($totalcount/$pagesize);
		$data = [
			"status"=> 'success',
			"code"=>1,
			"message"=>'操作成功',
			"data"=>$orders,
			'page' => [
				'pagesize'=>$pagesize,
				'totalpage'=>$totalpage,
				'totalnum' => $totalcount
			]
			// "pages"=>$pages,
			// "pagesize"=>$pagesize,
			// "pageindex"=>$pageindex,
			// "totalpage"=>$totalpage,
			// "totalcount"=>$totalcount
		];
		echo json_encode($data);
	}
	

	
	/**
	* 添加订单
	*/
	public function addorder(){
		$_userid = $_POST['userid'];
		if($_userid==null){
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		//$shopid = $_POST['shopid'];  //店铺id
		$ordersn = time() + mt_rand(100,999);
		//$buycarid = $_POST['buycarid']; //购物车id
		// $province = $_POST['province'];  //收货地址省 
		// $city = $_POST['city'];//收货地址市
		// $area = $_POST['area'];//收货地址区
		// $address = $_POST['address']; //详细地址
		// $lx_mobile = $_POST['lx_mobile']; //联系电话
		// $lx_name = $_POST['lx_name']; //联系人
		// $lx_code = $_POST['lx_code']; //联系邮编
		// $shopdata = $_POST['shopdata'];  //商品数据
		//$usernote = $_POST['usernote']; //备注
		$status = empty($_POST['status']) ? 1 : $_POST['status'];
		$try_status = empty($_POST['try_status']) ? 0 : $_POST['try_status'];
		$coupon_user_id = empty($_POST['coupon_user_id']) ? 0 : $_POST['coupon_user_id'];
		$addtime = time();//生成下单时间
		$data = [
			"userid"=>$_userid,
			//"shopid"=>$shopid,
			//"ordersn"=>$ordersn,
			//"buycarid"=>$buycarid,
			"province"=>$_POST['province'],  //收货地址省 
			"city"=>$_POST['city'],//收货地址市
			"area"=>$_POST['area'],//收货地址区
			"status"=>$status,
			"try_status"=>$try_status,
			"address"=>$_POST['address'], //详细地址
			"lx_mobile"=>$_POST['lx_mobile'], //联系电话
			"lx_name"=>$_POST['lx_name'], //联系人
			"lx_code"=>$_POST['lx_code'], //联系邮编
			//"totalprice"=>$totalprice,
			"usernote"=>$_POST['usernote'],
			"addtime"=>$addtime,
		];

		foreach($data as $k => $v){
            if(/*$v==null*/empty($v) && $k != "usernote"){
                // $re = ['code'=>0];
                // echo json_encode($re);
                // return ;
                if($v == '0' && $k = 'try_status')
                    continue;
                $result = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => '访问受限，缺少参数'.$k,
                ];
                exit(json_encode($result,JSON_UNESCAPED_UNICODE));
            }

        }

		$idarr = [];

		foreach ($_POST['shopdata'] as $ks => $vs) {

			$newdata = $data;
			$newdata['storeid'] = $vs['shopid'];
			$newdata['ordersn'] = time() + mt_rand(1000,9999);
			$newdata['totalprice'] = $vs['stprice'];
			$newdata['couponid'] = $coupon_user_id;
			$id = $this->order_db->insert($newdata,true);
			$idarr[] = [
				'oid' => $id,
				'order_sn' => $newdata['ordersn']
			];

			$len = count($vs['cartinfo']);
    		$sql= "insert into phpcms_order_goods ( `order_id`, `goods_id`, `goods_name`, `goods_num`, `goods_img`, `goods_price`, `specid`, `specid_name`,`final_price`) values";
			foreach ($vs['cartinfo'] as $key => $val) {
				$sql.="(".$id.", ".$val['goodsid'].", '".$val['goodsname']."', '".$val['cartnum']."', '".$val['goodsimg']."', '".$val['goodsprice']."', '".$val['goodsspec']."', '".$val['goodsspecs']."', '".$val['goodsprice']*$val['cartnum']."'),";
			};
			$sql = substr($sql,0,strlen($sql)-1);

			$this->ordergoods_db->query($sql);
		}
        $goodsInfo = $this->ordergoods_db->select(array("order_id"=>$id), "id,goods_id, is_count, specid, goods_num");
        foreach($goodsInfo as $k=>$v)
        {
            $this->ordergoods_db->update(array("is_count"=>1), array("id"=>$v["id"]));
            if($v["specid"] && $v["is_count"] == 0)
                $specidGoods[] = $v;
            elseif($v["is_count"] == 0)
                $notSpecidGoods[] = $v;
        }
        foreach($specidGoods as $k=>$v)
            $this->goods_specs_db->update(array("specstock"=>"-=".$v["goods_num"]), array("goodsid"=>$v["goods_id"], "specid"=>$v["specid"]));

        foreach($notSpecidGoods as $k=>$v)
            $this->goods_db->update(array("stock"=>"-=".$v["goods_num"]), array("id"=>$v["goods_id"]));

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $idarr
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));

		//exit($this->show_success($result));
	}


	
	
	
	
	
	
	
	
	
	
	
	
	
	  /********************************************** /
	*        统一判断状态方法                     * /
	*/                                            /**/
  
    //订单与用户相关
	public function check_uid($id,$_userid){	
		if($_userid!=null){	
			$order = $this->order_db->get_one(array('order_id'=>$id));
			if($order['userid'] != $_userid){	
				return false;
			}
			return true;
		}
	}
	
	//订单与商户相关  
    public function check_storeid($id,$storeid){
		if($storeid!=null){
		   $order = $this->order_db->get_one(array('storeid'=>$storeid));
		   if($order['storeid'] != $storeid){
		     return false;
		   }
		   return true;
		}
	}	
	
    //订单与用户，订单状态相关  
	public function check_uid_status($id,$_userid,$status){
		if ($_userid != null) {
			$order = $this->order_db->get_one(array('order_id' => $id));
			if (!in_array($order["status"], $status) || $order['userid'] != $_userid) {
				return false;
			}
			return true;
		}
	}        
		 
	//订单与商户，订单状态相关  
    public function check_storeid_status($id,$storeid,$status){
		if($storeid!=null){
			$order = $this->order_db->get_one(array('storeid'=>$storeid));
			if($order['status'] != $status || $order['storeid']!=$storeid){
			     return false;
			}
			return true;
		}
	}

	//解析各级分销金额json
	//$_userid 主用户
	//$userid 下级用户
	//$fx_money 分销金钱
	public function show_fx_money($_userid,$userid,$fx_money){
		$memberInfo= $this->zyfxmember_db->get_one(array('userid'=>$userid));
		$fx_money=json_decode($fx_money,true);

		if(!$_userid||!$userid||!$fx_money){
			return 0;
		}

		for($i=1; $i<= 3; $i++) {
			if ($memberInfo["pid"] == 0)//如果没有pid的话break;
				break;
			if($memberInfo["pid"]== $_userid){
				return $fx_money[$i];
			}
			$memberInfo = $this->zyfxmember_db->get_one(array('userid' => $memberInfo["pid"]));
		}
	}

	//查找所有下级
	//$_userid 主用户
	public function find_children($_userid){
		$_userid= empty($_POST['_userid'])? $_userid : $_POST['_userid'];
		if(!$_userid){
			return 0;
		}
		$data=[
			"userid"=>$_userid,
		];
		$url = APP_PATH.'index.php?m=zyfx&c=frontApi&a=getTeamInfo';	//yyy
		$return = json_decode($this->_crul_post($url,$data),true);

		$id[][]=null;
		if ( $return['code']=='1' ) {
			$infos=$return['data'];

			for($i=1; $i<= 3; $i++) {
				for($j=0; $j< count($infos[$i]); $j++) {
					$id[$i-1][$j] = $infos[$i][$j]['ID'];
				}
			}
			//exit(print_r($id));
			return $id;
		}else{
			return 0;
		}
	}
	
	
	/********************************************** /
	*        统一输出格式                          * /
	*/                                            /**/

	
	//check_uid
	function error_check_uid(){
		$data = [
					"status"=>'error',
					"code"=>203,
					"message"=>'订单与用户不匹配',
					"data"=>''
		];
		exit( json_encode($data));
		
	}
	
	//check_uid_status
    function error_check_uid_status(){
		$data = [
					"status"=>'error',
					"code"=>204,
					"message"=>'订单状态不匹配',
					"data"=>''
		];
		exit( json_encode($data));
	}
	//check_storeid
	function error_check_storeid(){
		$data = [
					"status"=>'error',
					"code"=>205,
					"message"=>'订单与商户不匹配',
					"data"=>''
		];
		exit( json_encode($data));
	}
	//check_storeid_status
    function error_check_storeid_status(){
		$data = [
					"status"=>'error',
					"code"=>206,
					"message"=>'订单与商户，订单状态不匹配',
					"data"=>''
		];
		exit( json_encode($data));
	}
	
	
	function caozuo_success($e){
		$data = [
					"status"=>'success',
					"code"=>200,
					"message"=>'操作成功',
					"data"=>$e
		];
		exit( json_encode($data));
	}
	function caozuo_fail(){
		$data = [
					"status"=>'error',
					"code"=>201,
					"message"=>'操作失败',
					"data"=>''
		];
		exit( json_encode($data));
	}
	
	function empty_userid(){
		$data = [
					"status"=>'error',
					"code"=>207,
					"message"=>'用户id为空',
					"data"=>''
		];
		exit( json_encode($data));
	}
	function empty_storeid(){
		$data = [
					"status"=>'error',
					"code"=>208,
					"message"=>'商户id为空',
					"data"=>''
		];
		exit( json_encode($data));
	}
		

	
	/********************************************** /
	*        用户订单api                               * /
	*/                                            /**/

		
	//订单详情
	public function order_info(){
		//用户id和用户名
		//$_userid = $_GET['userid'];
		$id = $_GET['id'];
		$_userid = param::get_cookie('_userid');
		$userid = $_GET['userid'];

		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}

		// if ( !$uid ) {
		// 	$result = [
		// 		'status' => 'error',
		// 		'code' => 0,
		// 		'message' => '请先登录！',
		// 	];
		// 	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		// }

		if($uid==null){
			exit($this->empty_userid());
		}
		if($this->check_uid($id,$uid)){
			$order = $this->order_db->get_one(array('order_id'=>$_GET['id'],'userid'=>$uid));
			$data=[
		      "ids"=>$order['storeid']
		    ];
		    $url = APP_PATH.'index.php?m=zymember&c=zymember_api&a=zyshop_nickname';
		    $return = json_decode($this->_crul_post($url,$data),true);
			if($this->check_uid_status($id,$uid,array("1","3", "4", "5", "8", "9", "10"))){
//			  $KdApi = pc_base::load_app_class('KdApiSearch');
//			  $KdApi = new KdApiSearch();
//			  $order = $this->order_db->get_one(array('order_id'=>$id,'userid'=>$uid));
//			  $logisticResult=$KdApi->getOrderTracesByJson($order['shipper_code'],$order['logistics_order']);

              list($order,$count) = $this->order_db->moreTableSelect(
                  array("zy_zy_order"=>array("*"), "zy_zy_logistics_company"=>array("name,value")),
                  array("EXid"),
                  array('order_id'=>$id,'userid'=>$uid)
              );
			  $EBINfo = pc_base::load_config('EXinfo');
              $data = getOrderTracesByJson($EBINfo, $order["value"],$order["logistics_order"]);
		    }
		
			$goods = $this->uordgoodsinfo(0,$order['order_id']);
			$order["addtime"] = date("Y-m-d H:i:s", $order["addtime"]);
			$order["deltime"] = date("Y-m-d H:i:s", $order["deltime"]);
			$order["overtime"] = date("Y-m-d H:i:s", $order["overtime"]);
			$order['goodsinfo'] = $goods;
			$order['totalprice'] = 0;
			foreach($goods as $good){
				$order['totalprice']  += $good['final_price'];
			}
			$order['goodsnum'] = count($goods);
			$order['storename'] = $return['data'][0]['shopname'] ;
			$order['wuliu'] = json_decode($data,true);
			$this->caozuo_success($order);
		}else{
			$this->error_check_uid();
		}	
	}
	
		//订单详情
	public function order_eva_info(){
		//用户id和用户名
		//$_userid = $_GET['userid'];
		$id = $_GET['id'];
		$_userid = param::get_cookie('_userid');
		$userid = $_GET['userid'];

		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}

		// if ( !$uid ) {
		// 	$result = [
		// 		'status' => 'error',
		// 		'code' => 0,
		// 		'message' => '请先登录！',
		// 	];
		// 	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		// }

		if($uid==null){
			exit($this->empty_userid());
		}
		if($this->check_uid($id,$uid)){
			$order = $this->order_db->get_one(array('order_id'=>$_GET['id'],'userid'=>$uid));
			$data=[
		      "ids"=>$order['storeid']
		    ];
		    $url = APP_PATH.'index.php?m=zymember&c=zymember_api&a=zyshop_nickname';
		    $return = json_decode($this->_crul_post($url,$data),true);
			$goods = $this->uordgoodsinfo(0,$order['id']);
			$order['goodsinfo'] = $goods;
			$order['storename'] = $return['data'][0]['shopname'] ;
			$this->caozuo_success($order);
		}else{
			$this->error_check_uid();
		}	
		
	}
	
	
	
	/**
	* 提醒发货
	*/
	public function order_txfh(){
		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		if($_userid ==null){
			$this->empty_userid();
		}
		if($this->check_uid_status($id,$_userid,array('2'))){
		    $result = $this->order_db->update(array('remind'=>'提醒发货'),array('order_id'=>$id));
			if($result){
			  $this->caozuo_success("操作成功");
			}
		}else{	
			$this->error_check_uid_status();
		}
	}
	
	public function order_stats_info(){
	    if($_GET['d']=='0'){
			$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
     	    $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
			$sql = " addtime >= ".$beginYesterday." AND addtime <= ".$endYesterday;
			$census = [
		   'date'=>'Yesterday'
		];
		}else if($_GET['d']=='1'){
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    	    $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
			$sql = " addtime >= ".$beginToday." AND addtime <= ".$endToday;
			$census = [
		   'date'=>'Today'
		];
		}else{
			exit($this->show_error('no params'));
		}
		$arr = [];
		$orders = $this->order_db->select($sql);
		foreach($orders as $order){
			array_push($arr,$order['userid']);
		}
		$count = array_count_values($arr);
	
		foreach($count as $k => $v){
			$census['tj'][] = [
			    'userid'=>$k,
				'count'=>$v
			];
		}
	    $this->caozuo_success($census);
		
	}
	
	
	public function order_stats(){
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
    	$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    	$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
     	$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
		$year = date('Y',time());
		$stats = [];
		$sql = " addtime >= ".$beginToday." AND addtime <= ".$endToday;
		$order = $this->order_db->select($sql);
		$stats[]=[
		    	"today".$i=>count($order)
		];
		$sql = " addtime >= ".$beginYesterday." AND addtime <= ".$endYesterday;
		$order = $this->order_db->select($sql);
		$stats[]=[
		    	"yesterday".$i=>count($order)
		];
		for($i = 1;$i<=12;$i++){
			$arr = $this->mFristAndLast($year,$i);
			$sql = " addtime >= ".$arr['firstday']." AND addtime <= ".$arr['lastday'];
		    $order = $this->order_db->select($sql);
		    $stats[]=[
		    	"month".$i=>count($order)
		    ];
		}
		echo json_encode($stats);
	}

	function mFristAndLast($y = "", $m = ""){
		if ($y == "") $y = date("Y");
		if ($m == "") $m = date("m");
		$m = sprintf("%02d", intval($m));
		$y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

		$m>12 || $m<1 ? $m=1 : $m=$m;
		$firstday = strtotime($y . $m . "01000000");
		$firstdaystr = date("Y-m-01", $firstday);
		$lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
      
		return array(
			"firstday" => $firstday,
			"lastday" => $lastday
		);
	}


	
	
   
	/**
	* 订单中心_待支付_取消订单
	*/
	public function order_cancel(){	

		$id = $_POST['id'];
		//$_userid = $_POST['userid'];

		$_userid = param::get_cookie('_userid');
		$userid = $_POST['userid'];

		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}

		if($uid ==null){
			$this->empty_userid();
		}
		if($this->check_uid_status($id,$uid,array('1', '2', '7'))){
			$result = $this->order_db->update(array('status'=>6),array('order_id'=>$id));
			$order_info = $this->order_db->get_one(array('order_id'=>$id));
			$this->coupon_cancel($order_info['couponid']);
            $goodsInfo = $this->ordergoods_db->select(array("order_id"=>$id), "id,goods_id, is_count, specid, goods_num");
            foreach($goodsInfo as $k=>$v)
            {
                if($v["specid"] && $v["is_count"] == 1)
                    $specidGoods[] = $v;
                elseif($v["is_count"] == 1)
                    $notSpecidGoods[] = $v;
            }
            foreach($specidGoods as $k=>$v)
                $this->goods_specs_db->update(array("specstock"=>"+=".$v["goods_num"]), array("goodsid"=>$v["goods_id"], "specid"=>$v["specid"]));

            foreach($notSpecidGoods as $k=>$v)
                $this->goods_db->update(array("stock"=>"+=".$v["goods_num"]), array("id"=>$v["goods_id"]));

            if($result){
				$this->caozuo_success("取消成功");
			}else{
				$this->caozuo_fail();
			}
		}else{	
			$this->error_check_uid_status();
		}
	}

    //删除订单
	public function order_delete(){	
		$id = $_POST['id'];
		//$_userid = $_POST['userid'];

		$_userid = param::get_cookie('_userid');
		$userid = $_POST['userid'];

		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}
		if($uid==null){
			$this->empty_userid();
		}
		if($this->check_uid_status($id,$uid,array('5', '6'))){
			$result = $this->order_db->delete(array('order_id'=>$id));
			if($result){
				$this->caozuo_success("删除成功");
			}else{
				$this->caozuo_fail();
			}
		}else{	
			 $this->error_check_uid_status();
		}
	}
  
 
	/**
	* 订单中心_支付
	*/
	public function order_pay(){
		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		$password = $_POST['password'];
		$totalprice = $_POST['totalprice'];

		if($_userid==null){
			$this->empty_userid();
		}
		$data=[
		   "userid"=>$_userid,
		   "pay_password"=>$password
		];
		$url = APP_PATH.'index.php?m=zymember&c=zymember_api&a=zyorder_offpaypas';
		$return = json_decode($this->_crul_post($url,$data),true);
		
		if($return['code']=='200'){
		  if($this->check_uid_status($id,$_userid,array('1'))){
		    $order = $this->order_db->get_one(array('order_id'=>$id));
			if($order['totalprice']!=$totalprice){
				$this->caozuo_fail("totalprice !=");
			}
			$paytime = time();
			$result =  $this->order_db->update(array('status'=>2,'paytime'=>$paytime),array('order_id'=>$id));
			if($result){
				$this->caozuo_success("支付成功");
			}
			else{
				$this->caozuo_fail();
			}
		}else{
			$this->error_check_uid();
		}
		}else{
			exit(json_encode($return));
		}
		
		
	}

	/**
	* 订单管理_快递鸟ajax传值
	*/
	public function kuaidi_ajx() {
		$id = $_POST['id'];
		$_userid = $_POST['userid'];
		if($this->check_uid_status($id,$_userid,array('3'))){
			$KdApi = pc_base::load_app_class('KdApiSearch');
			$KdApi = new KdApiSearch();
			$order = $this->order_db->get_one(array('order_id'=>$id,'userid'=>$_userid));
			$logisticResult=$KdApi->getOrderTracesByJson($order['shipper_code'],$order['logistics_order']);
			$logisticResult = json_decode($logisticResult);
			echo  $logisticResult;
		}
	}

	public function kuaidi_ajx2(){
		$shipper_code = $_POST['shipper_code'];
		$logistics_order = $_POST['logistics_order'];
		$KdApi = pc_base::load_app_class('KdApiSearch');
		$KdApi = new KdApiSearch();
		$logisticResult=$KdApi->getOrderTracesByJson($shipper_code,$logistics_order);
		return $logisticResult;
	}


	//确认收货
	public function order_qrsh(){
		$id = $_POST['id'];
        $try_status = $_POST['try_status'];
		$_userid = $_POST['userid']? $_POST['userid']: param::get_cookie('_userid');
		if($_userid == null){
			$this->empty_userid();
		}
        $order = $this->order_db->get_one(array('order_id' => $id), "try_status");

		if($this->check_uid_status($id,$_userid,array('3'), "`try_status`=".$try_status) && ($order["try_status"] == $try_status)){

		    if($order["try_status"]  == '0')
			    $result = $this->order_db->update(array('status'=>4, 'overtime'=>time()),array('order_id'=>$id, "try_status" => "0"));
		    elseif ($order["try_status"]  == "1")
                $result = $this->order_db->update(array('status'=>1),array('order_id'=>$id, "try_status" => "1"));
			if($result){
				$this->caozuo_success("确认收货");
			}else{
			    $this->caozuo_fail();
			}
		}else{
			 $this->error_check_uid_status();
		}
	}
	
	
	//评价
	public function evaluate(){
		echo json_encode($_POST);
		exit();
		$_userid = $_POST['userid'];
		$id = $_POST['id'];
		$evalute_arr = $_POST['evalute_arr'];
		if($_userid==null){
			 $this->empty_userid();
		}
		if($this->check_uid_status($id,$_userid,array('4'))){
		    foreach($evalute_arr as $val){
				$shopid = $val['shopid'];
		     	$content = $val['content'];
		        $star = [];
				$setarr = [];
				$evaluate_set = $this->evaluate_set_db->select(1);
				foreach($evaluate_set as $v){
				    array_push($setarr,$v['value']);
			    }

			    foreach($_POST as $k=>$v){
				   if(in_array($k,$setarr)){
				    	$star[$k] = $v;
				   }
			    }
				$result = $this->uploadimg($_FILES,$_userid);
				$data =[
				  'orderid'=>$id,
				  'shopid'=>$shopid,
				  'content'=>$content,
				  'star'=>$star,
				  'userid'=>$_userid,
				  'img'=>$result,
				  'addtime'=>time()
			     ];
			$evaluateid = $this->evaluate_db->insert($data,true);
			$result = $this->order_db->update(array('status'=>5),array('order_id'=>$_POST['id']));
			if($result){
			    $this->caozuo_success($result);
			}else{
				$this->caozuo_fail();
			}
	        } 
		}else{
			$this->error_check_uid_status();
		}
	}
	
	
	//申请退款
	function apply_tk(){

		$_userid = param::get_cookie('_userid') ? param::get_cookie('_userid') : $_POST['userid'];
		$orderid = $_POST['id'];
		$tk_reason = $_POST['tk_reason'];
		$tk_explain = $_POST['tk_explain'];
		if($_userid==null){
			$this->empty_userid();
		}
		if($this->check_uid($orderid,$_userid)){
		$order = $this->order_db->get_one(array('order_id'=>$orderid));

		if($order['status']>=2 && $order["status"] != '7'){
		   $result = $this->order_db->update(array('status'=>8,'prestatus'=>$order['status'],'tk_reason'=>$tk_reason,'tk_explain'=>$tk_explain,'shstatus'=>4),array('order_id'=>$orderid));
		   if($result){
		      $this->caozuo_success("退款成功");
		   }else{
		      $this->caozuo_fail("退款失败");
	       }
		}
		}else{
		   $this->error_check_uid();
		}
	}
	
	
	/********************************************** /
	*        店铺订单api                               * /
	*/                                            /**/
	
	
	/**
	* 订单中心_订单发货
	*/
	public function order_ddfh(){
		$id = $_POST['id'];
		$logistics_company_id = $_POST['logistics_company_id'];
		$logistics_order = $_POST['logistics_order'];
		$storeid = $_POST['storeid'];
		if($this->check_storeid_status($id,$storeid,2)){
		  $wuliu = $this->logistics_company_db->get_one(array('value'=>$logistics_company_id)); //获取物流
		  $result = $this->order_db->update(array('shipper_name' =>$wuliu['name'],'shipper_code' =>$wuliu['value'],'logistics_order' =>$logistics_order,'fhtime'=>time(),'status'=>'3'),array('order_id'=>$id));
		  if($result){
			  $this->caozuo_success("发货成功");
		  }else{
			 $this->caozuo_fail();
		  }
		}else{
			$this->error_check_storeid_status();
		}
	}
   
    //催款
    function cuikuan(){
		$id = $_POST['id'];
		$storeid = $_POST['storeid'];
		if($storeid==null){
			$this->empty_storeid();
		}
		$result = $this->order_db->update(array('remind'=>'催款'));
		if($result){
			$this->caozuo_success("催款成功s");
		}else{
			$this->caozuo_fail();
		}
		
	}

	//商铺修改订单收货地址
	function editaddress(){
		$id = $_POST['id'];
		$storeid = $_POST['storeid'];
		if($storeid==null){
			exit($this->show_error("no storeid"));
		}
		$lx_name = $_POST['lx_name'];
		$lx_mobile = $_POST['lx_mobile'];
		$lx_code = $_POST['lx_code'];
		$province = $_POST['province'];
		$city = $_POST['city'];
		$area = $_POST['area'];
		$address = $_POST['address'];
		$result = $this->order_db->update(array('lx_mobile'=>$lx_mobile,'lx_code'=>$lx_code,'lx_name'=>$lx_name,'province'=>$province,'city'=>$city,'area'=>$area,'address'=>$address),array('storeid'=>$storeid));
	    if($result){
		   $this->show_success("修改地址成功");
		}else{
		   $this->show_error("修改地址错误");
		}
	}

	//商铺修改价格
	function editprice(){
		$id = $_POST['id'];
		$storeid = $_POST['storeid'];
		$freeship = $_POST['freeship'];
		$freight = $_POST['freight'];
		$totalprice =$_POST['totalprice'];
		if($storeid==null){
			$this->empty_storeid();
		}
		$result = $this->order_db->update(array('freeship'=>$freeship,'freight'=>$freight,'totalprice'=>$totalprice),array('storeid'=>$storeid));
	    if($result){
		   $this->caozuo_success("修改价格成功");
		}else{
		   $this->caozuo_fail();
		}
	}
	//同意退款
	function approvetk(){
		$id = $_POST['id'];
		$storeid = $_POST['storeid'];
		if($storeid==null){
			$this->show_error("no storeid");
		}
		if($this->check_storeid_status($id,$storeid,7)){
		   $result = $this->order_db->update(array('status'=>8,'shstatus'=>1),array('order_id'=>$id));
		   if($result){
		       $this->caozuo_success("退款成功");
		   }else{
		       $this->caozuo_fail("退款失败");
		   }
		}else{
			$this->error_check_storeid_status();
		}
	}

	//拒绝退款
	function rejecttk(){
		$id = $_POST['id'];
		$storeid = $_POST['storeid'];
		$refuse_reason = $_POST['refuse_reason'];
		if($storeid==null){
			exit($this->empty_storeid());
		}
		if($this->check_storeid_status($id,$storeid,7)){
			$order = $this->order_db->get_one(array('order_id'=>$id));
		    $result = $this->order_db->update(array('status'=>$order['prestatus'],'refuse_reason'=>$refuse_reason,'shstatus'=>2),array('order_id'=>$id));
		    if($result){
		       $this->caozuo_success("退款成功");
		    }else{
		       $this->caozuo_fail();
		    }
		}else{
			$this->error_check_storeid_status();
		}
	
	}
	
	function uploadimg($files,$_userid){

		$basepath = str_replace( '\\' , '/',dirname(dirname(dirname(dirname(__FILE__)))));

		$savePath = $basepath.'/uploadfile/order/'.$_userid.'/'.date('Ymd',time());

		if(!file_exists($savePath)){
			mkdir($savePath,0777,true);
		}
		$result=[];
		foreach ($files as $key => $val) {
            $imgName = time().rand(1000, 9999);//随机数
            $file_dir = $savePath . "/" . $imgName . ".jpg";
            if (move_uploaded_file($val["tmp_name"], $file_dir)) {
            	$result['src']= APP_PATH.'uploadfile/zyshop/'.$_userid.'/'.date('Ymd',time()).'/'. $imgName . ".jpg";
            } else {
            	$info = [
            		'code'=>0,
            		'msg' =>'Error',
            		'data' => ''
            	];
            	$info = json_encode($info,JSON_UNESCAPED_UNICODE);
            	$info = stripslashes($info);
            	return $info;
            }
        }
        $info = [
        	'code'=>1,
        	'msg' =>'OK',
        	'data' => $result
        ];
        $info = json_encode($info,JSON_UNESCAPED_UNICODE);
        $info = stripslashes($info);
        return $info;

    }
	
	
	



	/**
	 * 订单模块_待支付
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $shopid [*商品id]
	 * @param  [type] $orderid [*订单id]
	 * @param  [type] $type [*类型：1web端、2APP端]
	 * @param  [type] $forward [接下来该跳转的页面链接]
	 * @return [json]         [数据组]
	 */
	public function shop_pay(){
		$userid = $_POST['userid'];	//用户id
		$shopid = $_POST['shopid'];	//商品id
		$orderid = $_POST['orderid'];	//商品订单号id
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$forward = $_POST['forward'] ? urldecode($_POST['forward']) : APP_PATH.'index.php?m=member&c=index';	//接下来该跳转的页面链接


		//==================	操作失败-验证 START
			//参数不能为空
			if(!$userid || !$shopid || !$orderid){
				$result = [
					'status'=>'error',
					'code'=>-1,
					'message'=>'请填写完整的参数',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//请先登录！
			if(!$userid){
				$result = [
					'status'=>'error',
					'code'=>-2,
					'message'=>'请先登录！',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//用户不存在
			$this->zyconfig_db = pc_base::load_model('zyconfig_model');
			$config = $this->zyconfig_db->get_one(array('key'=>'zymember1'),"url");
			$curl = [
				'userid'=>$userid,
				'field'=>'userid,username',
			];
			$memberinfo = $this->_crul_post($config['url'],$curl);
			$memberinfo = json_decode($memberinfo,true);

			if($memberinfo['code']!=200){
				$result = [
					'status'=>'error',
					'code'=>-3,
					'message'=>'用户不存在',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//判断订单号是否正确
			$shopid = explode(",", $shopid);
			$orderid = explode(",", $orderid);
			$shopcount = count($shopid);
			for ($i=0; $i < $shopcount; $i++) { 
				$orderarr[$i] = $this->order_db->get_one(['order_id'=>$shopid[$i],'ordersn'=>$orderid[$i]],'`order_id`,`storeid`,`status`,`userid`,`lx_mobile`,`lx_name`,`lx_code`,`province`,`city`,`area`,`address`,`totalprice`,`usernote`');

				$orderinfo['dianpu'][$i] = $this->order_db->get_one(['order_id'=>$shopid[$i],'ordersn'=>$orderid[$i]],'`order_id`,`ordersn`');

				if (!$orderarr[$i]) {
					$result = [
						'status'=>'error',
						'code'=>-4,
						'message'=>'订单号错误',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
				}
				
				//商品信息
				$orderinfo['dianpu'][$i]['shopinfo'] = $this->ordergoods_db->select('order_id in ('.$orderarr[$i]['order_id'].')','`goods_name`,`goods_num`,`goods_img`,`final_price`,`goods_price`,`specid_name`');

				//地址管理
				$orderinfo['address']['lx_mobile'] = $orderarr[$i]['lx_mobile'];
				$orderinfo['address']['lx_name'] = $orderarr[$i]['lx_name'];
				$orderinfo['address']['lx_code'] = $orderarr[$i]['lx_code'];
				$orderinfo['address']['province'] = $orderarr[$i]['province'];
				$orderinfo['address']['city'] = $orderarr[$i]['city'];
				$orderinfo['address']['area'] = $orderarr[$i]['area'];
				$orderinfo['address']['address'] = $orderarr[$i]['address'];

				//杂七杂八
				$orderinfo['other']['usernote']=$orderarr[$i]['usernote'];
				$orderinfo['other']['freight']+=$orderarr[$i]['freight'];
				$orderinfo['other']['total']+=$orderarr[$i]['totalprice'];

			}


		//==================	操作失败-验证 END

		//==================	操作成功-更新数据 START

			$data = [

			];
			$result = [
				'status'=>'success',
				'code'=>200,
				'message'=>'操作成功',
				'data'=>$orderinfo,
                'userid'=>$userid,
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

		//==================	操作成功-更新数据 END

	}
	
	
		
	
	
	
	
	
	/**
     *订单余额支付
     */
	public function prepay_for_balance (){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
		$oids = $_POST['oids'];
		$pass = $_POST['paycode'];


		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}

		if ( !$uid ) {
			$result = [
				'status' => 'error',
				'code' => 0,
				'message' => '请先登录！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if (!$oids || !$pass) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		foreach ($oids as $key => $value) {
			if ( isset($oid) ) {
				$oid .= ','.$value;
			} else {
				$oid = $value;
			}
		}

		$where = ' order_id in ('.$oid.') and status = 1 and userid = '.$uid;
		$count = $this->order_db->count($where);
		if ( $count != count($oids) ) {
			$result = [
				'status' => 'error',
				'code' => -2,
				'message' => '非法访问',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));	
		}
		$data=[
		   "userid"=>$uid,
		   "pay_password"=>$pass
		];
		$url = APP_PATH.'index.php?m=zymember&c=zymember_api&a=zyorder_offpaypas';	//yyy
		$return = json_decode($this->_crul_post($url,$data),true);

		if($return['code']=='200'){
			//$where = ' id in ('.$oid.') and userid = '.$uid;
			$sql = ' SELECT SUM(totalprice) as tprcie, `try_status` from zy_zy_order where '.$where;
			$rs = $this->order_db->query($sql);
			$res = $this->order_db->fetch_array($rs);//zy_order表
			$tprice = $res[0]['tprcie'];
			$data=[
			   'userid'=>$uid,
			   'amount'=>$tprice,
			   'describe'=>'余额支付',
			   'module'=>'zyorder'
			];
			//减少余额
            $goodsInfo = $this->ordergoods_db->select(array("order_id"=>$oid), "id,goods_id, is_count,is_xlCount,  specid, goods_num");
            foreach($goodsInfo as $k=>$v)
            {
                $this->ordergoods_db->update(array("is_xlCount"=>1), array("id"=>$v["id"]));
                if($v["specid"] && $v["is_xlCount"] == 0)
                    $specidGoods[] = $v;
                elseif($v["is_xlCount"] == 0)
                    $notSpecidGoods[] = $v;
            }
            if(isset($specidGoods))
            {
                foreach($specidGoods as $k=>$v)
                {
                    $this->goods_specs_db->update(array("salenum"=>"+=".$v["goods_num"]), array("goodsid"=>$v["goods_id"], "specid"=>$v["specid"]));
                    $this->goods_db->update(array("salesnum"=>"+=".$v["goods_num"]), array("id"=>$v["goods_id"]));
                }
            }
            if(isset($notSpecidGoods))
            {
                foreach($notSpecidGoods as $k=>$v)
                {
                    $this->goods_db->update(array("salesnum"=>"+=".$v["goods_num"]), array("id"=>$v["goods_id"]));
                }
            }
			$url = APP_PATH."index.php?m=zymember&c=zymember_api&a=pub_reduceamount&userid=$uid&amount=$tprice&describe=余额支付&module=zyorder";
			$return = json_decode($this->_crul_get($url,$data),true);

			if ( $return['code']=='200' ) {
//				$result =  $this->order_db->update(array('status'=>2),$where);
//				$result = [
//					'status' => 'success',
//					'code' => 1,
//					'message' => 'OK',
//                    'userid'=>$uid
//				];
				$data=[
					'userid'=>$uid,
					'oid'=>$oid,
				];
				//更新积分 分销佣金
				$url = APP_PATH."index.php?m=zypoints&c=api&a=api_update_points";
				$return = json_decode($this->_crul_post($url,$data),true);

				if( $return['code']=='200' ) {
				    if($res[0]["try_status"] == "0")
					    $result =  $this->order_db->update(array('status'=>2),$where);
				    else if($res[0]["try_status"] == "1")
                        $result =  $this->order_db->update(array('status'=>4, 'overtime'=>time()),$where);
					$result = [
						'status' => 'success',
						'code' => 1,
						'message' => 'OK',
						'userid'=>$uid,
                        'try_status'=>$res[0]["try_status"],
                        'id'=>$oids
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				}
				else {
					$result = [
						'status' => 'error',
						'code' => $return['code'],
						'message' => $return['message'],
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE));
				}

			} else {
				$result = [
					'status' => 'error',
					'code' => -5,
					'message' => $return['message'],
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));	
			} 

			
		}else{
			if($return['code']=='-3'){
				$code = -3;
			}else{
				$code = -4;
			}
			$result = [
				'status' => 'error',
				'code' => $code,
				'message' => $return['message'],
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));	
		}

	}



	/**
	 * 选择优惠券
	 * @param $_userid 用户ID
	 * @param _coupon_user_id 优惠券用户关联表ID
	 * @return json
	 * @internal param 关联表id $_coupon_user_id
	 */
	public function coupon_cancel($_coupon_user_id)
	{
		if($_coupon_user_id==0){
			return false;
		}
		$coupon_user_info = $this->zycoupon_user_db->get_one("id=".$_coupon_user_id." AND isused=1");
		$data['isused']=0;
		$data['isselect']=0;
		$info = $this->zycoupon_user_db->update($data,'id='.$_coupon_user_id);

		$coupon_info = $this->zycoupon_db->get_one("id=".$coupon_user_info['coupon']." AND takenum-usednum>0");
		$coupon_info['usednum']-=1;
		$info2 = $this->zycoupon_db->update($coupon_info,'id='.$coupon_user_info['coupon']);

		if($info&&$info2)
			return true;
		else
			return false;
	}
	
	
	
	
	
	
	

}
?>