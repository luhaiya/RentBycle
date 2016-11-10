<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：公用函数
 **
 **/
header("Content-type: text/html; charset=utf-8");
function httpCurl($url,$type='get',$res='json',$data=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if($type == 'post'){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	$resData = curl_exec($ch);
	curl_close($ch);
	if($res == 'json'){
		//     		if(curl_errno($ch)){
		//     			return curl_error($ch);
		//     		}else{
		return json_decode($resData, true);
		//     		}
	}else{
		return $resData;
	}
}
/**
 **根据时间戳生成字符串，作为文件名
 **/
function createStringByTime(){
	return date('Y').date('m').date('d').date('H').date('i').date('s').date('u');
}
/**
 **@随机生成字符串，作为文件名
 **@param length指字符串长度
 **/
function getRandChar($length){
	$str = null;
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	$max = strlen($strPol)-1;
	for($i=0;$i<$length;$i++){
		$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	return $str;
}
/**
 **@报错函数
 **@param errid指错误代码
 **/
function errorInfo($errid){
	$error = array('error'=>1);
	switch($errid){
		case 40001:
			$error['desc']='上传自行车图片失败';
			break;
		case 40002:
			$error['desc']="自行车没有图片";
			break;
		case 40003:
			$error['desc']="自行车注册失败";
			break;
		case 40004:
			$error['desc']="获取自行车信息失败";
			break;
		case 40005:
			$error['desc']='微信授权失败';
			break;
		default:
			$error['desc']='未知错误';
			break;
	}
	return json_encode($error);
}