<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/11/11
 * Time: 10:26
 */

//设置监控目录
$dir = __DIR__.'/../../app';

$events = [
    //文件被访问了
    IN_ACCESS => 'File Accessed',

    //文件被修改了
    IN_MODIFY => 'File Modified',

    //文件的元数据发送改变（如权限、修改时间)
    IN_ATTRIB => 'File Metadata Modified',

    //文件被打开用于写，被关闭了
    IN_CLOSE_WRITE => 'File Closed, Opened for Writing',

    //文件被打开，不用于写，被关闭了
    IN_CLOSE_NOWRITE => 'File Closed, Opened for Read',

    //文件打开了
    IN_OPEN => 'File Opened',

    //文件移动到被监控的目录
    IN_MOVED_TO => 'File Moved In',

    //文件被移出被监控的目录
    IN_MOVED_FROM => 'File Moved Out',

    //文件被创建
    IN_CREATE => 'File Created',

    //文件被删除
    IN_DELETE => 'File Deleted'
];

$my_event = array_sum(array_keys($events));
//创建一个inotify实例
$ifd = inotify_init();
//将文件(夹)移动至监控列表
$wd = inotify_add_watch($ifd, $dir, $my_event);
//设置为阻塞
stream_set_blocking($ifd, 1);

while ($event_list = inotify_read($ifd)) {

    $str = date('Y-m-d H:i:s').PHP_EOL;
    foreach ($event_list as $arr) {
        $ev_mask = $arr['mask'];
        $ev_file = $arr['name'];

        if (isset($events[$ev_mask])) {
            echo "  ",$events[$ev_mask], ", Filename: ", $ev_file, PHP_EOL;
            //当里面的文件产生上述事件的时候调用重启脚本
            //exec('sh ./httpReload.sh');
        }
    }
}

inotify_rm_watch($ifd, $wd);
fclose($ifd);