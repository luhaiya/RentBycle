<?php
/**
 **作者：陆海亚
 **时间：2016－11－9
 **功能：记录验证用户的登录状态
 **/
session_start();
if(!empty($_SESSION)){
	if($_SESSION['token']){
		echo "window.token=",$_SESSION['token'],";";
	}
}else{
	echo "window.token=0;";
}
