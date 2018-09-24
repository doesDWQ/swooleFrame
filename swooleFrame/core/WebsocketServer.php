<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 12:26
 */

namespace swooleFrame\core;
include __DIR__.'/SwooleServer.php';
class WebsocketServer extends SwooleServer {

    public static function init($config)
    {
        // TODO: Implement init() method.
        //返回对象
        return new \swoole_websocket_server($config['ip'],$config['port']);
    }

    //监听WebSocket连接打开事件
    public static function open($ws, $request){
        var_dump($request->fd, $request->get, $request->server);
        $ws->push($request->fd, "hello, welcome\n");
    }

    //监听WebSocket消息事件
    public static function message($ws, $frame){
        echo "Message: {$frame->data}\n";
        $ws->push($frame->fd, "server: {$frame->data}");
    }

    //监听WebSocket连接关闭事件
    public static function close($ws, $fd){
        echo "client-{$fd} is closed\n";
    }

    //不加上这个http回调，内置的http动态和静态服务器都是无法启动的
    public static function request($request, $response) {
        var_dump($request->get, $request->post);
        $response->header("Content-Type", "text/html; charset=utf-8");
        $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
    }

    //进程启动时的回调函数
    public static function WorkerStart($serv, $worker_id){
        //初始化加载框架配置
        include __DIR__.'/init.php';
    }

}