<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
class zypoints extends admin {
    static $pageSize = 5;
	function __construct() {
		parent::__construct();
		$this->module_db = pc_base::load_model('module_model');
		$this->zygift_db = pc_base::load_model('zygift_model');
        $this->zygift_user_db = pc_base::load_model('zygift_user_model');
        $this->member_db = pc_base::load_model('member_model');
	}
	/*
	 * 商品管理
	 * */
	public function init()
	{
        $neadArg = ["giftname"=>[false,0]];
        $info = checkArg($neadArg);

        $where = "";

        if(!empty($info['giftname'])){
            $where .= "giftname like '%".$info['giftname']."%' AND ";
        }
        $where .= "1";


		$page = empty($_GET['page'])?1:intval($_GET['page']);
		$infos = $this->zygift_db->listinfo($where ,'id asc',$page,10);
		$pages = $this->zygift_db->pages;
		include $this->admin_tpl('zygift_init');
	}
    public function init_rec()
    {
        $neadArg = ["id"=>[false,0]];
        $info = checkArg($neadArg);

        $where = "";

        if(!empty($info['id'])){
            $where .= "B1.id =".$info['id']." AND ";
        }
        $where .= "1";

        $page = empty($_GET['page'])?1:intval($_GET['page']);
        //$infos = $this->zygift_user_db->listinfo($where ,'id asc',$page,10);
        //$pages = $this->zygift_user_db->pages;

        //$sql="SELECT u.id,u.userid,u.giftid,g.giftname,g.thumb,m.username,m.nickname,m.mobile,u.gettime FROM zy_zygift_user JOIN zy_zygift g ON g.id=u.giftid LEFT JOIN zy_member m ON u.userid=m.userid WHERE ".$where;
        //list($info, $count) = $this->zygift_user_db->spcSql($sql,1,1);
        list($infos,$count) = $this->member_db->moreTableSelect(array("zy_zygift_user"=>array("id","userid","giftid","gettime"), "zy_zygift"=>array("giftname","thumb"), "zy_member"=>array("username","nickname","mobile")),
            array("giftid"=>"id","userid"=>"userid"),
            $where,
            ((string)($page-1)*self::$pageSize).",".self::$pageSize, "B1.id ASC","1"
        );
        //$count = count($info);
        list($page, $pagenums) = getPage($page, self::$pageSize, $count);
        include $this->admin_tpl('zygift_user_init');
    }

	/*
	 * 添加商品信息
	 * */
	public function add()
	{
        if(isset($_POST["dosubmit"]))
        {
            //print_r($_POST);
            $neadArg = ["giftname"=>[true,0], "giftdes"=>[false, 0], "thumb"=>[false, 0], "giftpoint"=>[true, 0], "giftnum"=>[true, 0],"status"=>[true, 0]];
            $info = checkArg($neadArg,"POST");

            $this->zygift_db->insert($info);
            showmessage(L('operation_success'), '', '', 'add');
        }
        else
        {
            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");

            include $this->admin_tpl("zygift_add");
        }
	}


	/*
	 * 编辑商品信息
	 * */
	public function edit()
	{
        if(isset($_POST["dosubmit"]))
        {
            //print_r($_POST);
            $neadArg = ["id"=>[true,0],"giftname"=>[true,0], "giftdes"=>[false, 0], "thumb"=>[false, 0], "giftpoint"=>[true, 0], "giftnum"=>[true, 0],"status"=>[true, 0]];
            $info = checkArg($neadArg,"POST");
            $where["id"] = array_shift($info);

            $this->zygift_db->update($info,$where);
            showmessage(L('operation_success'), '', '', 'edit');
        }
        else
        {
            $info=$this->zygift_db->get_one(array('id'=>$_GET['id']));

            $upload_allowext = 'jpg|jpeg|gif|png|bmp';
            $isselectimage = '1';
            $images_width = '';
            $images_height = '';
            $watermark = '0';
            $authkey = upload_key("1,$upload_allowext,$isselectimage,$images_width,$images_height,$watermark");
            include $this->admin_tpl("zygift_edit");
        }
	}
	
	/*
	 * 删除银行卡信息
	 * */
	public function del(){
		$id = intval($_GET['id']);
		if($id){
            //删除没用商品
            $one=$this->zygift_db->get_one(array('id'=>$id));
            if(file_exists($one['qrcode'])) {
                unlink($one['qrcode']);
            }
			$result=$this->zygift_db->delete(array('id'=>$id));
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
                $one=$this->zygift_db->get_one(array('id'=>$id));
                if(file_exists($one['qrcode'])) {
                    unlink($one['qrcode']);
                }
				$result=$this->zygift_db->delete(array('id'=>$id));
			}
			showmessage(L('operation_success'),HTTP_REFERER);
		}

		//都没有选择删除什么
		if(empty($_POST['id'])){
			showmessage('请选择要删除的订单',HTTP_REFERER);
		}
	}

    /*
	 * 删除银行卡信息
	 * */
    public function del_user(){
        $id = intval($_GET['id']);
        if($id){
            //删除没用商品
            $one=$this->zygift_db->get_one(array('id'=>$id));
            if(file_exists($one['qrcode'])) {
                unlink($one['qrcode']);
            }
            $result=$this->zygift_user_db->delete(array('id'=>$id));
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
                $one=$this->zygift_db->get_one(array('id'=>$id));
                if(file_exists($one['qrcode'])) {
                    unlink($one['qrcode']);
                }
                $result=$this->zygift_user_db->delete(array('id'=>$id));
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
	 * 截取字符
	 * $string 输入字符串
	 * return 返回uploadfile之后的字符串
	 * */
    public function strget($string=''){
        $newstring= strstr( $string, '.'); //默认返回查找值@之后的尾部，@jb51.net
        return $newstring;
    }

}








