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

          //用来防止内存溢出错误，每个worker进程最多接受请求的次数
          'max_request' => 100,

          //设置工作的线程数，默认会根据cpu的核数分配
          //'worker_num'=>3,

          //当需要使用taskWorker进程的时候，必须设置个数，形成一个进程池
          'task_worker_num'=>2,
          //防止taskWorker内存泄漏，当达到指定的次数后就销毁这个task进程
          'task_max_request'=>100,
          //设置携程的最大个数，避免内存溢出
          'max_coro_num'=>100,
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