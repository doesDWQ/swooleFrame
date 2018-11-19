<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 15:55
 */
//这个命名空间是有必要加上，可以减少一些以后可能产生的麻烦
namespace swooleFrame\core;

use swooleFrame\frameTools\ObjectFactory;

class Init{
    public static $server = null;
    //定义各种目录常量
    public static function defineConstant(){
        define('ROOT',__DIR__.'/../..');    //项目根目录
        define('APP',ROOT.'/app');          //应用根目录
        define('SWOOLEFRAME',ROOT.'/swooleFrame');     //框架主要代码根目录
    }

    //启动自动注册机制
    public static function autoLoad(){
        //引入自动注册类
        include SWOOLEFRAME.'/core/AutoLoad.php';

        //启动自动注册机制
        AutoLoad::Load();
    }


    public static function run($server){
        self::defineConstant();
        self::autoLoad();
        self::$server = $server;
    }

    //平滑重启所有的进程
    public static function restart(){
        self::$server->reload();
    }

}
