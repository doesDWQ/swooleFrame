<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 18:39
 */
namespace app\index\controller;


use app\appTools\AppTool;
use app\index\model\Hello;
use swooleFrame\frameTools\ObjectFactory;
use swooleFrame\frameTools\Request;

;
class TestController{

    public function hello(){
        echo '<pre>';
        ObjectFactory::getObj(Hello::class);
        var_dump(ObjectFactory::$arr);

    }

}
