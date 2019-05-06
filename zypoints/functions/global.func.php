<?php
// +------------------------------------------------------------
// | 卓远网络PHPCMS在线商城1.0
// +------------------------------------------------------------
// | 卓远网络：叶洋洋 QQ:1327262511 http://www.300c.cn/
// +------------------------------------------------------------
// | 欢迎加入卓远网络-Team，和卓远一起，精通PHPCMS
// +------------------------------------------------------------
// | 版本号：201600811
// +------------------------------------------------------------

	function getPage($page, $pageSize,$arrayCount)
	{
		$pagenums = ($arrayCount%$pageSize) == 0? ($arrayCount/$pageSize): (int)($arrayCount/$pageSize)+1;//总页数
		if($page > $pagenums)
			$page = 1;
		return array($page,$pagenums);
	}


/**
	 * 
	 * 取二维数组中一个字段
	 * @param array $arr
	 * @param str $keyField
	 * @param str $valueField
	 * @param str $preKeyField 当$keyField有值，索引前加字符
	 */
	function array_to($arr, $keyField='', $valueField='', $preKeyField='' )
	{
		if(empty($arr)) return $arr;

		$result = array();
		foreach($arr as $k=>$v)
		{
			if( is_object($v) )
			{
				if($keyField)
					$result[$preKeyField . $v->$keyField] = $valueField ? $v->$valueField : $v;
				else
					$result[$k] = $valueField ? $v->$valueField : $v;
			}
			elseif( is_array($v) )
			{
				if($keyField)
					$result[$preKeyField . $v[$keyField]] = $valueField ? $v[$valueField] : $v;
				else
					$result[$k] = $valueField ? $v[$valueField] : $v;
			}
		}
		return $result;
	}
	
	/**
	 * 
	 * 去掉数组中的重复ID并排序
	 * @param $arr 数组
	 * @param $id 排序的字段
	 */
	function array_order($arr,$id){
			$con=count($arr);
			
			for ($i = 0; $i <$con; $i++) {
				$pid[$i]=$arr[$i][$id];
			
				}
			
				$p=	array_flip($pid);
				$pp=array_flip($p);
				$c=0;
				for ($j = 0; $j <$con; $j++){
					if ($pp[$j]){
						$pc[$c]=$pp[$j];
						$c++;
						}
					
					}
			return $pc;
		
		}

	function string_array($data)
	{
		$s = explode("," ,$data);
		array_pop($s);
		return $s;
	}
	function pictury_array($data)
	{
		$picture = [];
		$num = 10;
		foreach($data as $row)
		{
			list($key,$value) = explode("|", $row);
			array_key_exists($key, $picture) ?$picture[$key.(string)$num] = $value:$picture[$key] = $value;
			$num++;
		}
		return $picture;
	}
	//api返回函数
	//200 成功返回码
	function returnAjaxData($code, $info="成功", $data='')//ajax返回函数
	{
		$json=[];
		if($code == 200)
		{
			$json = [
				'status' => 'success',
				'code' => $code,
				'message' => $info,
				'data' => $data,

			];
		}
		else {
			$json = [
				'status' => 'error',
				'code' => $code,
				'message' => $info,
			];
		}
		exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}
	//api返回函数
	//200 成功返回码
	function returnjsoninfo($code, $info="成功", $data='')//ajax返回函数
	{
		$json=[];
		if($code == 200)
		{
			$json = [
				'status' => 'success',
				'code' => $code,
				'message' => $info,
				'data' => $data,

			];
		}
		else {
			$json = [
				'status' => 'error',
				'code' => $code,
				'message' => $info,
			];
		}
		exit(json_encode($json,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
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
?>
