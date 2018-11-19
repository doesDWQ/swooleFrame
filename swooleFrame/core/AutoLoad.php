<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 16:07
 * 自动注册类
 */
namespace swooleFrame\core;
 use swooleFrame\frameTools\ObjectFactory;
 use think\image\Exception;

 class AutoLoad{

    public static function Load(){
        //自动注册
        spl_autoload_register(function ($className){
            $spacePath = str_replace("\\",'/',$className);
            $path = ROOT.'/'.$spacePath;
            $phpPath = $path.'.php';
            //兼容.class.php结尾的文件
            $classPath = $path.'.class.php';

            if(file_exists($phpPath)){
                require_once $phpPath;
            }
            elseif(file_exists($classPath)){
                require_once $classPath;
            }
            else{
                throw new \Exception("需要被加载的类{$className}不存在！");
            }
        });
    }

 }