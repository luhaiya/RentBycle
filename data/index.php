<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：服务器端一些数据接口
 **
 **指令代码说明
 **10001：获取自行车的列表信息
 **10002：获取自行车的详细信息
 **10003：获取用户的详细信息（加密）
 **/
include 'classBycle.php';
$command = $_REQUEST['cid']?$_REQUEST['cid']:0;
switch($command){
	case 10001:
		$bike = new Bycle(6);
		echo $bike->getInfoByBikeId();
		break;
	case 10002:
		break;
	case 10003:
		break;
	default:
		echo "error";
		break;
}