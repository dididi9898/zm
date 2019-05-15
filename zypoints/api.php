<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);

class api
{
    public function __construct()
    {
        $this->get_db = pc_base::load_model('get_model');
        //配置表
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
        //积分礼物表
        $this->zygift_db = pc_base::load_model('zygift_model');
        //礼物用户关联表
        $this->zygift_user_db = pc_base::load_model('zygift_user_model');
        //用过表
        $this->member_db = pc_base::load_model('member_model');
        //订单商品表
        $this->ordergoods_db = pc_base::load_model('zy_order_goods_model');
        //订单表
        $this->order_db = pc_base::load_model('zy_order_model');
        //商品表
        $this->goods_db = pc_base::load_model('goods_model');

        //分销用户表
        $this->zyfxmember_db = pc_base::load_model("zyfxmember_model");
        //分销配置
        $this->zyfxconfig_db = pc_base::load_model("zyfxconfig_model");
        //分销名称
        $this->zyfxgradetitle_db = pc_base::load_model("zyfxgradetitle_model");
        //分销佣金
        $this->zyfxmoney_db = pc_base::load_model("zyfxmoney_model");

        $this->_userid = param::get_cookie('_userid');
    }

    /**
     * 更新用户佣金
     * @param userid：用户ID
     * @param goodsPrice：商品总价格
     * @param ratio：分销比率 array
     * @param index：分销等级
     * @param oid：订单号
     * @return json
     */
    public function update_fx($userid,$goodsPrice,$ratio,$index=3){
//        $userid=$_POST['userid'];
//        $goodsPrice=$_POST['goodsPrice'];
          $ratio=json_decode(str_replace('\\', '', $ratio),true);
//        $index=$_POST['index'];
        $info=false;
        $memberInfo= $this->zyfxmember_db->get_one(array('userid'=>$userid));
        for($i=1; $i<= $index; $i++) {
            if ($memberInfo["pid"] == 0)//如果没有pid的话break;
                break;
            $memberInfo = $this->zyfxmember_db->get_one(array('userid' => $memberInfo["pid"]));
            if ($memberInfo != null) //计算各个pid应该发放多少奖励
            {
                $data['WTXmoney']= "+=".$ratio[$i]/100*$goodsPrice;
                $data['moneycount']= "+=".$ratio[$i]/100*$goodsPrice;
                $info = $this->zyfxmoney_db->update($data, array("userid" => $memberInfo['userid']));//更新各级佣金
            }
        }
        return $info;
        //exit(json_encode($info,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
    /**
     * 更新佣金明细
     * @param userid：用户ID
     * @param goodsPrice：商品总价格
     * @param ratio：分销比率 array
     * @param index：分销等级
     * @param oid：订单号
     * @return json
     */
    public function update_fxgoods($userid,$goodsPrice,$ratio,$ordergoodsid,$index=3){
//        $userid=$_POST['userid'];
//        $goodsPrice=$_POST['goodsPrice'];
//        $index=$_POST['index'];
//        $ordergoodsid=$_POST['ordergoodsid'];
        $ratio=json_decode(str_replace('\\', '', $ratio),true);

        $info=false;
        $memberInfo= $this->zyfxmember_db->get_one(array('userid'=>$userid));
        for($i=1; $i<= $index; $i++) {
            if ($memberInfo["pid"] == 0)//如果没有pid的话break;
                break;
            $memberInfo = $this->zyfxmember_db->get_one(array('userid' => $memberInfo["pid"]));
            if ($memberInfo != null) //计算各个pid应该发放多少奖励
            {
                $goods_fxmoney[$i]=$ratio[$i]/100*$goodsPrice;//商品分销的金额
                $ordergoods_data['goods_fxmoney']=json_encode($goods_fxmoney);
                $info = $this->ordergoods_db->update($ordergoods_data, array("id" => $ordergoodsid));//更新各级佣金
            }
        }
        return $info;
        //exit(json_encode($info,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
    /**
     * 更新订单佣金
     * @param userid：用户ID
     * @param goodsPrice：商品总价格
     * @param ratio：分销比率 array
     * @param index：分销等级
     * @param oid：订单号
     * @return json
     */
    public function update_fxorder($userid,$goodsPrice,$ratio,$oid,$index=3){
//        $userid=$_POST['userid'];
//        $goodsPrice=$_POST['goodsPrice'];
//        $ratio=json_decode(str_replace('\\', '', $_POST['ratio']),true);
//        $index=$_POST['index'];
//        $oid=$_POST['oid'];
        $ratio=json_decode(str_replace('\\', '', $ratio),true);


        $memberInfo= $this->zyfxmember_db->get_one(array('userid'=>$userid));
        $orderInfo= $this->order_db->get_one(array('order_id'=>$oid));
        $fx_money=json_decode($orderInfo['fx_money'],true);
        for($i=1; $i<= $index; $i++) {
            if ($memberInfo["pid"] == 0)//如果没有pid的话break;
                break;
            $memberInfo = $this->zyfxmember_db->get_one(array('userid' => $memberInfo["pid"]));
            if ($memberInfo != null) //计算各个pid应该发放多少奖励
            {
                    $fx_money[$i] += $ratio[$i] / 100 * $goodsPrice;//商品分销的金额
            }
        }
        $order_data['fx_money']=json_encode($fx_money);
        $info = $this->order_db->update($order_data, array("order_id" => $oid));//更新各级佣金

        return $info;
        //exit(json_encode($info,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }



    /**
     * 更新用户积分和佣金 包括总积分 新增积分
     * @param userid 用户ID
     * @param oid 订单ID
     * @return json
     */
    public function api_update_points()
    {
        if($_POST['userid']){
            $_userid=$_POST['userid'];
        }else{
            $_userid=_userid;
        }
        $member_info = $this->member_db->get_one(array('userid'=>$_userid));
        $oid = empty($_POST['oid']) ? 0 : $_POST['oid'];
        $points=array();
        if($member_info){
            $where ="order_id=".$oid;
            $goodsinfo = $this->ordergoods_db->select($where,("`id`,`goods_id`,`final_price`"));

            foreach($goodsinfo as $key=> $value){
                $goodinfo = $this->goods_db->get_one(array('id'=>$value['goods_id']),("`istry`,`point_mode`,`point_value`,point_sy_value,awardNumber,trialAwardNumber"));
                $goodsinfo[$key]['istry']=$goodinfo['istry'];
                $goodsinfo[$key]['point_mode']=$goodinfo['point_mode'];
                $goodsinfo[$key]['point_value']=$goodinfo['point_value'];
                $goodsinfo[$key]['point_sy_value']=$goodinfo['point_sy_value'];

                $goodsinfo[$key]['awardNumber']=$goodinfo['awardNumber'];
                $goodsinfo[$key]['trialAwardNumber']=$goodinfo['trialAwardNumber'];

                $awardNumber[$key]=json_decode($goodsinfo[$key]['awardNumber']);
                $trialAwardNumber[$key]=json_decode($goodsinfo[$key]['trialAwardNumber']);


                if($goodsinfo[$key]['istry']==0){//购买
                    if($goodsinfo[$key]['point_mode']==1){//百分比
                        $points[$key]=$goodsinfo[$key]['point_value']/100*$value['final_price'];
                    }else{//国定
                        $points[$key]=$goodsinfo[$key]['point_value'];
                    }
                    $bool= $this->update_fx($_userid,$value['final_price'],$awardNumber[$key]);
                    $bool= $this->update_fxgoods($_userid,$value['final_price'],$awardNumber[$key],$goodsinfo['id']);
                    $bool= $this->update_fxorder($_userid,$value['final_price'],$awardNumber[$key],$oid);

                }else{//试穿
                    if($goodsinfo[$key]['point_mode']==1){//百分比
                        $points[$key]=$goodsinfo[$key]['point_value']/100*$value['final_price'];
                    }else{//国定
                        $points[$key]=$goodsinfo[$key]['point_value'];
                    }
                    $bool=$this->update_fx($_userid,$value['final_price'],$trialAwardNumber[$key]);
                    $bool=$this->update_fxgoods($_userid,$value['final_price'],$trialAwardNumber[$key],$goodsinfo['id']);
                    $bool=$this->update_fxorder($_userid,$value['final_price'],$trialAwardNumber[$key],$oid);
                }

            }
            $total_points=0;
            foreach($points as $key=> $value){
                $total_points+=$value;
            }
            $data['point']=$member_info['point']+$total_points;
            $data['new_point']=$total_points;
            $bool= $this->member_db->update($data,array('userid'=>$_userid));
            if($bool){
                //returnjsoninfo('200','操作成功',$info);
                $json['status']='success';
                $json['code']='200';
                $json['message']='操作成功';
                $json['data']=$data;
            }else{
                //returnjsoninfo('-200','数据为空');
                $json['status']='error';
                $json['code']='-200';
                $json['message']='数据为空';
            }
        }else{
            returnjsoninfo('0','登陆超时');
        }

        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    /**
     * 获取兑换礼物列表
     * @param $_userid
     * @return json
     */
    public function api_gift_info($_userid)
    {
        if($_POST['userid']){
            $_userid=$_POST['userid'];
        }else{
            $_userid=$this->_userid;
        }
        if($_userid){
            $where ="status=1";
            $info = $this->zygift_db->select($where);
            if($info){
                //returnjsoninfo('200','操作成功',$info);
                $json['status']='success';
                $json['code']='200';
                $json['message']='操作成功';
                $json['data']=$info;
            }else{
                //returnjsoninfo('-200','数据为空');
                $json['status']='error';
                $json['code']='-200';
                $json['message']='数据为空';
            }
        }else{
            returnjsoninfo('0','登陆超时');
        }
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
	/**
     * 领取礼物
     * @param $_userid
     * @param $_giftid
     * 后期需要修改
     * @return json
     */
    public function api_gift_take($_userid)
    {
        if($_POST['userid']){
            $_userid=$_POST['userid'];
        }else{
            $_userid=$this->_userid;
        }
        if($_userid&&$_userid!=''){
            $_giftid = empty($_POST['_giftid']) ? 0 : $_POST['_giftid'];
            if(!$_giftid){
                returnjsoninfo('-1','参数不完整');
            }
            $member_info = $this->member_db->get_one(array('userid'=>$_userid));
            if($member_info){
                $gift_info = $this->zygift_db->get_one("id=$_giftid AND status=1 AND giftnum>0",'`id`,`giftnum`,`giftpoint`');
                if($gift_info){
                    if($gift_info['giftpoint']<=$member_info['point']){
                        $data['userid']=$_userid;
                        $data['giftid']=$_giftid;
                        $data['gettime']=time();
                        $info1 = $this->zygift_user_db->insert($data);
                        $gift_info['giftnum']-=1;
                        $info2 = $this->zygift_db->update($gift_info,'id='.$_giftid);
                        $member_info['point']-=$gift_info['giftpoint'];
                        $member_info['used_point']+=$gift_info['giftpoint'];
                        $info = $this->member_db->update($member_info,'userid='.$_userid);
                        if($info&&$info1&&$info2){
                            //returnjsoninfo('200','操作成功',$info);
                            $json['status']='success';
                            $json['code']='200';
                            $json['message']='领取成功';
                            $json['data']=$info;
                        }else{
                            //returnjsoninfo('-200','数据为空');
                            $json['status']='error';
                            $json['code']='-200';
                            $json['message']='领取失败';
                        }
                    }else{
                        returnjsoninfo('-3','积分不足');
                    }
                }else{
                    returnjsoninfo('-2','礼物不可用');
                }
            }else{
                returnjsoninfo('0','用户不存在');
            }
        }else{
            returnjsoninfo('0','登陆超时');
        }


        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }


    /**
     * 获取用户所有兑换的礼物
     * @param $_userid
     * @return json
     */
    public function api_gift_user($_userid)
    {
        if($_POST['userid']){
            $_userid=$_POST['userid'];
        }else{
            $_userid=$this->_userid;
        }

        if($_userid&&$_userid!=''&&$_userid!=null){
            $member_info = $this->member_db->get_one(array('userid'=>$_userid));

            if($member_info){
                $sql="SELECT u.id,g.giftname,g.thumb,g.giftdes FROM zy_zygift_user u LEFT JOIN zy_zygift g ON g.id=u.giftid WHERE u.userid=".$_userid;
                $info = $this->zygift_db->spcSql($sql,1,1);
                if($info){
                    //returnjsoninfo('200','操作成功',$info);
                    $json['status']='success';
                    $json['code']='200';
                    $json['message']='操作成功';
                    $json['data']=$info;
                }else{
                    //returnjsoninfo('-200','数据为空');
                    $json['status']='error';
                    $json['code']='-200';
                    $json['message']='数据为空';
                }
            }else{
                returnjsoninfo('0','用户不存在');
            }
        }else{
            returnjsoninfo('0','登陆超时');
        }

        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

}