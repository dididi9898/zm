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
        $this->zygift_db = pc_base::load_model('zygift_model');
        $this->zygift_user_db = pc_base::load_model('zygift_user_model');
        $this->member_db = pc_base::load_model('member_model');
    }

    /**
     * 获取兑换礼物列表
     * @param $_userid
     * @return json
     */
    public function api_gift_info($_userid)
    {
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


        echo "<pre>";
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        echo  "</pre>";
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
        $_userid = empty($_GET['_userid']) ? 0 : $_GET['_userid'];
        $_giftid = empty($_GET['_giftid']) ? 0 : $_GET['_giftid'];

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
            returnjsoninfo('-1','用户为空');
        }


        echo "<pre>";
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        echo  "</pre>";
    }


    /**
     * 获取用户所有兑换的礼物
     * @param $_userid
     * @return json
     */
    public function api_gift_user($_userid)
    {
        $_userid = empty($_GET['_userid']) ? 0 : $_GET['_userid'];
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
            returnjsoninfo('-1','用户为空');
        }

        echo "<pre>";
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        echo  "</pre>";
    }

}