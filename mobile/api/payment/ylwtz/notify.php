<?php

function debugLog ( $filename, $contents ) {
    $filename = __DIR__. '/' . $filename;
    file_put_contents($filename, $contents);
}

$contents = "\nGET\n" . print_r($_GET, true)
		  . "\nPOST\n" . print_r($_POST, true);


// $_POST['data']['payResult'] == '00';
// $_POST['data']['orgSendSeqId']

$data = json_decode($_POST['data'], true);

$mac = $data['mac'];


unset($data['mac']);
ksort($data);
$macstr = implode('', $data);
$salt   = "C5E908D7C1A2F705D5928701118079E8"; //签到后的Mackkey
$b      = $macstr . $salt;  //报文值和Mackkey拼接
$e      = md5($b);  //Md5加密
$macstr = base64_encode($e); //Base64加密

if ( $mac == $macstr ) {

	$filename = $data['orgSendSeqId'] .'.'.time(). '.txt';
	$contents .= "\n\nOK";

	if ($data['payResult'] == '00') {

		$orderNo = $data['orgSendSeqId'];
		$amt = $data['transAmt'];

		$_GET['act']  = 'payment';
		$_GET['op']   = 'unionpay_notify';
		$_GET['type'] = 'pd_order';
		$_GET['out_trade_no'] = $orderNo;
		$_GET['amount'] = $amt/100; // 单位元
		require_once(dirname(__FILE__).'/../../../index.php');

	}

} else {
	$filename = time() . '.txt';
	$contents .= "\n\nNO\n\n";
	$contents .= "\n\n.$macstr .' | '. $mac .\n\n";
	$contents .= "\n\n.print_r($data,true).\n\n";
}


// debugLog( $filename, $contents );