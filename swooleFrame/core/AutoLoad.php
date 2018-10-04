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

     private static $phpFiles = null;
     /*
      * $dirs:需要自动注册的类所在的目录的数组，当前每个目录都只支持一层
      */
     public static function auto($dirs){
         self::run($dirs);
         //注册
         self::Load();
     }

     public static function run($dirs){

         $sonDirs = [];
         //取出每一个目录
         foreach ($dirs as $dir){

             if(!file_exists($dir)){
                 //当目录不存在的时候跳过当前目录
                 continue;
             }

             //获取到目录里面所有的文件
             $files = scandir($dir);

             //后缀为.php的文件才需要被自动载入
             foreach ($files as $file){
                 //.和..两个东东需要被跳过,否则这个程序会陷入死循环
                 if($file=='.' || $file=='..'){
                     continue;
                 }

                 //拼接上目录
                 $file = $dir.'/'.$file;

                 if(is_file($file) && pathinfo($file,PATHINFO_EXTENSION)=='php'){
                     $className = self::getClassName($file);
                     self::$phpFiles[$className] = $file;
                 }
                 elseif(is_dir($file)){
                     //目录的处理
                     $sonDirs[] = $file;
                 }
             }
         }

         //目录需要递归调用注册---递归是一种很损耗性能的表现,
         if(!empty($sonDirs)){
             //不加这个判断会陷入死循环！！
             self::run($sonDirs);
         }
     }

     //自动为其注册
     public static function Load(){
         $phpFiles = self::$phpFiles;

         //自动注册
         spl_autoload_register(function ($className) use($phpFiles){
             if(key_exists($className,$phpFiles)){
                 $file = $phpFiles[$className];
                 //载入文件
                 include $file;
                 //当文件被载入后需要释放掉这个键值对,减少内存的占用
                 unset($phpFiles[$className]);
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