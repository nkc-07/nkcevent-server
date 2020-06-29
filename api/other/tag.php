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
		$ret = getTag($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
	
	case "POST":

		$ret = postTag($_POST);
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
		$ret = deleteTag($param);
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



//ユーザーとイベント用のタグ登録
function postTag($param){

	$ret = [
		'success' => true,
		'msg' => "",
    ];
    
	//$db = new DB();
	try{
		if(empty($param['tag_name']))			throw new ErrorException($errmsg."tag_name");
		$sql= "INSERT INTO tag(
			  tag_name)
			  VALUES(:tag_name)";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':tag_name',  $param['tag_name'],  PDO::PARAM_STR);
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


/*タグ情報の取得*/
function gettag($param){
    $ret = [
		'success' => true,
		'msg' => "",
    ];
    
	//$db = new DB();
	try{
        $sql= "SELECT tag_id, tag_name 
               FROM tag";
		$stmt = PDO()->prepare($sql);
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


/*タグ削除*/
function deleteTag($param){
    $ret = [
		'success' => true,
		'msg' => "",
    ];
    
	//$db = new DB();
	try{
		if(empty($param['tag_id']))			throw new ErrorException($errmsg."tag_id");
		$sql= "DELETE FROM tag
			   WHERE tag_id = :tag_id";
		$stmt = PDO()->prepare($sql);
		$stmt -> bindValue(':tag_id',  $param['tag_id'],  PDO::PARAM_INT);
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