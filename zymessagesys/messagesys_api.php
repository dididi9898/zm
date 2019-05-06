<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class messagesys_api{
	function __construct() {
		
		$this->get_db = pc_base::load_model('get_model');
		$this->sms_report_db = pc_base::load_model('sms_report_model');	//短信记录
		$this->sms_xf_record_db = pc_base::load_model('sms_xf_record_model');	//短信消费记录
		//消息
		$this->zymessagesys_record_db = pc_base::load_model('zymessagesys_record_model');
		//配置模块表
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');

	}


	/**
	 * 公共模块_发送邮件消息
	 * @status [状态] -1标题、内容、接收人邮箱不能为空/-2请去通讯模块进行配置
	 * @param  [type] $title       [*发送标题]
	 * @param  [type] $content       [*发送内容]
	 * @param  [type] $address       [*接收人邮箱]
	 * @return [json]         [数据组]
	 */
	public function pub_sendemail()
	{

		$title = $_POST['title'];
		$content = $_POST['content'];
		$address = $_POST['address'];

		//微信密钥
		$off = pc_base::load_config('zysystem', 'mail_off');	//邮件服务器
		$host = pc_base::load_config('zysystem', 'mail_host');	//邮件服务器
		$port = pc_base::load_config('zysystem', 'mail_port');	//邮件发送端口
		$secure = pc_base::load_config('zysystem', 'mail_secure');	//是否开启微信开放平台（0开启、1未开启）
		$username = pc_base::load_config('zysystem', 'mail_username');	//验证用户名
		$pwd = pc_base::load_config('zysystem', 'mail_pwd');	//验证密码
		$set_from = pc_base::load_config('zysystem', 'mail_set_from');	//发件人邮箱



		//==================	操作失败-验证 START
			//用户id、内容id不能为空
			if (!$address || !$title || !$content) {
				$result = [
					'status'=>'error',
					'code'=>-1,
					'message'=>'标题、内容、接收人邮箱不能为空',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//请去通讯模块进行配置
			if (!$host || !$port || !$secure || !$username || !$pwd || !$set_from || $off==1) {
				$result = [
					'status'=>'error',
					'code'=>-2,
					'message'=>'请去通讯模块进行配置',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
		//==================	操作失败-验证 END
		

		//==================	操作成功-增加数据 START
			$data=[
				'host'=>$host,
				'port'=>$port,
				'secure'=>$secure,
				'username'=>$username,//邮箱账号
				'pwd'=>$pwd,//邮箱密码
				'set_from'=>$set_from,//发送人
				'address'=>$address,//接收人邮箱
				'subject'=>$title,//邮件标题
				'content'=>$content
			];
			$data=http_build_query($data);
			$url="http://oa.300c.cn/zyapi/sms/sendemail?".$data;
			$result=file_get_contents($url);

			$this->_return_status(200);
		//==================	操作成功-增加数据 END

	}

	/**
	 * 公共模块_发送系统消息
	 * @status [状态] -1请输入用户id/-2用户不存在/-3数据不能为空
	 * @param  [type] $userid       [*用户id]
	 * @param  [type] $content       [*内容]
	 * @param  [type] $title       [*标题]
	 * @param  [type] $sendname       [*发件人：暂时填写=系统消息]
	 * @param  [type] $url       [外部连接]
	 * @return [json]         [数据组]
	 */
	public function pub_sendsystem()
	{
		$userid = $_POST['userid'];
		$content = $_POST['content'];
		$title = $_POST['title'];
		$url = $_POST['url'];
		$sendname = $_POST['sendname'];

		//==================	获取其他接口-接口 START
			$config = $this->zyconfig_db->get_one(array('key'=>'zymember1'),"url");
			$curl = [
				'userid'=>$userid,
				'field'=>"userid,nickname,mobile,username",
			];
			$memberinfo = _crul_post($config['url'],$curl);
			$memberinfo=json_decode($memberinfo,true);
		//==================	获取其他接口-接口 END		


		//==================	操作失败-验证 START
			//用户id、内容id不能为空
			if (!$userid || !$content || !$title || !$sendname) {
				$result = [
					'status'=>'error',
					'code'=>-3,
					'message'=>'数据不能为空',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
		//==================	操作失败-验证 END

		//==================	操作成功-增加数据 START
			$data = [
				'userid'=>$memberinfo['data']['userid'],
				'username'=>$memberinfo['data']['username'],
				'mobile'=>$memberinfo['data']['mobile'],
				'content'=>$content,
				'title'=>$title,
				'addtime'=>time(),
				'nickname'=>$memberinfo['data']['nickname'],
				'types'=>1,
				'status'=>1,
				'url'=>$url,
				'sendname'=>$sendname,
			];
			$this->zymessagesys_record_db->insert($data);

			$this->_return_status(200);
		//==================	操作成功-增加数据 END
	}

	

	/**
	 * 公共模块_发送短信验证码消息
	 * @status [状态] -1请设置短信配置信息/-2手机号码不能为空/-3手机号格式错误/-4当日发送短信数量超过限制 20 条/-5同一IP 24小时允许请求的最大数/-100短信发送失败
	 * @param  [type] $mobile       [手机号码]
	 * @return [json]            []
	 */
	public function pub_sendsms()
	{
		$mobile = $_POST['mobile'];	//手机号码
		$model = 'SMS_151997147';
		$code = random(6);
		$content = json_encode(['code'=>$code],JSON_UNESCAPED_UNICODE);;

		//短信密钥
		$this->_alisms_uid = pc_base::load_config('zysystem', 'alisms_uid');	//阿里云 id
		$this->_alisms_pid = pc_base::load_config('zysystem', 'alisms_pid');	//阿里云 帐号
		$this->_alisms_passwd = pc_base::load_config('zysystem', 'alisms_passwd');	//阿里云 密码


		//==================	操作失败-验证 START
			//请设置短信配置信息
			if (!$this->_alisms_uid || !$this->_alisms_pid || !$this->_alisms_passwd) {
				$result = [
					'status'=>'error',
					'code'=>-1,
					'message'=>'请设置短信配置信息',	//请设置短信配置信息
					
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//帐号密码类型不能为空
			if (!$mobile) {
				$result = [
					'status'=>'error',
					'code'=>-2,
					'message'=>'手机号码不能为空',
					
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//用户名格式验证（手机号码格式验证）
			if (!$this->_verify_ismobile($mobile)) {
				$result = [
					'status'=>'error',
					'code'=>-3,
					'message'=>'手机号格式错误',	//只允许 13，14，15，16，17，18，19的号码,11位
					
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//当日发送次数验证、同一IP 24小时允许请求的最大数、手机号码验证
			//判断当日发送次数验证
			$posttime = SYS_TIME-86400;
			$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
			$num = $this->sms_report_db->count($where);
			if ($num>20) {
				$result = [
					'status'=>'error',
					'code'=>-4,
					'message'=>'当日发送短信数量超过限制 20 条',	//当日发送短信数量超过限制 20 条
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			//同一IP 24小时允许请求的最大数
			$allow_max_ip = 20;//正常注册相当于 20 个人
			$ip = ip();
			$where = "`ip`='$ip' AND `posttime`>'$posttime'";
			$num = $this->sms_report_db->count($where);
			if($num >= $allow_max_ip) {
				$result = [
					'status'=>'error',
					'code'=>-5,
					'message'=>'同一IP 24小时允许请求的最大数',	//同一IP 24小时允许请求的最大数
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}


			//发送短信
			$url = "http://www.300c.cn/api.php?op=aliyun_sms&uid=$this->_alisms_uid&user=$this->_alisms_pid&password=$this->_alisms_passwd&model=$model&mobile=$mobile&content=$content";
			$results = json_decode(_crul_get($url),true);

			if($results['status']!=1){
				$result = [
					'status'=>'error',
					'code'=>-100,
					'message'=>'短信发送失败',	//短信发送失败
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

		//==================	操作失败-验证 END

		
		//==================	操作成功-插入数据 START
			$sms_data_msg = '尊敬的用户您好，您的验证码为：'.$code.'，验证码有效期为10分钟。';
			//插入一条到消费记录里去
			$xf_data = [
				'admin'=>$this->_alisms_uid,
				'content'=>$sms_data_msg,
				'reception'=>$mobile,
				'ip'=>ip(),
				'sendtime'=>SYS_TIME,
			];
			$this->sms_xf_record_db->insert($xf_data);

			//插入一条到短信记录里去
			$sms_data = [
				'mobile'=>$mobile,
				'posttime'=>SYS_TIME,
				'id_code'=>$code,
				'msg'=>$sms_data_msg,
				'send_userid'=>$this->_alisms_uid,
				'status'=>$results['status'],
				'return_id'=>$results['status'],
				'ip'=>ip(),
			];
			$this->sms_report_db->insert($sms_data);

			$this->_return_status(200);
		//==================	操作成功-插入数据 END
	}


	/**
	 * 公共模块_短信_验证验证码是否正确
	 * @status [状态] -1手机号码、验证码不能为空/-2验证码错误/-3验证码超时
	 * @param  [type] $mobile [*手机号码]
	 * @param  [type] $verify_code [*手机验证码]
	 * @param  [type] $clear  [*清空当前账号验证码：否=1，是=2]
	 * @return [json]         [数据组]
	 */
	public function pub_sms_verify()
	{
		$mobile = $_POST['mobile'];
		$verify_code = $_POST['verify_code'];
		$clear = $_POST['clear'];	//清空当前账号验证码：否=1，是=2

		$sms_record = $this->sms_report_db->get_one(['mobile'=>$mobile,'id_code'=>$verify_code]);

		//==================	操作失败-验证 START
			if(!$mobile || !$verify_code){
				$result = [
					'status'=>'error',
					'code'=>-1,
					'message'=>'手机号码、验证码不能为空',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			if(!$sms_record){
				$result = [
					'status'=>'error',
					'code'=>-2,
					'message'=>'验证码错误',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			$posttime = SYS_TIME;
			if($sms_record['posttime']+600<$posttime){	//10分钟
				$result = [
					'status'=>'error',
					'code'=>-3,
					'message'=>'验证码超时',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
		//==================	操作失败-验证 END
		

		//==================	操作成功-更新数据 START
			if ($clear==2) {
				$this->sms_report_db->update(['id_code'=>''],['mobile'=>$mobile]);
			}
			$result = [
				'status'=>'success',
				'code'=>200,
				'message'=>'验证码正确',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-更新数据 END

	}

	/**
	 * 公共模块_短信_清空当前账号验证码
	 * @param  [type] $mobile [*手机号码]
	 * @return [json]         [数据组]
	 */
	public function pub_sms_clear()
	{
		$mobile = $_POST['mobile'];

		//==================	操作成功-更新数据 START
			$this->sms_report_db->update(['id_code'=>''],['mobile'=>$mobile]);
			$this->_return_status(200);
		//==================	操作成功-更新数据 END

	}



//====================================	私有验证函数 START

	/*
	 * 私有验证_手机号
	 * 只允许 13，14，15，16，17，18，19的号码
	 * 长度进行了验证、手机号码前两位进行了验证
	 */
	private function _verify_ismobile($mobile) 
	{
		if (preg_match('/^(?:13\d{9}|14[5|7]\d{8}|15[0|1|2|3|5|6|7|8|9]\d{8}|16\d{9}|17\d{9}|18[0|2|3|5|6|7|8|9]\d{8}|19\d{9}|)$/',$mobile)){
			return true;
		} else {
			return false;
		}
	}

	/*
	 * 私有返回状态_返回状态
	 * @status [状态] 200操作成功/-100状态码不能为空，操作失败/-101账号不存在/-102帐号已锁定,无法登录/-103请先登录/-104参数不能为空
	 * @param  [type] $status [*状态]
	 * @param  [type] $data [*数据组]
	 * @param  [type] $page [*翻页数据]
	 */
	private function _return_status($status,$data,$pages) 
	{
		$status = $status;	//状态
		$data = $data;	//成功：返回数据组
		$pages = $pages;	//成功：返回数据组
		//==================	操作失败-验证 START
			switch ($status) {
				case 200:	//操作成功
					$result = [
						'status'=>'success',
						'code'=>200,
						'message'=>'操作成功',
						'data'=>$data,
					];
					if($pages){
						$result['page']=$pages;
					}
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -101:	//账号不存在
					$result = [
						'status'=>'error',
						'code'=>-101,
						'message'=>'账号不存在',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -102:	//帐号已锁定,无法登录
					$result = [
						'status'=>'error',
						'code'=>-102,
						'message'=>'帐号已锁定,无法登录',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -103:	//请先登录
					$result = [
						'status'=>'error',
						'code'=>-103,
						'message'=>'请先登录',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				case -104:	//参数不能为空
					$result = [
						'status'=>'error',
						'code'=>-104,
						'message'=>'参数不能为空',
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
				
				default:
					$result = [
						'status'=>'error',
						'code'=>-100,
						'message'=>'操作失败',	//帐号已锁定,无法登录
					];
					exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
					break;
			}
		//==================	操作失败-验证 END
	}

//====================================	私有验证函数 END



}