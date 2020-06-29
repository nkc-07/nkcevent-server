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
	/*
	case "GET":

		$param = $_GET;		
		$ret = getEventCancellation($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
	
    
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
	
	case "PUT":

		parse_str(file_get_contents('php://input'), $param);
		$ret = putEventCancellation($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

        break;
        
    /*    
	case "DELETE":

		
	    //parse_str(file_get_contents('php://input'), $param);
				
		parse_str(file_get_contents('php://input'), $param);
		$ret = deleteParticipant($param);
		if($ret['success']){
			$response['data'] = $ret['data'];
		}else{
			$resary['success'] = false;
			$resary['code'] = 400;
			$resary['msg'] = $ret['msg'];
		}

		break;
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
function getEventCancellatioin($param){

	$ret = [
		'success' => true,
		'msg' => "",
	];

	//$db = new DB();
	try{
		if(empty($param['event_id']))			throw new ErrorException($errmsg."event_id");

		$sql = "SELECT event_id, event_cancellation
                FROM 'event'
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

function putEventCancellation($param){
    $ret = [
        'success' => true,
        'msg' => "",
    ];

    //$db = new DB();
    try{
        if(empty($param['event_id']))					throw new ErrorException($errmsg."event_id");
        if(empty($param['event_cancellation']))			throw new ErrorException($errmsg."event_cancellation");

        $sql = "UPDATE 'event'
                SET event_cancellation = :event_cancellation
                WHERE event_id = :event_id";
        $stmt = PDO() -> prepare($sql);
        $stmt -> bindValue(':event_id', 		  $param['event_id'], 			PDO::PARAM_INT);
        $stmt -> bindValue(':event_cancellation', $param['event_cancellation'], PDO::PARAM_INT);
        $stmt -> execute();
        //$data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $ret['data'] = "success";

    }catch(Exception $err){
		//exceptionErrorPut($err, "EXCEPTION");
		$ret['success'] = false;
		$ret['msg'] = "[".date("Y-m-d H:i:s")."]".$err->getMessage();
    }
    
    return $ret;

}

?>