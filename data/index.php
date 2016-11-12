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
 **10004:用户手机号登录
 **/
require_once './config/dataBaseConfig.php';
include 'classBycle.php';
$data = file_get_contents("php://input",true);
$data=json_decode($data,true);
$command=isset($data['cid'])?$data['cid']:0;
$userId=isset($data['uid'])?$data['uid']:0;
$token=isset($data['token'])?$data['token']:'';
if(!$command){
	$command = $_REQUEST['cid']?$_REQUEST['cid']:0;
	$userId = $_REQUEST['uid']?$_REQUEST['uid']:0;
	$token = $_REQUEST['token']?$_REQUEST['token']:'';
}
$attr = array('userid'=>$userId,'token'=>$token);
$checkUser = User::checkAndQueryUserInfo($attr);
switch($command){
	case 10000:
		if(!empty($checkUser)){
			$bike = new Bycle($userId);
			echo $bike->signBycle();
		}else{
			echo errorInfo(40003);
		}
		break;
	case 10001:
		if(!empty($checkUser)){
			$bike = new Bycle($userId);
			echo $bike->getBikeList();
		}else{
			echo errorInfo(40004);
		}
		break;
	case 10002:
		$bikeId = $_REQUEST['bid']?$_REQUEST['bid']:0;
		if(!empty($checkUser)){
			$bike = new Bycle($userId);
			echo $bike->getInfoByBikeId($bikeId);
		}else{
			echo errorInfo(40004);
		}
		break;
	case 10003:
		//TODO:查询用户的信息，引入用户类
		echo $checkUser;
		break;
	case 10004:
		$tel = $data['tel'];
		$pwd = $data['pwd'];
		$attr = array('tel'=>$tel,'pwd'=>md5($pwd));
		$res = User::loginin($attr);
		if($res){
			session_start();
			$_SESSION['uid'] = $res[0]['userid'];
			$_SESSION['token'] = $res[0]['token'];
			$_SESSION['tel'] = $res[0]['tel'];
			$res = json_encode($res);
			echo $res;
		}else{
			echo false;
		}
		break;
	case 10005:
		$tel = $data['tel'];
		$pwd = $data['pwd'];
		$attr = array('tel'=>$tel,'pwd'=>md5($pwd),'userid'=>$userId);
		if(!empty($checkUser)){
			$res = User::setting($attr);
			if($res){
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
		break;
	default:
		echo errorInfo(40000);
		break;
}