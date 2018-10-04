<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/3
 * Time: 11:12
 */

return [
    /*
     * 配置格式
     * module=>[
     *      //存在同名的module则在调用模块的控制器之前必须先调用他对应的类
     *      module=>'',
     *      controllers=>[
     *          //存在同名的controller时则必须先调用这个类
     *          ''=>'',
     *          ...每一级的规则都是这样类推的
     *      ]
     *
     * }
     */
//    'index'=>[
//        //调用这个模块之前需要先new的类
//        'self'=>['app\aops\modules\Index','hello'],
//
//        //调用hello控制器时
//        'helloController'=>[
//            'self'=>['app\aops\controllers\Hello','hello'],
//
//            //调用hello方法时
//            'hello'=>['app\aops\functions\Hello','hello'],
//        ],
//
//    ],

];