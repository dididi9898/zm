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
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
        $this->zycoupon_db = pc_base::load_model('zycoupon_model');
        $this->zycoupon_user_db = pc_base::load_model('zycoupon_user_model');
        $this->zyshoptype_db = pc_base::load_model('goodscat_model');
        $this->member_db = pc_base::load_model('member_model');
    }

    /**
     * 获取当前用户所有可领取有效优惠券
     * @param $_userid
     * @return json
     */
    public function api_coupon_info($_userid)
    {
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $member_info = $this->member_db->get_one(array('userid'=>$_userid));

        if($member_info){
            //$sql="SELECT * FROM zy_zycoupon WHERE zy_zycoupon.id NOT IN (SELECT coupon FROM zy_zycoupon_user WHERE userid='".$_userid."')";
            $where ="id NOT IN (SELECT coupon FROM zy_zycoupon_user WHERE userid='".$_userid."')";
            $where .=" AND `status`=1 AND totalnum-takenum>0";
            //$info = $this->zycoupon_db->query($sql);
            $info = $this->zycoupon_db->select($where);
            $info= $this->visual($info);

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
            returnjsoninfo('-1','用户不存在,登录超时');
        }


        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
	/**
     * 领取优惠券
     * @param $_userid
     * @param $_couponid
     * @return json
     * bug 同一优惠券可以领多次
     */
    public function api_coupon_take($_userid)
    {
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $_couponid = empty($_GET['_couponid']) ? $_POST['_couponid'] : $_GET['_couponid'];

        $member_info = $this->member_db->get_one(array('userid'=>$_userid));
        if($member_info){
            $coupon_info = $this->zycoupon_db->get_one("id=$_couponid AND status=1 AND totalnum-takenum>0",'`id`,`takenum`');
            if($coupon_info){
                $data['userid']=$_userid;
                $data['coupon']=$_couponid;
                $data['gettime']=time();
                $info1 = $this->zycoupon_user_db->insert($data);
                $coupon_info['takenum']+=1;
                $info = $this->zycoupon_db->update($coupon_info,'id='.$_couponid);
                if($info&&$info1){
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
                returnjsoninfo('-2','优惠券已领完');
            }
        }else{
            returnjsoninfo('-1','用户为空');
        }


        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    /**
     * 使用优惠券
     * @param $_userid
     * @return json
     * @internal param 关联表id $_coupon_user_id
     */
    public function api_coupon_use($_userid)
    {
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $_coupon_user_id = empty($_GET['_coupon_user_id']) ? $_POST['_coupon_user_id'] : $_GET['_coupon_user_id'];

        $member_info = $this->member_db->get_one(array('userid'=>$_userid));
        if($member_info){
            $coupon_user_info = $this->zycoupon_user_db->get_one("id=".$_coupon_user_id." AND isused=0 and userid=".$_userid);
            if($coupon_user_info){
                $coupon_info = $this->zycoupon_db->get_one("id=".$coupon_user_info['coupon']." AND status=1 AND takenum-usednum>0");
                if($coupon_info['vaild_type']==1){
                    if($coupon_info['begintime']<=time()&&$coupon_info['endtime']>=time()){
                        //业务流程
                        $data['isused']=1;
                        $info1 = $this->zycoupon_user_db->update($data,'id='.$_coupon_user_id);

                        $coupon_info['usednum']+=1;
                        $info = $this->zycoupon_db->update($coupon_info,'id='.$coupon_user_info['coupon']);
                        //returnjsoninfo('200','ok',$info);
                    }else{
                        returnjsoninfo('-3','优惠券已过期');
                    }
                }else if($coupon_info['vaild_type']==2){
                    if(($coupon_info['days']*3600*24)+$coupon_user_info['gettime']>= time()){
                        //业务流程
                        //业务流程
                        $data['isused']=1;
                        $info1 = $this->zycoupon_user_db->update($data,'id='.$_coupon_user_id);

                        $coupon_info['usednum']+=1;
                        $info = $this->zycoupon_db->update($coupon_info,'id='.$coupon_user_info['coupon']);
                        //returnjsoninfo('200','ojbk',$info);
                    }else{
//                        echo (time()-$coupon_user_info['gettime']);
//                        echo "<br>";
//                        echo ($coupon_info['days']*3600*24);
                        returnjsoninfo('-5','优惠券已过期');
                    }
                }else{
                    returnjsoninfo('-4','优惠券无效');
                }
            }else{
                returnjsoninfo('-2','优惠券不存在');
            }
        }else{
            returnjsoninfo('-1','用户为空');
        }

        if($info&&$info1){
            //returnjsoninfo('200','操作成功',$info);
            $json['status']='success';
            $json['code']='200';
            $json['message']='使用成功';
            $json['data']=$info;
        }else{
            //returnjsoninfo('-200','数据为空');
            $json['status']='error';
            $json['code']='-200';
            $json['message']='使用失败';
        }
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }


//    public function use_coupon($_userid,$_usednum){
//        $data['isused']=1;
//        $info1 = $this->zycoupon_user_db->update($data,'id='.$_coupon_user_id);
//
//        $coupon_info['usednum']=$_usednum+1;
//        $info = $this->zycoupon_db->update($coupon_info,'id='.$coupon_user_info['coupon']);
//        if($info&&$info1){
//            //returnjsoninfo('200','操作成功',$info);
//            $json['status']='success';
//            $json['code']='200';
//            $json['message']='领取成功';
//            $json['data']=$info;
//        }else{
//            //returnjsoninfo('-200','数据为空');
//            $json['status']='error';
//            $json['code']='-200';
//            $json['message']='领取失败';
//        }
//        return $json;
//    }
    /**
     * 可使用优惠券数量
     * @param $_userid
     * @return json
     * @internal param 关联表id $_coupon_user_id
     */
    public function coupon_count(){
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $member_info = $this->member_db->get_one(array('userid'=>$_userid));
        if($member_info) {
            $info = $this->zycoupon_user_db->get_one(array('userid' => $_userid, 'isused' => 0), $data = 'count(*)');
            $json['status'] = 'success';
            $json['code'] = '200';
            $json['message'] = '领取成功';
            $json['data'] = $info;
        }else{
            $json['status'] = 'error';
            $json['code'] = '-1';
            $json['message'] = '用户不存在';
        }
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    /**
     * 获取用户所有可用的优惠券
     * @param $_userid
     * @return json
     */
    public function api_coupon_user($_userid)
    {
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $member_info = $this->member_db->get_one(array('userid'=>$_userid));

        if($member_info){
            $sql="SELECT * FROM zy_zycoupon c JOIN zy_zycoupon_user u ON c.id=u.coupon WHERE u.userid=".$_userid." AND c.`status`=1 AND u.isused=0";
            $where ="id NOT IN (SELECT coupon FROM zy_zycoupon_user WHERE userid='".$_userid."')";
            $where .=" AND `status`=1 AND totalnum-takenum>0";
            $info = $this->zycoupon_db->spcSql($sql,1,1);
            $info= $this->visual($info);

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
            returnjsoninfo('-1','用户为空');
        }


        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    /**
     * 转换limittype 时间戳
     * @param $info 优惠券数组
     * @return json
     */
    public function visual($info){
        $type = $this->zyshoptype_db->select(['isshow'=>'1']);
        foreach($info as $k=>$v){
            $info[$k]['begintime']= date("Y-m-d",$v['begintime']);
            $info[$k]['endtime']= date("Y-m-d",$v['endtime']);

            if($v["limittype"]==0){
                $info[$k]['limittypename']= '全场通用';
            }else {
                foreach ($type as $item) {
                    if ($item["id"] == $v["limittype"]) {
                        $info[$k]['limittypename'] = '仅限' . $item["cate_name"] . '类别';
                    }
                }
            }
        }
        return $info;
    }

    /**
     * 订单页可用优惠券数量
     * @param _userid
     * @return json
     * @internal param 关联表id $_coupon_user_id
     */
    public function order_coupon_count(){
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $_catid = empty($_GET['_catid']) ? $_POST['_catid'] : $_GET['_catid'];
        $_total = empty($_GET['_total']) ? $_POST['_total'] : $_GET['_total'];


        $member_info = $this->member_db->get_one(array('userid'=>$_userid));
        if($member_info) {

            $where='`status`=1 AND userid='.$_userid;
            if($_catid){
                $where.=' AND (limittype=0 OR limittype='.$_catid. ')';
            }
            if($_total){
                $where.=' AND (type=0 OR (type!=0 AND full<'.$_total. '))';
            }
            $sql='SELECT count(*) AS num FROM zy_zycoupon_user u LEFT JOIN zy_zycoupon c ON u.coupon =c.id WHERE '.$where;
            $info = $this->zycoupon_user_db->spcSql($sql,1,1);
            if($info) {
                $json['status'] = 'success';
                $json['code'] = '200';
                $json['message'] = '有可用优惠券';
                $json['data'] = $info;
            }else{
                $json['status'] = 'error';
                $json['code'] = '-200';
                $json['message'] = '无可用优惠券';
            }
        }else{
            $json['status'] = 'error';
            $json['code'] = '-1';
            $json['message'] = '用户不存在';
        }
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    /**
     * 订单页可用优惠券详情
     * @param _userid
     * @return json
     * @internal param 关联表id $_coupon_user_id
     */
    public function order_coupon_info(){
        $_userid = empty($_GET['_userid']) ? $_POST['_userid'] : $_GET['_userid'];
        $_catid = empty($_GET['_catid']) ? $_POST['_catid'] : $_GET['_catid'];
        $_total = empty($_GET['_total']) ? $_POST['_total'] : $_GET['_total'];


        $member_info = $this->member_db->get_one(array('userid'=>$_userid));
        if($member_info) {

            $where='`status`=1 AND userid='.$_userid;
            if($_catid){
                $where.=' AND (limittype=0 OR limittype='.$_catid. ')';
            }
            if($_total){
                $where.=' AND (type=0 OR (type!=0 AND full<'.$_total. '))';
            }
            $sql='SELECT * FROM zy_zycoupon_user u LEFT JOIN zy_zycoupon c ON u.coupon =c.id WHERE '.$where;
            $info = $this->zycoupon_user_db->spcSql($sql,1,1);
            if($info) {
                $json['status'] = 'success';
                $json['code'] = '200';
                $json['message'] = '有可用优惠券';
                $json['data'] = $info;
            }else{
                $json['status'] = 'error';
                $json['code'] = '-200';
                $json['message'] = '无可用优惠券';
            }
        }else{
            $json['status'] = 'error';
            $json['code'] = '-1';
            $json['message'] = '用户不存在';
        }
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
}