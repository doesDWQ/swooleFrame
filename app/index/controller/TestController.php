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
          echo 'hello it me 你好吗,我喜欢你,tts aa tt';
    }

    public function restart(){
        Init::restart();
        echo '重启成功';
    }

}
