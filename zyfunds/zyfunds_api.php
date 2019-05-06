<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
require_once dirname(__FILE__).'/index.php';

class zyfunds_api
{
    public function __construct()
    {
        $this->get_db = pc_base::load_model('get_model');
        $this->zyfound_bank_db = pc_base::load_model('zyfound_bank_model');
        $this->zyfound_bankcard_db = pc_base::load_model('zyfound_bankcard_model');
        $this->zyfound_pay_db = pc_base::load_model('zyfound_pay_model');
        $this->zyfound_tx_db = pc_base::load_model('zyfound_tx_model');
        $this->zyfound_account_db = pc_base::load_model('zyfound_account_model');
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
        $this->zymember_db = pc_base::load_model('zymember_model');
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
    public function member_api($url,$userid)
    {
        $params = array('userid'=>$userid);
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        return $content;
    }


    /*
     * 获取资金账户余额
     * @param int $id 会员ID
     * @param string $key 会员接口关键字
     * */
    public function cash($id,$key="zyfunds1")
    {
        $id = empty($id) ? $_GET['id'] : $id;
        $key = empty($_GET['key']) ? $key : $_GET['key'];
        $config = $this->zyconfig_db->get_one(array('key'=>$key),'url');  // 获取接口地址
        $api = empty($config['url'])? '' :$this->member_api($config['url'],$id);    // 会员结果
        if(empty($api)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '接口配置尚未配置'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        exit($api); // 返回会员json信息
    }

    /*
     * 获取会员账户默认账户,没有选择首次注册的账号
     * @param is_first 1 默认 -1 非默认 （默认账户只有一条）
     * @param userid 用户id
     * @param id ID
     * @param int 显示条数
     * */
    public function account($is_first = 1 ,$userid, $id, $limit = 1)
    {
        $userid = empty($userid) ? $_GET['userid'] : $userid;
        $id = empty($id) ? $_GET['id'] : $id;
        $limit = empty($limit) ? $_GET['limit'] : $limit;
        $is_first = empty($is_first) ? $_GET['is_first'] : $is_first;

        if(empty($userid)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入用户id'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $where['userid'] = $userid;

        if(!empty($id)){
           $where['id'] = $id;
        }else{
            $where['is_first'] = $is_first;
        }

        $count = $this->zyfound_bankcard_db->count($where);  // 是否没有默认账户

        /* 有默认的条件 */
        if ($count > 0) {
            $account = $this->zyfound_bankcard_db->select($where, '*', $limit);
            if (is_array($account)) {
                foreach ($account as $k => $v) {
                    if ($v['tid'] == 3) {
                        $len = strlen($v['account']);
                        $index = $len - 4;

                        if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $v['account'])>0){
                            $account[$k]['hide_account'] = $this->substr_cut($v['account']);
                        }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $v['account'])>0){
                            $account[$k]['hide_account'] = $this->substr_cut($v['account']);
                        }else{
                            $account[$k]['hide_account'] = str_repeat("****  ", 3) . substr($v['account'], $index, 4);
                        }
                        
                        $account[$k]['tname'] = explode(',', $v['tname'])[1];
                        $bankid = explode(',', $v['tname'])[0];
                        $account[$k]['thumb'] = $this->zyfound_bank_db->get_one(array('id' => $bankid), 'thumb')['thumb'];
                    } else {
                        $len = strlen($v['account']);
                        $show = floor($len / 3);
                        $hide = $len - $show * 2;
                        $index = $show + $hide;
                        

                        if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $v['account'])>0){
                            $account[$k]['hide_account'] = $this->substr_cut($v['account']);
                        }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $v['account'])>0){
                            $account[$k]['hide_account'] = $this->substr_cut($v['account']);
                        }else{
                            $account[$k]['hide_account'] = substr($v['account'], 0, $show) . str_repeat("*", $hide) . substr($v['account'], $index, $show);
                        }
                    }
                }
            }
        } else {
            /* 无默认的条件 */
            $account = $this->zyfound_bankcard_db->select('', "*", $limit);
            if (is_array($account)) {
                foreach ($account as $k => $v) {
                    if ($v['tid'] == 3) {
                        $len = strlen($v['account']);
                        $index = $len - 4;
                        $account[]['hide_account'] = str_repeat("****  ", 3) . substr($v['account'], $index, 4);
                        $account[]['tname'] = explode(',', $v['tname'])[1];
                        $bankid = explode(',', $v['tname'])[0];
                        $account[]['thumb'] = $this->zyfound_bank_db->get_one(array('id' => $bankid), 'thumb')['thumb'];
                    } else {
                        $len = strlen($v['account']);
                        $show = floor($len / 3);
                        $hide = $len - $show * 2;
                        $index = $show + $hide;
                        $account[$k]['hide_account'] = substr($v['account'], 0, $show) . str_repeat("*", $hide) . substr($v['account'], $index, $show);
                    }
                }
            } else {
                $result = ['status'=>'error','code' => 10002, 'message' => '尚未设置账户'];
                exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

        }
        $result = ['status'=>'success','code' => 200, 'message' => '操作成功', 'data' => $account];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     * 选择支付宝、微信或银行卡
     * @param id     int 用户ID
     * @param type   int 账户类型 1 支付宝 2 微信 3 银行卡
     */
    public function choosebank($id, $type)
    {
        $id = empty($id) ? $_GET['id'] : $id;
        $type = empty($type) ? $_GET['type'] : $type;
        if (empty($id)) {
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入用户ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $count = $this->zyfound_bankcard_db->count(array('userid' => $id));

        if ($count <= 0) {
            $msg = ['status'=>'error','code' => 10002, 'message' => '用户不存在'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if ($type < 1 || $type > 3) {
            $msg = ['status'=>'error','code' => 10003, 'message' => '账户类型错误或者不存在'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $where = array('userid' => $id, 'tid' => $type);
        $infos = $this->zyfound_bankcard_db->select($where);
        
        if (is_array($infos)) {
            foreach ($infos as $k => $v) {
                if ($v['tid'] != 3) {
                    $len = strlen($v['account']);
                    $show = floor($len / 3);
                    $hide = $len - $show * 2;
                    $index = $show + $hide;
                    
                    if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }else{
                        $infos[$k]['hide_account'] = substr($v['account'], 0, $show) . str_repeat("*", $hide) . substr($v['account'], $index, $show); 
                    }

                } else {
                    $len = strlen($v['account']);
                    $index = $len - 4;
                    if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }else{
                        $infos[$k]['hide_account'] = str_repeat("****  ", 3) . substr($v['account'], $index, 4);
                    }
                    
                    $infos[$k]['tname'] = explode(',', $v['tname'])[1];
                    $bankid = explode(',', $v['tname'])[0];
                    $infos[$k]['thumb'] = $this->zyfound_bank_db->get_one(array('id' => $bankid), 'thumb')['thumb'];
                }
            }
        } else {
            $infos = array();
        }

        $result = ['status'=>'success','code' =>200, 'message' => '操作成功', 'data' => $infos];
        exit(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /*
     * 支付密码验证
     * @param $userid 用户ID
     * @param $pass   交易密码
     * */
    public function trade_verify($userid,$pass)
    {
        $userid = empty($userid)? $_GET['userid']:$userid;
        $pass = empty($pass)? $_GET['pass']:$pass;
        $res = $this->cash($userid);
        $info = json_decode($res,true);

        if(empty($userid)){
            $msg = ['status'=>'error','code' => 10003, 'message' => '请输入用户ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if($info['code']==200){
            if(empty($pass)){
                $msg = ['status'=>'error','code' => 10001, 'message' => '请输入支付密码'];
                exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            if(password($pass, $info['data']['trade_encrypt']) != $info['data']['trade_pass']){
                $msg = ['status'=>'error','code' => 10002, 'message' => '支付密码错误'];
                exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            $msg = ['status'=>'success','code' => 200, 'message' => '支付密码匹配成功'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $msg = ['status'=>'error','code' => $info['code'], 'message' => $info['message']];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 获取余额提现
     * @param $userid  用户ID
     * @param $type 提现账户类型
     * @param $account 提现账户
     * @param $accountname 收款人
     * @param $amount 提现金额
     * @param $describe 提现描述
     * @param $module 提现模块
     */
    public function txgetdata($userid,$type,$account,$accountname,$amount,$pass,$key="zymember3")
    {
        $key = empty($_POST['key'])? $key : $_POST['key'];
        $userid = empty($userid)? $_POST['userid']:$userid;
        $type = empty($type)?$_POST['type']:$type;
        $account = empty($account)?$_POST['account']: $account;
        $accountname = empty($accountname)?$_POST['accountname']:$accountname;
        $amount = empty($amount)?$_POST['amount']:$amount;
        $pass = empty($pass)? $_POST['pass']:$pass;

        // 资金账户余额
        $urls = $this->zyconfig_db->get_one(array('key'=>'zyfunds2'));
        $param = array('id'=>$userid,"key"=>"zyfunds1");
        $paramstrings = http_build_query($param);
        $results = $this->juhecurl($urls['url'],$paramstrings);
        $info = json_decode($results,true);

        // 获取会员信息
        $memurl = $this->zyconfig_db->get_one(array('key'=>'zymember1'),'url');
        $paramstr = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memurl['url'], $paramstr);
        $res = json_decode($contents, true);

        if(empty($userid)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入用户ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($type)){
            $msg = ['status'=>'error','code' => 10004, 'message' => '请输入账户类型'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(!($type==1||$type==2||$type==3)){
            $msg = ['status'=>'error','code' => 10005, 'message' => '请输入正确的账户类型'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($account)){
            $msg = ['status'=>'error','code' => 10002, 'message' => '请输入账户'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($accountname)){
            $msg = ['status'=>'error','code' => 10003, 'message' => '请输入账户名'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($amount)){
            $msg = ['status'=>'error','code' => 10007, 'message' => '请输入提现金额'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }


        $data1 = array(
            'trade_sn' => date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'userid' => $userid,
            'username'=> $res['data']['username'],
            'nickname'=> $res['data']['nickname'],
            'phone'=>$res['data']['mobile'],
            'type'=>$type,
            'account'=>$account,
            'accountname'=>$accountname,
            'amount'=>$amount,
            'status'=>1,
            'addtime'=>strtotime('now')
        );

        $data2 = array(
            'userid'=>$userid,
            'amount'=>$amount,
            'describe'=>"资金提现",
            'module'=>"zyfunds"
        );

        if($info['code']==200){
            if(empty($pass)){
                $msg = ['status'=>'error','code' => 10008, 'message' => '请输入支付密码'];
                exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            if(password($pass, $info['data']['trade_encrypt']) != $info['data']['trade_pass']){
                $msg = ['status'=>'error','code' => 10006, 'message' => '支付密码错误'];
                exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            $txconfig = $this->zyconfig_db->get_one(array('key'=>$key),'url');


            if(!empty($txconfig['url'])){
                // 减少余额
                $paramst = http_build_query($data2);
                $content = $this->juhecurl($txconfig['url'], $paramst);
                $infos = json_decode($content, true);

                if($infos['code']!=200){
                    $msg = ['status'=>'error','code' => 10010, 'message' => '交易失败'];
                    exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                }

                if($insertid = $this->zyfound_tx_db->insert($data1,true)) {
                    $msg = ['status'=>'success','code' => 200, 'message' => '操作成功', 'data' => array('id'=>$insertid)];
                }else{
                    $msg = ['status'=>'error','code' => 10009, 'message' => "资金提现成功，流水清单记录失败"];
                }

                exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }
        }else{
            $msg = ['status'=>'error','code' => $info['code'], 'message' => $info['message']];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 添加提现流水清单
     * @param $data array 流水清单数据
     * */
    public function addtxls($userid,$type,$account,$accountname,$amount)
    {
        $userid = empty($userid)? $_GET['userid']:$userid;
        $type = empty($type)?$_GET['type']:$type;
        $account = empty($account)?$_GET['account']: $account;
        $accountname = empty($accountname)?$_GET['accountname']:$accountname;
        $amount = empty($amount)?$_GET['amount']:$amount;
        $describe = empty($describe)?$_GET['describe']:$describe;
        $module = empty($module)? $_GET['module']: $module;

        $memberinfo = $this->zyconfig_db->get_one(array('key'=>'zymember1'),'url');
        $paramstrings = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memberinfo['url'], $paramstrings);
        $res = json_decode($contents, true);

        $data1 = array(
            'trade_sn' => date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'userid' => $userid,
            'username'=> $res['data']['username'],
            'nickname'=> $res['data']['nickname'],
            'phone'=>$res['data']['phone'],
            'type'=>$type,
            'account'=>$account,
            'accountname'=>$accountname,
            'amount'=>$amount,
            'status'=>1,
            'addtime'=>strtotime('now')
        );

        if($insertid = $this->zyfound_tx_db->insert($data1,true)) {
            $msg = ['status'=>'success','code' => 200, 'message' => '流水清单记录成功', 'data' => array('id'=>$insertid)];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $msg = ['status'=>'error','code' => 10001, 'message' => "流水清单记录失败"];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 添加充值流水清单
     * @param $data array 流水清单数据
     * */
    public function addczls($userid,$type,$amount)
    {
        $userid = empty($userid)? $_GET['userid']:$userid;
        $type = empty($type)?$_GET['type']:$type;
        $amount = empty($amount)?$_GET['amount']:$amount;
        $describe = empty($describe)?$_GET['describe']:$describe;
        $module = empty($module)? $_GET['module']: $module;

        $memberinfo = $this->zyconfig_db->get_one(array('key'=>'zymember1'),'url');
        $paramstrings = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memberinfo['url'], $paramstrings);
        $res = json_decode($contents, true);

        $data1 = array(
            'trade_sn' => date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'userid' => $userid,
            'username'=> $res['data']['username'],
            'nickname'=> $res['data']['nickname'],
            'phone'=>$res['data']['phone'],
            'type'=>$type,
            'amount'=>$amount,
            'status'=>0,
            'addtime'=>strtotime('now')
        );

        if($insertid = $this->zyfound_pay_db->insert($data1,true)) {
            $msg = ['status'=>'success','code' => 200, 'message' => '流水清单记录成功', 'data' => array('id'=>$insertid)];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $msg = ['status'=>'error','code' => 10001, 'message' => "流水清单记录失败"];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 获取流水清单
     * @param $id int 用户ID
     * */
    public function lslist($id)
    {
        $id = empty($id)?$_GET['id']:$id;
        if (empty($id)) {
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入用户ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $data['tx'] = $this->zyfound_tx_db->select(array('userid'=>$id),"*","","addtime desc");
        $data['cz'] = $this->zyfound_pay_db->select(array('userid'=>$id),"*","","addtime desc");
        $msg = ['status'=>'success','code'=>200,'message'=>'操作成功','data'=>$data];
        exit(json_encode($msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /*
     * 查看提现流水清单详情
     * @param $oid int 订单ID
     * @param $id int ID
     * */
    public function txlists($id,$oid){
        $id = empty($id)?$_GET['id']:$id;
        $oid = empty($oid)?$_GET['oid']:$oid;
        if(empty($oid)&&empty($id)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入ID或者订单ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(!empty($id)){
            $where['id'] = $id;
        }

        if(!empty($oid)){
            $where['trade_sn'] = $oid;
        }
        $msg['status'] = 'success';
        $msg['data'] = $this->zyfound_tx_db->get_one($where);
        $msg['code'] = 200;
        $msg['message'] = '操作成功';
        exit(json_encode($msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /*
     * 查看充值流水清单详情
     * @param $oid int 订单ID
     * */
    public function czlists($id,$oid){
        $id = empty($id)?$_GET['id']:$id;
        $oid = empty($oid)?$_GET['oid']:$oid;

        if(empty($oid)&&empty($id)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入ID或者订单ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(!empty($id)){
            $where['id'] = $id;
        }

        if(!empty($oid)){
            $where['trade_sn'] = $oid;
        }
        $msg['data'] = $this->zyfound_pay_db->get_one($where);
        $msg['code'] = 200;
        $msg['status'] = 'success';
        $msg['message'] = '操作成功';
        exit(json_encode($msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /*
     * 我的账户
     * @param $id int 用户id
     * */
    public function moneywallet($id)
    {
        $id = empty($id)?$_GET['id']:$id;
        if(empty($id)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '用户ID是空值'];
            exit(json_encode($msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $map = array('userid'=>$id);
        $info = $this->zyfound_bankcard_db->select($map);

        if($info){
            foreach($info as $k => $v){
                if($v['tid'] != 3){
                    $len = strlen($v['account']);
                    $show = floor($len/3);
                    $hide = $len-$show*2;
                    $index = $show+$hide;

                    if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }else{
                        $info[$k]['hide_account'] = substr($v['account'],0,$show).str_repeat("*",$hide).substr($v['account'],$index,$show);
                    }
                }else{
                    $len = strlen($v['account']);
                    $index = $len-4;
                    if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $v['account'])>0){
                        $info[$k]['hide_account'] = $this->substr_cut($v['account']);
                    }else{
                        $info[$k]['hide_account'] = str_repeat("****  ",3).substr($v['account'],$index,4);
                    }
                    $info[$k]['tname'] = explode(',',$v['tname'])[1];
                    $bankid = explode(',',$v['tname'])[0];
                    $info[$k]['thumb'] = $this->zyfound_bank_db->get_one(array('id'=>$bankid),'thumb')['thumb'];
                }
            }
        }else{
            $info = array();
        }
        $msg = ['status'=>'success','code'=>200,'message'=>'操作成功','data'=>$info];
        exit(json_encode($msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
     * @param string $user_name 姓名
     * @return string 格式化后的姓名
     */
    public function substr_cut($user_name){
        $strlen     = mb_strlen($user_name, 'utf-8');
        $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }

    /*
     * 获取充值信息
     * @param $data array 提现数据
     * @param $info array 会员信息
     * @param $datas int  资金账户余额
     * @param $config int 会员信息接口是否配置
     */
    public function czgetdata($userid,$type,$amount,$key="zymember2")
    {
        $key = empty($_POST['key'])? $key : $_POST['key'];
        $userid = empty($userid)? $_POST['userid']:$userid;
        $type = empty($type)?$_POST['type']:$type;
        $amount = empty($amount)?$_POST['amount']:$amount;

        $memberinfo = $this->zyconfig_db->get_one(array('key'=>'zymember1'),'url');
        $paramstrings = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memberinfo['url'], $paramstrings);
        $res = json_decode($contents, true);

        $data1 = array(
            'trade_sn' => date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'userid' => $userid,
            'username'=> $res['data']['username'],
            'nickname'=> $res['data']['nickname'],
            'phone'=>$res['data']['mobile'],
            'type'=>$type,
            'amount'=>$amount,
            'status'=>0,
            'addtime'=>strtotime('now')
        );

        $data2 = array(
            'userid'=>$userid,
            'amount'=>$amount,
            'describe'=>"资金充值",
            'module'=>"zyfunds"
        );

        if(empty($userid)){
            $msg = ['status'=>'error','code' => 10001, 'message' => '请输入用户ID'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($type)){
            $msg = ['status'=>'error','code' => 10002, 'message' => '请输入账户类型'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(!($type==1||$type==2||$type==3)){
            $msg = ['status'=>'error','code' => 10003, 'message' => '请输入正确的账户类型'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }


        if(empty($amount)){
            $msg = ['status'=>'error','code' => 10005, 'message' => '请输入充值金额'];
            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $czconfig = $this->zyconfig_db->get_one(array('key'=>$key),'url');

        $url = $this->zyconfig_db->get_one(array('key'=>'zyfunds2'));
        $params = array('id'=>$userid,"key"=>"zyfunds1");
        $paramstring = http_build_query($params);
        $memconfig = json_decode($this->juhecurl($url['url'],$paramstring),true);
        
        if(!empty($czconfig['url'])){
            if($memconfig['code']==200){
                $paramstring = http_build_query($data2);
                $content = $this->juhecurl($czconfig['url'], $paramstring);
                $infos = json_decode($content, true);

                if($infos['code']!=200){
                    $msg = ['status'=>'error','code' => 10008, 'message' => '交易失败'];
                    exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                }
            }

            if($insertid = $this->zyfound_pay_db->insert($data1,true)) {
                $msg = ['status'=>'success','code' => 200, 'message' => '操作成功', 'data' => array('id'=>$insertid)];
            }else{
                $msg = ['status'=>'error','code' => 10007, 'message' => "资金充值成功，流水清单记录失败"];
            }

            exit(json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 获取银行信息
     * @param $status int 状态，默认为1
     * */
    public function bankinfo($status=1){
        $status = empty($status)? $_GET['status']:$status;
        $bank = $this->zyfound_bank_db->select(array('status'=>$status));
        $res = ['status'=>'success','code'=>200,'message'=>'操作成功','data'=>$bank];
        exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /*
     * 添加支付宝账号
     * @param $userid  用户id
     * @param $account 账号
     * @param $accountname 收款姓名
     * @param $key 获取会员信息接口关键字
     * */
    public function alipay_add($userid,$account,$accountname,$key='zymember1'){
        $userid = empty($userid)? $_GET['userid']:$userid;
        $account = empty($account)? $_GET['account']:$account;
        $accountname = empty($accountname)? $_GET['accountname']:$accountname;

        $memberinfo = $this->zyconfig_db->get_one(array('key'=>$key),'url');
        $paramstrings = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memberinfo['url'], $paramstrings);
        $res = json_decode($contents, true);

        if(empty($userid)){
            $res = ['status'=>'error','code'=>10001,'message'=>'请输入用户id'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($account)){
            $res = ['status'=>'error','code'=>10002,'message'=>'请输入账号'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($accountname)){
            $res = ['status'=>'error','code'=>10003,'message'=>'请输入账户姓名'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $count = $this->zyfound_bankcard_db->count(array('account'=>$account,'userid'=>$userid));
        if($count>0){
            $res = ['status'=>'error','code'=>10004,'message'=>'支付宝账号已存在'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $data = array(
            'userid' => $userid,
            'username' => $res['data']['username'],
            'nickname' => $res['data']['nickname'],
            'phone' => $res['data']['phone'],
            'addtime' => strtotime('now'),
            'account' => $account,
            'accountname' => $accountname,
            'is_first'=>-1,
            'tname'=>'支付宝',
            'tid'=>1
        );

        if($this->zyfound_bankcard_db->insert($data)){
            $res = ['status'=>'success','code'=>200,'message'=>'操作成功'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $res = ['status'=>'error','code'=>10005,'message'=>'操作失败'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 添加微信账号
     * @param $userid  用户id
     * @param $account 账号
     * @param $accountname 收款姓名
     * @param $key 获取会员信息接口关键字
     * */
    public function wechat_add($userid,$account,$accountname,$key='zymember1'){
        $userid = empty($userid)? $_GET['userid']:$userid;
        $account = empty($account)? $_GET['account']:$account;
        $accountname = empty($accountname)? $_GET['accountname']:$accountname;

        $memberinfo = $this->zyconfig_db->get_one(array('key'=>$key),'url');
        $paramstrings = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memberinfo['url'], $paramstrings);
        $res = json_decode($contents, true);

        if(empty($userid)){
            $res = ['status'=>'error','code'=>10001,'message'=>'请输入用户id'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($account)){
            $res = ['status'=>'error','code'=>10002,'message'=>'请输入账号'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($accountname)){
            $res = ['status'=>'error','code'=>10003,'message'=>'请输入账户姓名'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $count = $this->zyfound_bankcard_db->count(array('account'=>$account,'userid'=>$userid));
        if($count>0){
            $res = ['status'=>'error','code'=>10004,'message'=>'微信账号已存在'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $data = array(
            'userid' => $userid,
            'username' => $res['data']['username'],
            'nickname' => $res['data']['nickname'],
            'phone' => $res['data']['phone'],
            'addtime' => strtotime('now'),
            'account' => $account,
            'accountname' => $accountname,
            'is_first'=>-1,
            'tname'=>'微信',
            'tid'=>2
        );

        if($this->zyfound_bankcard_db->insert($data)){
            $res = ['status'=>'success','code'=>200,'message'=>'操作成功'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $res = ['status'=>'error','code'=>10005,'message'=>'操作失败'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 添加银行账号
     * @param $userid  用户id
     * @param $account 账号
     * @param $accountname 收款姓名
     * @param $key 获取会员信息接口关键字
     * */
    public function banks_add($userid,$account,$accountname,$tname,$key='zymember1'){
        $userid = empty($userid)? $_GET['userid']:$userid;
        $account = empty($account)? $_GET['account']:$account;
        $accountname = empty($accountname)? $_GET['accountname']:$accountname;
        $tname = empty($tname)? $_GET['tname']:$tname;

        $memberinfo = $this->zyconfig_db->get_one(array('key'=>$key),'url');
        $paramstrings = http_build_query(array('userid'=>$userid));
        $contents = $this->juhecurl($memberinfo['url'], $paramstrings);
        $res = json_decode($contents, true);

        if(empty($userid)){
            $res = ['status'=>'error','code'=>10001,'message'=>'请输入用户id'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($account)){
            $res = ['status'=>'error','code'=>10002,'message'=>'请输入账号'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($accountname)){
            $res = ['status'=>'error','code'=>10003,'message'=>'请输入账户姓名'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $count = $this->zyfound_bankcard_db->count(array('account'=>$account,'userid'=>$userid));
        if($count>0){
            $res = ['status'=>'error','code'=>10004,'message'=>'银行账号已存在'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($tname)){
            $res = ['status'=>'error','code'=>10006,'message'=>'请输入开户行'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $data = array(
            'userid' => $userid,
            'username' => $res['data']['username'],
            'nickname' => $res['data']['nickname'],
            'phone' => $res['data']['phone'],
            'addtime' => strtotime('now'),
            'account' => $account,
            'accountname' => $accountname,
            'is_first'=>-1,
            'tname'=>$tname,
            'tid'=>3
        );

        if($this->zyfound_bankcard_db->insert($data)){
            $res = ['status'=>'success','code'=>200,'message'=>'操作成功'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $res = ['status'=>'error','code'=>10005,'message'=>'操作失败'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }
    
    /*
     * 删除账户
     * @param id 账户id
     * */
    public function bcarddel($id,$userid){
        $id = empty($id)? $_GET['id'] : $id;
        $userid = empty($userid)? $_GET['userid'] : $id;
        if(empty($id)){
            $res = ['status'=>'error','code'=>10002,'message'=>'请输入ID'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        if(empty($userid)){
            $res = ['status'=>'error','code'=>10003,'message'=>'请输入用户ID'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if($this->zyfound_bankcard_db->delete(array('id'=>$id,'userid'=>$userid))){
            $res = ['status'=>'success','code'=>200,'message'=>'删除成功'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $res = ['status'=>'error','code'=>10001,'message'=>'删除失败'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 设置默认账户
     * @param id ID
     * */
    public function bcardtype($id,$is_first,$userid){
        $id = empty($id)?$_GET['id']:$id;
        $is_first = empty($is_first)?$_GET['is_first']:$is_first;
        $userid = empty($useid)?$_GET['userid']:$userid;

        $map = array('id'=>$id,'userid'=>$userid);
        $data = array('is_first'=>$is_first);
        
        if(empty($id)){
            $res = ['status'=>'error','code'=>10002,'message'=>'请输入ID'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($is_first)){
            $res = ['status'=>'error','code'=>10003,'message'=>'请输入默认参数'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if(empty($userid)){
            $res = ['status'=>'error','code'=>10004,'message'=>'请输入用户ID'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        if($is_first==1){
            $count = $this->zyfound_bankcard_db->count(array('is_first'=>1,'userid'=>$userid));
            if($count>0){
                $lists = $this->zyfound_bankcard_db->select();
                foreach($lists as $k => $v){
                    $this->zyfound_bankcard_db->update(array('is_first'=>-1),array('id'=>$v['id']));
                }
            }
        }

        if($this->zyfound_bankcard_db->update($data,$map)){
            $res = ['status'=>'success','code'=>200,'message'=>'操作成功'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }else{
            $res = ['status'=>'error','code'=>10001,'message'=>'操作失败'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    /*
     * 余额支付
     * @param amount 金额
     * @param userid 用户ID
     */
    public function balance($amount,$userid){
        $amount = empty($amount)?$_POST['amount']:$amount;

        if(empty($amount)){
            $res = ['status'=>'error','code'=>10001,'message'=>'请输入余额'];
            exit(json_encode($res,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $url = $this->zyconfig_db->get_one(array('key'=>'zymember3'),"url");

        $_userid = param::get_cookie("_userid");
        $userid = $_POST['userid'];

        if($userid){
            $userid = $userid;
        }else{
            $userid = $_userid;
        }
        
        $describe = "资金描述";
        $module = "zyfunds";

        $data = array(
            "userid" => $userid,
            "amount" => $amount,
            "describe" => $describe,
            "module" => $module
        );

        $paramstring = http_build_query($data);
        $res = $this->juhecurl($url['url'],$paramstring);
        exit($res);
    }
}