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