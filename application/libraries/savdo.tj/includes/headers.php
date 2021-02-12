<?php
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Api-Key, Locale');

$headers = getallheaders();
$apikey = $headers['Api-Key'];
$locale = strtoupper($headers['Locale']);
$ip = $_SERVER['REMOTE_ADDR'];

if(!isset($locale) or $locale == ""){
	$locale = "EN";
}
if($locale == "RU"){
	$locale = "";
}

if ($locale == "TJ") {
    include("includes/locale/tj.php");
} else if($locale == ""){
	include ("includes/locale/ru.php");
} else {
	$locale = "EN";
	include ("includes/locale/en.php");
}

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
	http_response_code(400);
	$out = array(
		"result" => 28,
		"msg" => sprintf(MSG_INVALID_CONTENT_TYPE, "application/json")
	);
    die(json_encode($out));	
}

?>