<?php
	include 'dataBaseConfig.php';
	class dataBaseOperate
	{
		private $link;
		function dataBaseOperate()
		{
			global $link;
			$link = new mysqli(servename,username,password,dbname);
			if($link->connect_error)
			{
				die("数据库连接失败".$link->connect_error);
			}else {
				echo "数据库连接成功";
			}
		}
		
		function insertData($info,$tableName)
		{
			global $link;
			$column=null;
			$value =null;
			foreach($info as $x=>$x_value)//拼接命令
			{
// 				$x="'".$x."'";
				$column=$column.$x.",";
				$x_value="'".$x_value."'";
				$value=$value.$x_value.",";
			}
			$column=substr($column, 0,strlen($column)-1);//去掉最后的逗号
			$value=substr($value,0,strlen($value)-1);
			$sql = "INSERT INTO $tableName($column) VALUES($value)";
			echo $sql;

			if($link->query($sql))
			{
				echo "新信息插入成功";
			}else
			{
				echo "Error: " . $sql . "<br>" . $link->error;
			}
		}
		function updateData($info,$KEY,$tableName)
		{
			global $link;
			$valueChange=null;
			foreach($info as $x=>$x_value)//拼接命令
			{
				$x_value="'".$x_value."'";
				$valueChange=$x."=".$x_value.",";
			}
			$valueChange=substr($valueChange,0,strlen($valueChange)-1);
			$majorKey=null;
			foreach($KEY as $y=>$y_value)
			{
				$y_value="'".$y_value."'";
				$majorKey=$y."=".$y_value.",";
			}
			$majorKey=substr($majorKey,0,strlen($majorKey)-1);
			$sql= "UPDATE $tableName SET $valueChange WHERE $majorKey";
			if($link->query($sql))
			{
				echo "新信息修改成功";
			}else
			{
				echo "Error: " . $sql . "<br>" . $link->error;
			}
		}
	}
?>