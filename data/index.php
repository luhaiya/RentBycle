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
 **10005:重设手机号密码
 **10006:向车主发起请求
 **10007:车主修改自行车的状态
 **10008:选出车主发布过的车
 **/
require_once './config/dataBaseConfig.php';
require_once 'classBycle.php';
require_once './weiXin/classWeixin.php';
require_once('./config/wxConfig.php');
require_once('./commonFunc.php');
$data = file_get_contents("php://input",true);
$data=json_decode($data,true);
$command=isset($data['cid'])?$data['cid']:0;
$userId=isset($data['uid'])?$data['uid']:0;
$token=isset($data['token'])?$data['token']:'';
if(!$command){
	$command = isset($_REQUEST['cid'])?$_REQUEST['cid']:0;
	$userId = isset($_REQUEST['uid'])?$_REQUEST['uid']:0;
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:'';
}
$attr = array('userid'=>$userId,'token'=>$token);
$checkUser = User::checkAndQueryUserInfo($attr);
switch($command){
	case 10000:
		if(!empty($checkUser)){
			$bike = new Bycle($userId);
			$tags=isset($data['descBycle'])?$data['descBycle']:'';
			$price=isset($data['priceBycle'])?$data['priceBycle']:0;
			if(!$tags||!$price){
				$tags = isset($_REQUEST['descBycle'])?$_REQUEST['descBycle']:'';
				$price = isset($_REQUEST['priceBycle'])?$_REQUEST['priceBycle']:0;
			}
			$attr = array("tags"=>$tags,"price"=>$price);
			if($bike->signBycle($attr)){
				echo "<script>alert('上传成功！');window.location='http://www.luhaiya.com/RentBycle/#/self/selfbycleinfo';</script>";
			}else{
				echo "<script>alert('上传失败！');window.location='http://www.luhaiya.com/RentBycle/#/self/setbycleinfo';</script>";
			}
		}else{
			errorInfo(40003);
			echo false;
		}
		break;
	case 10001:
		$bike = new Bycle($userId);
		$temp = json_decode($bike->getBikeList(),true);
		$keyword = isset($data['keyword'])?$data['keyword']:'';
		if(!$keyword){
			$keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:'';
		}
		$data = array();
		foreach($temp as $k=>$v){
			$desc = '';
			$tags = json_decode($v['tags'],true);
			for($i=0;$i<count($tags);$i++){
				$desc .= '  '.$tags[$i];
			}
			$data[] = array(
					'id'=>$v['bikeid'],
					'src'=>'http:'.json_decode($v['picurl'],true)[0],
					'desc'=> $desc,
					'price'=>'￥'.$v['price'],
			);
		}
		$page = isset($data['page'])?$data['page']:0;
		if(!$page){
			$page = isset($_REQUEST['page'])?$_REQUEST['page']:0;
		}
		$last=$page*4+4;
		$data = array_slice($data, 0, $last);
		echo json_encode($data);
		break;
	case 10002:
		$bikeId = isset($_REQUEST['bid'])?$_REQUEST['bid']:0;
		if(!empty($checkUser)){
			$bike = new Bycle($userId);
			echo $bike->getInfoByBikeId($bikeId);
		}else{
			errorInfo(40004);
			echo false;
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
	case 10006:
		$bycleid = $data['bycleid'];
		$tel = $data['tel'];
		if(!empty($checkUser)){
			$res = Bycle::getInfoByBikeId($bycleid);
			if($res){
				$res = json_decode($res,true);
				$user = User::getUserInfoById($res[0]['userid']);
				$user = json_decode($user,true);
				$attr = array('wxid'=>$user[0]['wxid'],'tel'=>$tel);
				$wxOperator = new Weixin();
				$wxOperator->rentToUser($attr);
				echo true;
			}else{
				echo false;
			}
		}else{
			echo false;
		}
		break;
	case 10007:
		if(!empty($checkUser)){
			$bycleid = isset($data['bycleid'])?$data['bycleid']:0;
			if(!$bycleid){
				$bycleid = isset($_REQUEST['bycleid'])?$_REQUEST['bycleid']:0;
			}
			Bycle::changeBycleState($bycleid);
			$temp = json_decode(Bycle::getInfoByUserId($userId),true);
			$state = array('未租出','已租出');
			$data = array();
			foreach($temp as $k=>$v){
				$desc = '';
				$tags = json_decode($v['tags'],true);
				for($i=0;$i<count($tags);$i++){
					$desc .= '  '.$tags[$i];
				}
				$data[] = array(
						'id'=>$v['bikeid'],
						'src'=>'http:'.json_decode($v['picurl'],true)[0],
						'desc'=> $desc,
						'price'=>'￥'.$v['price'],
						'state'=> $state[$v['state']],
				);
			}
			session_start();
			$page = isset($_SESSION['page'])?$_SESSION['page']:0;
			$first=$page*4;
			$data = array_slice($data, $first, 4);
			echo json_encode($data);
		}else{
			echo false;
		}
		break;
	case 10008:
		if(!empty($checkUser)){
			$temp = json_decode(Bycle::getInfoByUserId($userId),true);
			$state = array('未租出','已租出');
			$data = array();
			foreach($temp as $k=>$v){
				$desc = '';
				$tags = json_decode($v['tags'],true);
				for($i=0;$i<count($tags);$i++){
					$desc .= '  '.$tags[$i];
				}
				$data[] = array(
						'id'=>$v['bikeid'],
						'src'=>'http:'.json_decode($v['picurl'],true)[0],
						'desc'=> $desc,
						'price'=>'￥'.$v['price'],
						'state'=> $state[$v['state']],
				);
			}
			$maxPage = ceil(count($data)/4)-1;
			session_start();
			$page = isset($_SESSION['page'])?$_SESSION['page']:0;
			$type = isset($data['type'])?$data['type']:0;
			if(!$type){
				$type = isset($_REQUEST['type'])?$_REQUEST['type']:0;
			}
			if($type == 0){
				$page = 0;
			}
			if($type == 2){
				if($page<$maxPage){
					$page += 1;
				}
			}
			if($type == 1){
				if($page){
					$page -= 1;
				}
			}
			$_SESSION['page'] = $page;
			$first=$page*4;
			$data = array_slice($data, $first, 4);
			echo json_encode($data);
		}else{
			echo false;
		}
		break;
	default://微信授权登录入口
		session_start();
		$webauth = new Weixin();
		$wxuserInfo = $webauth->getWxUserInfo();
		if($wxuserInfo){
			$wxuserInfo = json_decode($wxuserInfo, true);
			$user = new User($wxuserInfo);
			$userinfo = $user->getUserInfo($wxuserInfo);
			$userinfo = json_decode($userinfo,true);
			session_start();
			$_SESSION['token'] = $userinfo['token'];
			$_SESSION['uid'] = $userinfo['userid'];
			$_SESSION['tel'] = $userinfo['tel'];
			header("Location: http://www.luhaiya.com/RentBycle/#/rent");
			exit;
		}else{
			header("Location: http://www.luhaiya.com/RentBycle/#/rent");
			exit;
		}
		break;
}