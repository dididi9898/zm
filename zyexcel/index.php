<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class index{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');
		
		//第三方临时登录记录表
		$this->temporary_login_db = pc_base::load_model('temporary_login_model');
		//会员主表
		$this->members_db = pc_base::load_model('members_model');
		//会员附表
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		//会员sso表
		$this->sso_members_db = pc_base::load_model('sso_members_model');

		//引入卓远网络公共函数库
		//require_once 'zywl/functions/global.func.php';
	}



	/**
	* 登录_账号密码登入
	*/
	public function login(){		
		if($_POST['dosubmit']){
			$member_arr = array();
			$member_arr['mobile']=$_POST['mobile'];
			$member_arr['password']=$_POST['password'];
			
			var_dump($member_arr);
			$member_info = $this->member_db->get_one(array('mobile'=>$member_arr['mobile']));
			if(empty($member_info))exit('0');
			
			
		}else{
			
			
			if (check_wap()) {
				include template('zy_member', 'm_login');
			} else {
				include template('zy_member', 'login');
			}
		}
	}
	
	
	/**
	* 登录_手机验证码获取登入
	*/
	public function login_mobile(){
		
		$member_arr = array();
		$member_arr['mobile']=$_POST['mobile'];
		$member_arr['password']=$_POST['password'];
		
		
	}

	//===================================帐号绑定
	/**
	* 帐号绑定_微信号绑定
	*/
	public function account_binding_wechat(){
		//用户id和用户名
		$_userid = param::get_cookie('_userid');
		if (!$_userid) showmessage(L('login_website'), APP_PATH.'index.php?m=member&c=index&a=login&forward='.urlencode($_SERVER["REQUEST_URI"]));
		
		$members_db = $this->members_db->get_one(array('userid'=>$_userid));
		if($members_db['wechat_unionid']){
			exit('帐号已绑定,请勿重复绑定');
		}
		
		//微信密钥
		$_appid = pc_base::load_config('system', 'wechat_appid');
		$_appsecret = pc_base::load_config('system', 'wechat_appsecret');
		$_appid_pc = pc_base::load_config('system', 'wechatpc_appid');
		$_appsecret_pc = pc_base::load_config('system', 'wechatpc_appsecret');
		$state = $_GET['state'];		//1、电脑还是公众号（1：电脑2：公众号）
		$code = $_GET['code'];
		
		
		//微信公众号的电脑的区别
		if($state==1){
			$appid = $_appid_pc;
			$appsecret = $_appsecret_pc;
			
			$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		}elseif($state==2){
			$appid = $_appid;
			$appsecret = $_appsecret;
			
			$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		}
		
		$token = json_decode(file_get_contents($token_url));
		 // var_dump($token);
         // exit;
		
        if (isset($token->errcode)) {

            showmessage(L('<br/><h2>错误信息：</h2>'.$token->errmsg), HTTP_REFERER);
            exit;
        }
		
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
        //转成对象
		
        $access_token = json_decode(file_get_contents($access_token_url));
        if (isset($access_token->errcode)) {
            showmessage(L('<br/><h2>错误信息：</h2>'.$access_token->errmsg), HTTP_REFERER);

            exit;
        }
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';

        //转成对象
        $user_info = json_decode(file_get_contents($user_info_url));
	//	var_dump($user_info);
	//	exit;
        if (isset($user_info->errcode)) {

            showmessage(L( '<br/><h2>错误信息：</h2>'.$user_info->errmsg), HTTP_REFERER);
            exit;
        }
		
        $rs =  json_decode(json_encode($user_info),true);//转换成数组
		
		
		//查看这个微信号是否已经绑定其他帐号了
		$no_member = $this->members_db->get_one(array('wechat_unionid'=>$rs['unionid']));
		if($no_member){
			exit('该微信号已绑定过其他帐号');
		}
		
		$temporary_name = str_replace(' ','',$rs['nickname']);
		if(empty($temporary_name)){
			$temporary_name='???';
		}
		$this->members_db->update(array('wechat_name'=>$temporary_name),'userid='.$_userid);
		$memberinfo = $this->members_db->get_one('userid='.$_userid);
		if(empty($memberinfo['wechat_name'])){
			$temporary_name='???';
		}
		
		$data = array(
			'nickname'=>$temporary_name,
			'wechat_name'=>$temporary_name,			
			'wechatpc_openid'=>$rs['openid'],		//PC
			'wechat_unionid'=>$rs['unionid'],		//
			'headimgurl'=>$rs['headimgurl'],
		);
		$this->members_db->update($data,'userid='.$_userid);
		exit('Success');
	}

	/**
	* 帐号绑定_微信号绑定_APP
	* 1、绑定成功；-1、帐号已绑定,请勿重复绑定；-2、该微信号已绑定其他帐号
	*/
	public function account_binding_wechat_app(){
		//用户id和用户名
		$_userid= $_POST['userid'];
		$members_db = $this->members_db->get_one(array('userid'=>$_userid));
		if($members_db['wechat_unionid']){
			$json_record = array(
				'status'=>'-1',	//帐号已绑定,请勿重复绑定
				'userid'=>$members_db['userid'],
			);
			$zzz= json_encode($json_record , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
			exit($zzz);
		}
		
		
        $rs =  json_decode(json_encode($user_info),true);//转换成数组
		$rs['nickname'] = $_POST['nickname'];
		$rs['openid'] = $_POST['openid'];
		$rs['unionid'] = $_POST['unionid'];
		$rs['headimgurl'] = $_POST['headimgurl'];
		$temporary_name = str_replace(' ','',$rs['nickname']);
		
		
		//查看这个微信号是否已经绑定其他帐号了
		$no_member = $this->members_db->get_one(array('wechat_unionid'=>$rs['unionid']));
		if($no_member){
			$json_record = array(
				'status'=>'-2',	//该微信号已绑定其他帐号
				'userid'=>$no_member['userid'],
			);
			$zzz= json_encode($json_record , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
			exit($zzz);
		}
		
		if(empty($temporary_name)){
			$temporary_name='???';
		}
		$this->members_db->update(array('wechat_name'=>$temporary_name),'userid='.$_userid);
		$memberinfo = $this->members_db->get_one('userid='.$_userid);
		if(empty($memberinfo['wechat_name'])){
			$temporary_name='???';
		}

		$data = array(
			'nickname'=>$temporary_name,
			'wechat_name'=>$temporary_name,			
			'wechatapp_openid'=>$rs['openid'],		//APP
			'wechat_unionid'=>$rs['unionid'],		//
			'headimgurl'=>$rs['headimgurl'],
		);
		$this->members_db->update($data,'userid='.$_userid);
		
		$json_record = array(
			'status'=>'1',	//绑定成功
			'userid'=>$members_db['userid'],
		);
		$zzz= json_encode($json_record , JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
		exit($zzz);
	}




}
?>