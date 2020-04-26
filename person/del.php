<?php

$dir = dirname(dirname(__FILE__));
require_once($dir . '/config.php');

//删除列表的接口


$userId  = array_key_exists('userId', $_REQUEST) ? $_REQUEST['userId'] : 0;

logf("$userId 请求删除");
$ret = del_person($userId);
if($ret != 0){
	echoErr('del_person_failed:'.$ret);
}
echoK('success');




function del_list($userId){	
	global $g_writeMdb;		
	try{	
		$sql = "delete from `rec_user` where `userId` = ?  limit 1";			
		if(!($pstmt = $g_writeMdb->prepare($sql))){         
            return 12;    
        } 
		if($pstmt->execute(array($userId))){			
			return 0;
		}else{
			return 13;
		}		
	}catch(PDOException $e){
		return 11;
	}	
	return 10;	
}