<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/25
 * Time: 11:33
 */
namespace Decorator;

interface FxIn
{
    //public function func1(): int;这种写法只支持PHP7以上
    public function returnAwardMoney($_userid, $num, $data, $shoptype); //处理奖励函数
    public function returnAddChirdSQL($_userid, $type, $neadSymbol);
    public function returnWhereSQL($info);//处理条件语句


}