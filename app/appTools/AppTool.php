<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/4
 * Time: 18:14
 * 主要功能用来获取模型对象，每个模型对象最好都在里面注册一遍
 */

namespace app\appTools;


use swooleFrame\frameTools\ObjectFactory;

class AppTool
{
    //获得模型操作对象
    public static function getModel($className){
        $class = 'app\\index\\model\\'.$className;
        return ObjectFactory::getObj($class);
    }
}