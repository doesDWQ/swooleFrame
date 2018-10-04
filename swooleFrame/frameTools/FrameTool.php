<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/27
 * Time: 22:05
 */
namespace swooleFrame\frameTools;

class FrameTool{

    //自动加载配置文件的助手函数
    public static function getConfig($fileName,$keyName){
        $config = include __DIR__.'/../config/'.$fileName;
        $needConfig = isset($config[$keyName])?$config[$keyName]:false;
        return $needConfig;
    }



}