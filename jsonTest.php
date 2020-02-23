<?php

$_POST = json_decode(file_get_contents('php://input'), true);
$testUN=$_POST->username;
//$testUN = "username1";

$json=json_encode(array("database"=> array("success"=> true, "message"=> "Successful login with $testUN(database)." ),"webnjit"=> array("success"=> null,"message"=> null )));
echo $json;

?>