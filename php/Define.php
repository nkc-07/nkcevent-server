<?php

/**
 * ----------------------------------------------------------------------------
 * 以下定数
 * ----------------------------------------------------------------------------
 * 
 */

date_default_timezone_set ('Asia/Tokyo');

define("SERVER_HOST",			"http://localhost:3306");

define("PDO_DSN",				"mysql:host=localhost:3306;dbname=卒研7班_西郷");
define("PDO_USER",				"sa");
define("PDO_PASS",				"P@ssw0rd");

define("ERROR_EXCEPTION_LOG",	dirname(__DIR__)."/log");	// ログを吐き出すディレクトリ

define("DATETIME",				date("Y-m-d H:i:s"));

define("CURRENT_TIMESTAMP",		"CURRENT_TIMESTAMP");		// sqlで使う、サーバの時刻に依存するので必要ならここで変える
define("DATE_INIT_VALUE",		"1899-12-30 00:00:00");		// 日付の初期値

/**
 * ----------------------------------------------------------------------------
 * 汎用関数
 * ----------------------------------------------------------------------------
 * 
 */


/**
 * PDO作成して返す
 * 
 */
function PDO(){
	$pdo	= new PDO(PDO_DSN,PDO_USER,PDO_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $pdo;
}

?>