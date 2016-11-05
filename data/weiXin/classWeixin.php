<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：微信类
 **
 **/
require_once('../config/wxConfig.php');
require_once('../commonFunc.php');
class Weixin{
	private $fromUsername;
	private $toUsername;
	private $dataFromUser;
	private $postTime;
	private $appId;
	private $appSecret;
	private $token;
	public function __construct(){
		$this->token = TOKEN;
		$this->appId = APP_ID;
		$this->appSecret = APP_SECRET;
	}
	public function wxConfig(){
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = $this->token;
		$tmpArr = array($timestamp, $nonce, $token);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature && $_GET['echostr']){
			echo $_GET['echostr'];
			exit;
		}else{
			$this->defineMenu();
			$this->responseMsg();
		}
	}
	public function responseMsg(){
		$dataFromWx = $GLOBALS['HTTP_RAW_POST_DATA'];
		if(!empty($dataFromWx)){
			$dataFromWxObi = simplexml_load_string($dataFromWx);
			$this->fromUsername = $dataFromWxObi->ToUserName;
			$this->toUsername = $dataFromWxObi->FromUserName;
			$this->dataFromUser = $dataFromWxObi->Content;
			$this->postTime = time();
			switch(strtolower($dataFromWxObi->MsgType)){
				case 'event':
					$this->responseToEvent(strtolower($dataFromWxObi->Event), $dataFromWxObi);
					break;
				default:
					$data = array('text'=>$this->robotTalk($this->dataFromUser));
					echo $this->createXml('text',$data);
					break;
			}
		}else{
			$data = array('text'=>'抱歉，没听清！请再输入一遍');
			echo $this->createXml('text',$data);
		}
	}
	public function robotTalk($req){
		$api = 'http://www.tuling123.com/openapi/api?key='.robotKey.'&info='.$req;
		$data = httpCurl($api, 'get', 'json');
		if(!empty($data)){
			if($data['code']!=100000){
				return '抱歉，没听清！';
			}else{
				return $data['text'];
			}
		}else{
			return '抱歉，没听清！';
		}
	}
	public function responseToEvent($event, $postObj){
		switch($event){
			case 'subscribe':
				$data = array('text'=>'欢迎订阅我们的公众号，这里你可以查看北邮的在租自行车哦！！！');
				echo $this->createXml('text',$data);
				break;
			case 'click':
				switch($postObj->EventKey){
					case 'signIn':
						$data = array('text'=>'这是注册事件');
						echo $this->createXml('text',$data);
						break;
					case 'talk':
						$data = array('text'=>'听说你想调戏主编，来跟我聊天吧！！！');
						echo $this->createXml('text',$data);
						break;
				}
				break;
			default:
				$data = array('text'=>'您需要触发事件');
				echo $this->createXml('text',$data);
				break;
		}
	}
	public function getWxAccessToken(){
		if($_SESSION['accseeToken'] && $_SESSION['expireTime']>time()){
			return $_SESSION['accessToken'];
		}else{
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;
			$res = httpCurl($url, 'get', 'json');
			$accessToken = $res['access_token'];
			$_SESSION['accessToken'] = $accessToken;
			$_SESSION['expireTime'] = time()+7000;
			return $accessToken;
		}
	}
	public function defineMenu(){
		header('content-type:text/html;charset=utf-8');
		$accessToken = $this->getWxAccessToken();
		$itemUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$accessToken;
		httpCurl($itemUrl, 'post', 'json', MENU);
	}
	public function createXml($type='text', $data){
		$header = "<xml>
		<ToUserName><![CDATA[{$this->toUsername}]]></ToUserName>
		<FromUserName><![CDATA[{$this->fromUsername}]]></FromUserName>
		<CreateTime>{$this->postTime}</CreateTime>
		<MsgType><![CDATA[{$type}]]></MsgType>";
		switch($type){
			case 'text':
				$header .= "<Content><![CDATA[{$data['text']}]]></Content>";
				break;
		}
		$header .='</xml>';
		return $header;
	}
}