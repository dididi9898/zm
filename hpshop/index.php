<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);



class index{
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
		//商品规格组合表
		$this->goods_specs_db = pc_base::load_model('goods_specs_model');
		//商品属性表
		$this->goods_attr_db = pc_base::load_model('goods_attr_model');


		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
		$this->config = $this->zyconfig_db->get_one('','url');
        $this->userid = param::get_cookie('_userid'); // 模拟数据
		
		//登录后方可操作
		// $_userid = param::get_cookie('_userid');
		// if (!$_userid) showmessage(L('login_website'), APP_PATH.'index.php?m=member&c=index&a=login&forward='.urlencode($_SERVER["REQUEST_URI"]));	
	}


	/**
     *商品详情
     */
	public function goodsinfo(){

		$where =' id = '.$_GET['id'].' and isok = 1 and on_sale = 1 ';
		$info = $this->goods_db->get_one($where);
		if ( count($info) == 0 ) {
			showmessage('商品不存在或已下架', HTTP_REFERER);
		}
		
		include template('hpshop', 'goodsinfo');
	}


	/**
     *商品详情
     */
	public function goodsinfos(){

		$where =' id = '.$_GET['id'].' and isok = 1 and on_sale = 1 ';
		$info = $this->goods_db->get_one($where);
		if ( count($info) == 0 ) {
			showmessage('商品不存在或已下架', HTTP_REFERER);
		}
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
			$az = 2;
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $az = 1;
        }else{
            $az = 3;
        }
		include template('hpshop', 'goodsinfo');
	}



	/**
     *添加商品
     */
	public function goodsadd(){

		
		include template('hpshop', 'goodsadd');
	}



	/**
     *商品分类
     */
	public function allcat(){

		
		include template('hpshop', 'allcat');
	}


	/**
     *商品分类列表
     */
	public function cgoodslist(){

		
		include template('hpshop', 'cgoodslist');
	}


	/**
     *商品搜索
     */
	public function search(){

		
		include template('hpshop', 'search');
	}

	/**
	 *商品搜索
	 */
	public function all_search(){


		include template('hpshop', 'all_search');
	}

	/**
     *商品结算
     */
	public function settlement(){

        $url = $this->zyconfig_db->get_one(array('key'=>"zyaddr1"));
        $params = array('userid'=>$this->userid);
        $paramstring = http_build_query($params);
        $data = json_decode($this->juhecurl($url['url'],$paramstring),true);
        $lists = $data['data'];
		include template('hpshop', 'settlement');
	}

	/**
	 *试穿订单确认
	 */
	public function trysettlement(){


		include template('hpshop', 'trysettlement');
	}


	/**
     *购物车
     */
	public function goodscart(){

		
		include template('hpshop', 'goodscart');
	}


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
?>