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

$rtype = RTYPE_CHARGE;

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
$tranid = $data->{'tranid'};
$amount = $data->{'amount'};

if(
    is_numeric($agentid) &&
    is_numeric($tranid) &&
    is_numeric($amount)
){
    $comment = "";

    $params = array(
        $userid,
        $agentid,
        $tranid,
        $amount,
        $comment,
        $ip
    );

    $tsql = "{call PS_Charge(?, ?, ?, ?, ?, ?)}";
    $stmt = sqlsrv_query($conn, $tsql, $params);
    if ($stmt) {
        $row = sqlsrv_fetch_array($stmt);

        if ($row['RESULT'] == 0) {

            if($row['PARENTID'] == 550000){
                $remarks = sprintf(MSG_CHARGE_ROOT, $row['PARENTNAME'], $agentid);
            } else {
                $remarks = sprintf(MSG_CHARGE, $row['PARENTNAME'], $row['AGENTNAME'], $agentid);
            }

            $out = array(
                "result" => $row['RESULT'],
                "msg" => $row['MSG'.$locale],
                "deposit-remarks" => $remarks
            );

        } else if($row['RESULT'] == 11){
            $txn = array(
                "agentid" => $agentid,
                "amount" => $row['AMOUNT'],
                "datetime" => $row['CREATED']
            );
            $out = array(
                "result" => $row['RESULT'],
                "msg" => $row['MSG'.$locale],
                "duplicate-info" => $txn
            );

        } else {
            $out = array(
                "result" => $row['RESULT'],
                "msg" => $row['MSG'.$locale]
            );
        }
        
    } else {
        $out = array(
            "result" => 1,
            "msg" => MSG_SERVICE_UNVAILABLE
        );
    }
    die(json_encode($out));

} else {
	http_response_code(400);
	$err = array();
	if(!is_numeric($agentid)){array_push($err, "{agenid}");}
	if(!is_numeric($tranid)){array_push($err, "{tranid}");}
	if(!is_numeric($amount)){array_push($err, "{amount}");}
	$out = array(
		"result" => 28,
		"msg" => sprintf(MSG_INVALID_INPUT_DATA, implode(", ", $err))
	);
	die(json_encode($out));
}
 
?>