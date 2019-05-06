<?php

/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/2/20
 * Time: 11:09
 */
/*
 *
 */
require_once "/../functions/global.func.php";
class Res
{
    public $ok;
    public $info;
    function __construct($ok, $info = null)
    {
        $this->ok = $ok;
        $this->info = $info;
    }
    static function Success($info = null)
    {
        return new Res(true, $info);
    }
    static function Error($info)
    {
        return new Res(false, $info);
    }
    static function AssertOk(Res $res, $type="1")
    {
        if($res->ok == false)
        {
            switch ($type)
            {
                case "1":
                    showmessage($res->info);break;
                case "2":
                    returnAjaxData("-1", $res->info);
            }
        }
        else{
            if($res->info != null)
                return $res->info;
        }
    }
}