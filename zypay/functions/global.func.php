<?php 

/**
 * CURL方式的GET传值
 * @param  [type] $url  [GET传值的URL]
 * @return [type]       [description]
 */
function _crul_get($url){
   $oCurl = curl_init();  
   if(stripos($url,"https://")!==FALSE){  
     curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);  
     curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);  
     curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1  
   }  
   curl_setopt($oCurl, CURLOPT_URL, $url);  
   curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );  
   $sContent = curl_exec($oCurl);  
   $aStatus = curl_getinfo($oCurl);  
   curl_close($oCurl);  
   if(intval($aStatus["http_code"])==200){  
     return $sContent;  
   }else{  
     return false;  
   }  
}  





/**
 * CURL方式的POST传值
 * @param  [type] $url  [POST传值的URL]
 * @param  [type] $data [POST传值的参数]
 * @return [type]       [description]
 */
function _crul_post($url,$data){
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
	 * 设置config文件
	 * @param $config 配属信息
	 * @param $filename 要配置的文件名称
	 */
	function _set_config($config, $filename="zysystem") {
		$configfile = CACHE_PATH.'configs'.DIRECTORY_SEPARATOR.$filename.'.php';
		if(!is_writable($configfile)) showmessage('Please chmod '.$configfile.' to 0777 !');
		$pattern = $replacement = array();
		foreach($config as $k=>$v) {
			if(in_array($k,array('wechatpay_off','wechatpay_appid','wechatpay_appsecret','wechatpay_mchid','wechatpay_key','alipay_off','alipay_appid','alipay_private_key','alipay_public_key','alipay_gatewayUrl','alipay_notify_url_pc','alipay_return_url_pc','alipay_notify_url_pe','alipay_return_url_pe','alipay_yb_shopbuy','alipay_tb_shopbuy','alipay_yb_memberbuy','alipay_tb_memberbuy','wechatpay_yb_shopbuy','wechatpay_tb_shopbuy','wechatpay_yb_memberbuy','wechatpay_tb_memberbuy'))) {
				$v = trim($v);
				$configs[$k] = $v;
				$pattern[$k] = "/'".$k."'\s*=>\s*([']?)[^']*([']?)(\s*),/is";
	        	$replacement[$k] = "'".$k."' => \${1}".$v."\${2}\${3},";					
			}
		}
		$str = file_get_contents($configfile);
		$str = preg_replace($pattern, $replacement, $str);
		return pc_base::load_config('zysystem','lock_ex') ? file_put_contents($configfile, $str, LOCK_EX) : file_put_contents($configfile, $str);		
	}


  /**
   * [write_log 写入日志]
   * @param  [type] $data [写入的数据]
   * @return [type]       [description]
   */
  function write_log($data){ 
      $years = date('Y-m');
      //设置路径目录信息
      $url = $_SERVER["DOCUMENT_ROOT"].'/phpcms/modules/zypay/classes/alipay/log/'.date('Ymd').'_request_log.txt';  
      $dir_name=dirname($url);
        //目录不存在就创建
        if(!file_exists($dir_name))
        {
          //iconv防止中文名乱码
         $res = mkdir(iconv("UTF-8", "GBK", $dir_name),0777,true);
        }
        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建       
      fwrite($fp,date("Y-m-d H:i:s").var_export($data,true)."\r\n");//写入文件
      fclose($fp);//关闭资源通道
  }
function returnAjaxData($code, $info="成功", $data=[])//ajax返回函数
{
    $resule = ['code'=>$code, 'data'=>$data];
    if($code == 1)
    {
        $resule_info = [
            'status' => 'success',
            'message' => $info,
        ];
    }
    else {
        $resule_info = [
            'status' => 'error',
            'message' => $info,
        ];
    }
    exit(json_encode(array_merge($resule, $resule_info),JSON_UNESCAPED_UNICODE));
}


 ?>