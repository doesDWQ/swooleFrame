<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 19:15
 * 主配置文件
 */

return [
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

    //日志文件的位置
    'logDir'=>__DIR__.'/../../logs',
];