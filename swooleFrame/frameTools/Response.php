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
    //标志是否已经响应过了
    private static $flag = false;

    public static function init($response)
    {
        self::$response = $response;
        self::$flag = false;
    }

    public static function returnContent($content){
        if(self::$flag){
           return;
        }
        //设置允许跨域访问
        self::$response->header('Access-Control-Allow-Origin','*');
        $allow_header='X-ACCESS_TOKEN,Access-Control-Allow-Origin,Authorization,Origin,x-requested-with,Content-Type,Content-Range,Content-Disposition,Content-Description';

        self::$response->header('Access-Control-Allow-Headers',$allow_header);
        self::$response->header('Access-Control-Allow-Methods','GET,POST');
        self::$response->header("Content-Type", "text/html;");
        self::$response->end($content);
    }

    //默认6表示成功，返回4表示有异常抛出
    public static function returnJson($content,$code='6'){
        self::$flag = true;
        //设置允许跨域访问
        self::$response->header('Access-Control-Allow-Origin','*');
        $allow_header='X-ACCESS_TOKEN,Access-Control-Allow-Origin,Authorization,Origin,x-requested-with,Content-Type,Content-Range,Content-Disposition,Content-Description';

        self::$response->header('Access-Control-Allow-Headers',$allow_header);
        self::$response->header('Access-Control-Allow-Methods','GET,POST');
        self::$response->header("Content-Type", "application/json; charset=utf-8");
        //返回的数据
        self::$response->end(json_encode([
            'code'=>$code,
            'data'=>$content,
        ]));
    }
}