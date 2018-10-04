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
    private static $arr = [];

    //获取对象的方法
    public static function getObj($className){

        if(!isset(self::$arr[$className])){
            $className = "\\".$className;
            self::$arr[$className] = new $className();
        }

        return self::$arr[$className];
    }

}