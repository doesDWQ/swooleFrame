<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 16:07
 * 自动注册类
 */
namespace swooleFrame\core;
 class AutoLoad{

     /*
      * $dirs:需要自动注册的类所在的目录的数组，当前每个目录都只支持一层
      */
     public static function run($dirs){

         $phpFiles = [];
         //取出每一个目录
         foreach ($dirs as $dir){

             if(!file_exists($dir)){
                 //当目录不存在的时候跳过当前目录
                 continue;
             }

             //获取到目录里面所有的文件
             $files = scandir($dir);

             //后缀为.php的文件才做出来
             foreach ($files as $file){
                 //拼接上目录
                 $file = $dir.'/'.$file;
                 if(pathinfo($file,PATHINFO_EXTENSION)=='php'){
                     $className = self::getClassName($file);
                     $phpFiles[$className] = $file;
                 }
             }
         }
         //注册
         self::Load($phpFiles);
     }

     //自动为其注册
     public static function Load($phpFiles){

         //自动注册
         spl_autoload_register(function ($className) use($phpFiles){
             if(key_exists($className,$phpFiles)){
                 $file = $phpFiles[$className];
                 //载入文件
                 include $file;
             }
         });
     }

     //获取包含空间名的类名
     public static function getClassName($file){
         $ret = '';
         //以包含空间名的类名对应文件路径的方式保存进入数组
         //拿到包含空间名的类名
         $rootLength = strlen(realpath(ROOT));
         $fiePath = realpath($file);
         $FileLength = strlen($fiePath);

         if($rootLength<$FileLength){
             //得到类名
             $className = substr($fiePath,$rootLength+1);
             //为了兼容linux，将linux下面的/替换成\
             $className = str_replace('/','\\',$className);

             $len = strlen($className);
             $phpLen = strlen('.php');

             if($len>$phpLen){
                 //截掉尾部的.php后缀
                 $ret = substr($className,0,$len-$phpLen);
             }
         }
         return $ret;
     }
 }

// define('ROOT','.');
// AutoLoad::run(['./classes']);
////echo AutoLoad::getClassName('./classes/Person.php');
//$p = new \classes\Person();
//$p = new \classes\Son();