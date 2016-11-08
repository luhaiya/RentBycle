<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：服务器端一些数据接口
 **
 **指令代码说明
 **10000；自行车注册
 **10001：获取自行车的列表信息
 **10002：获取自行车的详细信息
 **10003：获取用户的详细信息（加密）
 **/
include 'classBycle.php';
$command = $_REQUEST['cid']?$_REQUEST['cid']:0;
switch($command){
	case 10000:
		$userId = $_REQUEST['uid']?$_REQUEST['uid']:0;
		if($userId){
			$bike = new Bycle($userId);
			echo $bike->signBycle();
		}else{
			echo errorInfo(40003);
		}
		break;
	case 10001:
		$userId = $_REQUEST['uid']?$_REQUEST['uid']:0;
		if($userId){
			$bike = new Bycle($userId);
			echo $bike->getBikeList();
		}else{
			echo errorInfo(40004);
		}
		break;
	case 10002:
		$userId = $_REQUEST['uid']?$_REQUEST['uid']:0;
		$bikeId = $_REQUEST['bid']?$_REQUEST['bid']:0;
		if($userId){
			$bike = new Bycle($userId);
			echo $bike->getInfoByBikeId($bikeId);
		}else{
			echo errorInfo(40004);
		}
		break;
	case 10003:
		//TODO:查询用户的信息，引入用户类
		break;
	default:
		echo errorInfo(40000);
		break;
}