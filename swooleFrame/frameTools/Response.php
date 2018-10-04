<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 16:33
 */
namespace swooleFrame\frameTools;

class Response{
    private static $response = null;
    public static function init($response)
    {
        self::$response = $response;
    }

    public static function returnContent($content){
        self::$response->header("Content-Type", "text/html; charset=utf-8");
        self::$response->end($content);
    }
}