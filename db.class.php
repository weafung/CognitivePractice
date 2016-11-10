<?php
/**
 * File: db.class.php
 * Author: Weafung
 * Email: weafung@gmail.com
 * Generate: 2016/10/26
 */

class db {
	public $conn;
	public static $sql;
	public static $instance=null;
	private function __construct(){
		require_once('./db.config.php');
		$this->conn = mysql_connect($db['host'],$db['user'],$db['password']);
		if(!mysql_select_db($db['database'],$this->conn)){
			echo "连接数据库失败";
		};
		mysql_query('set names utf8',$this->conn);		
	}
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new db;
		}
		return self::$instance;
	}
	/**
	 * 查询数据库
	 */
	public function select($table,$condition=array(),$field = array(),$order_by='',$order='desc'){
		$where='';
		if(!empty($condition)){
			
			foreach($condition as $k=>$v){
				$where.=$k."='".$v."' and ";
			}
			$where='where '.$where .'1=1';
		}
		$fieldstr = '';
		if(!empty($field)){
			
			foreach($field as $k=>$v){
				$fieldstr.= $v.',';
			}
			 $fieldstr = rtrim($fieldstr,',');
		}else{
			$fieldstr = '*';
		}
		self::$sql = "select {$fieldstr} from {$table} {$where} order by {$order_by} {$order}";
		$result=mysql_query(self::$sql,$this->conn);
		$resuleRow = array();
		$i = 0;
		while($row=mysql_fetch_assoc($result)){
			foreach($row as $k=>$v){
				$resuleRow[$i][$k] = $v;
			}
			$i++;
		}
		return $resuleRow;
	}
	/**
	 * 添加一条记录
	 */
	 public function insert($table,$data){
	 	$values = '';
	 	$datas = '';
	 	foreach($data as $k=>$v){
	 		$values.=$k.',';
	 		$datas.="'$v'".',';
	 	}
	 	$values = rtrim($values,',');
	 	$datas   = rtrim($datas,',');
	 	self::$sql = "INSERT INTO  {$table} ({$values}) VALUES ({$datas})";
		if(mysql_query(self::$sql)){
			return mysql_insert_id();
		}else{
			return false;
		};
	 }
	 /**
	  * 修改一条记录
	  */
	public function update($table,$data,$condition=array()){
		$where='';
		if(!empty($condition)){
			
			foreach($condition as $k=>$v){
				$where.=$k."='".$v."' and ";
			}
			$where='where '.$where .'1=1';
		}
		$updatastr = '';
		if(!empty($data)){
			foreach($data as $k=>$v){
				$updatastr.= $k."='".$v."',";
			}
			$updatastr = 'set '.rtrim($updatastr,',');
		}
		self::$sql = "update {$table} {$updatastr} {$where}";
		return mysql_query(self::$sql);
	}
	/**
	 * 删除记录
	 */
	 public function delete($table,$condition){
	 	$where='';
		if(!empty($condition)){
			
			foreach($condition as $k=>$v){
				$where.=$k."='".$v."' and ";
			}
			$where='where '.$where .'1=1';
		}
		self::$sql = "delete from {$table} {$where}";
		return mysql_query(self::$sql);
		
	 }
	
	public static function getLastSql(){
		echo self::$sql;
	}
	
	
	
}
/*
$db = db::getInstance();
$list = $db->select('demo',array('name'=>'tom','password'=>'ds'),array('name','password'));
echo $db->insert('demo',array('name'=>'最近你啦','password'=>'123'));
echo $db->update('demo',array("name"=>'xxx',"password"=>'123'),array('id'=>1));
echo $db->delete('demo',array('id'=>'2'));
db::getLastSql();
echo "<pre>";
*/