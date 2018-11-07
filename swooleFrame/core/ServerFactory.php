<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 12:31
 */
namespace swooleFrame\core;

class ServerFactory{
    private $server = null;
    public static $serverName = '';

    public function __construct($serverName,$config)
    {
        self::$serverName = $serverName;
        //引入对应的类
        include __DIR__."/{$serverName}.php";

        //必须要加上这么一句，不然php解释器会报错
        $serverName = "\\swooleFrame\\core\\".$serverName;

        //获得初始化的server对象
        $this->server = $serverName::init($config['server_address']);

        //初始化server里面所有要的配置参数
        $this->server->set($config['set']);

        //设置里面的回调
        $methods = get_class_methods($serverName);
        foreach ($methods as $method){
            if($method==='init'){
                //排除init方法
                continue;
            }
            $this->server->on($method,[$serverName,$method]);
        }

        //将服务器跑起来
        $this->server->start();
    }
}