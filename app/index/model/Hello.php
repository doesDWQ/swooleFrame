<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/3
 * Time: 18:58
 */

namespace app\index\model;


use swooleFrame\frameTools\Model;

class Hello extends Model
{
    //持有表名
    protected $tableName = 'user';

    public function test(){
        echo 'hello 11';
    }

    public static function hello(){
        echo 'hello';
    }

}