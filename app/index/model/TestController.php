<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 18:39
 */
namespace app\index\model;


use app\appTools\AppTool;
use App\Controllers\Test\TestBaseController;
use app\index\model\Hello;

class TestController{

    public function hello(){
        echo TestController::class;
    }

}
