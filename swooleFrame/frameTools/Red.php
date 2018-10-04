<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/4
 * Time: 16:52
 */

namespace swooleFrame\frameTools;


class Red
{
    //持有redis对象
    private $redis = null;

    public function __construct()
    {
        $config = FrameTool::getConfig('main.php','redis');
        //获取redis连接对象
        $this->redis = new \Redis();
        $this->redis->connect($config['host'],$config['port']);
    }

    /*---对键的通用操作---如设置键的有效时间等----*/

    /*---------字符串操作------------*/
    /*
     * 设置键值对
     */
    public function set($key,$value){
        $this->redis->set($key,$value);
    }

    /*
     * 获取对应的键值
     */
    public function get($key){
        return $this->redis->get($key);
    }

    /*-----------用到啥就完善啥----------------*/
}