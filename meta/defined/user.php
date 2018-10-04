<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/3
 * Time: 15:04
 */

/*
 * 'type'=>'int' :类型
 * ,'length'=>10, :长度
 * 'default'=>'', :默认值
 * 'comment'=>''  :注释
 */
return [
    'name'=>['type'=>'varchar','length'=>10,'default'=>'','comment'=>''],
    'age'=>['type'=>'int','length'=>10,'default'=>'0','comment'=>'年龄'],
    'gender'=>['type'=>'varchar','length'=>10,'default'=>'男','comment'=>'性别'],
];