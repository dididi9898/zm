<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);



class goods_api{

	function __construct() {
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
		//商品属性组合表
		$this->goods_specs_db = pc_base::load_model('goods_specs_model');
		//商品属性表
		$this->goods_attr_db = pc_base::load_model('goods_attr_model');
		//购物车表
		$this->goodscarts_db = pc_base::load_model('goodscarts_model');
		//商品搜索历史表
		$this->goods_sh_db = pc_base::load_model('goods_sh_model');

		
	}


	/**
     *推荐商品
     */
	public function recgoods(){
		$rid = $_GET['rid'];//推荐位ID，实际参考后台推荐位内容

		if ( !$rid ) {
			$result = [
				'status' => 'error',
				'code' => 0,
				'message' => '参数错误！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$where = 'a.id = b.goodsid and b.pos_id = '.$rid;
        $sql ='SELECT a.id,a.goods_name,a.thumb,a.summary,a.market_price,a.shop_price,a.salesnum FROM phpcms_goods a,phpcms_goodspositem b WHERE '.$where.' ORDER BY a.addtime DESC';
        $page = $_GET['page'] ? $_GET['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,$pagesize = 10);

  //       $sqls = 'SELECT COUNT(*) as num FROM phpcms_goods a,phpcms_goodspositem b WHERE '.$where.' ORDER BY a.addtime DESC';
  //       $res = $this->goods_db->query($sqls);
  //       $page = $this->goods_db->fetch_array($res);
		// $totalnum = $page[0]['num'];
		// $totalpage = ceil($totalnum/10);
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
			// 'page' => [
			// 	'pagesize'=>10,
			// 	'totalpage'=>$totalpage,
			// 	'totalnum' => $totalnum
			// ]
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);
	}

	/**
     *分类栏目商品
     */
	public function catgoods(){
		$rid = $_GET['catid'];
		if ( !$rid ) {
			$result = [
				'status' => 'error',
				'code' => 0,
				'message' => '参数错误！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		$did = childrenids($rid);
		$did = array_unique($did);

		$did = implode(',', $did);	
		if(empty($did)){
			$did = $rid;
		}else{
			$did .= ','.$rid;
		}	

		$where ='catid in ('.$did.')';
		$order =' id desc ';

		if( isset($_GET['order']) ){
			switch ($_GET['order']) {
				case '1':
					$order =' salesnum desc ';
					break;
				case '2':
					$order =' salesnum asc ';
					break;
				case '3':
					$order =' shop_price desc ';
					break;
				case '4':
					$order =' shop_price asc ';
					break;
				case '5':
					$order =' addtime desc ';
					break;
				case '6':
					$order =' addtime asc ';
					break;					
				
				default:
					
					break;
			}
		}

		$sql = 'SELECT id,goods_name,thumb,summary,market_price,shop_price FROM phpcms_goods WHERE '.$where.'ORDER BY'.$order;
        $page = $_GET['page'] ? $_GET['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,$pagesize = 10);
		$sqls = 'SELECT COUNT(*) as num FROM phpcms_goods WHERE '.$where.'ORDER BY'.$order;
        $res = $this->goods_db->query($sqls);
        $page = $this->goods_db->fetch_array($res);
		$totalnum = $page[0]['num'];
		$totalpage = ceil($totalnum/10);

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
			'page' => [
				'pagesize'=>10,
				'totalpage'=>$totalpage,
				'totalnum' => $totalnum
			]
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);

	}


	/**
     *分类栏目
     */
	public function allcat(){
        require('classes/PHPTree.class.php');//加载树形结构类
        $infos = $this->goodscat_db->select($where,'id,cate_name,cate_img,pid','',$order = 'sort ASC,id ASC');
        $data = catetree($infos);

        //dump($infos,true);
        $r=PHPTree::makeTree($data, array(

        ));
        $rdata = [

        	'status'=>'success',
            'code'=>1,
            'message'=>'OK',
            'data'=> $r
        ];
        $content = json_encode((object)$rdata,JSON_UNESCAPED_UNICODE);
        $content = preg_replace("/cate_name/","name",$content);
        $content = preg_replace("/cate_img/","img",$content);

        echo $content;
    }
    


	/**
     *商品搜索
     */
	public function sergoods(){
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
        $type = $_POST['type'];
		if($_userid){
			$uid = $_userid;
		}else{
			$uid = $userid;
		}

		$where = ' isok = 1 and on_sale = 1 ';
		if (!empty($_POST['sercon'])) {
			$where .= " and goods_name like '%".$_POST['sercon']."%' ";
			if ( !empty($uid) ) {
				$his = $this->goods_sh_db->get_one(['userid'=>$uid]);
				if ( count($his) == 0 ) {
					$hisarr = [];
					$hisarr[] = $_POST['sercon'];
					$hiscon = array2string($hisarr); 
					$this->goods_sh_db->insert(['userid'=>$uid,'searchHistory'=>$hiscon]);
				} else {
					$hisarr = string2array($his['searchHistory']);
					foreach ($hisarr as $k => $v) {
						if ( $_POST['sercon'] == $v ) {
							unset($hisarr[$k]);
							array_values($hisarr);
							break;
						}
					}
					if ( count($hisarr) < 10 ) {
						//$hisarr[] = $_POST['sercon'];
						array_unshift($hisarr,$_POST['sercon']);
					} else {
						unset($hisarr[9]);
						array_unshift($hisarr,$_POST['sercon']);
					}
					$hiscon = array2string($hisarr); 
					$this->goods_sh_db->update(['searchHistory'=>$hiscon],['userid'=>$uid]);
				}
			}
		}



        switch ($type) {
            case '2':
                $order =' salesnum DEsc ';
                break;
            case '3':
                $order =' addtime desc ';
                break;
            case '4':
                $order =' shop_price asc ';
                break;
            case '5':
                $order =' shop_price Desc ';
                break;
            default:
                $order =' id desc ';
                break;
			}

		$sql = 'SELECT id,goods_name,thumb,summary,market_price,shop_price FROM phpcms_goods WHERE '.$where.'ORDER BY'.$order;
        $page = $_POST['page'] ? $_POST['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,$pagesize = 10);
		//$pages = $this->goods_db->pages;
		
        $sqls = 'SELECT COUNT(*) as num FROM phpcms_goods WHERE '.$where.'ORDER BY'.$order;
        $res = $this->goods_db->query($sqls);
        $page = $this->goods_db->fetch_array($res);
		$totalnum = $page[0]['num'];
		$totalpage = ceil($totalnum/10);

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
			'page' => [
				'pagesize'=>10,
				'totalpage'=>$totalpage,
				'totalnum' => $totalnum
			]
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);
	}


	/**
     *获取用户商品搜索记录
     */
	public function goods_sh(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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

		$info = $this->goods_sh_db->get_one(['userid'=>$uid]);
		$hisarr = string2array($info['searchHistory']);
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => [
				'hiscon' => $hisarr,
			]
		];

		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	}	
	public function clearHistory()
    {
        $_userid = param::get_cookie('_userid');
        $userid = $_POST['uid'];

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

        $info = $this->goods_sh_db->update(['searchHistory'=>''] ,['userid'=>$uid]);
        $hisarr = string2array($info['searchHistory']);
        $result = [
            'status' => 'success',
            'code' => 1,
            'message' => 'OK',
        ];

        exit(json_encode($result,JSON_UNESCAPED_UNICODE));
    }

	/**
     *商品上下架状态
     */
	public function goodssale(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['gid']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		$info = $this->goods_db->get_one(['shopid'=>$uid,'id'=>$_POST['gid']]);
		if ( count($info) == 0 ) {
			$result = [
				'status' => 'error',
				'code' => -2,
				'message' => '非法访问',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		
		if ( $info['on_sale'] == 1 ) {
			$this->goods_db->update(['on_sale'=>2],['shopid'=>$uid,'id'=>$_POST['gid']]);
		}else{
			$this->goods_db->update(['on_sale'=>1],['shopid'=>$uid,'id'=>$_POST['gid']]);
		}

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => '操作成功',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	}	



	/**
     *商品删除
     */
	public function goodsdel(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['gid']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		$num = $this->goods_db->count(['shopid'=>$uid,'id'=>$_POST['gid']]);
		if ( $num == 0 ) {
			$result = [
				'status' => 'error',
				'code' => -2,
				'message' => '非法访问',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$op = $this->goods_db->delete(['shopid'=>$uid,'id'=>$_POST['gid']]);

		if ( $op ) {
			$result = [
				'status' => 'success',
				'code' => 1,
				'message' => '删除商品成功',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		$result = [
			'status' => 'success',
			'code' => -3,
			'message' => '删除商品失败',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		
	}	



	/**
     *购物车修改操作
     */
	public function operacars(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$check = checkspec($_POST['gid'],$_POST['spec']);
		if ( $check['code'] == -2 ) {
			exit(json_encode($check,JSON_UNESCAPED_UNICODE));
		}

		if ( $check['data']['stock'] < $_POST['cnum'] ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '加入购物车失败，商品库存不足',
				'data' => $check['data']['stock'],
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		// if ( $check['data']['sid'] == $uid ) {
		// 	$result = [
		// 		'status' => 'error',
		// 		'code' => -4,
		// 		'message' => '商家不能购买自己店铺的商品',
		// 	];
		// 	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		// }

		//$info = $this->goodscarts_db->get_one(['userid'=>$uid,'goodsspecId'=>$_POST['spec'],'goodsid'=>$_POST['gid']]);

		// if ( count($info) == 0 ) {
		// 	$data = [];
		// 	$data['userid'] = $uid;
		// 	$data['goodsid'] = $_POST['gid'];
		// 	$data['goodsspecid'] = $_POST['spec'];
		// 	$data['ischeck'] = 1;
		// 	$data['cartnum'] = $_POST['cnum'];
		// 	$this->goodscarts_db->insert($data);
		// }else{
		$this->goodscarts_db->update(['cartnum'=>/*'+='.*/$_POST['cnum']],['userid'=>$uid,'goodsspecId'=>$_POST['spec'],'goodsid'=>$_POST['gid']]);
		// }

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => '操作成功',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));

		
	}



	/**
     *加入购物车
     */
	public function addbuycart(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$check = checkspec($_POST['gid'],$_POST['spec']);
		if ( $check['code'] == -2 ) {
			exit(json_encode($check,JSON_UNESCAPED_UNICODE));
		}

		if ( $check['data']['stock'] < $_POST['cnum'] ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '加入购物车失败，商品库存不足',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		// if ( $check['data']['sid'] == $uid ) {
		// 	$result = [
		// 		'status' => 'error',
		// 		'code' => -4,
		// 		'message' => '商家不能购买自己店铺的商品',
		// 	];
		// 	exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		// }

		$info = $this->goodscarts_db->get_one(['userid'=>$uid,'goodsspecId'=>$_POST['spec'],'goodsid'=>$_POST['gid']]);

		if ( count($info) == 0 ) {
			$data = [];
			$data['userid'] = $uid;
			$data['goodsid'] = $_POST['gid'];
			$data['goodsspecid'] = $_POST['spec'];
			$data['ischeck'] = 1;
			$data['cartnum'] = $_POST['cnum'];
			$this->goodscarts_db->insert($data);
		}else{
			$this->goodscarts_db->update(['cartnum'=>'+='.$_POST['cnum']],['userid'=>$uid,'goodsspecId'=>$_POST['spec'],'goodsid'=>$_POST['gid']]);
		}

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => '操作成功',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));

		
	}


	/**
     *删除购物车商品
     */
	public function delcars(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['cid']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if(is_numeric($_POST['cid'])){
			$op = $this->goodscarts_db->delete(['userid'=>$uid,'id'=>$_POST['cid']]);
		}else{
			$op = $this->goodscarts_db->delete(' id in ('.$_POST['cid'].') and userid = '.$uid.' and ischeck <> 2');
		}
		
		if ( $op ) {
			$result = [
				'status' => 'success',
				'code' => 1,
				'message' => '删除商品成功',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		$result = [
			'status' => 'success',
			'code' => -2,
			'message' => '删除商品失败',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));

		
	}



	/**
     *购物车数据
     */
	public function getcarts(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
		//exit($_POST['uid']);
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

        $sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.ischeck <> 2 LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';
        // $sql = 'SELECT a.id, a.thumb, a.goods_name, a.shop_price, a.stock, b.goodsspecid, b.cartnum, c.specprice, c.specid, c.specids FROM phpcms_goods a LEFT OUTER JOIN phpcms_goodscarts b ON a.id = b.goodsid and b.userid = '.$uid.' LEFT OUTER JOIN phpcms_goods_specs c ON a.id = c.goodsid and c.shopid = '.$uid;
        $sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = '.$uid.' group by b.shopid ';
        $page = $_POST['page'] ? $_POST['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,88888888);
        $infos = $this->get_db->multi_listinfo($sqls,$page,88888888);
        $idarr = '';
        foreach ($infos as $key => $value) {
        	if ( empty($idarr) ) {
        		$idarr = $value['id'];
        	}else{
        		$idarr .= ','.$value['id'];
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
        //dump($snamarr,true);
        
        $narr = [];
        foreach ($info as $k => $v) {
        	if(!isset($narr[$v['shopid']])){
        		$narr[$v['shopid']] = [
	        		'shopid' => $v['shopid'],
	        		'shopname' => $snamarr[$v['shopid']]['shopname'],
        		];
        	}
        	
        	if ( $v['goodsspecid'] != 0 ) {
        		$jg = $v['specprice'];
        	} else {
        		$jg = $v['shop_price'];
        	}
        	$narr[$v['shopid']]['cartinfo'][] = [
        		'cartid' => $v['cartid'],
        		'goodsid' => $v['id'],
        		'goodsname' => $v['goods_name'],
        		'goodsimg' => $v['thumb'],
        		'goodsspec' => $v['specid'],
        		'goodsspecs' => $v['specids'],
        		'goodsprice' => $jg,
        		'cartnum' => $v['cartnum'],
        	];

        }

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => [
				'carts' => array_values($narr),
				'uid' => $uid
			],
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);
	}	



	/**
     *立即购买前置操作
     */
	public function buynow(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$check = checkspec($_POST['gid'],$_POST['spec']);
		if ( $check['code'] == -2 ) {
			exit(json_encode($check,JSON_UNESCAPED_UNICODE));
		}

		if ( $check['data']['stock'] < $_POST['cnum'] ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '购买失败，商品库存不足',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( $check['data']['stock'] < $_POST['cnum'] ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '购买失败，商品库存不足',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$info = $this->goodscarts_db->get_one(['userid'=>$uid,'ischeck'=>2]);

		if ( count($info) == 0 ) {
			$data = [];
			$data['userid'] = $uid;
			$data['goodsid'] = $_POST['gid'];
			$data['goodsspecid'] = $_POST['spec'];
			$data['ischeck'] = 2;
			$data['cartnum'] = $_POST['cnum'];
			$this->goodscarts_db->insert($data);
		}else{
			$data = [];
			$data['goodsid'] = $_POST['gid'];
			$data['goodsspecid'] = $_POST['spec'];
			$data['cartnum'] = $_POST['cnum'];
			$this->goodscarts_db->update($data,['userid'=>$uid,'ischeck'=>2]);
		}

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		
	}

	/**
	 *立即购买前置操作
	 */
	public function trynow(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
		if (!$_POST['gid'] || !isset($_POST['spec']) || !$_POST['cnum']) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '访问受限，缺少必要参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$check = checkspec($_POST['gid'],$_POST['spec']);
		if ( $check['code'] == -2 ) {
			exit(json_encode($check,JSON_UNESCAPED_UNICODE));
		}

		if ( $check['data']['stock'] < $_POST['cnum'] ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '购买失败，商品库存不足',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( $check['data']['stock'] < $_POST['cnum'] ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '购买失败，商品库存不足',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$info = $this->goodscarts_db->get_one(['userid'=>$uid,'ischeck'=>2]);

		if ( count($info) == 0 ) {
			$data = [];
			$data['userid'] = $uid;
			$data['goodsid'] = $_POST['gid'];
			$data['goodsspecid'] = $_POST['spec'];
			$data['ischeck'] = 2;
			$data['cartnum'] = $_POST['cnum'];
			$this->goodscarts_db->insert($data);
		}else{
			$data = [];
			$data['goodsid'] = $_POST['gid'];
			$data['goodsspecid'] = $_POST['spec'];
			$data['cartnum'] = $_POST['cnum'];
			$this->goodscarts_db->update($data,['userid'=>$uid,'ischeck'=>2]);
		}

		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));

	}

	/**
     *购物车结算前置操作
     */
	// public function cartsettle(){
	// 	//dump($_POST,true);
	// 	$_userid = param::get_cookie('_userid');
	// 	$userid = $_POST['uid'];
	// 	$cids = $_POST['cids'];

	// 	if($_userid){
	// 		$uid = $_userid;
	// 	}else{
	// 		$uid = $userid;
	// 	}

	// 	if ( !$uid ) {
	// 		$result = [
	// 			'status' => 'error',
	// 			'code' => 0,
	// 			'message' => '请先登录！',
	// 		];
	// 		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	// 	}
	// 	if (!$cids) {
	// 		$result = [
	// 			'status' => 'error',
	// 			'code' => -1,
	// 			'message' => '访问受限，缺少必要参数',
	// 		];
	// 		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
	// 	}


		
	// }



	/**
     *订单结算预览
     */
	public function settlement(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
		$method = $_POST['met'];
		$cids = $_POST['cids'];

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
		if ($method == 1) {
			$info = $this->goodscarts_db->select(['userid'=>$uid,'ischeck'=>2]);
			if(count($info) == 0){
				$result = [
					'status' => 'error',
					'code' => -1,
					'message' => '访问受限，参数无效',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
			$cids = $info[0]['id'];	
		} else {
			if ( empty($cids) ) {
				$result = [
					'status' => 'error',
					'code' => -2,
					'message' => '访问受限，缺少参数',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
			$arr = explode(',', $cids);
			$where = ' userid = '.$uid.' and id in('.$cids.') and ischeck <> 2 ';	
			$info = $this->goodscarts_db->select($where);
			if ( count($info) != count($arr) ) {
				$result = [
					'status' => 'error',
					'code' => -1,
					'message' => '访问受限，参数无效',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}	
		}


		$sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock,b.catid, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.id in('.$cids.') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';

		$sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.id in('.$cids.') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = '.$uid.' group by b.shopid ';

        $page = $_GET['page'] ? $_GET['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,88888888);
        //dump($info,true);
        $infos = $this->get_db->multi_listinfo($sqls,$page,88888888);
        $idarr = '';
        foreach ($infos as $key => $value) {
        	if ( empty($idarr) ) {
        		$idarr = $value['id'];
        	}else{
        		$idarr .= ','.$value['id'];
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

        $narr = [];

        $total = 0;
        $tnum = 0;

        foreach ($info as $k => $v) {
        	if(!isset($narr[$v['shopid']])){
        		$narr[$v['shopid']] = [
	        		'shopid' => $v['shopid'],
	        		'shopname' => $snamarr[$v['shopid']]['shopname'],
	        		'stprice'=>0,
	        		'stnum'=>0
        		];
        	}
        	
        	if ( $v['goodsspecid'] != 0 ) {
        		$jg = $v['specprice'];
        	} else {
        		$jg = $v['shop_price'];
        	}
        	$narr[$v['shopid']]['stprice'] += $jg*$v['cartnum'];
        	$narr[$v['shopid']]['stnum'] += $v['cartnum'];
        	$total += $jg*$v['cartnum'];
        	$tnum += $v['cartnum'];
        	$narr[$v['shopid']]['cartinfo'][] = [
        		'cartid' => $v['cartid'],
        		'goodsid' => $v['id'],
        		'catid' => $v['catid'],
        		'goodsname' => $v['goods_name'],
        		'goodsimg' => $v['thumb'],
        		'goodsspec' => $v['specid'],
        		'goodsspecs' => $v['specids'],
        		'goodsprice' => $jg,
        		'cartnum' => $v['cartnum'],
        	];

        }

        $result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => [
				'shops' => array_values($narr),
				'uid' => $uid,
				'totalprice' => $total,
				'totalnum' => $tnum
			],
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);
		
	}






	/**
     *店铺商品
     */
	public function myshopgoods(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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


		$where ='shopid = '.$uid;
		$gstatus = isset($_POST['gstatus']) ? $_POST['gstatus'] : '1';
		switch ( $gstatus ) {
		 	case 0:
		 	case 1:
		 		$where .=' and isok = 1 and on_sale = 1';
		 		break;
		 	case 2:
		 		$where .=' and isok = 1 and on_sale = 2';
		 		break;	
		 	case 3:
		 		$where .=' and isok BETWEEN 2 AND 4 ';
		 		break;
		 	case 3:
		 		$where .=' and isok BETWEEN 2 AND 4 ';
		 		break;	
		 	default:
		 		
		 		break;
		 }  
		$order =' id desc ';
		$sql = 'SELECT id,goods_name,thumb,summary,market_price,shop_price,on_sale,isok,stock FROM phpcms_goods WHERE '.$where.' ORDER BY '.$order;
        $page = $_GET['page'] ? $_GET['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,$pagesize = 10);
		//$pages = $this->goods_db->pages;

        $sqls = 'SELECT COUNT(*) as num FROM phpcms_goods WHERE '.$where.' ORDER BY '.$order;
        $res = $this->goods_db->query($sqls);
        $page = $this->goods_db->fetch_array($res);
		$totalnum = $page[0]['num'];
		$totalpage = ceil($totalnum/10);
			
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => $info,
			'page' => [
				'pagesize'=>10,
				'totalpage'=>$totalpage,
				'totalnum' => $totalnum
			]
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);	
		

	}	


	/**
     *店铺上下架商品数量
     */
	public function on_sales_num(){
		
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['userid'];

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


		$where1 = 'shopid = '.$uid.' and on_sale = 1 ';
		$where2 = 'shopid = '.$uid.' and on_sale = 2 ';
		$count1 = $this->goods_db->count($where1);
		$count2 = $this->goods_db->count($where2);		
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' => [
				'ysj_shopnum' => $count1,
				'wsj_shopnum' => $count2
			],
		];
		$jg = json_encode($result,JSON_UNESCAPED_UNICODE);
        $jg = stripslashes($jg);

        exit($jg);	
		

	}	






	/**
     * 试穿订单确认订单生成
     */
	public function tryMakeOrder(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
		$method = $_POST['met'];
		$cids = $_POST['cids'];
		$addid = $_POST['address'];

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
		if ($method == 1) {
			$info = $this->goodscarts_db->select(['userid'=>$uid,'ischeck'=>2]);
			if(count($info) == 0){
				$result = [
					'status' => 'error',
					'code' => -1,
					'message' => '访问受限，参数无效',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
			$cids = $info[0]['id'];	
		} else {
			if ( empty($cids) ) {
				$result = [
					'status' => 'error',
					'code' => -2,
					'message' => '访问受限，缺少参数',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
			$arr = explode(',', $cids);
			$where = ' userid = '.$uid.' and id in('.$cids.') and ischeck <> 2 ';	
			$info = $this->goodscarts_db->select($where);
			if ( count($info) != count($arr) ) {
				$result = [
					'status' => 'error',
					'code' => -1,
					'message' => '访问受限，参数无效',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}	
		}

		if ( empty($addid) ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '访问受限，缺少参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$token_url= APP_PATH.'index.php?m=zyaddr&c=zyaddr_api&a=getaddr';       
        $data = array (
        	'userid' => $uid,
        	'id' => $addid,   		
        );
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

		$province = $rs['data']['province']/*$_POST['province']*/;  //收货地址省 
		$city = $rs['data']['city']/*$_POST['city']*/;//收货地址市
		$area = $rs['data']['district']/*$_POST['area']*/;//收货地址区
		$address = $rs['data']['address']/*$_POST['address']*/; //详细地址
		$lx_mobile = $rs['data']['phone']/* $_POST['lx_mobile']*/; //联系电话
		$lx_name = $rs['data']['name']/*$_POST['lx_name']*/; //联系人
		$lx_code = '　'/*$_POST['lx_code']*/; //联系邮编
		$mes = $_POST['usernote'];//用户留言

		if ( empty($province) || empty($city) || empty($area) || empty($address) ) {
			$result = [
				'status' => 'error',
				'code' => -4,
				'message' => '地址信息不全',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( empty($lx_mobile) || empty($lx_name) || empty($lx_code) ) {
			$result = [
				'status' => 'error',
				'code' => -5,
				'message' => '联系人信息不全',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}


		$sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.id in('.$cids.') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';

		$sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.id in('.$cids.') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = '.$uid.' group by b.shopid ';

        $page = $_GET['page'] ? $_GET['page'] : '1';
        $info = $this->get_db->multi_listinfo($sql,$page,88888888);   
	    
        $narr = [];
        $total = 0;
        // $tnum = 0;
        foreach ($info as $k => $v) {
        	if(!isset($narr[$v['shopid']])){
        		$narr[$v['shopid']] = [
	        		'shopid' => $v['shopid'],
	        		'stprice'=>0,
	        		'stnum'=>0
        		];
        	}
        	
        	if ( $v['goodsspecid'] != 0 ) {
        		$jg = $v['specprice'];
        	} else {
        		$jg = $v['shop_price'];
        	}

        	$narr[$v['shopid']]['stprice'] += $jg*$v['cartnum'];
        	$narr[$v['shopid']]['stnum'] += $v['cartnum'];
        	$total += $jg*$v['cartnum'];
        	// $tnum += $v['cartnum'];
        	$narr[$v['shopid']]['cartinfo'][] = [
        		'cartid' => $v['cartid'],
        		'goodsid' => $v['id'],
        		'goodsname' => $v['goods_name'],
        		'goodsimg' => $v['thumb'],
        		'goodsspec' => $v['specid'],
        		'goodsspecs' => $v['specids'],
        		'goodsprice' => $jg,
        		'cartnum' => $v['cartnum'],
        	];
        }

        $token_url= APP_PATH.'index.php?m=zyorder&c=zyorder_api&a=addorder';
        
        $data = array (
			'userid' => $uid,
			'province' => $province,
			'city' => $city,
			'area' => $area,
			'address' => $address,
			'lx_mobile' => $lx_mobile,
			'lx_name' => $lx_name,
			'lx_code' => $lx_code,
			'usernote' => $mes,
			'shopdata' => $narr,
			'status'=> 7,
			'try_status'=>1 ,

        );
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

	    if ( $rs['data']['code'] != 1 ) {

	    }
	    if ($where) {
	    	$this->goodscarts_db->delete($where);
	    }
        $result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' =>$rs['data']
		];
		
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		
	}

	/**
	 *订单确认订单生成
	 */
	public function sureMakeOrder(){
		//dump($_POST,true);
		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
		$method = $_POST['met'];
		$cids = $_POST['cids'];
		$addid = $_POST['address'];

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
		if ($method == 1) {
			$info = $this->goodscarts_db->select(['userid'=>$uid,'ischeck'=>2]);
			if(count($info) == 0){
				$result = [
					'status' => 'error',
					'code' => -1,
					'message' => '访问受限，参数无效',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
			$cids = $info[0]['id'];
		} else {
			if ( empty($cids) ) {
				$result = [
					'status' => 'error',
					'code' => -2,
					'message' => '访问受限，缺少参数',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
			$arr = explode(',', $cids);
			$where = ' userid = '.$uid.' and id in('.$cids.') and ischeck <> 2 ';
			$info = $this->goodscarts_db->select($where);
			if ( count($info) != count($arr) ) {
				$result = [
					'status' => 'error',
					'code' => -1,
					'message' => '访问受限，参数无效',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE));
			}
		}

		if ( empty($addid) ) {
			$result = [
				'status' => 'error',
				'code' => -3,
				'message' => '访问受限，缺少参数',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$token_url= APP_PATH.'index.php?m=zyaddr&c=zyaddr_api&a=getaddr';
		$data = array (
			'userid' => $uid,
			'id' => $addid,
		);
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

		$province = $rs['data']['province']/*$_POST['province']*/;  //收货地址省
		$city = $rs['data']['city']/*$_POST['city']*/;//收货地址市
		$area = $rs['data']['district']/*$_POST['area']*/;//收货地址区
		$address = $rs['data']['address']/*$_POST['address']*/; //详细地址
		$lx_mobile = $rs['data']['phone']/* $_POST['lx_mobile']*/; //联系电话
		$lx_name = $rs['data']['name']/*$_POST['lx_name']*/; //联系人
		$lx_code = '　'/*$_POST['lx_code']*/; //联系邮编
		$mes = $_POST['usernote'];//用户留言

		if ( empty($province) || empty($city) || empty($area) || empty($address) ) {
			$result = [
				'status' => 'error',
				'code' => -4,
				'message' => '地址信息不全',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		if ( empty($lx_mobile) || empty($lx_name) || empty($lx_code) ) {
			$result = [
				'status' => 'error',
				'code' => -5,
				'message' => '联系人信息不全',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}


		$sql = 'SELECT b.id, b.shopid, b.thumb, b.goods_name, b.shop_price, b.stock, a.id as cartid, a.goodsspecid, a.cartnum, c.specprice, c.specstock, c.specid, c.specids FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.id in('.$cids.') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = b.shopid and c.goodsid = b.id';

		$sqls = 'SELECT b.shopid as id FROM phpcms_goodscarts a INNER JOIN phpcms_goods b ON a.goodsid = b.id and a.userid = '.$uid.' and a.id in('.$cids.') LEFT OUTER JOIN phpcms_goods_specs c ON a.goodsspecid = c.specid and c.shopid = '.$uid.' group by b.shopid ';

		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->get_db->multi_listinfo($sql,$page,88888888);

		$narr = [];
		$total = 0;
		// $tnum = 0;
		foreach ($infos as $k => $v) {

		    if($v["stock"]-$v["cartnum"]< 0)
            {
                $result = [
                    'status' => 'error',
                    'code' => 0,
                    'message' => $v["goods_name"]."的库存不足",
                ];
                exit(json_encode($result,JSON_UNESCAPED_UNICODE));
            }

			if(!isset($narr[$v['shopid']])){
				$narr[$v['shopid']] = [
					'shopid' => $v['shopid'],
					'stprice'=>0,
					'stnum'=>0
				];
			}

			if ( $v['goodsspecid'] != 0 ) {
				$jg = $v['specprice'];
			} else {
				$jg = $v['shop_price'];
			}

			$narr[$v['shopid']]['stprice'] += $jg*$v['cartnum'];
			$narr[$v['shopid']]['stnum'] += $v['cartnum'];
			$total += $jg*$v['cartnum'];
			// $tnum += $v['cartnum'];
			$narr[$v['shopid']]['cartinfo'][] = [
				'cartid' => $v['cartid'],
				'goodsid' => $v['id'],
				'goodsname' => $v['goods_name'],
				'goodsimg' => $v['thumb'],
				'goodsspec' => $v['specid'],
				'goodsspecs' => $v['specids'],
				'goodsprice' => $jg,
				'cartnum' => $v['cartnum'],
			];
		}

		$token_url= APP_PATH.'index.php?m=zyorder&c=zyorder_api&a=addorder';

		$data = array (
			'userid' => $uid,
			'province' => $province,
			'city' => $city,
			'area' => $area,
			'address' => $address,
			'lx_mobile' => $lx_mobile,
			'lx_name' => $lx_name,
			'lx_code' => $lx_code,
			'usernote' => $mes,
			'shopdata' => $narr
		);
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

		if ( $rs['data']['code'] != 1 ) {

		}
		if ($where) {
			$this->goodscarts_db->delete($where);
		}
		$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
			'data' =>$rs['data']
		];

		exit(json_encode($result,JSON_UNESCAPED_UNICODE));

	}




	/**
     *商品详情
     */
	public function goodsinfo(){
		
		$gid = $_GET['gid'];

		if ( !$gid || !is_numeric($gid) ) {
			$result = [
				'status' => 'error',
				'code' => 0,
				'message' => '参数异常！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$where =' id = '.$gid.' and isok = 1 and on_sale = 1 ';
		$info = $this->goods_db->get_one($where);
		if ( count($info) == 0 ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '商品不存在或已经下架！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}
		$info['album'] = string2array($info['album']);
		$info['goodsimg_infos'] = string2array($info['goodsimg_infos']);
		$info['awardNumber'] = string2array($info['awardNumber']);
		$info['trialAwardNumber'] = string2array($info['trialAwardNumber']);
		unset($info['content']);
		if ( $info['isspec'] == 1) {
			$where = ' goodsid = '.$gid;
			$sinfo = $this->goods_specs_db->select($where,'id,specid,specids,specprice,specstock,status,salenum','',$order = ' id ASC ');
			$info['specdata'] = $sinfo;
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
     *添加商品获取商品类型相关规格数据
     */
	public function goodstypedata(){
		
		$fid = $_POST['fid'];
        	
		if ( !$fid || !is_numeric($fid) ) {
			$result = [
				'status' => 'error',
				'code' => 0,
				'message' => '参数异常！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}

		$info = getspec($fid);
		//dump($info,true);

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
     *获取品牌信息
     */
	public function goodsbrand(){

		$info = $this->brand_db->select('1','id,brandname','',$order = 'sort ASC, id DESC');
		//dump($info,true);

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
     *获取所有商品类型
     */
	public function getgoodstype(){

		$info = $this->goodstype_db->select('1','id,type_name','',$order = 'id ASC');
		//dump($info,true);

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
     *订单支付完成计算销量库存
     */
	public function sales_balance (){

		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];
		$oids = $_POST['oids'];

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

		if ( !$oids ) {
			$result = [
				'status' => 'error',
				'code' => -1,
				'message' => '缺少必要参数！',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE));
		}


		

	}	






	/**
	 *添加商品
	 */
	public function goodsadd(){

		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
			
		$goodsimg = array2string($goodsimg);
    	$data=[
    		'shopid' => $uid,
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
			'isok' => 2,
			'addtime'=>time(),
    	];
    	
    	$results=$this->goods_db->insert($data,true);
    	
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

    	$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
    }



    /**
	 *修改商品信息
	 */
	public function goodsedit(){

		$_userid = param::get_cookie('_userid');
		$userid = $_POST['uid'];

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
    	];

    	
    	if(isset($_POST['pos'])){
    		$results = $this->positem_db->delete(['goodsid'=>$id]);
    		foreach ($_POST['pos'] as $kp => $vp) {
    			$resultss=$this->positem_db->insert(['pos_id'=>$vp,'goodsid'=>$id]);
    		}
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

    	$result = [
			'status' => 'success',
			'code' => 1,
			'message' => 'OK',
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE));
    }


}
?>