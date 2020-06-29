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

	case "POST":

		$ret = postProfiletag($_POST);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;

	case "DELETE":

		/**
		 * parse_str(file_get_contents('php://input'), $param);
		 */		
		parse_str(file_get_contents('php://input'), $param);
		$ret = deleteProfiletag($param);
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



//ユーザーのプロフィールタグ登録
function postProfiletag($param){

	$ret = [
		'success' => true,
		'msg' => "",
    ];
    
	//$db = new DB();
	try{
		if(empty($param['member_id']))			throw new ErrorException($errmsg."member_id");
        if(empty($param['event_tag']))			throw new ErrorException($errmsg."event_tag");
        $sql= "INSERT INTO member_tag(
			   member_id,event_tag)
               VALUES(:member_tag,:event_tag)";
              
		$stmt = PDO()->prepare($sql);
        $stmt -> bindValue(':member_id',  $param['member_id'],  PDO::PARAM_INT);
        $stmt -> bindValue(':event_tag',  $param['event_tag'],  PDO::PARAM_INT);
		$stmt -> execute();
		
		$ret['data'] = "success";

	}catch(Exception $err){
		//exceptionErrorPut($err, "EXCEPTION");
		$ret['success'] = false;
		$ret['msg'] = "[".date("Y-m-d H:i:s")."]".$err->getMessage();
	}

	return $ret;
}



/*タグ削除*/
function deleteProfiletag($param){

    $ret = [
		'success' => true,
		'msg' => "",
    ];
    
	//$db = new DB();
	try{
        if(empty($param['member_id']))			throw new ErrorException($errmsg."member_id");
        if(empty($param['event_tag']))			throw new ErrorException($errmsg."event_tag");
		$sql= "DELETE FROM member_tag
               WHERE member_id = :member_id
               AND event_tag = :event_tag";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':member_id',  $param['member_id'],  PDO::PARAM_INT);
		$stmt -> bindValue(':event_tag',  $param['event_tag'],  PDO::PARAM_INT);
		$stmt -> execute();
		
		
		$ret['data'] = "success";

	}catch(Exception $err){
		//exceptionErrorPut($err, "EXCEPTION");
		$ret['success'] = false;
		$ret['msg'] = "[".date("Y-m-d H:i:s")."]".$err->getMessage();
	}

	return $ret;
    
}

?>