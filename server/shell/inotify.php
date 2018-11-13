<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/11/11
 * Time: 10:26
 */

class Inotify{

    //只监视文件被修改的时候
    public static $events = [
        //文件被访问了
        #IN_ACCESS => 'File Accessed',

        //文件被修改了
        IN_MODIFY => 'File Modified',

        //文件的元数据发送改变（如权限、修改时间)
        #IN_ATTRIB => 'File Metadata Modified',

        //文件被打开用于写，被关闭了
        //IN_CLOSE_WRITE => 'File Closed, Opened for Writing',

        //文件被打开，不用于写，被关闭了
        //IN_CLOSE_NOWRITE => 'File Closed, Opened for Read',

        //文件打开了
        //IN_OPEN => 'File Opened',

        //文件移动到被监控的目录
        //IN_MOVED_TO => 'File Moved In',

        //文件被移出被监控的目录
        //IN_MOVED_FROM => 'File Moved Out',

        //文件被创建
        #IN_CREATE => 'File Created',

        //文件被删除
        //IN_DELETE => 'File Deleted'
    ];

    private static $ifd = null;
    private static $my_event = null;

    public static function run(){
        $pid = exec('pidof swoole_http');

        if(empty($pid)){
            //当为空的时候,表示需要启动服务器
            exec('sh httpStart.sh');
        }

        //设置监控根目录
        $dir = __DIR__.'/../../app';

        self::$my_event = array_sum(array_keys(self::$events));
        //创建一个inotify实例
        self::$ifd = inotify_init();

        //递归监视所有的目录
        self::LookAllDirectory($dir);
        //检查变化
        self::check();

    }

    public static function check(){
        //设置为阻塞
        //stream_set_blocking(self::$ifd, 1);

        while ($event_list = inotify_read(self::$ifd)) {

            foreach ($event_list as $arr) {
                $ev_mask = $arr['mask'];
                $ev_file = $arr['name'];

                if (isset(self::$events[$ev_mask])) {
                    echo "  ".self::$events[$ev_mask]. ", Filename: ". $ev_file. PHP_EOL;
                    //当里面的文件产生上述事件的时候调用重启脚本
                    exec('./httpReload.sh');
                }


            }

        }
    }

    public static function LookAllDirectory($dir){
        //将文件(夹)移动至监控列表
        inotify_add_watch(self::$ifd, $dir, self::$my_event);

        $files = scandir($dir);

        foreach ($files as $file){
            $path = $dir.'/'.$file;

            if($file=='.' || $file=='..'){
                continue;
            }
            elseif(is_dir($path)){
                inotify_add_watch(self::$ifd, $path, self::$my_event);
                self::LookAllDirectory($path);
            }

        }

    }



}

Inotify::run();








