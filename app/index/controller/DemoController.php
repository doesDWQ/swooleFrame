<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/11/19
 * Time: 21:39
 */
namespace app\index\controller;

use swooleFrame\frameTools\Response;

class DemoController{
    public function hello(){
//        echo 'aaa';
        //Response::returnJson(['a'=>'hello']);
        echo 'hello';
    }
}