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

    //获得memcache操作对象
    public static function getMemcache(){
        return ObjectFactory::getObj('swooleFrame\frameTools\Mem');
    }

    //获得Redis操作对象
    public static function getRedis(){
        return ObjectFactory::getObj('swooleFrame\frameTools\Red');
    }

    //获得mysql操作类
    public static function getMysql(){
        return ObjectFactory::getObj('swooleFrame\frameTools\Mysql');
    }

    //获得操作对象
    public static function getObj($className){
        $class = 'swooleFrame\\frameTools\\'.$className;
        return ObjectFactory::getObj($class);
    }
}