<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');

class api{
	function __construct() {

		$this->get_db = pc_base::load_model('get_model');
		//消息
		$this->zymessagesys_record_db = pc_base::load_model('zymessagesys_record_model');
		//配置模块表
		$this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->module_db = pc_base::load_model('module_model');
	}



	/**
	* 前台_消息列表页
	* @status [状态] -103请先登录
	* @param  [type] $userid [*用户id]
	* @param  [type] $type [*类型：1web端、2APP端]
	* @param  [type] $page [当前页码，默认第一页]
	* @param  [type] $pagesize [当前的条数，默认20条]
	* @return [json] [json数组]
	*/
	public function message_list($userid)
	{
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];

		$page = $_POST['page'] ? $_POST['page'] : 1;	//当前页码
		$pagesize = $_POST['pagesize'] ? $_POST['pagesize'] : 20;	//当前的条数，默认20条
		
		//差数据
		$where = ['userid'=>$userid];
		$order = 'id DESC';
		$page = $page;
		$pagesize = $pagesize;
		$info=$this->zymessagesys_record_db->listinfo($where,$order,$page,$pagesize); //读取数据库里的字段
		$totalnum = $this->zymessagesys_record_db->count($where);
		$totalpage = ceil($totalnum/$pagesize);

		//==================	操作失败-验证 START
			//请先登录
			if (!$userid) {
				$this->_return_status(-103);
			}
		//==================	操作失败-验证 END

		//==================	操作成功-返回数据 START
			foreach ($info as $key => $value) {
				$infos[$key]['id']=$value['id'];
				$infos[$key]['title']=$value['title'];
				$infos[$key]['content']=$value['content'];
				$infos[$key]['thumb']=APP_PATH.$value['thumb'];
				$infos[$key]['sendname']=$value['sendname'];
				$infos[$key]['time']=date('Y/m/d',$value['addtime']);
			}

			$pages['pagesize'] = $pagesize;	//每页10条
			$pages['totalpage'] = $totalpage;	//总页数
			$pages['totalnum'] = $totalnum;	//总数据
			
			$this->_return_status(200,$infos,$pages);
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
	public function message_show($userid,$showid)
	{
		$type = $_POST['type'] ? $_POST['type'] : 1;	//类型：1web端、2APP端
		$userid = $type==1 ? param::get_cookie('_userid') : $_POST['userid'];

		$showid = $_POST['showid'];

		//用手机号码查出用户账号
		$messageinfo = $this->zymessagesys_record_db->get_one(array('userid'=>$userid,'id'=>$showid));
		//==================	操作失败-验证 START
			//请先登录
			if (!$userid) {
				$this->_return_status(-103);
			}
			//参数不能为空
			if (!$userid || !$showid) {
				$this->_return_status(-104);
			}
		//==================	操作失败-验证 END

		//==================	操作成功-返回数据 START
			$data = [
				'title'=>$messageinfo['title'],
				'content'=>$messageinfo['content'],
				'url'=>$messageinfo['url'],
				'sendname'=>$messageinfo['sendname'],
				'time'=>date('Y/m/d',$messageinfo['addtime'])
			];
			$this->_return_status(200,$data);
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
