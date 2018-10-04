<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/3
 * Time: 18:45
 * pdo的mysql操作类
 */

namespace swooleFrame\frameTools;


class Mysql
{
    private static $pdo = null;

    public function __construct()
    {
        self::$pdo = ObjectFactory::getObj('swooleFrame\frameTools\Pdo');
    }

    //插入方法
    public function insert($tableName,$data){
        $keyStr = implode(',',array_keys($data));
        $valueStr = "'";
        $valueStr .= implode("','",array_values($data));
        $valueStr .= "'";
        $sql = "insert into {$tableName}($keyStr) values ($valueStr);";
        self::$pdo->exec($sql);
    }

    //修改方法
    public function update($tableName,$data){
        //data里面应该包含两个部分，一个是set数组，where字符串
        if(!isset($data['set']) || !isset($data['where'])){
            throw new \Exception('set或where子数组有不存在的！');
        }

        $setStr = '';
        foreach ($data['set'] as $key=>$value){
            $setStr .= "{$key}='{$value}',";
        }

        $setStr = substr($setStr,0,strlen($setStr)-1);
        $whereStr = $data['where'];
        $sql = "update {$tableName} set {$setStr} where 1=1 {$whereStr}";
        self::$pdo->exec($sql);
    }


}