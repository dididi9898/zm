<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
class zyshop extends admin {
	function __construct() {
		parent::__construct();
		$this->module_db = pc_base::load_model('module_model');
		$this->zyshop_db = pc_base::load_model('zyshop_model');
        $this->zyshoptype_db = pc_base::load_model('zyshoptype_model');
	}
    static $shopLabel = array("shopname"=>"", "sketch"=>"","typeID"=>"","price"=>"", "repertory"=>"","putaway"=>"","thumb"=>"", "content"=>"", "infopicture"=>"", "addtime"=>"", "sort"=>"", "unspecification"=>"","specification"=>"", "shoppicture"=>"");
    //shopname商品名，sketch商品简述，typeID商品类型，price价格，repertory库存，putaway是否上架，thumb缩略图，content详细时间，picture详细图片地址，addtime添加时间，sort商品排序，unspecification未处理的商品规格
	/*
	 * 商品管理
	 * */
	public function init()
	{
        $page = isset($_GET["page"])?intval($_GET["page"]): 1;
        $sql = "select B1.*, B2.typename from `zy_zyshop`B1 left join `zy_zyshoptype` B2 ON B1.typeID=B2.typeID WHERE ";

        $neadArg = ["shopname"=>[false,0], "start_addtime"=>[false, 2], "end_addtime"=>[false, 2], "type"=>[false, 0], "status"=>[false, 0]];
        $info = checkArg($neadArg);

        $where = "";

        if(!empty($info['shopname'])){
            $where .= "B1.shopname like '%".$info['shopname']."%' AND ";
        }
        if(!empty($info['start_addtime']))
            $where .= "B1.addtime > '".$info['start_addtime']."' AND ";
        if(!empty($info['end_addtime']))
            $where .= "B1.addtime < '".$info['end_addtime']."' AND ";
        if(!empty($type))
            $where .= "B1.typeID = '$type' AND ";
        if(!empty($info['status']) || $info['status']=='0')
            $where .= "B1.putaway = ".$info['status']." AND ";
        $where .= "1";

		$page = empty($_GET['page'])?1:intval($_GET['page']);
		//$infos = $this->zyshop_db->listinfo( $where,'shopid asc',$page,10);
		$infos = $this->zyshop_db->spcSql($sql.$where,1,1);
		$pages = $this->zyshop_db->pages;
		include $this->admin_tpl('zyshop_init');
	}

