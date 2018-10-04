<?php
namespace swooleFrame\frameTools;
class Pdo{
	private $type = '';
	private $host = '';
	private $port = '';
	private $user = '';
	private $pwd = '';
	private $charset = '';
	private $dbname = '';
	private $pdo = null;
	
	public function __construct(){
	    //引入mysql配置
	    $arr = FrameTool::getConfig('main.php','mysql');

		//初始化所有的参数并且获得链接对象
		$this->type = isset($arr['type'])?$arr['type']:'mysql';
		$this->host = isset($arr['host'])?$arr['host']:'127.0.0.1';
		$this->port = isset($arr['port'])?$arr['port']:'3306';
		$this->user = isset($arr['user'])?$arr['user']:'root';
		$this->pwd = isset($arr['password'])?$arr['password']:'root';
		$this->charset = isset($arr['charset'])?$arr['charset']:'utf8';
		$this->dbname = isset($arr['database'])?$arr['database']:'swoole';
		
		$this->link();
		//开启pdo操作报错机制
		$this->enableException();
	}
	
	//获取pdo对象
	private function link(){
		$dsn = "$this->type:host=$this->host;port=$this->port;charset=$this->charset;dbname=$this->dbname";
		try{
			//如果新建对象失败会直接抛出异常
			$this->pdo = new \PDO($dsn, $this->user, $this->pwd);
		}catch (\Error $e){
			//var_dump($e);
			echo 'pdo对象获取失败:',$e->getMessage();
		}
	}
	
	//开启异常,因为默认的pdo操作内出错是不报错的
	private function enableException(){
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	//增删改方法
	public function exec($sql){
	    $ret = false;
		//返回的是受影响的行数
		try{
			$ret = $this->pdo->exec($sql);
		}catch (\PDOException $e){
			echo 'sql语句执行失败:',$e->getMessage();
		}
		
		return $ret;
	}
	
	//获取最后一个插入id
	public function lastInsertid(){
		return $this->pdo->lastInsertId();
	}
	
	//获取一条记录的方法,(一般方法,效率相较预处理低)
	public function fetchRow($sql){
		
		$state = null;
		try{
		$state = $this->pdo->query($sql);
		}catch (\PDOException $e){
			echo '查询一条记录失败:',$e->getMessage();
			exit;
		}
		//解析pdostatemnt对象,返回一个关联数组
		return $state->fetch(\PDO::FETCH_ASSOC);
	}
	
	//获取一条记录的方法,(一般方法,效率相较预处理低)
	public function fetchAll($sql){
	
		$state = null;
		try{
			$state = $this->pdo->query($sql);
		}catch (\PDOException $e){
			echo '查询一组记录失败:',$e->getMessage();
			exit;
		}
		//解析pdostatemnt对象,返回一个关联数组
		return $state->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	//传入进来的是预处理sql
	/*
	 * 传入预定义sql,返回PDOStatement对象,后期可以把这个对象存储到缓存或者session里面,以备重复使用
	 */
	public function setPrepareSql($sql){
		return $this->pdo->prepare($sql);
	}
	
	//执行预定义sql
	/*
	 * $state:传入setPrepareSql所返回的PDOStatement对象
	 * $ArrChars:预定义sql所有的占位符和其值组成的关联数组
	 * 
	 * 返回值:查询的结果
	 */
	public function execPerpare($state,$arrCharsValues){
		$ret = null;
		try{
			foreach ($arrCharsValues as $key=>$value){
				$state->bindValue($key,$value);
			}
			$state->execute();
			$ret = $state->fetchAll();
		}catch (\Exception $e){
			echo '预定义sql执行出错:',$e->getMessage();
			exit;
		}
		return $ret;
	}
	
	//事物
	//开启事物
	public function beginTransaction(){
		$this->pdo->beginTransaction();
	}
	//执行一个完整的事务中所有的sql语句
	/*
	 * $arrSql:所有sql的数组
	 */
	public function execSqls($arrSql){
		$ret = true;
		try {
			foreach ($arrSql as $sql){
				$ret = $this->exec($sql);
				if(!$ret){
					$ret=false;
					break;
				}
			}
		}catch (\Exception $e){
			echo '事务中的某一sql执行失败:',$e->getMessage();
		}
		
		return $ret;
	}
	//提交事务
	public function commit(){
		$this->pdo->commit();
	}
	//回滚事务
	public function rollBack(){
		$this->pdo->rollBack();
	}
}

//测试
// $config = ['user'=>'root','pwd'=>'root','dbname'=>'php10'];
// $mp = new MyPdo($config);

// $mp->beginTransaction();
// $arrSql = ['update bank set money1 = money+100 where id =1;',
// 'update bank set money = money-100 where id =2;'];
// $ret = $mp->execSqls($arrSql);
// if($ret){
// 	$mp->commit();
// }
// else{
// 	$mp->rollBack();
// 	echo '事务执行失败!';
// }

// $sql = "select * from bank where id>:min and id<:max;";
// $state = $mp->setPrepareSql($sql);
// $arrCharsValues = ['min'=>2,'max'=>5];
// $arrCharsValues1 = ['min'=>4,'max'=>6];
// $ret = $mp->execPerpare($state, $arrCharsValues);

// $ret1 = $mp->execPerpare($state, $arrCharsValues1);
// // $ret = $mp->fetchAll($sql);
// // // echo $mp->lastInsertid();
// echo '<pre>';
// var_dump($ret);
// echo '<br /><br />';
// var_dump($ret1);