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
        //app下面所有的php文件都需要被自动载入
        __DIR__.'/../../app',
    ],


    //MySQL配置
    'mysql'=>[
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'root',
        'database' => 'swoole',
        'charset' => 'utf8', //指定字符集
    ],

    //redis配置
    'redis'=>[
        'host' => '127.0.0.1',
        'port' => 6379,
    ],

    //memcache配置
    'memcache'=>[
        'host' => '127.0.0.1',
        'port' => 11211,
    ],

];