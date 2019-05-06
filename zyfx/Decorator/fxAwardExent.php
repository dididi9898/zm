<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/26
 * Time: 17:13
 */

namespace Decorator;
require_once "fxAbs.php";


class fxAwardExent extends fxAbs//装饰器，装饰fxBeDec函数
{
    function __construct(FxIn $fx)
    {
        parent::__construct($fx);
    }

    public function returnAwardMoney($_userid, $num, $shopPrice)
    {
        $data = $this->fx->returnAwardMoney($_userid, $num, $shopPrice);
        $data["WTXmoney"] .= "+ 400";
        return $data;
    }
    function returnAddChirdSQL($info, $type="", $neadSymbol="") //返回添加子类的SQL语句 .现在不可装饰，以后再添加装饰
    {
        return $this->fx->returnAddChirdSQL($info, $type, $neadSymbol);
    }
    function returnWhereSQL($info)
    {
        return $this->fx->returnWhereSQL($info);
    }

}

