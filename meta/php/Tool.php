<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/3
 * Time: 14:49
 */
class Tool{
    private static $tablesDefined = [];


    public static function run(){
        //引入所有的配置
        $path = __DIR__.'/../defined';
        $files = scandir($path);

        foreach ($files as $file){
            //跳过.和..
            if($files==='.' || $file==='..'){
                continue;
            }
            if(pathinfo($file,PATHINFO_EXTENSION)==='php'){
                $name = pathinfo($file,PATHINFO_FILENAME);

                $file = $path.'/'.$file;


                $arr = include $file;

                //加上后置参数
                $arr = self::addAfterParams($arr);

                //生成sql文件
                self::createSqlFile($arr,$name);
            }
        }


    }


    public static function createSqlFile($arr,$name){
        $config = include __DIR__."/../../swooleFrame/config/main.php";
        $database = $config['mysql']['database'];

        $str = "use {$database};\n";
        $str .= "create table IF NOT EXISTS ";
        $str .= "{$name} (\n";
        //添加上自增长主键
        $str .= "   `id` int(11) primary key auto_increment comment '主键',\n";
        $fileds = 'type,length,default,comment';
        $fileds = explode(',',$fileds);

        foreach ($arr as $key=>$v){
            //$fileds里面的所有字段必须都存在
            foreach ($fileds as $filed){
                if(!isset($v[$filed])){
                    echo $name.'的'.$filed.'缺失！'.PHP_EOL;die;
                }
            }

            $str .= "   `{$key}` {$v['type']}({$v['length']}) not null default '{$v['default']}' comment '{$v['comment']}'";

            if($key!=='mtime'){
                //sql语句的最后一个字段不能用上逗号
                $str .= ',';
            }

            $str .= "\n";

        }


        $str .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n";

        $path = __DIR__."/../sql/{$name}.sql";
        file_put_contents($path,$str);
        echo $path.'----->sql文件创建完成.'.PHP_EOL;
    }

    public static function addAfterParams($arr){
        //创建时间
        $arr['ctime'] = ['type'=>'int','length'=>11,'default'=>'0','comment'=>'表的创建时间'];
        //最后一次修改时间
        $arr['mtime'] = ['type'=>'int','length'=>11,'default'=>'0','comment'=>'表的最后修改时间'];
        return $arr;
    }
}

Tool::run();