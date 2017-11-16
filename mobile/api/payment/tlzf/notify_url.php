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
	$ext=explode("|",$ext1);

	
	
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
	
				
	//验签
	if($signType == '1')
	{
		/*
		//解析publickey.txt文本获取公钥信息
		
		$publickeyfile = './publickey.txt';
		$publickeycontent = file_get_contents($publickeyfile);

		$publickeyarray = explode(PHP_EOL, $publickeycontent);
		$publickey_arr = explode('=',$publickeyarray[0]);
		$modulus_arr = explode('=',$publickeyarray[1]);
		$publickey = trim($publickey_arr[1]);
		$modulus = trim($modulus_arr[1]);
			
			

		$keylength = 1024;
		//验签结果
		$verifyResult = rsa_verify($bufSignSrc,$signMsg, $publickey, $modulus, $keylength,"sha1");
		*/
		
		
		
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
				$host=$_SERVER['HTTP_HOST'];
				
					if($ext[0]=="pd_rechange"){
						$ext1="predeposit";
					}else{
						$ext1="product_buy";
					}	
				$orderAmount=$orderAmount/100;
					$_GET['act']	= 'payment';
$_GET['op']		= 'notify';
//写入日志文件
        header("Content-type: text/html; charset=utf-8");
        $file  = '/data/wwwlogs/notify'.date('y-m-d',time()).'.log';
        $content='\r\n订单号：'.$orderNo.'|金额：'.$payAmount.'|ID：'.$ext2.'|时间:'.date('y-m-d h:i:s',time());
        $f  = file_put_contents($file, $content,FILE_APPEND);
require_once(dirname(__FILE__).'/../../../index.php');
exit;
				}else{
				$pay_Result = "订单支付失败!";
			}
		}else{
			$verify_Result = "报文验签失败!";
			$pay_Result = "因报文验签失败，订单支付失败!";
		}
	}
	
	
	// echo dirname(__FILE__).'/../../../index.php';
	
	
	
	//signType 0 验签
	
	
		
?>
	
	
	
