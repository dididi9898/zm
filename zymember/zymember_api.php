<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class zymember_api{
	function __construct() {
		$this->get_db = pc_base::load_model('get_model');	
		$this->members_db = pc_base::load_model('members_model');
		//会员附表
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		//会员组表
		$this->member_group_db = pc_base::load_model('member_group_model');
		$this->zycoupon_user_db = pc_base::load_model('zycoupon_user_model');
		$this->zycoupon_db = pc_base::load_model('zycoupon_model');
		//分销金额
		$this->zyfxmoney_db=pc_base::load_model("zyfxmoney_model");
		//聊天记录
		$this->online_talk_record_db = pc_base::load_model('online_talk_record_model');
		//订单
		$this->order_db = pc_base::load_model('zy_order_model');
		$this->_userid=param::get_cookie('_userid');
	}

	/**
	 * 可使用优惠券数量
	 * @param $_userid
	 * @return json
	 * @internal param 关联表id $_coupon_user_id
	 */
	public function coupon_count($_userid){

		//$_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
		$member_info = $this->members_db->get_one(array('userid'=>$_userid));
		if($member_info) {
			$where='isused=0 AND isselect=0 AND userid='.$_userid;
			$where.=' AND ((vaild_type=2 AND UNIX_TIMESTAMP(NOW())<(gettime+days*24*3600)) OR (vaild_type=1 AND begintime<UNIX_TIMESTAMP(NOW()) AND endtime>UNIX_TIMESTAMP(NOW()) AND `status`=1)) ';
			$sql="SELECT count(*) FROM zy_zycoupon c JOIN zy_zycoupon_user u ON c.id=u.coupon WHERE ".$where;
			$info = $this->zycoupon_db->spcSql($sql,1,0);
			//$info = $this->zycoupon_user_db->get_one(array('userid' => $_userid, 'isused' => 0), $data = 'count(*)');
			return $info['count(*)'];
		}else{
			return false;
		}
	}

	/**
	 * 资金模块_用于用户提现
	 * @param  [type] $userid [*用户id]
	 * @return [json]         [数据组]
	 */
	public function zyfunds_withdrawal($userid)
	{
		$userid = $_GET['userid'];
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>[
				'userid'=>$memberinfo['userid'],
				'username'=>$memberinfo['username'],
				'nickname'=>$memberinfo['nickname'],
				'phone'=>$memberinfo['mobile'],
				'cash'=>$memberinfo['amount'],
				'trade_pass'=>$memberinfo['trade_password'],
				'trade_encrypt'=>$memberinfo['trade_encrypt'],
			]
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}


	/**
	 * 公共模块_获取会员组信息
	 * @return [json]         [数据组]
	 */
	public function pub_membergroup()
	{

		$member_group = $this->member_group_db->select();
		foreach ($member_group as $key => $value) {
			$member_groups[$key]['groupid'] = $value['groupid'];
			$member_groups[$key]['name'] = $value['name'];
		}
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$member_groups
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}



	/**
	 * 公共模块_会员详细信息
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $field [需要查询的字段，已逗号隔开]
	 * @return [json]         [数据组]
	 */
	public function pub_memberinfo($userid=NULL,$field)
	{
	    $userid = empty($_POST['userid']) ? param::get_cookie('_userid') : $_POST['userid'];
		if($_POST['field']){
			$field = $_POST['field'] ? $_POST['field'] : '';
		}else{
			$field = $_GET['field'] ? $_GET['field'] : '';
		}
		//==================	操作失败-验证 START
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请登录',
                'forward'=> APP_PATH.'index.php?m=member&c=index&a=login',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		//==================	操作失败-验证 END

		//==================	操作成功-查询数据 START
		//如果有字段数组传值过来，那么就只显示传值过来的字段值(利用逗号进行打散操作)
		//如果没有字段数组传值过来，那么就显示当前用户的全部信息
		if($field){
			$field = explode(",", $field);		//打散成数组，到时候进行重新组装
			foreach ($field as $key => $value) {
				$data[$value] = $memberinfo[$value];
			}
		}else{
			$data = $memberinfo;
		}
		//加上域名
		if($data['headimgurl']=='statics/images/member/nophoto.gif'){
			$data['headimgurl'] = APP_PATH.'statics/images/member/nophoto.gif';
		}
		//优惠券数量
		$data['coupon_count'] = $this->coupon_count($userid);
		//未提现佣金
		$zyfxmoney = $this->zyfxmoney_db->get_one(['userid'=>$userid]);
		if($zyfxmoney) {
			$data['WTXmoney'] = $zyfxmoney['WTXmoney'];
		}else{
			$data['WTXmoney'] = 0.00;
		}
		//客服信息
		$data['unlook']=$this->online_talk_record_db->count(array('to_user'=>$userid,'records_id'=>$userid,'status'=>0));
		//订单红点
		$sql='SELECT COUNT(*) AS num,try_status,`status` FROM zy_zy_order WHERE `userid`='.$userid.' GROUP BY try_status,`status` ';
		$res=$this->order_db->spcSql($sql,1,1);
		if($res) {
			foreach ($res as $v) {
				if($v['try_status']==0) {
					$data['order_count'][$v['status']] = $v['num'];
				}elseif($v['try_status']==1){
					$data['try_order_count'][$v['status']] = $v['num'];
				}
			}
		}else{
			$data['order_count']=[];
			$data['try_order_count']=[];
		}
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$data
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-查询数据 END
	}



	/**
	 * 公共模块_增加余额
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $amount [*金额]
	 * @param  [type] $describe [*描述]
	 * @param  [type] $module [*所属模块]
	 * @return [json]         [数据组]
	 */
	public function pub_increaseamount($userid=NULL,$amount=0,$describe,$module)
	{
		$userid = $_GET['userid'];
		$describe = $_GET['describe'];
		$module = $_GET['module'];
		$amount = $_GET['amount'];
		//==================	操作失败-验证 START
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		if(!$describe || !$module){
			$result = [
				'status'=>'error',
				'code'=>-4,
				'message'=>'描述、所属模块内容不能为空',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		//==================	操作失败-验证 END

		//==================	操作成功-更新数据 START
		$this->members_db->update(['amount'=>'+='.$amount],['userid'=>$userid]);
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-更新数据 END
	}


	/**
	 * 公共模块_减少余额
	 * @param  [type] $userid [*用户id]
	 * @param  [int] $amount [*金额]
	 * @param  [type] $describe [*描述]
	 * @param  [type] $module [*所属模块]
	 * @return [json]         [数据组]
	 */
	public function pub_reduceamount($userid=NULL,$amount=0,$describe,$module)
	{
		$userid = $_GET['userid'];
		$describe = $_GET['describe'];
		$module = $_GET['module'];
		$amount = $_GET['amount'];
		//==================	操作失败-验证 START
		if(!$userid){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'请输入用户id',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
		if(!$memberinfo){
			$result = [
				'status'=>'error',
				'code'=>-2,
				'message'=>'用户不存在',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		if($memberinfo['amount']<$amount){
			$result = [
				'status'=>'error',
				'code'=>-3,
				'message'=>'余额不足',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		if(!$describe || !$module){
			$result = [
				'status'=>'error',
				'code'=>-4,
				'message'=>'描述、所属模块内容不能为空',
				
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		//==================	操作失败-验证 END

		//==================	操作成功-更新数据 START
		$this->members_db->update(['amount'=>'-='.$amount],['userid'=>$userid]);
		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		//==================	操作成功-更新数据 END
	}




	/**
	 * 商品模块_获取用户昵称
	 * @param  [type] $ids [*用户id，已逗号的形式传值]
	 * @return [json]         [数据组]
	 */
	public function zyshop_nickname($ids)
	{
		$useridstr = $_POST['ids'];
		if(!$useridstr){
			$result = [
				'status'=>'error',
				'code'=>-1,
				'message'=>'用户id不能为空',
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}

		$where='userid in ('.$useridstr.')';
		$sql='SELECT `userid`,`shopname`,`groupid`as`group`,`proprietary` FROM phpcms_member WHERE '.$where;
		$infos = $this->get_db->multi_listinfo($sql);

		foreach ($infos as $key => $value) {
			$infos[$key]['group'] = $value['group']==2 ? 0 : 1;
			$infos[$key]['proprietary'] = $value['proprietary']==1 ? 1 : 0;
		}


		$result = [
			'status'=>'success',
			'code'=>200,
			'message'=>'操作成功',
			'data'=>$infos,
		];
		exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}


	/**
	 * 通讯模块_获取会员组的全部会员
	 * @param  [type] $groupid [*会员组id，默认是0=全部会员]
	 * @return [json]         [数据组]
	 */
	public function zymessagesys_group($groupid)
	{
		$groupid = $_POST['groupid'] ? $_POST['groupid'] : 0;
		if($groupid==0){
			$memberinfo = $this->members_db->select('','`userid`,`username`,`nickname`,`mobile`,`groupid`');
		}else{
			$memberinfo = $this->members_db->select(['groupid'=>$groupid],'`userid`,`username`,`nickname`,`mobile`,`groupid`');
		}


		//==================	操作成功-更新数据 START

			$result = [
				'status'=>'success',
				'code'=>200,
				'message'=>'操作成功',
				'data'=>$memberinfo,
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

		//==================	操作成功-更新数据 END

	}
    public function zyorder_check_pay_password()
    {
        $memberinfo = $this->members_db->get_one(['userid'=>$this->_userid]);
        if(!$memberinfo['trade_password']) {
            $result = [
                'status'=>'error',
                'code'=>-3,
                'message'=>'未设置交易密码',
            ];

        }
        else
        {
            $result = [
                'status'=>'success',
                'code'=>3,
                'message'=>'以设置',
            ];
        }
        exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

	/**
	 * 订单模块_验证支付密码是否正确
	 * @param  [type] $userid [*用户id]
	 * @param  [type] $pay_password [*支付密码]
	 * @return [json]         [数据组]
	 */
	public function zyorder_offpaypas()
	{
		$userid = $_POST['userid'];	//用户id
		$pay_password = $_POST['pay_password'];	//支付密码



		//==================	操作失败-验证 START
			if(!$userid || !$pay_password){
				$result = [
					'status'=>'error',
					'code'=>-1,
					'message'=>'用户id、交易密码不能为空',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

			$memberinfo = $this->members_db->get_one(['userid'=>$userid]);
			if(!$memberinfo){
				$result = [
					'status'=>'error',
					'code'=>-2,
					'message'=>'用户不存在',
					
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

			//请先设置支付密码
			if(!$memberinfo['trade_password']) {
				$result = [
					'status'=>'error',
					'code'=>-3,
					'message'=>'请先设置交易密码',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

			//密码错误
			if($memberinfo['trade_password'] != password($pay_password, $memberinfo['trade_encrypt'])) {
				$result = [
					'status'=>'error',
					'code'=>-4,
					'message'=>'密码错误',
				];
				exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}

		//==================	操作成功-返回数据 START

			$result = [
				'status'=>'success',
				'code'=>200,
				'message'=>'操作成功'
			];
			exit(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

		//==================	操作成功-返回数据 END

	}





}