<?php
/**
 * Created by PhpStorm.
 * User: 徐强
 * Date: 2019/4/25
 * Time: 14:56
 */
require_once "modelFactory.php";
//得到数据执行的SQL语句
class fxConfig
{
    function __construct()
    {
        $this->configModel = modelFactory::Create()->getModel("zyfxconfig");
        $this->config = $this->configModel->get_one(array("ID"=>1));
        $this->gradeTitle = modelFactory::Create()->getModel("zygradetitle");
    }
    function returnConfig($name)//返回具体的config配置
    {
        return $this->config[$name];
    }
    function returnAllConfig()
    {
        return $this->config;
    }
    function returnAllGrade()
    {
        return $this->gradeTitle->select("1");
    }
    function updateConfig($info)
    {
        $info["awardNumber"] = json_encode($info["awardNumber"]);
        $this->configModel->update($info, array("ID"=>1));
    }
    function updateGradetitle($info)
    {
        foreach($info as $key=>$value)
        {
            $this->gradeTitle->update($value, array("titleID"=>$key));
        }
    }


}