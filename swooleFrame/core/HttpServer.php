<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 12:26
 */
namespace swooleFrame\core;
include __DIR__.'/SwooleServer.php';
class HttpServer extends SwooleServer{

    public static function init($config)
    {
        // TODO: Implement init() method.
        return new \swoole_http_server($config['ip'],$config['port']);
    }

    //不加上这个http回调，内置的http动态和静态服务器都是无法启动的
    public static function request($request, $response) {
        var_dump($request->get, $request->post);
        $response->header("Content-Type", "text/html; charset=utf-8");
        $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
    }

}