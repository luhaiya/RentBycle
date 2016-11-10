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
			return errorInfo(40001);
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
		$db = new dBoperate('BycleInfo');
		if(empty($this->attr['picurl'])){
			return errorInfo(40002);
		}else{
			$this->saveImgFromPost();
			$this->attr['picurl'] = json_encode($this->attr['picurl']);
			$this->attr['tags'] = json_encode($this->attr['tags']);
			$bycleId = $db->insertData($this->attr);
			if($bycleId){
				User::upgrade($this->attr);
				return $bycleId;
			}else{
				return errorInfo(40003);
			}
		}
	}
	public function getInfoByBikeId($bycleId){
		$db = new dBoperate('BycleInfo');
		$sql = 'select * from BycleInfo where bikeid='.$bycleId;
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			return errorInfo(40004);
		}	
	}
	public function getInfoByUserId($uId){
		$db = new dBoperate('BycleInfo');
		$sql = 'select * from BycleInfo where userid='.$uId;
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			return errorInfo(40004);
		}
	}
	public function updateBycleInfo($bycleId){
	//TODO：用户更新自己自行车的信息
	}
	public function getBikeList(){
	//TODO:搜索出符合用户需求的自行车
		$db = new dBoperate('BycleInfo');
		$sql = 'select * from BycleInfo';
		$res = $db->query($sql);
		if(!empty($res)){
			return json_encode($res);
		}else{
			return errorInfo(40004);
		}
	}
}