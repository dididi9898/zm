<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');

class api{
	function __construct() {

		$this->get_db = pc_base::load_model('get_model');
		//消息
		$this->online_talk_list_db = pc_base::load_model('online_talk_list_model');	//聊天列表
		$this->online_talk_record_db = pc_base::load_model('online_talk_record_model');	//聊天记录
		$this->member_db = pc_base::load_model('member_model');
	}



	/**
	 * 创建会话
	 * @status [状态] -103请先登录
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $type [*类型：1web端、2APP端]
	 * @param  [type] $page [当前页码，默认第一页]
	 * @param  [type] $pagesize [当前的条数，默认20条]
	 * @return [json] [json数组]
	 */
	public function create_talk($userid)
	{
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];

		$infos=$this->online_talk_list_db->get_one(['talk_from_uid'=>$userid]);
		if($infos){
			$data['status']=1;
			$infos=$this->online_talk_record_db->update($data,['records_id'=>$userid,'status'=>0,'to_user'=>$userid]);
			$this->_return_status(-1,$infos);
		}
		//数据
		$where = ['userid'=>$userid];
		$info=$this->member_db->get_one($where);
		$data['talk_from_uid']=$userid;
		$data['talk_from_name']=$info['nickname'];
		$data['talk_from_img']=$info['headimgurl'];
		$data['talk_to_uid']=0;
		$data['talk_to_img']=APP_PATH.'statics/images/zz_bg.jpg';
		$data['talk_to_name']='惠集信购客服';
		$data['records_id']=$info['userid'];

		//==================	操作失败-验证 START
		//请先登录
		if (!$userid) {
			$this->_return_status(-103);
		}else{
			$info=$this->online_talk_list_db->insert($data);
		}
		//==================	操作失败-验证 END

		$this->_return_status(200,$info);
		//==================	操作成功-返回数据 END
	}


	/**
	* 前台_消息内容页
	* @status [状态] -103请先登录/-104参数不能为空
	* @param  [type] $userid [*用户id]
	* @param  [type] $type [*类型：1web端、2APP端]
	* @param  [type] $showid [*消息id]
	* @return [json] [json数组]
	*/
	public function get_records()
	{
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1 client端、2 server端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];

		//用手机号码查出用户账号
		$where=' where records_id='.$userid;
		$sql='SELECT * FROM ( select * from zy_online_talk_record '.$where.' order by send_time desc limit 10) aa ORDER BY send_time';
		$records_info = $this->online_talk_record_db->spcSql($sql,1,1);
		//$records_info = $this->online_talk_record_db->select(array('records_id'=>$userid),'*',15,'send_time desc');
		//==================	操作失败-验证 START
			//请先登录
			if (!$userid) {
				$this->_return_status(-103);
			}
			//参数不能为空
			if (!$userid || !$type) {
				$this->_return_status(-104);
			}
		//==================	操作失败-验证 END


		//==================	操作成功-返回数据 START
		foreach($records_info as $k=> $value){
			$records_info[$k]['send_time']=date('Y-m-d H:i:s',$value['send_time']);
		}

			$this->_return_status(200,$records_info);
		//==================	操作成功-返回数据 END

	}

	/**
	 * 创建会话
	 * @status [状态] -103请先登录
	 * @param  [type] $userid [*from用户id]
	 * @param  [type] $records_id [*记录id]
	 * @return [json] [json数组]
	 */
	public function look_msg($userid,$records_id)
	{
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1 client端、2 server端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];
		$records_id = $_POST['records_id'];

		if($records_id)
		//==================	操作失败-验证 START
		//请先登录
		if (!$userid) {
			$this->_return_status(-103);
		}else{
			$data['status']=1;
			$infos=$this->online_talk_record_db->update($data,['records_id'=>$records_id,'status'=>0,'from_user'=>$userid]);
		}
		//==================	操作失败-验证 END

		$this->_return_status(200,$infos);
		//==================	操作成功-返回数据 END
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
	 * 私有验证_用户帐号
	 * 4-20位之间,只允许数字、大小写英文
	 */
	private function _verify_isusername($username) 
	{
		if (preg_match('/^[0-9a-zA-Z]{4,20}$/i',$username)){
			return true;
		}else {
			return false;
		}
	}
	/*
	 * 私有验证_密码
	 * 6-16位之间,只允许数字、大小写英文、下划线
	 */
	private function _verify_ispassword($password) 
	{
		if (preg_match('/^[_0-9a-zA-Z]{6,16}$/i',$password)){
			return true;
		}else {
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
				case -1:	//参数不能为空
					$result = [
						'status'=>'error',
						'code'=>-1,
						'message'=>'列表已存在，不重复添加',
						'data'=>$data,
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
?>
