<?php
$dir = dirname(dirname(__FILE__));
require_once($dir . '/config.php');

//直接给前端用的api，用于获取列表


$ret = get_top_100();
if($ret['ret'] != 0){
	echoErr('get_list:'.$ret['ret']);
}
echoK($ret['data']);



function get_top_100(){
	global $g_writeMdb;			
	$retArr = array();	
	try{		
		$sql = "select * from rec_user limit 100";
		
		if(!($pstmt = $g_writeMdb->prepare($sql))){
            $retArr['ret'] = 12;return $retArr;    
        } 
		if($pstmt->execute()){
			$result = $pstmt->fetchAll();        
			$resNum = count($result);	
			
			$info  = array();
			$index = 0;
					
			for($i = 0; $i < $resNum; $i++){	
         			
				$userId         = $result[$i][1];				
				$name           = $result[$i][2];				
				$sex 			= $result[$i][3];	
				$school 		= $result[$i][4];	
				$edu_experience = $result[$i][5];	
				$contact 		= $result[$i][6];	
				$like_index 	= $result[$i][7];					
				
				
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
