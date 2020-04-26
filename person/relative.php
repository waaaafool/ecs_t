<?php
$dir = dirname(dirname(__FILE__));
require_once($dir . '/config.php');

//userId 是可选参数，如果userId有值，说明是获取自已的channel列表，没有值是获取所有的channel。
$userId    = array_key_exists('userId', $_REQUEST) ? $_REQUEST['userId'] : 0;

$ret_li = get_like_index($userId);
if($ret_li['ret'] != 0){
	echoErr('get_index_like:'.$ret['ret']);
}
$g_like_index = $ret_li['data'];

$ret = get_relative($g_like_index);
if($ret['ret'] != 0){
	echoErr('get_list:'.$ret['ret']);
}
echoK($ret['data']);

function get_like_index($userId){
	global $g_writeMdb;			
	$retArr = array();	
	try{		
		$sql = sprintf("select like_index from rec_user where userId = (%s) limit 1", $userId);	
		
		if(!($pstmt = $g_writeMdb->prepare($sql))){
            $retArr['ret'] = 12;return $retArr;    
        } 
		if($pstmt->execute()){
			$result = $pstmt->fetchAll();        
			$resNum = count($result);	
			
			$info  = array();
			$index = 0;
					
			for($i = 0; $i < $resNum; $i++){							
				$ret_like_index   = $result[$i][0];						
			}		
										
			$retArr['ret']  = 0;
			$retArr['data'] = $ret_like_index;					 
			return $retArr;					
		}else{			
			$retArr['ret'] = 13;return $retArr;	
		}    
	}catch(PDOException $e){	
		$retArr['ret'] = 11;return $retArr;	
	}
	$retArr['ret'] = 10;return $retArr;	
}

function get_relative($g_like_index){
	global $g_writeMdb;			
	$retArr = array();	
	try{		
		
		$sql = "select * from rec_user limit 10000";
		
		if(!($pstmt = $g_writeMdb->prepare($sql))){
            $retArr['ret'] = 12;return $retArr;    
        } 
		if($pstmt->execute()){
			$result = $pstmt->fetchAll();        
			$resNum = count($result);	
			
			$info  = array();
			$index = 0;
			
			$minflag 		= 'f';
			$mincount		= -1;
			
			$firstindex 	= -1;
			$firstcount		= -1;
			
			$secondindex 	= -1;
			$seconcount		= -1;
			
			$thridindex		= -1;
			$thridcount		= -1;
			
			for($i = 0; $i < $resNum; $i++){			
				$like_index 	= $result[$i][7];											
				$temp_count     = get_similiar($like_index, $g_like_index);
				if($temp_count > $mincount){
					if($minflag == 'f'){
						$firstindex 	= $i;
						$firstcount		= $temp_count;
					}
					elseif($minflag == 's'){
						$secondindex 	= $i;
						$seconcount		= $temp_count;
					}
					else{
						$thridindex		= $i;
						$thridcount		= $temp_count;
					}
					
					if($firstcount <= $seconcount && $firstcount <= $thridcount){
						$minflag 		= 'f';
						$mincount		= $firstcount;
					}
					elseif($seconcount <= $firstcount  && $seconcount <= $thridcount){
						$minflag 		= 's';
						$mincount		= $seconcount;
					}
					elseif($thridcount <= $firstcount  && $thridcount <= $seconcount){
						$minflag 		= 't';
						$mincount		= $thridcount;
					}
				}
				
			}		
			if($firstindex != -1){
				$userId         = $result[$firstindex][1];				
				$name           = $result[$firstindex][2];				
				$sex 			= $result[$firstindex][3];	
				$school 		= $result[$firstindex][4];	
				$edu_experience = $result[$firstindex][5];	
				$contact 		= $result[$firstindex][6];	
				$like_index 	= $result[$firstindex][7];					
				
				
				$item = array();				
				$item['userId']    		= $userId;
				$item['name']   		= $name;
				$item['sex'] 			= $sex;
				$item['school'] 		= $school;
				$item['edu_experience'] = $edu_experience;
				$item['contact'] 		= $contact;
				$item['like_index'] 	= $like_index;
				
				$info[$index++]   		= $item;		
			}	
			if($secondindex != -1){
				$userId         = $result[$secondindex][1];				
				$name           = $result[$secondindex][2];				
				$sex 			= $result[$secondindex][3];	
				$school 		= $result[$secondindex][4];	
				$edu_experience = $result[$secondindex][5];	
				$contact 		= $result[$secondindex][6];	
				$like_index 	= $result[$secondindex][7];					
				
				
				$item = array();				
				$item['userId']    		= $userId;
				$item['name']   		= $name;
				$item['sex'] 			= $sex;
				$item['school'] 		= $school;
				$item['edu_experience'] = $edu_experience;
				$item['contact'] 		= $contact;
				$item['like_index'] 	= $like_index;
				
				$info[$index++]   		= $item;		
			}				
			if($thridindex != -1){
				$userId         = $result[$thridindex][1];				
				$name           = $result[$thridindex][2];				
				$sex 			= $result[$thridindex][3];	
				$school 		= $result[$thridindex][4];	
				$edu_experience = $result[$thridindex][5];	
				$contact 		= $result[$thridindex][6];	
				$like_index 	= $result[$thridindex][7];					
				
				
				$item = array();				
				$item['userId']    		= $userId;
				$item['name']   		= $name;
				$item['sex'] 			= $sex;
				$item['school'] 		= $school;
				$item['edu_experience'] = $edu_experience;
				$item['contact'] 		= $contact;
				$item['like_index'] 	= $like_index;
				
				$info[$index++]   		= $item;		
			}			
			$retArr['ret']  = 0;
			$retArr['data'] = $info;					 
			return $retArr;					
		}else{			
			$retArr['ret'] = 13;return $retArr;	
		}    
	}catch(PDOException $e){	
		$retArr['ret'] = 11;return $retArr;	
	}
	$retArr['ret'] = 10;return $retArr;	
}
function get_similiar($str, $str1){
	$len = strlen(&str);
	$retint = 0;
	for($i = 0; $i < $len; $i++){
		if($str[$i] == $str1[$i]){
			$retint++;
		}
	}
	return $retint;
}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
