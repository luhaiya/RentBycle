<?php


include 'UserAndBike.php';
$use1 = new user(1);
//$use1->register(3, "shuai", "18811384119", 1);
//$use1->deleteinfo(2);
$wxid="hehe";
$use1->updateinfo($wxid);
?>