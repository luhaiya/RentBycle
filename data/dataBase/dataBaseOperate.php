<?php
header("Content-type: text/html; charset=utf-8");
	class dBOperate
	{
		private $link;
		private $tableName;
		public function __construct($tabName)
		{
			$this->tableName=$tabName;
			$this->link = new mysqli(servename,username,password,dbname);
			if($this->link->connect_error)
			{
				return false;
			}else {
				return true;
			}
		}
		
		public function query($sql){
			$result = $this->link->query($sql);
			if($result === false){
				return false;
			}else{
				$data = array();
				$result->data_seek(0); #重置指针到起始
				while($row = $result->fetch_assoc())
				{
					$data[] = $row;
				}
				return $data;
			}
		}
		
		function insertData($info)
		{
			$column=null;
			$value =null;
			foreach($info as $x=>$x_value)
			{
// 				$x="'".$x."'";
				$column=$column.$x.",";
				$x_value="'".$x_value."'";
				$value=$value.$x_value.",";
			}
			$column=substr($column, 0,strlen($column)-1);
			$value=substr($value,0,strlen($value)-1);
			$sql = "INSERT INTO $this->tableName($column) VALUES($value)";
			$this->link->query($sql);//TODO:
		}
		function updateData($info,$KEY)
		{
			$valueChange=null;
			foreach($info as $x=>$x_value)
			{
				$x_value="'".$x_value."'";
				$valueChange=$valueChange.$x."=".$x_value.",";
			}
			$valueChange=substr($valueChange,0,strlen($valueChange)-1);
			$majorKey=null;
			foreach($KEY as $y=>$y_value)
			{
				$y_value="'".$y_value."'";
				$majorKey=$majorKey.$y."=".$y_value.",";
			}
			$majorKey=substr($majorKey,0,strlen($majorKey)-1);
			$sql= "UPDATE $this->tableName SET $valueChange WHERE $majorKey";
			$this->link->query($sql);
		}
	}