<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/25
 * Time: 11:44
 */
require_once "fxConfig.php";
require_once "/../Decorator/fxAwardExent.php";
require_once "/../Decorator/fxBeDec.php";
require_once "/../Extend/modelFactory.php";
use Decorator\fxBeDec;
use Decorator\fxAwardExent;//加载配置文件
pc_base::load_app_func('global');

//require_once "../Extend/modelFactory.php";
//require_once "../Extend/Res.php";
//PHP在命名空间内部访问全局类、函数和常量:https://blog.csdn.net/gtacer/article/details/72454482
//fxmember主函数
class Fx
{
    public function __construct()
    {
        $this->zyfxmember = modelFactory::Create()->getModel("zyfxmember");
        $this->fxBeDec= new fxBeDec();
        //$this->fxBeDec = new fxAwardExent($this->fxBeDec);
        $this->config = new fxConfig();
        $this->grade = intval($this->config->returnConfig("tier"));
    }
    //****************************************************************************************************************
    //数据操作代码
    public function getTeamID($_userid, $num, $data, $grade = "")
    {
        $grade = $grade == ""?$this->grade: $grade;
        $info = $this->zyfxmember->get_one($_userid, "userid, childID");
        $childID = array_pop($info);
        $data[$num][] = $info;
        if($num == ($grade == 0? $this->grade:$grade))
            return $data;
        $fatherId = string_Array($childID);
        foreach($fatherId as $row)
        {
            $return_data = $this->getTeamID(array("userid"=>$row), $num+1, array(0=>array(), 1=>array(), 2=>array(), 3=>array()), $grade);//递归取上一级数据
            for($i = ($grade == 0? $this->grade:$grade); $i >= $num; $i--)
            {
                $data[$i] = array_merge_recursive($data[$i], $return_data[$i]);
            }
        }
        return $data;
    }
    public function awardMoney($_userid, $shopPrice = 0)
    {
        try{
            $memberInfo = $this->getMemberInfo($_userid);
            if($memberInfo == null)
                Error("没有该用户信息");
            $fxMoney = modelFactory::Create()->getModel("zyfxmoney");
            for($i=1; $i<= $this->gride; $i++)
            {
                if($memberInfo["pid"] == 0)//如果没有pid的话break;
                    break;
                $memberInfo = $this->getMemberInfo(array('userid'=>$memberInfo["pid"]));
                if($memberInfo != null) //计算各个pid应该发放多少奖励
                {
                    $info = $this->fxBeDec->returnAwardMoney($memberInfo, $i, $shopPrice);//修改用户表的金额
                    $fxMoney->update($info, array("userid"=>$memberInfo['userid']));
//                $sql = "update `fxBack`.`zy_tx_table` set `WTXmoney`=WTXmoney+".$money.", `moneyCount`=WTXmoney+TXmoney where `userid`=".$memberInfo["userid"].";";
//                $this->tx_table->spcSql($sql);
                }
            }
        }
        catch (Exception $e)
        {
            return Res::Error($e->getMessage());
        }
        return Res::Success();

        // TODO: Implement func2() method.
    }
    public function addchild($_userid, $pid) //传入$_userid格式array("userid"=>()),$pid类似
    {
        try{
            $userData = $this->getMemberInfo($_userid);
            if($userData == null)
                Error("没有用户ID为".$_userid["userid"]."的信息");
            $pidData = $this->getMemberInfo(array("userid"=>$pid["pid"]));
            if($pidData == null)
                Error("没有用户ID为".$pid["pid"]."的信息");
            if($userData["pid"] != 0)
                Error("已经存在上一级");
            $this->zyfxmember->update($pid, $_userid);
            $info = $this->fxBeDec->returnAddChirdSQL(array("childID"=>$_userid["userid"]), "C=", ",");
            $this->zyfxmember->update($info, array("userid"=>$pid["pid"]));
        }
        catch (Exception $e)
        {
            return Res::Error($e->getMessage());
        }
        return Res::Success("添加成功");
    }
    public function getMemberInfo($_userid, $type="one")//获得用户信息
    {
        switch ($type)
        {
            case "one":$data = $this->zyfxmember->get_one($_userid);break;
            case "all":$data = $this->zyfxmember->select($_userid);break;
        }
        return $data;
    }
    public function insertMember($_userid)//添加新用户
    {
        try
        {
            //$member = modelFactory::Create()->getModel("zyfxmember");
            $money = modelFactory::Create()->getModel("zyfxmoney");
            $data = $this->zyfxmember->get_one($_userid);
            if($data !=null)
                Error("用户已存在");
            $money->insert($_userid);
            $_userid["addTime"] = time();
            $_userid["updateTime"] = time();
            $this->zyfxmember->insert($_userid);

        }
        catch(Exception $e)
        {
            return Res::Error($e->getMessage());
        }
        return Res::Success("添加成功");
    }
    public function TX($userid, $moneyCount)//得到单单的金额，并没有更新数据库
    {
        $money = modelFactory::Create()->getModel("zyfxmoney");
        try{
            $userInfo = $money->get_one($userid);
            if($userInfo == null)
                Error("没有该用户信息");
            if($userInfo["WTXmoney"] < $moneyCount)
                Error("您的金额不足");
            $TXmoney = $this->fxBeDec->returnTXAmount($moneyCount);
        }
        catch (Exception $e)
        {
            return Res::Error($e->getMessage());
        }
        return Res::Success($TXmoney);
    }
    public function TXAffirm($userid, $moneyCount)
    {
        $money = modelFactory::Create()->getModel("zyfxmoney");
        try{
            $userInfo = $money->get_one($userid);
            if($userInfo == null)
                Error("没有该用户信息");
            if($userInfo["WTXmoney"] < $moneyCount)
                Error("您的金额不足");
            $sql = $TXmoney["TXmoney"] = $this->fxBeDec->returnTXSQL($moneyCount);
            $money->update($sql, $userid);
        }
        catch (Exception $e)
        {
            return Res::Error($e->getMessage());
        }
        return Res::Success("成功");
    }
    public function updateLoginTime($info)
    {
        $this->zyfxmember->update(array("updateTime"=>time()), $info);
    }

    //****************************************************************************************************************
    //和后台的交互代码
    function returnMemberInfo($page, $pageSize, $info)
    {
        $where = $this->fxBeDec->returnWhereSQL($info);
        list($info, $count) = $this->zyfxmember->moreTableSelect(
            array("zy_zyfxmember"=>array("*"), "zy_zyfxgradetitle"=>array("*"), "zy_zyfxmoney"=>array("*"),"zy_member"=>array("username", "mobile")),
            array("titleID", "userid", "userid"),
            $where
            , ((string)($page-1)*$pageSize).",".$pageSize, "B1.userid ASC","1"
        );
        return array($info, $count);
    }
}