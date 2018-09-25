<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/25
 * Time: 22:05
 *针对于swoole原生代码的一个封装
 */
namespace swooleFrame\frameTools;

class SwooleTool{

    //回调执行
    /*
     * task封装
     * task里面的代码必须是同步阻塞的，否则swoole不支持
     * task为什么可以用来做队列，那是因为里面自身就有队列的支持，task任务是队列执行的。     * $server,服务器对象
     * $callback,task里面的回调函数，以call_user_func形式调用的，
     * $successCallback，task函数执行成功的回调函数，以call_user_func形式调用的，
     *
     * 调用实例：
     * TaskWorkerTool::taskCallback($ws,__CLASS__.'::callback',__CLASS__.'::successCallback');
     */
    public static function taskCallback($server,$callback,$successCallback=''){
        //var_dump($callback);
        $data = [
            'callback'=>$callback,
            'successCallback'=>$successCallback,
        ];
        $server->task($data);
    }

}