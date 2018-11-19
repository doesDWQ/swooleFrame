<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/2
 * Time: 16:32
 */
namespace swooleFrame\frameTools;

use swooleFrame\core\AutoLoad;

class Request{
    private static $get = null;
    private static $post = null;
    private static $server = null;
    private static $request = null;
    private static $header = null;
    private static $cookie = null;
    //访问的模块
    public static $module = null;
    //访问的控制器
    public static $controller = null;
    //访问的方法
    public static $function = null;

    //初始化，拿到每次的get和post以及server数据
    public static function init($request)
    {
        ObjectFactory::init();
        //设置变量值
        self::setInfo($request);
        //分发请求
        self::requestToFunction();
        unset($objFactory);
    }

    //分发请求
    public static function requestToFunction(){
        //过滤掉/favicon.ico请求
        if(self::$server['path_info']==='/favicon.ico'){
            return ;
        }

        //得到请求的路径，默认分为三层，也只能是三层,需要截取最前面的一个'\'
        $pathInfo = substr(self::$server['path_info'],1);
        $path = explode('/',$pathInfo);

        if(count($path)===3){
            self::$module = $path[0];
            self::$controller = $path[1];
            self::$function = $path[2];

            //切面控制
            self::Aops();

            //手动拼接出带空间名的类名
            $module = self::$module;
            //这里规定控制器的首字母可以小写
            $realClass = ucfirst(self::$controller);
            $className = "app\\{$module}\\controller\\{$realClass}Controller";

            $controllerObj = ObjectFactory::getObj($className);
            //执行对应的方法
            $function = self::$function;
            $controllerObj->$function();

        }
        else{
            echo '访问路径必须只能是三层pathinfo！'.PHP_EOL;
        }
    }

    //切面操作方法
    public static function Aops(){
        //切面控制
        $moduleAop = FrameTool::getConfig('aops.php',self::$module);
        if($moduleAop!==false){

            //模型的截面操作
            if(isset($moduleAop['self'])){
                $obj = ObjectFactory::getObj($moduleAop['self'][0]);
                //调用配置的对象的方法
                call_user_func([$obj,$moduleAop['self'][1]]);
            }

            $controller = self::$controller.'Controller';
            if(isset($moduleAop[$controller])){

                //控制器的截面操作
                $arr = $moduleAop[$controller];
                if(isset($arr['self'])){
                    $obj = ObjectFactory::getObj($arr['self'][0]);
                    //调用配置的对象的方法
                    call_user_func([$obj,$arr['self'][1]]);
                }

                $function = self::$function;
                //方法的截面操作
                if(isset($arr[$function])){
                    $obj = ObjectFactory::getObj($arr[$function][0]);
                    //调用配置的对象的方法
                    call_user_func([$obj,$arr[$function][1]]);
                }

            }

        }
    }

    public static function setInfo($request){
        self::$request = $request;
        self::$get = isset($request->get)?$request->get:[];
        self::$post = isset($request->post)?$request->post:[];
        self::$server = isset($request->server)?$request->server:[];
        self::$header = isset($request->header)?$request->header:[];
        self::$header = isset($request->cookie)?$request->cookie:[];
    }

    //获得get请求的参数值
    public static function get($fileds=''){
        return self::getParams(self::$get,$fileds);
    }

    //获得post请求的参数值
    public static function post($fileds=''){
        return self::getParams(self::$post,$fileds);
    }

    public static function getParams($arr,$fileds=''){
        $key = $fileds;
        //需要做一个防sql注入的机制
        foreach ($arr as $key=>$v) {
            htmlspecialchars($arr[$key]);
        }

        if($fileds===''){
            return self::$get;
        }

        $fileds = explode(',',$fileds);

        $ret = [];
        if(count($fileds)==1){
            $ret = isset($arr[$key])?$arr[$key]:'';
        }
        else{
            foreach ($fileds as $filed){
                $ret[$filed] = isset($arr[$filed])?self::$get[$filed]:'';
            }
        }

        return $ret;
    }

    public static function cookie(){
        return self::$cookie;
    }

    /*
     * 微信开发的时候使用，用户获取到xml数据包
     */
    public static function getRawContent(){
        return self::$server->rawContent();
    }

    //获取到request对象
    public static function getRequest(){
        return self::$request;
    }
}