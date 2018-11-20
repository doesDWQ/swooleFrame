<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 18:50
 * 获取到指定的对象，对象工厂
 */

namespace swooleFrame\frameTools;

class ObjectFactory
{
    //临时对象,需要每次访问完毕都初始化的
    public static $arr = null;
    //常驻内存的对象,一般为资源对象,如mysql或者redis连接对象,小心内存溢出
    private static $resource = [];
    private static $resourceCnt = 0;

    public static function init(){
        self::$arr = [];
    }

    public static function getObj($className){
        if(!isset(self::$arr[$className])){
            $className_total = '\\'.$className;
            self::$arr[$className] = new $className_total();
        }

        return self::$arr[$className];
    }

    public static function getResources($className){
        if(!isset(self::$resource[$className])){
            if(self::$resourceCnt>9){
                throw new \Exception('资源对象不能超过10个,容易内存溢出!');
            }
            self::$resourceCnt++;
            $className_total = '\\'.$className;
            self::$resource[$className] = new $className_total();
        }
        return self::$resource[$className];
    }

}