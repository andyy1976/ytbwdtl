	<?php
	//如果需要用证书加密，使用phpseclib包
	set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
	require("File/X509.php"); 
	require("Crypt/RSA.php");

	//如果不用证书加密，使用php_rsa.php函数
	require_once("./php_rsa.php"); 
	
	//测试商户的key! 请修改。
	$md5key = "1234567890";
	
	$merchantId=$_POST["merchantId"];
	$version=$_POST['version'];
	$language=$_POST['language'];
	$signType=$_POST['signType'];
	$payType=$_POST['payType'];
	$issuerId=$_POST['issuerId'];
	$paymentOrderId=$_POST['paymentOrderId'];
	$orderNo=$_POST['orderNo'];
	$orderDatetime=$_POST['orderDatetime'];
	$orderAmount=$_POST['orderAmount'];
	$payDatetime=$_POST['payDatetime'];
	$payAmount=$_POST['payAmount'];
	$ext1=$_POST['ext1'];
	$ext2=$_POST['ext2'];
	$payResult=$_POST['payResult'];
	$errorCode=$_POST['errorCode'];
	$returnDatetime=$_POST['returnDatetime'];
	$signMsg=$_POST["signMsg"];
	$bufSignSrc="";
	if($merchantId != "")
	$bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
	if($version != "")
	$bufSignSrc=$bufSignSrc."version=".$version."&";		
	if($language != "")
	$bufSignSrc=$bufSignSrc."language=".$language."&";		
	if($signType != "")
	$bufSignSrc=$bufSignSrc."signType=".$signType."&";		
	if($payType != "")
	$bufSignSrc=$bufSignSrc."payType=".$payType."&";
	if($issuerId != "")
	$bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
	if($paymentOrderId != "")
	$bufSignSrc=$bufSignSrc."paymentOrderId=".$paymentOrderId."&";
	if($orderNo != "")
	$bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
	if($orderDatetime != "")
	$bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
	if($orderAmount != "")
	$bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
	if($payDatetime != "")
	$bufSignSrc=$bufSignSrc."payDatetime=".$payDatetime."&";
	if($payAmount != "")
	$bufSignSrc=$bufSignSrc."payAmount=".$payAmount."&";
	if($ext1 != "")
	$bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
	if($ext2 != "")
	$bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
	if($payResult != "")
	$bufSignSrc=$bufSignSrc."payResult=".$payResult."&";
	if($errorCode != "")
	$bufSignSrc=$bufSignSrc."errorCode=".$errorCode."&";
	if($returnDatetime != "")
	$bufSignSrc=$bufSignSrc."returnDatetime=".$returnDatetime;
	$ext=explode("|",$ext1);
	//验签
	if($signType == '1')
	{		
		//解析证书方式
		$certfile = file_get_contents('TLCert.cer');		
		$x509 = new File_X509();
		$cert = $x509->loadX509($certfile);
		$pubkey = $x509->getPublicKey();
		$rsa = new Crypt_RSA();
		$rsa->loadKey($pubkey); // public key
		$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
		$verifyResult = $rsa->verify($bufSignSrc, base64_decode(trim($signMsg)));		
		$verify_Result = null;
		$pay_Result = null;
		if($verifyResult){
			$verify_Result = "报文验签成功!";
			if($payResult == 1){
				$pay_Result = "订单支付成功!";
				error_reporting(7);
				$_GET['act']	= 'payment';
				if ($ext[0] == 'opshop_real_order') {
					$_GET['op']	= 'pan_return';
				} else {
					$_GET['op'] = 'return';
				}
				// $_GET['payment_code'] = 'alipay';
				$_GET['payment_code'] = 'tlzf';
				require_once(dirname(__FILE__).'/../../../index.php');
			}else{
				$pay_Result = "订单支付失败!";
			}
		}else{
			$verify_Result = "报文验签失败!";
			$pay_Result = "因报文验签失败，订单支付失败!";
		}
	}
	
	//signType 0 验签
	
	if($payResult == 1)
	{
		$pay_Result_0 = "订单支付成功！";
	}
	else
	{
		$pay_Result_0 = "订单支付失败！";
	}
	
	if($signMsg == strtoupper(md5($bufSignSrc."&key=".$md5key)))
	{	
		$verify_Result_0 = "报文验签成功!";	
	}
	else
	{
		$verify_Result_0 = "报文验签失败!";
	}
	
	
		
?>
	
	
