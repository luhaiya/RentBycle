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
				'state'=>1,
		);
	}
	public function saveImgFromPost(){
		if($_FILE["img"]["name"]){
			$filename = "../src/imgs/User/" . createStringByTime() . 'jpg';
			move_uploaded_file($_FILES["img"]["tmp_name"],$filename);
			$this->attr['picurl'][] = $filename;
			return true;
		}else{
			errorInfo(40001);
			return false;
		}
	}
	public function addTags($string){
		$this->attr['tags'][] = $string;
		return true;
	}
	public function addBrand($string){
		$this->attr['brand'] = $string;
		return true;
	}
	public function addPrice($string){
		$this->attr['price'] = $string;
		return true;
	}
	public function signBycle(){
		$db = new dBoperate('bycleinfo');
		if(empty($this->attr['picurl'])){
			errorInfo(40002);
			return false;
		}else{
			$this->saveImgFromPost();
			$this->attr['picurl'] = json_encode($this->attr['picurl']);
			$this->attr['tags'] = json_encode($this->attr['tags']);
			$bycleId = $db->insertData($this->attr);
			if($bycleId){
				User::upgrade($this->attr);
				return $bycleId;
			}else{
				errorInfo(40003);
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
	public function getInfoByUserId($uId){
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
	public function updateBycleInfo($bycleId){
	//TODO：用户更新自己自行车的信息
	}
	public function getBikeList(){
	//TODO:搜索出符合用户需求的自行车
		$db = new dBoperate('bycleinfo');
		$sql = 'select * from bycleinfo';
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			errorInfo(40004);
			return false;
		}
	}
}