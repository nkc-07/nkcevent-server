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
		$ret = getEventchat($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
	
	case "POST":

		$ret = postEventchat($_POST);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
	
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
	case "DELETE":

		/**
		 * parse_str(file_get_contents('php://input'), $param);
		 */		
		parse_str(file_get_contents('php://input'), $param);
		$ret = deleteEventchat($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
	
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
function getEventchat($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		if(empty($param['event_id']))			throw new ErrorException($errmsg."event_id");

	$sql = "SELECT event_id, member_id, chat_cont, chat_time 
			FROM event_chat 
			WHERE event_id = :event_id";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':event_id', $param['event_id'], PDO::PARAM_INT);
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

function postEventchat($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		if(empty($param['event_id']))			throw new ErrorException($errmsg."event_id");
		if(empty($param['member_id']))			throw new ErrorException($errmsg."member_id");
		if(empty($param['chat_cont']))			throw new ErrorException($errmsg."chat_cont");
		$sql = "INSERT INTO event_chat(
				event_id,
				member_id,
				chat_cont,
				chat_time)
				VALUES(:event_id,:member_id,:chat_cont,CURRENT_TIMESTAMP)";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':event_id',  $param['event_id'],  PDO::PARAM_INT);
		$stmt -> bindValue(':member_id', $param['member_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':chat_cont', $param['chat_cont'], PDO::PARAM_STR);
		//$stmt -> bindValue(':chat_time', CURRENT_TIMESTAMP, PDO::PARAM_STR);
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
function deleteEventchat($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		if(empty($param['chat_id']))			throw new ErrorException($errmsg."chat_id");
		$sql = "DELETE FROM event_chat
			    WHERE chat_id = :chat_id";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':chat_id',  $param['chat_id'],  PDO::PARAM_INT);
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

?>