<?php
/**
 **作者：陆海亚
 **时间：2016－11－9
 **功能：记录验证用户的登录状态
 **/
// header("Cache-Control: no-cache");
// header("Pragma: no-cache");
session_start();
if(!empty($_SESSION)){
	if($_SESSION['token']){
		echo "window.token='",$_SESSION['token'],"';window.uid=",$_SESSION['uid'],";window.tel='",$_SESSION['tel'],"';";
	}
}else{
	echo "window.token='';window.uid=0;window.tel='';";
}