	/*
	 * 添加商品信息
	 * */
	public function add()
	{
        if(isset($_POST["dosubmit"]))
        {
            $pic = "";
            $shopPic = "";
            $_POST['info']["unspecification"] = $specification = str_replace("，",",", $_POST['info']["unspecification"]);
            foreach($_POST["info"] as $key=>$value)
            {
                if(array_key_exists($key, self::$shopLabel))
                    self::$shopLabel[$key] = $value;
            }
            if(count($_POST["pictureurls_url"])!= count($_POST["pictureurls_alt"]))
                showmessage("操作失败", '', '', 'add');
            if(count($_POST["shopPictureurls_url"])!= count($_POST["shopPictureurls_alt"]))
                showmessage("操作失败", '', '', 'add');
            if(isset($_POST["pictureurls_url"]))
            {
                foreach($_POST["pictureurls_url"] as $key=>$value)
                {
                    $pic .= $_POST["pictureurls_alt"][$key]."|";
                    $pic .= $value.",";
                }
                self::$shopLabel["infopicture"] = $pic;

            }
            if(isset($_POST["shopPictureurls_url"]))
            {
                foreach($_POST["shopPictureurls_url"] as $key=>$value)
                {
                    $shopPic .= $_POST["shopPictureurls_alt"][$key]."|";
                    $shopPic .= $value.",";
                }
                self::$shopLabel["shoppicture"] = $shopPic;
            }
            self::$shopLabel["addtime"] = time();
            self::$shopLabel["specification"] = json_encode(explode(",",$specification),JSON_UNESCAPED_UNICODE);
            $this->zyshop_db->insert(self::$shopLabel);
            showmessage(L('operation_success'), '', '', 'add');
        }
        else
        {
            $type = $this->zyshoptype_db->select(array("isshow"=>1));
            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            $authkey_1 = upload_key("10,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            include $this->admin_tpl("addshop");
        }
	}
    public function adds(){
        showmessage('已操作','','','add');
    }

	/*
	 * 编辑商品信息
	 * */
	public function edit()
	{
        if($_POST["dosubmit"])
        {
            $shopID = $_POST["shopID"];
            $pic = "";
            $shopPic = "";
            $_POST["info"]["unspecification"] = $specification = str_replace("，",",", $_POST["info"]["unspecification"]);
            foreach($_POST["info"] as $key=>$value)
            {
                if(array_key_exists($key, self::$shopLabel))
                    self::$shopLabel[$key] = $value;
            }
            if(count($_POST["pictureurls_url"])!= count($_POST["pictureurls_alt"]))
                showmessage("操作失败", '', '', 'edit');
            if(count($_POST["shopPictureurls_url"])!= count($_POST["shopPictureurls_alt"]))
                showmessage("操作失败", '', '', 'edit');
            if(isset($_POST["pictureurls_url"]))
            {
                foreach($_POST["pictureurls_url"] as $key=>$value)
                {
                    $pic .= $_POST["pictureurls_alt"][$key]."|";
                    $pic .= $value.",";
                }
                self::$shopLabel["infopicture"] = $pic;
            }
            if(isset($_POST["shopPictureurls_url"]))
            {
                foreach($_POST["shopPictureurls_url"] as $key=>$value)
                {
                    $shopPic .= $_POST["shopPictureurls_alt"][$key]."|";
                    $shopPic .= $value.",";
                }
                self::$shopLabel["shoppicture"] = $shopPic;
            }
            self::$shopLabel["specification"] = json_encode(explode(",",$specification),JSON_UNESCAPED_UNICODE);
            $this->zyshop_db->update(self::$shopLabel, array("shopID"=>$shopID));
            showmessage(L('operation_success'), '', '', 'edit');
        }
        else
        {
            $id = isset($_GET["id"])?$_GET["id"]:"1";
            $info = $this->zyshop_db->spcSql("select * from `zy_zyshop` where `shopID`=".$id.";", 1);
            $shopType= $this->zyshoptype_db->select("1");
            $picture = string_array($info["infopicture"]);
            $picture = pictury_array($picture);
            $shopPicture = string_array($info["shoppicture"]);
            $shopPicture = pictury_array($shopPicture);
            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            $authkey_1 = upload_key("10,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            include $this->admin_tpl("editshop");
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
            //删除没用商品
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
                //删除没用商品
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
     * 更新商品
     * */
    //生成原始的商品(生成图片文件)
    public function create_qrcode($url=''){
        //require_once 'QRcode.class.php';
        $value = $url;                //商品内容
        $errorCorrectionLevel = 'L';  //容错级别
        $matrixPointSize = 5;         //生成图片大小
        //生成商品图片
        $this->mkdirs("uploadfile/qrcode/");
        $filename = 'uploadfile/qrcode/'.time().'.png';
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    // 在生成的商品中加上logo(生成图片文件)
    function create_qrcode_pic($url='',$logo_path=''){
        //require_once 'phpqrcode.php';
        $value = $url;         //商品内容
        $errorCorrectionLevel = 'H';  //容错级别
        $matrixPointSize = 6;      //生成图片大小
        //生成商品图片
        $this->mkdirs("uploadfile/qrcode/");
        $filename = 'uploadfile/qrcode/'.time().'.png';
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = $this->strget($logo_path); //准备好的logo图片
        //$QR=$filename;//已经生成的原始商品图
        if (file_exists($logo)) {
            $QR = imagecreatefromstring(file_get_contents($filename));    //目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo));  //源图象连接资源。
            $QR_width = imagesx($QR);      //商品图片宽度
            $QR_height = imagesy($QR);     //商品图片高度
            $logo_width = imagesx($logo);    //logo图片宽度
            $logo_height = imagesy($logo);   //logo图片高度
            $logo_qr_width = $QR_width / 4;   //组合之后logo的宽度(占商品的1/5)
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
            'javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=zyshop&c=zyshop&a=configadd\', title:\'添加配置\', width:\'700\', height:\'200\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function()   {window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加配置'
        );
        $order = 'id DESC';
        $where = array("item_name"=>'zyshop');
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
            $num = $this->zyconfig_db->count(array('item_name'=>"zyshop"))+1;
            $car=array
            (
                'config_name'=>$_POST['config_name'],
                'model_name'=>$_POST['model_name'],
                'item_name'=>"zyshop",
                "key"=>"zyshop".$num,
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








