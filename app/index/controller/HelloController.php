<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 18:39
 */
namespace app\index\controller;

use swooleFrame\frameTools\ObjectFactory;
use swooleFrame\frameTools\Request;

class HelloController{

    public function hello(){
        $mUser = ObjectFactory::getObj('app\index\model\Hello');
        echo 'nihao吗ddd';
        $mUser->setData(Request::get());
        $mUser->save();

    }
}