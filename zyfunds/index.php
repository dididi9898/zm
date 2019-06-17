<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
//require_once dirname(__FILE__).'/zyfunds_api.php';

class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		$this->zyfound_bank_db = pc_base::load_model('zyfound_bank_model');
		$this->zyfound_bankcard_db = pc_base::load_model('zyfound_bankcard_model');
		$this->zyfound_pay_db = pc_base::load_model('zyfound_pay_model');
		$this->zyfound_tx_db = pc_base::load_model('zyfound_tx_model');
		$this->zyfound_account_db = pc_base::load_model('zyfound_account_model');
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');

		$this->userid = param::get_cookie("_userid"); // 模拟数据
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

	/**
	 * CURL方式的POST传值
	 * @param  [type] $url  [POST传值的URL]
	 * @param  [type] $data [POST传值的参数]
	 * @return [type]       [description]
	 */
	public function _crul_post($url,$data){
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
	 * 获取会员信息
	 * @param $url
	 * @return json
	 */
	public function member_api($url)
	{
		$params = array('userid'=>$this->userid);
		$paramstring = http_build_query($params);
		$content = $this->juhecurl($url,$paramstring);
		$result = json_decode($content,true);
		return $result;
	}

	/**
	 * 我的钱包
	 */
	public function wallet()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds2'));
		$params = array('id'=>$this->userid,"key"=>"zyfunds1");
		$paramstring = http_build_query($params);
		$res = $this->juhecurl($url['url'],$paramstring);
		include template('zyfunds','wallet');
	}

	/**
	 * 零钱提现
	 */
	public function wcash()
	{
		// 从资金账户里获取资金总额
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds2')); // cash
		$params = array('id'=>$this->userid,"key"=>"zyfunds1");
		$paramstring = http_build_query($params);
		$account = $this->juhecurl($url['url'],$paramstring);

		/* 显示账户是用默认还是选择的账户 */
		$where['userid'] = $this->userid;

		if(empty($_GET['id'])){
			$is_first = 1;
			$id = '';
		}else{
			$id = $_GET['id'];
			$is_first = '';
		}

		$urls = $this->zyconfig_db->get_one(array('key'=>'zyfunds3'));  // account
		$param = array('is_first'=>$is_first,'userid'=>$this->userid,'id'=>$id,'limit'=>1);
		$paramstrings = http_build_query($param);
		$result = $this->juhecurl($urls['url'],$paramstrings);

		include template('zyfunds', 'wcash');
	}

	/**
	 * 零钱提现
	 */
	public function with_cash()
	{
		// 从资金账户里获取资金总额
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds2'));
		$params = array('id'=>$this->userid,"key"=>"zyfunds1");
		$paramstring = http_build_query($params);
		$res = $this->juhecurl($url['url'],$paramstring);

		include template('zyfunds', 'with_cash');
	}

	/**
	 * 佣金提现
	 */
	public function brokerage2wallet()
	{
		// 从资金账户里获取资金总额
		$params = array('id'=>$this->userid,"key"=>"zyfunds1");
		$paramstring = http_build_query($params);
		$account = $this->juhecurl('',$paramstring);

		include template('zyfunds', 'brokerage2wallet');
	}

	/*
	 * 选择账户信息
	 * */
	public function bank()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds4')); // choosebank
		$params = array('id'=>$this->userid,'type'=>$_GET['type']);
		$paramstring = http_build_query($params);
		$result = $this->juhecurl($url['url'],$paramstring);
		include template('zyfunds','bank');
	}

	/*
	 * 验证支付密码
	 */
	public function checkTradePass()
	{
		// 获取会员账户金额
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds2')); // cash
		$params = array('id'=>$this->userid);
		$paramstring = http_build_query($params);
		$res = $this->juhecurl($url['url'],$paramstring);
		$info = json_decode($res,true);

		/* 插入数据 */
		if($info['code']==200){
			/* 提现数据表 */
			$data = $_POST;

			$datas = array(
				"userid" => $this->userid,
				"type" => $data['type'],
				"account" => $data['account'],
				"accountname" => $data['accountname'],
				"amount" => $data['amount'],
				"pass" => $data['pass'],
				"key" => "zymember3"
			);

			$urls = $this->zyconfig_db->get_one(array('key'=>'zyfunds6')); // txgetdata
			echo $this->_crul_post($urls['url'],$datas);
		}
	}

	/*
	 * 提现提交申请成功
	 * */
	public function txsuccess(){
		$info = $this->zyfound_tx_db->get_one(array('id'=>$_GET['id']));
		include template('zyfunds','success');
	}

	/*
	 * 流水明细账单
	 * */
	public function bill()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds7')); // lslist
		$params = array('id'=>$this->userid);
		$paramstring = http_build_query($params);
		$results = $this->juhecurl($url['url'],$paramstring);

		$tab = empty($_GET['tab'])?1:$_GET['tab'];
		$info = $this->params;
		include template('zyfunds','bill');
	}

	/*
	 * 提现详情订单
	 * */
	public function billdetail()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds8')); // txlists
		$params = array('id'=>$_GET['id']);
		$paramstring = http_build_query($params);
		$r = json_decode($this->juhecurl($url['url'],$paramstring),true);

		$r['data']['addtime'] = date('Y-m-d H:i:s',$r['data']['addtime']);
		$result = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		include template('zyfunds','billdetail');
	}

	/*
	 * 充值详情订单
	 * */
	public function billdetails()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds10')); // czlists
		$params = array('id'=>$_GET['id']);
		$paramstring = http_build_query($params);
		$r = json_decode($this->juhecurl($url['url'],$paramstring),true);

		$r['data']['addtime'] = date('Y-m-d H:i:s',$r['data']['addtime']);
		$result = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		include template('zyfunds','billdetails');
	}

	/**
	 * 银行卡管理
	 */
	public function bcard(){
		$config = $this->zyconfig_db->get_one(array('key'=>'zyfunds1'),"url");
		$this->config = empty($config["url"])?0:1;

		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds11')); // moneywallet
		$params = array('id'=>$this->userid);
		$paramstring = http_build_query($params);
		$result = $this->juhecurl($url['url'],$paramstring);

		include template('zyfunds', 'account');
	}

	/*
	 * 选择添加账号
	 */
	public function account_add(){
		include template('zyfunds', 'pay');
	}
	/*
	 * 添加支付宝账号（改）
	 */
	public function alipay()
	{
		include template('zyfunds', 'alipay');
	}

	/*
	 * 添加支付宝账号
	 * */
	public function alipay_add()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds13')); // alipay_add
		$params = array(
			'userid'=>$this->userid,
			'account'=>$_POST['account'],
			'accountname'=>$_POST['accountname'],
			'key'=>'zymember1'
		);
		$paramstring = http_build_query($params);
		echo $this->juhecurl($url['url'],$paramstring);
	}

	/*
	 * 选择添加微信账号（改）
	 */
	public function wechat() {
		include template('zyfunds', 'wechat');
	}

	/*
	 * 添加微信账号
	 * */
	public function wechat_add()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds14')); // wechat_add
		$params = array(
			'userid'=>$this->userid,
			'account'=>$_POST['account'],
			'accountname'=>$_POST['accountname'],
			'key'=>'zymember1'
		);
		$paramstring = http_build_query($params);
		echo $this->juhecurl($url['url'],$paramstring);
	}

	/*
	 * 选择添加银行卡账号
	 */
	public function banks(){
		$zyfunds_api = new zyfunds_api();
		$bank = json_decode($zyfunds_api->bankinfo(),true)['data'];
		foreach($bank as $k => $v){
			$result[$k]['value'] = $v['id'];
			$result[$k]['text'] = $v['bank'];
		}
		$result = json_encode($result);
		include template('zyfunds', 'banks');
	}

	/*
	 * 添加银行账号
	 * */
	public function banks_add()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds15')); // banks_add
		$params = array(
			'userid'=>$this->userid,
			'account'=>$_POST['account'],
			'accountname'=>$_POST['accountname'],
			'tname'=>$_POST['tname'],
			'key'=>'zymember1'
		);
		$paramstring = http_build_query($params);
		echo $this->juhecurl($url['url'],$paramstring);
	}

	/*
	 * 是否设置为默认账户
	 * */
	public function bcardtype(){
		$id = $_POST['id'];
		$is_first = $_POST['is_first'];
		/*是否取消默认卡 - 否*/
		if($is_first==-1){
			$oid = $this->zyfound_bankcard_db->get_one(array('is_first'=>1),'id');
			if($oid['id'] != $id){
				$data['is_first'] = -1;
				$map['id'] = $oid['id'];
				$res = $this->zyfound_bankcard_db->update($data,$map);
				if($res){
					$data['is_first'] = 1;
					$map['id'] = $id;
					if($this->zyfound_bankcard_db->update($data,$map)){
						echo json_encode(array('code'=>0,'msg'=>'操作成功',id=>$id,'is_first'=>1));
					}else{
						echo json_encode(array('code'=>1,'msg'=>'操作失败'));
					}
				}
			}else{
				$data['is_first'] = 1;
				$map['id'] = $id;
				if($this->zyfound_bankcard_db->update($data,$map)){
					echo json_encode(array('code'=>0,'msg'=>'操作成功',id=>$id,'is_first'=>1));
				}else{
					echo $this->iserror();
				}
			}
		}else{
			/* --是--*/
			$data['is_first'] = -1;
			$map['id'] = $id;
			if($this->zyfound_bankcard_db->update($data,$map)){
				echo json_encode(array('code'=>0,'msg'=>'操作成功',id=>$id,'is_first'=>-1));
			}else{
				echo json_encode(array('code'=>1,'msg'=>'操作失败'));
			}
		}
	}

	/*
	 * 删除账户
	 * */
	public function bcarddel()
	{
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds19')); // bcarddel
		$params = array('id'=>$_POST['id'],'userid'=>$this->userid);
		$paramstring = http_build_query($params);
		echo $this->juhecurl($url['url'],$paramstring);
	}

	/*
	 * 充值账户
	 * */
	public function czcash(){
		include template('zyfunds','czcash');
	}
	
	/*
	 * 充值交易
	 * */
	public function cztradecash(){
		// 获取会员账户金额
		$url = $this->zyconfig_db->get_one(array('key'=>'zyfunds2')); // cash
		$params = array('id'=>$this->userid,"key"=>"zyfunds1");
		$paramstring = http_build_query($params);
		$res = $this->juhecurl($url['url'],$paramstring);

		$info = json_decode($res,true);

		/* 提现数据表 */
		$data = $_POST;

		$datas = array(
				"userid" => $this->userid,
				"type" => $data['type'],
				"amount" => $data['amount'],
				"key" => "zymember2"
			);

		$urls = $this->zyconfig_db->get_one(array('key'=>'zyfunds9')); // txgetdata
		echo $this->_crul_post($urls['url'],$datas);
	}

	/*
	 * 充值提交申请成功
	 * */
	public function paysuccess(){
		$info = $this->zyfound_pay_db->get_one(array('id'=>$_GET['id']));
		include template('zyfunds','paysuccess');
	}

	/*
	 * 返回信息成功
	 * return json
	 * */
	public function isok($msg='操作成功'){
		$info = array('code'=>0,'msg'=>$msg);
		return json_encode($info);
	}

	/*
	 * 返回信息失败
	 * return json
	 * */
	public function iserror($code=1,$msg='操作失败'){
		$info = array('code'=>$code,'msg'=>$msg);
		return json_encode($info);
	}
}
?>