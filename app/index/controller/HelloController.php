<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 18:39
 */
namespace app\index\controller;


use swooleFrame\frameTools\FrameTool;

class HelloController{

    public function hello(){
//        $mem = FrameTool::getMemcache();
//        $mem->set('name','小白');
//        echo $mem->get('name');
        $redis = FrameTool::getRedis();
        $redis->set('redis','小白2');
        echo $redis->get('redis');

    }
}