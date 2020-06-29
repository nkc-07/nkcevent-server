<?php

require_once("Define.php");

try {
    $dbh = new PDO(
        PDO_DSN,
        PDO_USER,
        PDO_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
    );

} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>