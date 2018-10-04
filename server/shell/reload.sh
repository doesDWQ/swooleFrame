echo "reloading ....."
pid=`pidof swoole_websocket`
echo $pid;
kill -USR1 $pid
echo "reloading success"
