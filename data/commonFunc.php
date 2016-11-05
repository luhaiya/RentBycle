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