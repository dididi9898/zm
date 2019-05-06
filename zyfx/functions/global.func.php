<?php
    function Error($info)
    {
        throw new Exception($info);
    }


    function string_array($data)
    {
        $s = explode("," ,$data);
        array_pop($s);
        return $s;
    }
    //api返回函数
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
    //前台
    //检测传入参数 形似：$neadArg = ["userid"=>[true,1], "shopID"=>[true,1], "count"=>[1,1]];[1,1]第一个参数代表是否必须，true是false否。第二个参数表示是否转换为INT类型。0为否，1位是
    //2019/4/7增加参数2表示将时间转化为时间戳
    function checkArg($data, $type="GET")
    {
        $info = [];
        if($type == "GET")
        {
            foreach($data as $key=>$value)
            {
                if(!isset($_GET[$key])&&$value[0])
                    returnAjaxData("-1", "请传入".$key);

                if(!empty($_GET[$key])|| $_GET[$key] == "0")
                    $info[$key] = $value[1] == 1? intval($_GET[$key]): ($value[1] == 2? strtotime($_GET[$key]):$_GET[$key]);
                else
                {
                    if($value[0])
                        returnAjaxData("-1", "请传入".$key);
                }
            }
        }
        elseif($type == "POST")
        {
            foreach($data as $key=>$value)
            {
                if(!isset($_POST[$key])&&$value[0])
                    returnAjaxData("-1", "请传入".$key);

                if(!empty($_POST[$key])|| $_POST[$key] == "0")
                    $info[$key] = $value[1] == 1? intval($_POST[$key]): ($value[1] == 2? strtotime($_POST[$key]):$_POST[$key]);
                else
                {
                    if($value[0])
                        returnAjaxData("-1", "请传入".$key);
                }
            }
        }
        return $info;
    }
    //后台
    //检测传入参数 形似：$neadArg = ["userid"=>[true,1], "shopID"=>[true,1], "count"=>[1,1]];[1,1]第一个参数代表是否必须，true是false否。第二个参数表示是否转换为INT类型。0为否，1位是
    //2019/4/7增加参数2表示将时间转化为时间戳
    function checkArgBcak($data, $type="GET"){
        $info = [];
        if($type == "GET")
        {
            foreach($data as $key=>$value)
            {
                if(!isset($_GET[$key])&&$value[0])
                    showmessage( "请传入".$key);

                if(!empty($_GET[$key]) || $_GET[$key] == "0")
                    $info[$key] = $value[1] == 1? intval($_GET[$key]): ($value[1] == 2? strtotime($_GET[$key]):$_GET[$key]);
                else
                {
                    if($value[0])
                        showmessage( "请传入".$key);
                }
            }
        }
        elseif($type == "POST")
        {
            foreach($data as $key=>$value)
            {
                if(!isset($_POST[$key])&&$value[0])
                    showmessage( "请传入".$key);

                if(!empty($_POST[$key]) || $_POST[$key] == "0")
                    $info[$key] = $value[1] == 1? intval($_POST[$key]): ($value[1] == 2? strtotime($_POST[$key]):$_POST[$key]);
                else
                {
                    if($value[0])
                        showmessage( "请传入".$key);
                }
            }
        }
        return $info;
    }
    //获得page和pagenums的函数
    function getPage($page, $pageSize,$arrayCount)
    {
        $pagenums = ($arrayCount%$pageSize) == 0? ($arrayCount/$pageSize): (int)($arrayCount/$pageSize)+1;//总页数
        if($page > $pagenums)
            $page = 1;
        return array($page,$pagenums);
    }
    /**
     * CURL方式的GET传值
     * @param  [type] $url  [GET传值的URL]
     * @return [type]       [description]
     */
    function _crul_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://") != FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);  //https请求时不去验证证书
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);  //https请求时不去验证hosts
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
?>
