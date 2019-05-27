<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');

class excel extends admin {
	/**
	*构造函数，初始化
	*/
	//表和字段的对应
	public static $DBField = array("zy_goods"=>array("goods_name"=>'', 'goods_number'=>'',"summary"=>'', 'thumb'=>'', 'album'=>'', 'goodsimg_infos'=>'', 'content'=>'', 'market_price'=>'', "shop_price"=>'', 'addtime'=>'', 'point_mode'=>'', 'point_value'=>'', 'awardNumber'=>'', 'trialAwardNumber'=>'', 'point_sy_value'=>''));
	//翻译
	public static $CN = array("goods_name"=>'商品名称', "goods_number"=>"商品编号", "summary"=>'商品简述', "thumb"=>'商品缩略图', "album"=>"商品轮播图", "goodsimg_infos"=>"商品详细图", "content"=>"商品内容", "market_price"=>"市场价", "shop_price"=>'本店价', "point_mode"=>'获取积分方式', 'point_value'=>'积分数值', 'awardNumber'=>'佣金', 'trialAwardNumber'=>'试穿佣金', "point_sy_value"=>'试用积分');
    public static $excle_no=array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    //不能为空的字段名
    public static $neadArg = array("goods_name", "goods_number");
	public function __construct()
	{
		
		//开启session会话
		session_start();
		//初始化父级的构造函数
		parent::__construct();
		//引入数据表
		$this->get_db = pc_base::load_model('get_model');
		$this->member_db = pc_base::load_model('members_model');
		$this->member_detail_db = pc_base::load_model('member_detail_model');
		$this->zyexcelconfig = pc_base::load_model('zyexcelconfig_model');
        $this->goods_db = pc_base::load_model('goods_model');
        $this->zyexcelerror = pc_base::load_model('zyexcelerror_model');
		//$this->enze_db=pc_base::load_model('zyenze_model');
		//引入卓远网络公共函数库
		//require_once 'zywl/functions/global.func.php';
	}

/**
* 菜单===========================================
*/
//excel导入导出
//	--excel导入
//	--excel导出
    public function notEmpty($actValue,$configArray)//某些字段不能为空,在这里检测
    {
        foreach(self::$neadArg as $k)
        {
            $s = $configArray[$k];
            if(!isset($actValue[$s]))
                Error(self::$CN[$k]."不能为空");
        }
    }
    public function alterP($value, $configArray)
    {
        foreach ($value as $k=>$v)
        {
            $s = array_search($k, $configArray);
            switch ($s)
            {
                case "market_price":
                    if(!is_numeric($v))
                        Error(self::$CN["market_price"]."必须为数字，输入值为：".$v);
                    break;
                case "shop_price":
                    if(!is_numeric($v))
                        Error(self::$CN["shop_price"]."必须为数字，输入值为：".$v);
                    break;
                case "point_mode":
                    if($v != '0' && $v != '1')
                        Error(self::$CN["point_mode"]."只能为0或者1，输入值为：".$v);
                    break;
                case "thumb":
                    if(count($value[$k]) > 1)
                        Error(self::$CN["thumb"]."商品缩略图只能有一张，现在有".count($value[$k])."张");
                    $value[$k] = $v[0]["url"];break;
                case "album":
                    $value[$k] = array2string($value[$k]);break;
                case "goodsimg_infos":
                    $value[$k] = array2string($value[$k]);break;
                default :break;
            }
        }
        return $value;
    }

/**
* excel导入导出===========================================
*/
	/**
	* excel导入_列表
	*/
	public function excel_drlist(){
		$show_header = false;		//去掉最上面的线条
		include $this->admin_tpl('excel_drlist'); //和模板对应上
		
	}
	/**
	* excel导出_列表
	*/
	public function excel_dclist(){
		$show_header = false;		//去掉最上面的线条
		include $this->admin_tpl('excel_dclist'); //和模板对应上
	}
	
	
	/**
	* excel导入_测试数据
	*/
	public function excel_dr_ceshi(){
	$ceshi_db  = pc_base::load_model('qxc_lottery_num_model');		//需要增加的表
	if (!empty ( $_FILES ['file_stu'] ['name'] ))
  	 $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
    $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
    $file_type = $file_types [count ( $file_types ) - 1];
     /*判别是不是.xls文件，判别是不是excel文件*/
     if (strtolower ( $file_type ) != "xls" && strtolower ( $file_type ) != "xlsx")              
    {
         showmessage('不是excel文件，请重新选择文件',HTTP_REFERER);
     }
	 $basepath = str_replace( '\\' , '/' , realpath(dirname(__FILE__).'/../../../'));
    /*设置上传路径*/
    $savePath = $basepath.'/uploadfile/excel/';
    //如果文件夹不存在，那么就新建文件夹
	if(!file_exists($savePath)){
		mkdir($savePath);
	}


    /*以时间来命名上传的文件*/
     $str = date ( 'Ymdhis' );
     $file_name = $str . "." . $file_type;
     /*是否上传成功*/
     if (! copy ( $tmp_file, $savePath.$file_name ))
      {
          showmessage('上传失败',HTTP_REFERER);
      }
    $filename =$savePath.$file_name;
    //$filename = "C:/wamp/www/zm/uploadfile/excel/1.xlsx";
    //**************************************************************************************************************
    list($res, $img) = excel_import($filename, self::$excle_no);

	$excelConfig = $this->zyexcelconfig->get_one(["id"=>1], "DBField");
	$configArray = json_decode($excelConfig["DBField"], true);
	$errorNum = $this->zyexcelerror->get_one("1", "Max(errorNumber) as errorNumber");
	foreach($img as $num=>$v)
    {
        foreach($v as $x=>$y)
        {
            if(empty($res[$num][$x]))
                $res[$num][$x] = $y;
            else
            {
                $this->zyexcelerror->insert(array("errorNumber"=>$errorNum["errorNumber"]+1, 'info'=>"在".$num."行".$x."列,数据和图片重合", "addtime"=>time(), 'row'=>$num));
                unset($res[$num]);
                break;
            }
        }
    }

	foreach($res as $key=>$value)//$key=>行$value=>数据
    {
        try{
            $this->notEmpty($value, $configArray);
            $value = $this->alterP($value, $configArray);

            foreach(self::$DBField as $k=>$v) //$key=>表名 $v=>需要加入的字段名（array）
            {

                foreach ($v as $name=>$attr)
                {
                    self::$DBField[$k][$name] = $value[$configArray[$name]];
                }
                self::$DBField[$k]["addtime"] = time();
                switch ($k)
                {
                    case "zy_goods":$this->goods_db->insert(self::$DBField[$k]);break;//底层暂时不支持一条sql插入多行数据，暂时先这样
                    default :break;
                }
            }
        }
        catch (Exception $e)
        {
            $this->zyexcelerror->insert(array("errorNumber"=>$errorNum["errorNumber"]+1, 'info'=>$e->getMessage(), "addtime"=>time(), 'row'=>$key));
        }
    }



	////*********************************************写入数据库需要修改的地方*******************************************
//	foreach ($res as $v) {
//		//if(empty($v[8])){$v[8]='0';}
//		//$v[1]=str_replace('\'', '', $v[1]);
//		$data=array(
//			'jobnumber'=> $v[0],	//工号
//			'password'=> $v[0],	//医院所在地
//			'headpic'=> $v[1],	//医院所在地
//			'thumb'=> $v[2],	//医院所在地
//			'name'=> $v[3],	//医院所在地代码
//			'sex'=>$v[4],	//状态
//			'phone'=>$v[5],	//具体位置
//			'power'=>$v[6],	//具体位置
//			'undownload'=>$v[7],	//具体位置
//			'addtime'=>time(),	//等级
//		);
//		$memid=$this->enze_db->insert($data,$return_insert_id = true);
//	}
//		if ($memid){
//			 showmessage('导入成功',HTTP_REFERER);
//			}else{
//			showmessage('导入失败',HTTP_REFERER);
//		}
	}

	
	/**
	* excel导出_测试数据
	*/
	public function excel_dc_ceshi(){
		//脚本执行不受时间限制，可消耗内存2G
		set_time_limit(0);
		ini_set('memory_limit','2048M');
		$ceshi_db  = pc_base::load_model('qxc_lottery_num_model');
		// $ceshi_db  = pc_base::load_model('hospitals_model');
		//*******************************************************导出需要修改数据库名称*********************************
		//会员资料所有导出，主表
		$member_array = $this->enze_db->select();

	//封装所有的会员资料到一个数组#all_member，主表加附表
 	 $all_member = $member_array;
	  
	  //设置不要导出的字段，并且过滤掉
	  $no_exeport_fields = array('password','addtime');
      //$no_exeport_fields = array();
	  
	  foreach ($all_member as $k=>$member){
		  foreach($member as $field=>$value){
			if(in_array($field,$no_exeport_fields)){
				//删除过滤掉得字段
				
				unset($all_member[$k][$field]);
				}  
		  	}
		  
		  }
	
		 //字段转向翻译
		 $field_en_array=array();
		 $field_en_array['id']='编号';
		 $field_en_array['jobnumber']='工号';
		 $field_en_array['password']='密码';
		 $field_en_array['headpic']='头像';
		 $field_en_array['thumb']='图组';
		 $field_en_array['name']='姓名';
		 $field_en_array['sex']='性别';
		 $field_en_array['phone']='手机';

		 $field_en_array['power']='权限';
		 $field_en_array['addtime']='添加时间';
		 $field_en_array['undownload']='不可下载图片';

		 
		 	/*$model_field_array = $this->db_model_field->listinfo();
			
	
				echo "<pre>";
      			  var_dump($model_field_array);
     			   echo'</pre>';
      			  exit;*/
		 
		export_to_excel($all_member,'用户数据导出', $field_en_array);

	}
	
	

/**
* excel导入导出===========================================
*/

}
?>