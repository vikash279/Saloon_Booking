<?php 
include_once 'includes/headers.php';
include_once 'includes/config.php';
include_once 'includes/functions.php';

if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
	http_response_code(400);
	$out = array(
		"result" => 28,
		"msg" => sprintf(MSG_INVALID_REQUEST, "POST")
	);
    die(json_encode($out));	
}

$data = json_decode(file_get_contents("php://input"));

if ($data == null){	
	http_response_code(400);
	$out = array(
		"result" => 28,
		"msg" => MSG_INVALID_JSON
	);
	die(json_encode($out));
}

$rtype = RTYPE_CHECK;

/************************* AUTH SECTION ****************************/

$params = array(
	$apikey,
	$rtype,
    $ip
);

$tsql = "{call PS_Auth(?, ?, ?)}";
$stmt = sqlsrv_query($conn, $tsql, $params);
if($stmt) {
	$row = sqlsrv_fetch_array($stmt);
	
	if($row['RESULT'] == 0){
		
        $balance = number_format($row['BALANCE'], 2, '.', '');
        $userid = $row['USERID'];
        $bankid = $row['BANKID'];
        
	} else if($row['RESULT'] == 15) {
		http_response_code(403); //Forbiddden
		$out = array(
			"result" => $row['RESULT'],
			"msg" => $row['MSG'.$locale]
		);
        die(json_encode($out));
	} else {
		http_response_code(401); //Unauthorized
		$out = array(
			"result" => $row['RESULT'],
			"msg" => $row['MSG'.$locale]
		);
        die(json_encode($out));
	}

} else {
	http_response_code(503);
	$out = array(
		"result" => 1,
		"msg" => MSG_SERVICE_UNVAILABLE
	);
    die(json_encode($out));
}

/************************* END OF AUTH ****************************/
$agentid = $data->{'agentid'};

if(
    is_numeric($agentid)
){
    

    $params = array(
        $userid,
        $agentid,
    );

    $tsql = "{call PS_CheckRequest(?, ?)}";
    $stmt = sqlsrv_query($conn, $tsql, $params);
    if ($stmt) {
        $row = sqlsrv_fetch_array($stmt);

        $out = array(
            "result" => $row['RESULT'],
            "msg" => $row['MSG'.$locale],
            "agentid" => $agentid,
            "agentname" => $row['AGENTNAME'],
            "parentid" => $row['PARENTID'],
            "parentname" => $row['PARENTNAME']
        );
        
    } else {
        $out = array(
            "result" => 1,
            "msg" => MSG_SERVICE_UNVAILABLE
        );
    }
    die(json_encode($out));

} else {
	http_response_code(400);
	$out = array(
		"result" => 28,
		"msg" => sprintf(MSG_INVALID_INPUT_DATA, "{agentid}")
	);
	die(json_encode($out));
}
 
?>