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
class frontApi
{
    function __construct()
    {
        $this->member = new Fx();
    }
    function insertMember()//创建用户的时候插入分销用户表和提现中，
    {
        $neadArg = ["userid"=>[true, 1]];
        $info = checkArg($neadArg);
        Res::AssertOk($this->member->insertMember($info), "2");
        returnAjaxData("1", "添加成功");
    }
    function updateMemberLoginTime()
    {
        $neadArg = ["userid"=>[true, 1]];
        $info = checkArg($neadArg);
        $this->member->updateLoginTime($info);
        returnAjaxData("1", "修改成功");

    }
    function getMemberInfo()//得到用户所有信息
    {
        $neadArg = ["userid"=>[true, 1]];
        $info = checkArg($neadArg);
        $s = $this->member->getMemberInfo($info);
        if($s == null)
            returnAjaxData("-1", "没有该用户信息");
        returnAjaxData("1", "成功", $s);
    }
    function getTeamInfo()//得到所有下级队员的信息
    {
        $neadArg = ["userid"=>[true, 1], "grade"=>[false, 1]];
        $info = checkArg($neadArg);
        $grade = isset($info['grade'])?array_pop($info):0;
        $s = $this->member->getMemberInfo($info);
        if($s == null)
            returnAjaxData("-1", "没有该用户信息");
        $data = array(0=>array(), 1=>array(), 2=>array(), 3=>array());
        $s = $this->member->getTeamID($info, 0, $data, $grade);
        returnAjaxData("1", "成功", $s);
    }
    function addchild()//添加下级队员
    {
        $neadArg = ["userid"=>[true, 1], "pid"=>[true, 1]];
        $info = checkArg($neadArg);
        $userid["userid"] = $info["userid"];
        $pid["pid"] = $info["pid"];
        if($userid["userid"] == $pid["pid"])
            returnAjaxData("-1", "无法添加自己为上级");
        Res::AssertOk($this->member->addchild($userid, $pid) ,"2");
        returnAjaxData("1", "添加成功");
    }
    function awardMoney()//奖励钱****
    {
        $neadArg = ["userid"=>[true, 1], "shopprice"=>[false, 1]];
        $info = checkArg($neadArg);
        $shopprice = isset($info["shopprice"])?array_pop($info):0;
        Res::AssertOk($this->member->awardMoney($info,$shopprice),"2");
        returnAjaxData("1", "奖励成功");
    }
    function TX()
    {
        $neadArg = ["userid"=>[true, 1], "money"=>[true, 1]];
        $info = checkArg($neadArg);
        $money = array_pop($info);
        $TXmoney = Res::AssertOk($this->member->TX($info, $money), "2");
        returnAjaxData("1", "得到金额成功", $TXmoney);
    }
    function TXAffirm()
    {
        $neadArg = ["userid"=>[true, 1], "money"=>[true, 0]];
        $info = checkArg($neadArg);
        $money = array_pop($info);
        Res::AssertOk($this->member->TXAffirm( $info, $money),"2");
        returnAjaxData("1", "提现成功");
    }
}