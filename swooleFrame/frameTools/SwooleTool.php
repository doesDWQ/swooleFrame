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
     * task里面的代码必须是同步的，但是可以使用异步定时器和延时器
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

    /*
     * 异步毫秒定时器
     */
    public static function setTimerTick($millisecond,$functionName){
        //注意这里面虽然没有主动传入id，但里面回调的时候会主动传入timer_id
        $timer_id = \swoole_timer_tick($millisecond,function ($timer_id) use($functionName){
            call_user_func($functionName,['timerId'=>$timer_id]);
        });
        return $timer_id;
    }

    //----------------------------需要测试的代码------------------------------
    /*
     * 异步毫秒延时器，这个和sleep不一样，sleep会阻塞进程执行
     */
    public static function setTimerAfter($millisecond,$functionName){
        //注意这里面虽然没有主动传入id，但里面回调的时候会主动传入timer_id
        $timer_id = \swoole_timer_after($millisecond,function () use($functionName){
            call_user_func($functionName);
        });
        return $timer_id;
    }

    /*
     * 清除定时器和延时器的函数
     */
    public static function clearTimer($timer_id){
        return \swoole_timer_clear($timer_id);
    }


    /*
     * 异步文件读取函数，文件大小不能超过4M
     *
     */
    public static function asyncReadFile($fileName,$functionName){
        \swoole_async_readfile( $fileName, function ($filename, $content) use($functionName){
            call_user_func($functionName,['fileName'=>$filename,'content'=>$content]);
        });
    }

    /*
     * 异步文件写入函数，文件大小不能超过4M
     * $fileName:文件名，
     * $content：写入的字符串
     * $functionName:读取成功后的回调函数
     * $flag:为FILE_APPEND标识追加，默认为0标识覆盖写
     */
    public static function asyncWriteFile($fileName,$content,$functionName='',$flag = 0){
        \sswoole_async_writefile($fileName, $content, function($filename) use($functionName){
            $data = [
                'fileName'=>$filename,
            ];
            if($functionName!==''){
                call_user_func($functionName,$data);
            }
        }, $flag);
    }

    /*
     * 异步读取超大文件
     * $filename, 文件名
     * $callback, 指定的这一段读取成功的回调处理
     * $size = 8192, 一次读取的字节数，默认4M
     * $offset = 0，开始读取的地方
     */
    public static function asyncReadFileBig($filename, $callback, $size = 8192, $offset = 0){

        /*
         * $fileName:文件名，
         * $content:读取出来的内容
         */
        return \swoole_async_read($filename, function ($fileName,$content) use($callback){
            call_user_func($callback,['fileName'=>$fileName,'content'=>$content]);
        }, $size, $offset);
    }

    /*
     * 异步写入超大文件
     * $filename:要写入的文件名
     * $content:要写入的内容，
     * $offset：默认值-1表示写入末尾，如果是具体的数字就表示写到具体的位置处
     * $callback：写入成功的回调
     */
    public static function asyncWriteBig($filename,$content,$offset = -1,$callback = ''){
        return \swoole_async_write($filename,$content,$offset,function () use($callback){
            if($callback!==''){
                call_user_func($callback);
            }
        });
    }

    /*
     * 异步mysql
     */
    public static function asyncMysql($config,$sql,$successFun){
        $db = new \swoole_mysql();

        $db->connect($config, function ($db, $r) use($sql,$successFun){
            if ($r === false) {
                var_dump($db->connect_errno, $db->connect_error);
                die;
            }

            $db->query($sql, function($db, $r) use($successFun){
                if ($r === false)
                {
                    var_dump($db->error, $db->errno);
                }
                elseif ($r === true )
                {
                    var_dump($db->affected_rows, $db->insert_id);
                }
                call_user_func_array($successFun,$r);
                $db->close();
            });
        });
    }

    /*
     * 异步redis的操作底层调用__call()
     * 异步redis，需要重新编译swoole扩展，并且添加上这个hiredis扩展
     * 详情见官网
     * 对于redis的资源无法被序列化，所有无法传递到回调函数里面(重点注意)
     */
    public static function asyncRedisSet($config,$key,$value){
        $client = new \swoole_redis;
        $client->connect($config['host'], $config['port'], function ($client, $result) use($key,$value){
            if ($result === false) {
                echo "connect to redis server failed.\n";
                return;
            }

            //成功的回调不能少，否则回报错
            $client->set($key,$value, function ($client, $result) {
                //var_dump($result);
            });
        });
    }

    /*
     * 协程有些东西需要加上参数重新编译redis
     * 协程和异步不能同时使用
     */
    public static function cronRedis($config,$key){
        $redis = new \Swoole\Coroutine\Redis();
        $redis->connect($config['host'], $config['port']);
        return $redis->get($key);
    }

    /*
     * 协程mysql,这些东东都需要封装成单独的类的。因为这些对象需要被反复的使用到
     */
    public static function cronMysql($config,$sql,$retFun){
        $swoole_mysql = new \Swoole\Coroutine\MySQL();
        $swoole_mysql->connect($config);
        $res = $swoole_mysql->query($sql);
        call_user_func($retFun,['ret'=>$res]);
    }




}