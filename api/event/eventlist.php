<?php

require_once(__DIR__.'/../../php/Define.php');
require_once(__DIR__.'/../../php/db.php');
//require_once(__DIR__.'/../../php/ErrorHandling.php');


$response = [];
$resary = [
	'success'=> true,
	'code' => 200,
	'msg' => "",
];

switch($_SERVER['REQUEST_METHOD']){
	case "GET":

		$param = $_GET;		
		$ret = getEventList($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
    
    /*
	case "POST":

		$ret = postParticipant($_POST);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
	*/
	/*
	case "PUT":

		parse_str(file_get_contents('php://input'), $param);
		$ret = putEventchat($param);
		if($ret['success']){
			//$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
    */
    /*
	case "DELETE":

		
		// parse_str(file_get_contents('php://input'), $param);
				
		parse_str(file_get_contents('php://input'), $param);
		$ret = deleteParticipant($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;;
    */
    
	default:
		$resary['success'] = false;
		$resary['code'] = 405;
		$resary['msg'] = "許可されていないリクエストです。";
		break;
}

header("Content-Type: application/json; charset=utf-8");

if($resary['success']){
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
}else{
	http_response_code($resary['code']);
	$response['msg'] = $resary['msg'];
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
}



/**
 * ----------------------------------------------------------------------------
 * 	以下関数
 * ----------------------------------------------------------------------------
 */

/**
 * 
 */
function getEventList($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		$flag_Eid = 0;
		$flag_Ename = 0;
		$flag_Tid = 0;
		$sql = "SELECT event_id, event_name, map, 'image', held_date,	
					m.nickname as organizer, icon, event_cancellation, member_limit
				FROM 'event' e
				INNER JOIN member m
				ON e.organizer = m.member_id
				WHERE held_date >= CURDATE()";

		if($param['event_id']){ 
			$sql .= "AND event_id = :event_id";
			$flag_Eid = 1;
		}
		if($param['event_name']){
			$sql .= "AND event_name = :event_name";
			$flag_Ename = 1;
		}
			if($param['tag_id']){
			$sql .= "AND tag_id = :tag_id";
			$flag_Tid = 1;
		}
		$stmt = PDO()->prepare($sql);
		if($flag_Eid == 1)
			$stmt -> bindValue(':event_id', $param['event_id'], PDO::PARAM_INT);
		if($flag_Ename == 1)
			$stmt -> bindValue(':event_name', $param['event_name'], PDO::PARAM_STR);
		if($flag_Tid ==1)
			$stmt -> bindValue(':tag_id', $param['tag_id'], PDO::PARAM_INT);
		$stmt -> execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$ret['data'] = $data;

	}catch(Exception $err){
		//exceptionErrorPut($err, "EXCEPTION");
		$ret['success'] = false;
		$ret['msg'] = "[".date("Y-m-d H:i:s")."]".$err->getMessage();
	}

	return $ret;
}

/*
function postParticipant($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		if(empty($param['event_id']))			throw new ErrorException($errmsg."event_id");
        if(empty($param['member_id']))			throw new ErrorException($errmsg."member_id");
        $sql= "INSERT INTO event_participant(
			event_id,
			member_id)
			VALUES(:event_id,:member_id)";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':event_id',  $param['event_id'],  PDO::PARAM_INT);
		$stmt -> bindValue(':member_id', $param['member_id'], PDO::PARAM_INT);
		$stmt -> execute();
		//$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$ret['data'] = "success";

	}catch(Exception $err){
		//exceptionErrorPut($err, "EXCEPTION");
		$ret['success'] = false;
		$ret['msg'] = "[".date("Y-m-d H:i:s")."]".$err->getMessage();
	}

	return $ret;
}
*/
/*
function deleteParticipant($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		if(empty($param['event_id']))			throw new ErrorException($errmsg."event_id");
        if(empty($param['member_id']))			throw new ErrorException($errmsg."member_id");
		$sql= "DELETE FROM event_participant
               WHERE event_id = :event_id
               AND   member_id = :member_id";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':event_id',   $param['event_id'],   PDO::PARAM_INT);
        $stmt -> bindValue(':member_id',  $param['member_id'],  PDO::PARAM_INT);
        $stmt -> execute();
		//$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$ret['data'] = "success";

	}catch(Exception $err){
		//exceptionErrorPut($err, "EXCEPTION");
		$ret['success'] = false;
		$ret['msg'] = "[".date("Y-m-d H:i:s")."]".$err->getMessage();
	}

	return $ret;
}
*/
?>