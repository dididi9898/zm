<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/25
 * Time: 14:12
 */
//工厂模式取model文件
interface model_List
{
    static function getModel($modelName);
}
class modelList implements model_List
{
    public function __construct()
    {
    }
    static function getModel($modelName)
    {
        switch ($modelName)
        {
            case "zyfxmember": return pc_base::load_model("zyfxmember_model");
            case "zyfxconfig": return pc_base::load_model("zyfxconfig_model");
            case "zygradetitle": return pc_base::load_model("zyfxgradetitle_model");
            case "zyfxmoney": return pc_base::load_model("zyfxmoney_model");
        }
        // TODO: Implement getModel() method.
    }
}
class modelFactory
{
    public static function Create()
    {
        return new modelList();
    }
}