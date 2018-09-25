<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 19:15
 * 主配置文件
 */

return [
    //需要被自动注册的文件夹的数组
    'autoDirs'=>[
        //框架目录下面的自动载入类
        __DIR__ . '/../frameTools',
        //app目录下面的自动载入类
        APP.'/appTools',
    ],


];