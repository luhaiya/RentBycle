<?php
/**
 **作者：陆海亚
 **时间：2016－11－1
 **功能：自行车类
 **
 **/
class Bycle{
	private $attr;
	public function __construct($userId){
		$this->attr = array(
				'userId'=>$userId,
				'bikeId'=>0,
				'imgUrl'=>array(),
				'tags'=>array(),
				'brand'=>'',
				'price'=>'',
		);
	}
	public function uploadImg(){
		
	}
	public function getInfoByBikeId(){
		return json_encode($this->attr);
	}
	public function getBikeList(){
	
	}
	public function signInBike(){
		
	}
}