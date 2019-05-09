<?php

/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/26
 * Time: 10:51
 */
require_once "Extend/fxMember.php";
require_once "Extend/Res.php";
require_once "Extend/fxConfig.php";
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
//Api函数(返回数据暂时没处理)
class index
{
    function __construct()
    {
        $this->member = new Fx();
        $this->_userid = param::get_cookie('_userid');
    }

    //我的下级
    public function subordinate()
    {
        $_userid = $this->_userid;
        include template('zyfx','subordinate');
    }

    //我的下级
    public function yj_detail()
    {
        $_userid = $this->_userid;
        include template('zyfx','yj_detail');
    }
}