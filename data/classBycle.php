<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：自行车类
 **
 **/
require_once 'commonFunc.php';
require_once 'classUser.php';
require_once './dataBase/dataBaseOperate.php';
class Bycle{
	private $attr;
	public function __construct($userId){
		$this->attr = array(
				'userid'=>$userId,
				'picurl'=>array(),
				'tags'=>array(),
				'brand'=>'',
				'price'=>'',
				'state'=>0,
		);
	}
	public function signBycle($para){
		$db = new dBoperate('bycleinfo');
		$this->attr['tags'][] = urlencode($para['tags']);
		$this->attr['price'] = $para['price'];
		$file = "/src/imgs/User/" . createStringByTime() . '.jpg';
		if($para['img']){
			$img = base64_decode($para['img']);
			file_put_contents('..'.$file,$img);
			$this->attr['picurl'][] = '//www.luhaiya.com/RentBycle'.$file;
			$this->attr['picurl'] = json_encode($this->attr['picurl']);
			$this->attr['tags'] = urldecode(json_encode($this->attr['tags']));
			$db->insertData($this->attr);
			User::upgrade($this->attr);
			return true;
		}else{
			if(move_uploaded_file($_FILES['byclepic']['tmp_name'],'..'.$file)){
				zipPic('..'.$file);
				$this->attr['picurl'][] = '//www.luhaiya.com/RentBycle'.$file;
				$this->attr['picurl'] = json_encode($this->attr['picurl']);
				$this->attr['tags'] = urldecode(json_encode($this->attr['tags']));
				$db->insertData($this->attr);
				User::upgrade($this->attr);
				return true;
			}else{
				return false;
			}
		}
	}
	public static function getInfoByBikeId($bycleId){
		$db = new dBoperate('bycleinfo');
		$sql = 'select * from bycleinfo where bikeid='.$bycleId;
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			errorInfo(40004);
			return false;
		}	
	}
	public static function getInfoByUserId($uId){
		$db = new dBoperate('bycleinfo');
		$sql = 'select * from bycleinfo where userid='.$uId;
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			errorInfo(40004);
			return false;
		}
	}
	public static function changeBycleState($bycleId){
		$db = new dBoperate('bycleinfo');
		$sql = 'select state from bycleinfo where bikeid='.$bycleId;
		$res = $db->query($sql);
		$state = decbin($res[0]['state']+1)%10;
		$sql = 'update bycleinfo set state='.$state.' where bikeid='.$bycleId;
		$db->query($sql);
		return true;
	}
	public function getBikeList(){
	//TODO:搜索出符合用户需求的自行车
		$db = new dBoperate('bycleinfo');
		$sql = 'select * from bycleinfo where state=0';
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			errorInfo(40004);
			return false;
		}
	}
}