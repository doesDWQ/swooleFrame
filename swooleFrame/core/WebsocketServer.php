<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 12:26
 */

namespace swooleFrame\core;

use swooleFrame\frameTools\Error;
use swooleFrame\frameTools\Request;
use swooleFrame\frameTools\Response;

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
        //var_dump($request->fd, $request->get, $request->server);
        $ws->push($request->fd, "hello, welcome\n");
    }

    //监听WebSocket消息事件
    public static function message($ws, $frame){
        //echo "Message: {$frame->data}\n";
        //使用push对客户端推送消息
        $ws->push($frame->fd, "server: {$frame->data}");
    }

    //监听WebSocket连接关闭事件
    public static function close($ws, $fd){
        //echo "client-{$fd} is closed\n";
    }

    //不加上这个http回调，内置的http动态和静态服务器都是无法启动的
    public static function request($request, $response) {
        //初始化响应类
        Response::init($response);

        ob_start();

        //捕获用户级别错误
        set_error_handler('\swooleFrame\frameTools\Error::error_handler');
        set_exception_handler('\swooleFrame\frameTools\Error::error_handler');

        try{
            Request::init($request);
        }catch (\Error $e){
            //捕获错误
            Error::getError($e);
        }catch (\Exception $e){
            //捕获异常
            Error::getError($e);
        }


        //收集缓冲区内容
        $content = ob_get_contents();
        ob_clean();

        Response::returnContent($content);
    }

    //进程启动时的回调函数
    public static function WorkerStart($serv, $worker_id){
        //初始化加载框架配置
        include __DIR__.'/init.php';

        //初始化worker进程里面的操作
        Init::run();

    }

    //task任务执行的回调函数
    /*$server:当前服务器对象
     * $task_id：当前task进程id，和worker_id才能组成全局唯一task标识
     * $worker_id:当前task在哪个worker进程下面
     * $data:使用$server->task($data),投递进来的任务的数据
     */
    public static function task($server,$task_id,$worker_id,$data){
        $callback = $data['callback'];
        $ret = ['server'=>$server,'taskId'=>$task_id,'worker_id'=>$worker_id,'data'=>$data];
        call_user_func($callback,$ret);
        return $data;
    }

    /*
     * task进程执行结束后将结果投递给这个回调函数
     * $server:当前服务器对象
     * $task_id：当前task进程id，和worker_id才能组成全局唯一task标识
     * $data:task进程return出来的数据
     */
    public static function finish($server,$task_id,$data){
        $successCallback = $data['successCallback'];
        if($successCallback!==''){
            $ret = [
                'server'=>$server,
                'task_id'=>$task_id,
                'data'=>$data,
            ];
            //只有传入了回调函数的时候才使用成功回调
            call_user_func($successCallback,$ret);
        }
    }

    /*
     * 设置主进程名称，用来平滑重启所有的子进程
     */
    public static function start($server){
        swoole_set_process_name('swoole_websocket');
    }

}