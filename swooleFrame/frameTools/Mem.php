<?php
namespace swooleFrame\frameTools;
class Mem{
    private $con = null;
    private $memType = '\Memcached';
    
    public function __construct(){
        $config = FrameTool::getConfig('main.php','memcache');
        //如果memcache类存在就使用这个扩展
        if(class_exists('\Memcache')){
            $this->memType = '\Memcache';
        }

        $this->con = new $this->memType();
        $this->con->addServer($config['host'],$config['port']);
        
    }
    
    //如果传入有效时间就永久有效
    public function set($key,$value,$expire=0){
        if($this->memType='Memcached'){
            $this->con->set($key,$value,$expire);
        }
        else{
            $this->con->set($key,$value,0,$expire);
        }
    }
    
    public function get($key){
        return $this->con->get($key);
    }
    
}

//测试
// $obj = new Mem('119.29.240.52', '11211');
// $obj->set('name','小明');
// echo $obj->get('name');
