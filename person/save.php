<?php

$dir = dirname(dirname(__FILE__));
require_once($dir . '/config.php');

//保存列表的接口


if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])){
	$GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents('php://input');
}		
$postStr = $GLOBALS['HTTP_RAW_POST_DATA']; //接收安卓端的数据

if(empty($postStr)){
	$dataArr = $_REQUEST;//会自动urldecode，其它端
}else{
	$postArr = explode('&', $postStr);
	$dataArr = array();
	foreach($postArr as $v){
		$item = explode('=', $v);
		$key   = $item[0];
		$value = $item[1];
		$dataArr[$key] = $value;	
	}
}

$userId              = array_key_exists('userId', $dataArr) ? $dataArr['userId'] : 0;
$name  	             = array_key_exists('name', $dataArr) ? $dataArr['name'] : -1;
$sex                 = array_key_exists('sex', $dataArr) ? $dataArr['sex'] : 0;
$school              = array_key_exists('school', $dataArr) ? $dataArr['school'] : 0;
$edu_experience      = array_key_exists('edu_experience', $dataArr) ? $dataArr['edu_experience'] : 0;
$contact             = array_key_exists('contact', $dataArr) ? $dataArr['contact'] : 0;
$like_index          = array_key_exists('like_index', $dataArr) ? $dataArr['like_index'] : 0;



logf("保存person：$userId  $name $sex   $school $edu_experience $contact $like_index ");

$ret = save_to_rec_user($userId, $name, $sex , $school, $edu_experience, $contact, $like_index );
if($ret != 0){
	echoErr('save_to_rec_user_failed:'.$ret);
}
echoK('success');




function save_to_rec_user($userId, $name, $sex , $school, $edu_experience, $contact, $like_index ){	
	global $g_writeMdb;			
	try{	
		$sql = "insert into rec_user (userId, name, sex, school, edu_experience,contact,like_index) values (?,?,?,?,?,?,?)";
		if(!($pstmt = $g_writeMdb->prepare($sql))){           
            return 13;    
        } 
		if($pstmt->execute(array($userId, $name, $sex, $school, $edu_experience, $contact, $like_index))){				
			return 0;
		}else{
			return 14;
		}		
	}catch(PDOException $e){
		return 11;
	}	
	return 10;
}