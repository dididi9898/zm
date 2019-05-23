<?php

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);	//加载应用类方法
pc_base::load_sys_class('form', 0, 0);
pc_base::load_app_func('global');

class excel extends admin {
	/**
	*构造函数，初始化
	*/
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
		$this->enze_db=pc_base::load_model('zyenze_model');
		//引入卓远网络公共函数库
		//require_once 'zywl/functions/global.func.php';
	}

/**
* 菜单===========================================
*/
//excel导入导出
//	--excel导入
//	--excel导出

		



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
		//**************************************************************************************************************
		$res = excel_import($filename);
		
		
//echo '<pre>';
//var_dump($res);
//echo '<pre>';
//exit;
	
	////*********************************************写入数据库需要修改的地方*******************************************
	foreach ($res as $v) {
		//if(empty($v[8])){$v[8]='0';}
		//$v[1]=str_replace('\'', '', $v[1]);
		$data=array(
			'jobnumber'=> $v[0],	//工号
			'password'=> $v[0],	//医院所在地
			'headpic'=> $v[1],	//医院所在地
			'thumb'=> $v[2],	//医院所在地
			'name'=> $v[3],	//医院所在地代码
			'sex'=>$v[4],	//状态
			'phone'=>$v[5],	//具体位置
			'power'=>$v[6],	//具体位置
			'undownload'=>$v[7],	//具体位置
			'addtime'=>time(),	//等级
		);
		$memid=$this->enze_db->insert($data,$return_insert_id = true);
	}
		if ($memid){
			 showmessage('导入成功',HTTP_REFERER);
			}else{
			showmessage('导入失败',HTTP_REFERER);
		}
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