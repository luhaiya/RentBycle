<?php
/**
 **作者：陆海亚
 **时间：2016－11－10
 **功能：用户类
 **
 **/
require_once './dataBase/dataBaseOperate.php';
require_once 'commonFunc.php';
class User{
	private $attr;
	public function __construct($attr){
		$table = 'userinfo';
		$where = 'wxid="'.$attr['openid'].'"';
		$data = $this->isExist($table, $where);
		if($data){
			$data = json_decode($data, true);
			$this->attr = array(
					'userid'=>$data[0]['userid'],
					'wxid'=>$data[0]['wxid'],
					'nickname'=>$data[0]['nickname'],
					'sex'=>$data[0]['sex'],
					'headimgurl'=>$data[0]['headimgurl'],
					'tel'=>$data[0]['tel'],
					'pwd'=>$data[0]['pwd'],
					'type'=>$data[0]['type'],
					'token'=>$data[0]['token'],
			);//暂时不考虑微信信息更新
		}else{
			$this->attr = array(
					'wxid'=>$attr['openid'],
					'nickname'=>$attr['nickname'],
					'sex'=>$attr['sex'],
					'headimgurl'=>$attr['headimgurl'],
					'tel'=>'',
					'pwd'=>'',
					'type'=>1,
					'token'=>getRandChar(12),
			);
			$db = new dBoperate('userinfo');
			$db->insertData($this->attr);
			$table = 'userinfo';
			$where = 'wxid="'.$attr['openid'].'"';
			$data = $this->isExist($table, $where);
			if($data){
				$data = json_decode($data, true);
				$this->attr['userid'] = $data[0]['userid'];
			}
		}
	}
	public function getUserInfo(){
		return json_encode($this->attr);
	}
	public function isExist($table, $where){
		$db = new dBoperate('userinfo');
		$sql = "select * from $table where $where";
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			return false;
		}
	}
	public function upgrade($attr){
		$db = new dBoperate('userinfo');
		if(!empty($attr['picurl'])&&!empty($attr['userid'])){
			$db->updateData(array('type'=>2),array('userid'=>$attr['userid']));
			return true;
		}else{
			return false;
		}
	}
	public static function checkAndQueryUserInfo($attr){
		if(!empty($attr['userid'])&&!empty($attr['token'])){
			$sql = "select * from userinfo where userid='".$attr['userid']."' and token='".$attr['token']."'";
			$db = new dBoperate('userinfo');
			$res = $db->query($sql);
			if(!empty($res)){
				return json_encode($res);
			}else{
				return false;
			}
		}
	}
	public static function setting($attr){
		$db = new dBoperate('userinfo');
		if($attr['tel']&&$attr['pwd']){
			$db->updateData(array('tel'=>$attr['tel'],'pwd'=>$attr['pwd']),array('userid'=>$attr['userid']));
			return true;
		}else{
			return false;
		}
	}
	public static function loginin($attr){
		if($attr['tel']&&$attr['pwd']){
			$sql = "select * from userinfo where tel='".$attr['tel']."' and pwd='".$attr['pwd']."'";
			$db = new dBoperate('userinfo');
			$res = $db->query($sql);
			if(!empty($res)){
				return $res;
			}else{
				return false;
			}
		}
	}
	public static function getUserInfoById($id){
		$db = new dBoperate('userinfo');
		$sql = 'select * from userinfo where userid='.$id;
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			errorInfo(40006);
			return false;
		}
	}
}