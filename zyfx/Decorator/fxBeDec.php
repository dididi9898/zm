<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/26
 * Time: 20:22
 */

namespace Decorator;
require_once "fxInf.php";
require_once "/../Extend/fxConfig.php";

class fxBeDec implements FxIn//被装饰函数
{
    function __construct()
    {
        $this->config = new \fxConfig();
    }

    function returnAwardMoney($memberInfo, $i, $shopPrice, $shopType="0") //返回需要添加的金额.可装饰
    {

        $awardMoney = $shopType== "0"?json_decode($this->config->returnConfig("awardNumber"), true):json_decode($this->config->returnConfig("trialAwardNumber"), true);
        $awardType = $this->config->returnConfig("awardType");
        if ($awardType == '1')
            $info["WTXmoney"] = "+=".$awardMoney[$i];
        else
            $info["WTXmoney"] = "+=".$awardMoney[$i] * $shopPrice;
        $info["moneyCount"] = "DJ WTXmoney+TXmoney";
        return $info;
    }
    function returnAddChirdSQL($info, $type="", $neadSymbol="") //返回添加子类的SQL语句 .现在不可装饰，以后再添加装饰
    {
        $data = [];
        foreach($info as $key=>$value)
            $data[$key] = $type.$value.$neadSymbol;
        return $data;
    }
    function returnWhereSQL($info)
    {
        $where = "1 ";
        foreach($info as $key=>$value)
        {
            if(strpos($key, "name"))
                $where .= " AND `".$key."` like '%".$value."%'";
            elseif($key == "start_addtime")
                $where .= "AND `addTime` > '$value'  ";
            elseif($key == "end_addtime")
                $where .= "AND `addTime` < '$value'  ";
            else
                $where .= "AND `".$key."` = '$value'  ";
        }
        return $where;
    }
    function returnTXAmount($money)
    {
        $TXchange = intval($this->config->returnConfig("TXcharge"));
        $serviceChange = ($TXchange/100)*$money;
        return array("serviceCharge"=>$serviceChange, "money"=>(1-$TXchange/100)*$money);
    }
    function returnTXSQL($money)
    {
        $TXchange = intval($this->config->returnConfig("TXcharge"));
        $money = $money/(1-$TXchange/100);
        $info["WTXmoney"] = "-=".$money;
        $info["TXmoney"] = "+=".$money;
        $info["moneycount"] = "DJWTXmoney+TXmoney";
        return $info;
    }
}
