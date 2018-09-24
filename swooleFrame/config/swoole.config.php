<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 13:29
 */

return [
  'websocket'=>[
      'server_address'=>[
          'ip'=>'0.0.0.0',
          'port'=>'8000',
      ],
      'set'=>[
          //设置html根目录
          'document_root' => __DIR__.'/../../html',
          'enable_static_handler' => true,
          //用来防止内存溢出错误，每个woker进出最多接受请求的次数
          'max_request' => 3,
      ],
  ],

  'http'=>[
      'server_address'=>[
          'ip'=>'0.0.0.0',
          'port'=>'8001',
      ],
      'set'=>[
          //设置html根目录
          'document_root' => __DIR__.'/../../html',
          'enable_static_handler' => true,
      ],
  ],

];