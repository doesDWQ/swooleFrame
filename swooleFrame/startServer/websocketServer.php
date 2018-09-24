<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/9/24
 * Time: 13:23
 */

include __DIR__.'/../core/ServerFactory.php';
$config = include __DIR__.'/../config/swoole.config.php';
new \swooleFrame\core\ServerFactory('WebsocketServer',$config['websocket']);