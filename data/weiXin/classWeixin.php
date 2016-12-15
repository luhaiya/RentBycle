<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：微信类
 **
 **/

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
					case 'about':
						$data = array('text'=>'about us');
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
	public function getWxUserInfo(){
		if($_SESSION['access_authtoken'] && $_SESSION['expire_authtime']>time()){
			$flag = 1;
		}else{
			$flag = 0;
			$state = isset($_REQUEST['state'])?$_REQUEST['state']:'';
			$code = isset($_REQUEST['code'])?$_REQUEST['code']:'';
			$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appSecret.'&code='.$code.'&grant_type=authorization_code';
			$res = httpCurl($url, 'get', 'json');
			if(isset($res['errcode'])){
				//TODO:无法获得授权
				$flag = 0;
			}else{
				$access_authtoken = $res['access_token'];
				$refresh_token = $res['refresh_token'];
				$openid = $res['openid'];
				$unionid = isset($res['unionid'])?$res['unionid']:'';
				session_start();
				$_SESSION['access_authtoken'] = $access_authtoken;
				$_SESSION['refresh_token'] = $refresh_token;
				$_SESSION['expire_authtime'] = time()+7000;
				$_SESSION['openid'] = $openid;
				$flag = 1;
			}
		}
		if($flag){
			$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$_SESSION['access_authtoken'].'&openid='.$_SESSION['openid'].'&lang=zh_CN';
			$res = httpCurl($url, 'get', 'json');
			if(isset($res['errcode'])){
				return false;
			}else{
				return json_encode($res);
			}
		}else{
			return false;
		}
	}
	public function rentToUser($attr){
		$url = 'https://www.luhaiya.com/RentBycle/data';
		$data = array(
				"touser"=>$attr['wxid'],
				"template_id"=>tpid,
				"url"=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state=bupt1#wechat_redirect',
				"topcolor"=>"#FF0000",
				"data"=>array(
						"tel"=>array("value"=>$attr['tel'],"color"=>"#173177"),
						"last"=>array("value"=>"欢迎使用！","color"=>"#173177"),
				),
		);
		$data = json_encode($data);
		$accessToken = $this->getWxAccessToken();
		$itemUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$accessToken;
		httpCurl($itemUrl, 'post', 'json', $data);
		return true;
	}
}