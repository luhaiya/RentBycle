<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：对接微信,总控制器
 **
 **/
header("Content-type: text/html; charset=utf-8");
require_once('./classWeixin.php');
require_once('../config/wxConfig.php');
require_once('../commonFunc.php');
session_start();
$rentBycle = new Weixin();
$rentBycle->wxConfig();