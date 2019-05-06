<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
require_once dirname(__FILE__).'/index.php';

class api
{
    public function __construct()
    {
        $this->get_db = pc_base::load_model('get_model');
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
		$this->qrcode_db = pc_base::load_model('zyqrcode_model');
    }
	
	/**
     * 获取会员信息
     * @param $project
     * @return json
     */
    public function qrcode_api($project)
    {
		$project = empty($_POST['project']) ? $key : $_POST['project'];
		//$sql="SELECT * FROM zy_zyqrcode WHERE isshow=1 AND project='".$project."' ORDER BY id DESC";
		$info = $this->qrcode_db->select('isshow=1 AND project="'.$project.'"','`id`,`project`,`name`,`url`,`thumb`,`qrcode`','', $order = 'id DESC');
        if($info){
            $json['status']='success';
            $json['code']='200';
            $json['message']='操作成功';
            $json['data']=$info;
        }else{
            $json['status']='error';
            $json['code']='-200';
            $json['message']='数据为空';
        }
		
		echo "<pre>";
        exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        echo  "</pre>";
    }

}