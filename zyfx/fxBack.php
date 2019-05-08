<?php

/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/25
 * Time: 11:32
 */
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');
require_once "Extend/fxConfig.php";
require_once "Extend/fxMember.php";
require_once "Extend/Res.php";
class fxBack extends admin
{
    static $pageSize = 10;
    function __construct()
    {
        parent::__construct();
        $this->config = new fxConfig();
        $this->fxMember = new Fx();
    }

    function check_cookie()
    {
        $_userid = param::get_cookie("_userid");
        if (!$_userid) showmessage(L('login_website'), APP_PATH . 'index.php?m=member&c=index&a=login&forward=' . urlencode($_SERVER["REQUEST_URI"]));
        return $_userid;
    }

    function init()
    {

    }
    function config()//拿到配置文件，暂时没时间更新配置，JS代码还在改善
    {
        if($_POST["tier"])
        {
            $neadArg = ["tier"=>[true, 0], "awardType"=>[true, 0], "TXcharge"=>[true, 1],"awardNumber"=>[true, 0], "trialAwardNumber"=>[true, 0],"gradeTitleType"=>[true, 0], "gradeNumber"=>[true, 0]];
            $info = checkArgBcak($neadArg, "POST");
            if($info["TXcharge"] > 100 || $info["TXcharge"] < 0)
                showmessage("提现手续费错误","index.php?m=zyfx&c=fxBack&a=config", "2000");
            $neadArg = ["titleID"=>[true, 0]];
            $gradeTitleInfo = checkArgBcak($neadArg, "POST");
            $this->config->updateConfig($info);
            $this->config->updateGradetitle($gradeTitleInfo["titleID"]);
            showmessage("更新成功", "index.php?m=zyfx&c=fxBack&a=config", "1000");
        }
        else
        {
            $info = $this->config->returnAllConfig();
            $grideInfo = $this->config->returnAllGrade();
            $unifyTitle = array_shift($grideInfo);
            $info["awardNumber"] = json_decode($info["awardNumber"], true);
            $info["trialAwardNumber"] = json_decode($info["trialAwardNumber"], true);
            include $this->admin_tpl('conf');
        }
    }
    function member()//后台用户表数据
    {
        $neadArg = ["mobile" => [false, 0], "username"=>[false, 0], "start_addtime"=>[false, 2], "end_addtime"=>[false, 2], "page"=>[false, 0]];
        $info = checkArgBcak($neadArg);
        $page = isset($info["page"])?array_pop($info):1;
        list($data, $count) = $this->fxMember->returnMemberInfo($page, self::$pageSize, $info);
        list($page, $pagenums) = getPage($page, self::$pageSize, $count);
        include $this->admin_tpl("FXmember/capitaldetails");
    }
    function memberView()
    {
        $neadArg = ["userid" =>[true, 1]];
        $info = checkArgBcak($neadArg);
        $member = $this->fxMember->getMemberInfo($info);
        include $this->admin_tpl("FXmember/memberView");
    }
    function teamView()
    {
        $neadArg = ["userid" =>[true, 1]];
        $info = checkArgBcak($neadArg);
        $data = array(0=>array(), 1=>array(), 2=>array(), 3=>array());
        $data = $this->fxMember->getTeamID($info, 0, $data);
        array_shift($data);//删除自己的信息
        include $this->admin_tpl("FXmember/teamView");
    }
}