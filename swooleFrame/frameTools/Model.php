<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/3
 * Time: 18:56
 * 所有模型都需要继承的父类
 */

namespace swooleFrame\frameTools;


class Model
{
    //需要被覆盖的表名
    protected $tableName = '';
    //需要保存的整条数据
    private $data = [];
    //mysql操作对象
    private $mysql = null;

    public function __construct()
    {
        $this->mysql = ObjectFactory::getObj('swooleFrame\frameTools\Mysql');
    }

    //保存整条数据
    public function save(){
        if($this->tableName===''){
            throw new \Exception('模型类里面没有持有表名！');
        }

        if(!isset($this->data['id']) || empty($this->data['id'])){
            //不存在id或id为空就是保存
            //保存的时候需要添加上时间
            $this->data['ctime'] = time();
            $this->data['mtime'] = $this->data['ctime'];
            $this->mysql->insert($this->tableName,$this->data);
        }
        else{
            //修改的时候需要添加上修改时间
            $this->data['mtime'] = time();
            //存在id就是修改
            $id = $this->data['id'];
            unset($this->data['id']);
            $data = [
              'set'=>$this->data,
              'where'=>" and id={$id}",
            ];
            $this->mysql->update($this->tableName,$data);
        }

        //保存完毕后需要将整条数据置空
        $this->data = [];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function setData($data){
        $this->data = $data;
    }
}