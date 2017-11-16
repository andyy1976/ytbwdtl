<?php
// header("Content-type: text/html; charset=gb2312"); 
include 'yeepayCommon.php';	
	
#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$data = array();

$data['p1_MerId']  = $_REQUEST['p1_MerId'];	
$data['r0_Cmd']    = $_REQUEST['r0_Cmd'];
$data['r1_Code']   = $_REQUEST['r1_Code'];
$data['r2_TrxId']  = $_REQUEST['r2_TrxId'];
$data['r3_Amt']    = $_REQUEST['r3_Amt'];
$data['r4_Cur']    = $_REQUEST['r4_Cur']; 
$data['r5_Pid']    = $_REQUEST['r5_Pid'] ;
$data['r6_Order']  = $_REQUEST['r6_Order'];
$data['r7_Uid']    = $_REQUEST['r7_Uid'];
$data['r8_MP']     = $_REQUEST['r8_MP'] ;
$data['r9_BType']  = $_REQUEST['r9_BType']; 
$data['hmac']      = $_REQUEST['hmac'];
$data['hmac_safe'] = $_REQUEST['hmac_safe'];

//var_dump($data);
//本地签名
$hmacLocal = HmacLocal($data);
// echo "</br>hmacLocal:".$hmacLocal;
$safeLocal = gethamc_safe($data);
// echo "</br>safeLocal:".$safeLocal;


 //验签
if($data['hmac'] != $hmacLocal || $data['hmac_safe'] != $safeLocal) {	
	echo "验签失败";
	return;
} else {
	 if ($data['r1_Code']=="1" ) {


	 	/////////////////////////////////////////////////
	 	// 1 - 浏览器重定向
	 	/////////////////////////////////////////////////
        if($data['r9_BType']=="1"){
        	
			$_GET['act']	= 'payment';
			$_GET['op']		= 'pan_notify';
			$_GET['out_trade_no']=$data['r6_Order'];
			$_GET['payment_code']='ybzf';
			require_once(dirname(__FILE__).'/../../../index.php');

		}



		/////////////////////////////////////////////////
		// 2 - 服务器点对点通讯
		/////////////////////////////////////////////////
		elseif($data['r9_BType']=="2"){
			$_GET['act']	= 'payment';
			$_GET['op']		= 'pan_notify';
			
			$_GET['out_trade_no']=$data['r6_Order'];
			$_GET['payment_code']='ybzf';
			require_once(dirname(__FILE__).'/../../../index.php');
			#如果需要应答机制则必须回写success.
			echo "SUCCESS";
			return;
		}

    }
}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通知回调</title>
</head>

</html>



	




