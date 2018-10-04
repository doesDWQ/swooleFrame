<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 18:39
 */
namespace app\index\controller;


use swooleFrame\core\Init;
use swooleFrame\frameTools\FrameTool;
use swooleFrame\frameTools\Request;

class TestController{

    public function hello(){
//        $mem = FrameTool::getMemcache();
//        $mem->set('name','小白');
//        echo $mem->get('name');
//        $redis = FrameTool::getRedis();
//        $redis->set('redis','小白2');
//        echo $redis->get('redis');
          echo 'hello it me 你好吗';
    }

    public function restart(){
        Init::restart();
        echo '重启成功';
    }
}