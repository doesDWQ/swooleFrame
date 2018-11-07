#/bin/bash
#最好在linux下修改shell脚本，否则容易损坏脚本的格式
echo "reloading .....";
pid=`pidof swoole_http`;
echo $pid;
kill -USR1 $pid;
echo "reloading success";
