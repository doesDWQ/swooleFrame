<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 20:01
 */

namespace swooleFrame\frameTools;

use swooleFrame\core\ServerFactory;

class Error
{

    //捕获错误自定函数
    public static function error_handler ($error_level, $error_message, $file, $line) {
        switch ($error_level) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $error_type = 'Notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $error_type = 'Warning';
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $error_type = 'Fatal Error';
                break;
            default:
                $error_type = 'Unknown';
                break;
        }

        $str1 = "{$error_type}: {$error_message} in {$file} on line {$line}";
        //将错误写入到日志中
        FrameTool::writeLog($str1,'error');
        $str = "<span style='color:red;font-size: 20px;'>{$str1}</span></span><br/>";
        //默认返回json
        Response::returnJson($str,4);
    }

    public static function getError($e){
        $code = $e->getCode();
        $mess = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        self::error_handler($code,$mess,$file,$line);
    }

}