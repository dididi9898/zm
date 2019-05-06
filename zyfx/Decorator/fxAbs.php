<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/25
 * Time: 11:36
 */

namespace Decorator;
require_once "fxInf.php";

abstract class fxAbs implements FxIn
{
    protected  $fx;
    public function __construct(FxIn $fx)
    {
        $this->fx = $fx;
    }
}