<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
require_once 'classes/phpqrcode/QRcode.class.php';
class zyqrcode extends admin {
	function __construct() {
		parent::__construct();
		$this->module_db = pc_base::load_model('module_model');
		$this->qrcode_db = pc_base::load_model('zyqrcode_model');
        $this->zyconfig_db = pc_base::load_model('zyconfig_model');
	}

	/*
	 * 二维码管理
	 * */
	public function init()
	{
		$page = empty($_GET['page'])?1:intval($_GET['page']);
		$info = $this->qrcode_db->listinfo('','id asc',$page,10);
		$pages = $this->qrcode_db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyqrcode&c=zyqrcode&a=add\', title: \'添加二维码\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加二维码');
		include $this->admin_tpl('zyqrcode_init');
	}

	/*
	 * 添加二维码信息
	 * */
	public function add()
	{
		if(isset($_POST['dosubmit'])){
			$data['name'] = $_POST['name'];
            $data['project'] = $_POST['project'];
			$data['url'] = $_POST['url'];
			$data['thumb'] = $_POST['thumb'];
            $data['isthumb'] = $_POST['isthumb'];
            $data['isshow'] = $_POST['isshow'];
			if(empty($_POST['isshow'])){
				$data['isshow'] = 0;
			}
            if(empty($_POST['isthumb'])){
                $data['isthumb'] = 0;
            }
            if ($data['isthumb']==1){
                $data['qrcode']=$this->create_qrcode_pic($data['url'],$data['thumb']);
            }else {
                $data['qrcode'] = $this->create_qrcode($data['url']);
            }
			if($this->qrcode_db->insert($data)){
				showmessage('操作成功','index.php?m=zyqrcode&c=zyqrcode&a=adds');
			}else{
				showmessage('操作失败','index.php?m=zyqrcode&c=zyqrcode&a=adds');
			}
		}else{
            $show_validator = $show_scroll = $show_header = true;
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('zyqrcode_add');
		}
	}
    public function adds(){
        showmessage('已操作','','','add');
    }

	/*
	 * 编辑二维码信息
	 * */
	public function edit()
	{
		if(isset($_POST['dosubmit'])){
            $data['name'] = $_POST['name'];
            $data['project'] = $_POST['project'];
            $data['url'] = $_POST['url'];
            $data['thumb'] = $_POST['thumb'];
            $data['isthumb'] = $_POST['isthumb'];
            $data['isshow'] = $_POST['isshow'];
            //删除没用二维码
            $one=$this->qrcode_db->get_one(array('id'=>$_POST['id']));
            if(file_exists($one['qrcode'])) {
                unlink($one['qrcode']);
            }
            if(empty($_POST['isshow'])){
                $data['isshow'] = 0;
            }
            if(empty($_POST['isthumb'])){
                $data['isthumb'] = 0;
            }
            if ($data['isthumb']==1){
                $data['qrcode']=$this->create_qrcode_pic($data['url'],$data['thumb']);
            }else {
                $data['qrcode'] = $this->create_qrcode($data['url']);
            }
			if($this->qrcode_db->update($data,array('id'=>$_POST['id']))){
				showmessage('操作成功','index.php?m=zyqrcode&c=zyqrcode&a=edits');
			}else{
				showmessage('操作失败','index.php?m=zyqrcode&c=zyqrcode&a=edits');
			}
		}else{
            $show_validator = $show_scroll = $show_header = true;
			$info = $this->qrcode_db->get_one(array('id'=>$_GET['id']));
			$upload_allowext = 'jpg|jpeg|gif|png|bmp';
			$isselectimage = '1';
			$images_width = '';
			$images_height = '';
			$watermark = '0';
			$authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
			include $this->admin_tpl('zyqrcode_edit');
		}
	}

	public function edits(){
		showmessage('已操作','','','edit');
	}
	
	/*
	 * 删除银行卡信息
	 * */
	public function del(){
		$id = intval($_GET['id']);
		if($id){
            //删除没用二维码
            $one=$this->qrcode_db->get_one(array('id'=>$id));
            if(file_exists($one['qrcode'])) {
                unlink($one['qrcode']);
            }
			$result=$this->qrcode_db->delete(array('id'=>$id));
			if($result)
			{
				showmessage(L('operation_success'),HTTP_REFERER);
			}else {
				showmessage(L("operation_failure"),HTTP_REFERER);
			}
		}

		//批量删除；
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $id) {
                //删除没用二维码
                $one=$this->qrcode_db->get_one(array('id'=>$id));
                if(file_exists($one['qrcode'])) {
                    unlink($one['qrcode']);
                }
				$result=$this->qrcode_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])){
			showmessage('请选择要删除的订单',HTTP_REFERER);
		}
	}

    //生成文件路径
    function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!mkdirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }

    /*
     * 更新二维码
     * */
    //生成原始的二维码(生成图片文件)
    public function create_qrcode($url=''){
        //require_once 'QRcode.class.php';
        $value = $url;                //二维码内容
        $errorCorrectionLevel = 'L';  //容错级别
        $matrixPointSize = 5;         //生成图片大小
        //生成二维码图片
        $this->mkdirs("uploadfile/qrcode/");
        $filename = 'uploadfile/qrcode/'.time().'.png';
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    // 在生成的二维码中加上logo(生成图片文件)
    function create_qrcode_pic($url='',$logo_path=''){
        //require_once 'phpqrcode.php';
        $value = $url;         //二维码内容
        $errorCorrectionLevel = 'H';  //容错级别
        $matrixPointSize = 6;      //生成图片大小
        //生成二维码图片
        $this->mkdirs("uploadfile/qrcode/");
        $filename = 'uploadfile/qrcode/'.time().'.png';
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = $this->strget($logo_path); //准备好的logo图片
        //$QR=$filename;//已经生成的原始二维码图
        if (file_exists($logo)) {
            $QR = imagecreatefromstring(file_get_contents($filename));    //目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo));  //源图象连接资源。
            $QR_width = imagesx($QR);      //二维码图片宽度
            $QR_height = imagesy($QR);     //二维码图片高度
            $logo_width = imagesx($logo);    //logo图片宽度
            $logo_height = imagesy($logo);   //logo图片高度
            $logo_qr_width = $QR_width / 4;   //组合之后logo的宽度(占二维码的1/5)
            $scale = $logo_width/$logo_qr_width;  //logo的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height/$scale; //组合之后logo的高度
            $from_width = ($QR_width - $logo_qr_width) / 2;  //组合之后logo左上角所在坐标点
            //重新组合图片并调整大小
            /*
             * imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        imagepng($QR, $filename);
        imagedestroy($QR);
        imagedestroy($logo);
        return $filename;
    }

    /*
	 * 截取字符
	 * $string 输入字符串
	 * return 返回uploadfile之后的字符串
	 * */
    public function strget($string=''){
        $newstring= strstr( $string, 'uploadfile'); //默认返回查找值@之后的尾部，@jb51.net
        return $newstring;
    }






    /*
     * 配置模块
     * */
    public function zyconfig()
    {
        $big_menu = array
        (
            'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyqrcode&c=zyqrcode&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()   {window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
        );
        $order = 'id DESC';
        $where = array("item_name"=>'zyqrcode');
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $info=$this->zyconfig_db->listinfo($where,$order,$page,20);
        $pages = $this->zyconfig_db->pages;

        include $this->admin_tpl('zyconfig');
    }

    /*
     * 添加配置
     * */
    public function configadd()
    {

        if($_POST['dosubmit'])
        {
            if(empty($_POST['config_name']))
            {
                showmessage('请输入项目名',HTTP_REFERER);
            }
            $num = $this->zyconfig_db->count(array('item_name'=>"zyqrcode"))+1;
            $car=array
            (
                'config_name'=>$_POST['config_name'],
                'model_name'=>$_POST['model_name'],
                'item_name'=>"zyqrcode",
                "key"=>"zyqrcode".$num,
                'url'=>$_POST['url'],
            );

            $this->zyconfig_db->insert($car); //修改
            showmessage(L('operation_success'), '', '', 'add');
        }
        else
        {
            $into=$this->module_db->select();
            include $this->admin_tpl('zyconfigadd');
        }
    }

    /**
     * 编辑配置界面
     * @return [type] [description]
     */
    public function configedit()
    {
        if(isset($_POST['dosubmit']))
        {
            $car=array
            (
                'url'=>$_POST['url'],
                'model_name'=>$_POST['model_name'],
            );
            $this->zyconfig_db->update($car, array('id'=>$_POST['id'])); //修改
            showmessage('操作完成','','','edit');
        }
        else
        {
            if(!$_GET['id'])
            {
                showmessage('id不能为空',HTTP_REFERER);
            }
            $into=$this->module_db->select();
            $info =$this->zyconfig_db->get_one(array('id'=>$_GET['id']));
            include $this->admin_tpl('zyconfigshow');
        }
    }

    /**
     * 编辑文档界面
     * @return [type] [description]
     */
    public function configeditD()
    {
        if(isset($_POST['dosubmit']))
        {
            $car=array
            (
                'api_url'=>$_POST['api_url'],
                'explain'=>$_POST['explain'],
                'api_explain'=>$_POST['api_explain'],
            );
            $this->zyconfig_db->update($car, array('id'=>$_GET['id'])); //修改
            showmessage('操作完成','','','show');
        }
        else
        {
            if(!$_GET['id'])
            {
                showmessage('id不能为空',HTTP_REFERER);
            }
            $info =$this->zyconfig_db->get_one(array('id'=>$_GET['id']));
            include $this->admin_tpl('zyconfigdoc');
        }
    }

    /**
     * 删除配置
     * @return [type] [description]
     */
    public function configdel()
    {
        if(intval($_GET['id']))
        {
            $result=$this->zyconfig_db->delete(array('id'=>$_GET['id']));
            if($result)
            {
                showmessage(L('operation_success'),HTTP_REFERER);
            }else {
                showmessage(L("operation_failure"),HTTP_REFERER);
            }
        }

        //批量删除；
        if(is_array($_POST['id']))
        {
            foreach($_POST['id'] as $id) {
                $result=$this->zyconfig_db->delete(array('id'=>$id));
            }
            showmessage(L('operation_success'),HTTP_REFERER);
        }

        //都没有选择删除什么
        if(empty($_POST['id'])) {
            showmessage('请选择要删除的记录',HTTP_REFERER);
        }
    }
}








