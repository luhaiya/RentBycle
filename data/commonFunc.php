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
		case 40006:
			$error['desc']='租车失败';
			break;
		default:
			$error['desc']='未知错误';
			break;
	}
	return json_encode($error);
}
function zipPic($img,$maxW='300',$maxH='300'){
	$ImgInfo=GetImageSize($img);
	switch($ImgInfo[2]){
		case 1:
			$newimg = @ImageCreateFromGIF($img);
			break;
		case 2:
			$newimg = @ImageCreateFromJPEG($img);
			break;
		case 3:
			$newimg = @ImageCreateFromPNG($img);
			break;
		default:
			$newimg = @ImageCreateFromJPEG($img);
			break;
	}
	$w = ImagesX($newimg);
	$h = ImagesY($newimg);
	$width = ImagesX($newimg);
	$height = ImagesY($newimg);
	if($width>$maxW){
		$Par = $maxW/$width;
		$width = $maxW;
		$height = $height*$Par;
		if($height>$maxH){
			$Par=$maxH/$height;
			$height=$maxH;
			$width=$width*$Par;
		}
	}
	if($height>$maxH){
		$Par=$maxH/$height;
		$height=$maxH;
		$width=$width*$Par;
		if($width>$maxW){
			$Par = $maxW/$width;
			$width = $maxW;
			$height = $height*$Par;
		}
	}
	$nImg = ImageCreateTrueColor($width,$height);
	ImageCopyReSampled($nImg,$newimg,0,0,0,0,$width,$height,$w,$h);
	ImageJpeg ($nImg,$img);
	return true;
}
//拆分词语为单个字符
function split_name($name) {
	preg_match_all("/./u", $name, $arr);
	return $arr[0];
}
//最长公共子序列
function LCS($str_1, $str_2) {
	$len_1 = strlen($str_1);
	$len_2 = strlen($str_2);
	$len = $len_1 > $len_2 ? $len_1 : $len_2;
	$dp = array();
	for ($i = 0; $i <= $len; $i++) {
		$dp[$i] = array();
		$dp[$i][0] = 0;
		$dp[0][$i] = 0;
	}
	for ($i = 1; $i <= $len_1; $i++) {
		for ($j = 1; $j <= $len_2; $j++) {
			if ($str_1[$i - 1] == $str_2[$j - 1]) {
				$dp[$i][$j] = $dp[$i - 1][$j - 1] + 1;
			} else {
				$dp[$i][$j] = $dp[$i - 1][$j] > $dp[$i][$j - 1] ? $dp[$i - 1][$j] : $dp[$i][$j - 1];
			}
		}
	}
	return $dp[$len_1][$len_2];
}
function sortByKeyword($data,$key){
	$sort_list = array();
	if (mb_strlen($key, 'utf-8') != strlen($key)) { // 是否全英文字符
		$arr_1 = array_unique(split_name($key));
		foreach ($data as $k=>$v) {
			$value = implode(',',$v);
			$arr_2 = array_unique(split_name($value));
			$similarity = count($arr_2) - count(array_diff($arr_2, $arr_1));
			$sort_list[$k] = $similarity;
		}
	} else {
		foreach ($data as $k=>$v) {
			$value = implode(',',$v);
			$similarity = LCS($key, $value);
			$sort_list[$k] = $similarity;
		}
	}
	arsort($sort_list);
	$res = array();
	foreach($sort_list as $k=>$v){
		$res[] = $data[$k];
	}
	return $res;
}