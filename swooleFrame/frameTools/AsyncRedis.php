<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/28
 * Time: 23:58
 */

namespace swooleFrame\frameTools;
class AsyncRedis{
    private static $server = null;
    private static $obj = null;

    private function __construct($config)
    {
        self::$server = new \Swoole\Coroutine\Redis();
        self::$server->connect($config['host'], $config['port']);
    }

    public static function getObj($config){
        if(self::$obj!==null){
            self::$obj = new self($config);
        }
        return self::$obj;
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $str = implode(',',$arguments);

        return self::$server->$name($str);
    }
}