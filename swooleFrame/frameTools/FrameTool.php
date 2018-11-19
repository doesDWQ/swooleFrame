<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/27
 * Time: 22:05
 */
namespace swooleFrame\frameTools;

use swooleFrame\core\ServerFactory;

class FrameTool{

    public static $config = '';

    //自动加载配置文件的助手函数
    public static function getConfig($fileName,$keyName){
        self::$config = include_once __DIR__.'/../config/'.$fileName;
        $needConfig = isset(self::$config[$keyName])?self::$config[$keyName]:false;

        return $needConfig;
    }

    //写日志
    public static function writeLog($data,$fileName){
        //获取到日志目录
        $logDir = self::getConfig('main.php','logDir');
        if(!empty($fileName)){
            $file = $logDir.'/'.$fileName.'.log';
            if(!is_string($data)){
                $data = json_encode($data);
            }
            $time = date('Y:m:d H:i:s');
            $data1 = "===={$time}=============".PHP_EOL;
            //在错误前面加上当前日志的所属服务器
            $data = ServerFactory::$serverName.':'.$data1.$data.PHP_EOL;
            file_put_contents($file,$data,FILE_APPEND);
        }
    }

}