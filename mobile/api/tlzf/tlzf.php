<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="Content-Language" content="zh-CN"/>
	<meta http-equiv="Expires" CONTENT="0">        
	<meta http-equiv="Cache-Control" CONTENT="no-cache">        
	<meta http-equiv="Pragma" CONTENT="no-cache">
	<title>通联网上支付平台通联网上支付平台-商户接口范例-支付请求信息签名</title>
	<link href="css.css" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript">
window.onload=function(){
	document.getElementById("myForm").submit();
}

</script>
<body>	

<?php
//如果需要用证书加密，使用phpseclib包
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
$X509=BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.'phpseclib/File/X509.php';
$RSA=BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.'phpseclib/Crypt/RSA.php';
$rsa=BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.'php_rsa.php';

require_once($X509);

require($RSA);

//如果不用证书加密，使用php_rsa.php函数
require_once($rsa); 


//页面编码要与参数inputCharset一致，否则服务器收到参数值中的汉字为乱码而导致验证签名失败。	
$serverUrl="https://service.allinpay.com/gateway/index.do";
$inputCharset='1';
$host=$_SERVER["HTTP_HOST"];
$pickupUrl='http://'.$host.SHOP_SITE_URL."/api/payment/tlzf/return_url.php";
$receiveUrl='http://'.$host.SHOP_SITE_URL."/api/payment/tlzf/notify_url.php";
$fileType = mb_detect_encoding($pickupUrl, array('UTF-8','GBK','LATIN1','BIG5'));
$host=$_SERVER['HTTP_HOST'];
$version="v1.0";
$language="1";
$signType="1";
$merchantId="109030220612001";
$payerName=$_SESSION['member_id'];
$payerEmail="";	
$payerTelephone="";
$payerIDCard="";
$pid="";
$orderNo=$pdr_sn;
$orderAmount=$amout*100;
$orderDatetime=date('Ymdhis',time());
$orderCurrency='0';
$orderExpireDatetime="";
$productName='';
$productId='1';
$productPrice='';
$productNum='';
$productDesc='';
$ext1=$type.'|'.$gc_id;
$ext2=$_SESSION['member_id'];
$extTL="";
$payType="0"; //payType   不能为空，必须放在表单中提交。
$issuerId=""; //issueId 直联时不为空，必须放在表单中提交。
$pan="";	
$tradeNature="GOODS";
$customsExt="";
$key="1234567890"; 
// 生成签名字符串。

$bufSignSrc=""; 
if($inputCharset != "")
	$bufSignSrc=$bufSignSrc."inputCharset=".$inputCharset."&";		
if($pickupUrl != "")
	$bufSignSrc=$bufSignSrc."pickupUrl=".$pickupUrl."&";		
if($receiveUrl != "")
	$bufSignSrc=$bufSignSrc."receiveUrl=".$receiveUrl."&";		
if($version != "")
	$bufSignSrc=$bufSignSrc."version=".$version."&";		
if($language != "")
	$bufSignSrc=$bufSignSrc."language=".$language."&";		
if($signType != "")
	$bufSignSrc=$bufSignSrc."signType=".$signType."&";		
if($merchantId != "")
	$bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
if($payerName != "")
	$bufSignSrc=$bufSignSrc."payerName=".$payerName."&";		
if($payerEmail != "")
	$bufSignSrc=$bufSignSrc."payerEmail=".$payerEmail."&";		
if($payerTelephone != "")
	$bufSignSrc=$bufSignSrc."payerTelephone=".$payerTelephone."&";	

//需要加密付款人身份证信息
if($payerIDCard != "")
{		
	//测身份证信息认证使用商户号：20150513442 
	//加密函数从phpseclib调用
	$certfile = file_get_contents('TLCert-test.cer');
	$x509 = new File_X509();
	$cert = $x509->loadX509($certfile);
	$pubkey = $x509->getPublicKey();	
	$rsa = new Crypt_RSA();
	$rsa->loadKey($pubkey);
	$rsa->setPublicKey();
	$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	$ciphertext = $rsa->encrypt($payerIDCard);
	$ciphertext = base64_encode($ciphertext);	
	$payerIDCard = $ciphertext;
	$bufSignSrc=$bufSignSrc."payerIDCard=".$payerIDCard."&";	
}
				
if($pid != "")
	$bufSignSrc=$bufSignSrc."pid=".$pid."&";		
if($orderNo != "")
	$bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
if($orderAmount != "")
	$bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
if($orderCurrency != "")
	$bufSignSrc=$bufSignSrc."orderCurrency=".$orderCurrency."&";
if($orderDatetime != "")
	$bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
if($orderExpireDatetime != "")
	$bufSignSrc=$bufSignSrc."orderExpireDatetime=".$orderExpireDatetime."&";
if($productName != "")
	$bufSignSrc=$bufSignSrc."productName=".$productName."&";
if($productPrice != "")
	$bufSignSrc=$bufSignSrc."productPrice=".$productPrice."&";
if($productNum != "")
	$bufSignSrc=$bufSignSrc."productNum=".$productNum."&";
if($productId != "")
	$bufSignSrc=$bufSignSrc."productId=".$productId."&";
if($productDesc != "")
	$bufSignSrc=$bufSignSrc."productDesc=".$productDesc."&";
if($ext1 != "")
	$bufSignSrc=$bufSignSrc."ext1=".$ext1."&";

//如果海关扩展字段不为空，需要做个MD5填写到ext2里
if($ext2 == "" && $customsExt != "")
{
	$ext2 = strtoupper(md5($customsExt));
	$bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
}
else if($ext2 != "")
{
	$bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
}
	
if($extTL != "")
	$bufSignSrc=$bufSignSrc."extTL".$extTL."&";
if($payType != "")
	$bufSignSrc=$bufSignSrc."payType=".$payType."&";		
if($issuerId != "")
	$bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
if($pan != "")
	$bufSignSrc=$bufSignSrc."pan=".$pan."&";	
if($tradeNature != "")
	$bufSignSrc=$bufSignSrc."tradeNature=".$tradeNature."&";
	$bufSignSrc=$bufSignSrc."key=".$key; //key为MD5密钥，密钥是在通联支付网关商户服务网站上设置。

//签名，设为signMsg字段值。
$signMsg = strtoupper(md5($bufSignSrc));	

?>

<!--
	1、订单可以通过post方式或get方式提交，建议使用post方式；
	   提交支付请求可以使用http或https方式，建议使用https方式。
	2、通联支付网关地址、商户号及key值，在接入测试时由通联提供；
	   通联支付网关地址、商户号，在接入生产时由通联提供，key值在通联支付网关会员服务网站上设置。
-->
<!--================= post 方式提交支付请求 start =====================-->
<!--================= 测试地址为 http://ceshi.allinpay.com/gateway/index.do =====================-->
<!--================= 生产地址请在测试环境下通过后从业务人员获取 =====================-->

<body>
<form name="form2" id="myForm" action="<?php echo $serverUrl ?>" method="post">
	<input type="hidden" name="inputCharset" id="inputCharset" value="<?php echo $inputCharset ?>" />
	<input type="hidden" name="pickupUrl" id="pickupUrl" value="<?php echo $pickupUrl?>"/>
	<input type="hidden" name="receiveUrl" id="receiveUrl" value="<?php echo $receiveUrl?>" />
	<input type="hidden" name="version" id="version" value="<?php echo $version?>"/>
	<input type="hidden" name="language" id="language" value="<?php echo $language?>" />
	<input type="hidden" name="signType" id="signType" value="<?php echo $signType?>"/>
	<input type="hidden" name="merchantId" id="merchantId" value="<?php echo $merchantId?>" />
	<input type="hidden" name="payerName" id="payerName" value="<?php echo $payerName?>"/>
	<input type="hidden" name="payerEmail" id="payerEmail" value="<?php echo $payerEmail?>" />
	<input type="hidden" name="payerTelephone" id="payerTelephone" value="<?php echo $payerTelephone ?>" />
	<input type="hidden" name="payerIDCard" id="payerIDCard" value="<?php echo $payerIDCard ?>" />
	<input type="hidden" name="pid" id="pid" value="<?php echo $pid?>"/>
	<input type="hidden" name="orderNo" id="orderNo" value="<?php echo $orderNo?>" />
	<input type="hidden" name="orderAmount" id="orderAmount" value="<?php echo $orderAmount ?>"/>
	<input type="hidden" name="orderCurrency" id="orderCurrency" value="<?php echo $orderCurrency?>" />
	<input type="hidden" name="orderDatetime" id="orderDatetime" value="<?php echo $orderDatetime?>" />
	<input type="hidden" name="orderExpireDatetime" id="orderExpireDatetime" value="<?php echo $orderExpireDatetime ?>"/>
	<input type="hidden" name="productName" id="productName" value="<?php echo $productName?>" />
	<input type="hidden" name="productPrice" id="productPrice" value="<?php echo $productPrice?>" />
	<input type="hidden" name="productNum" id="productNum" value="<?php echo $productNum?>"/>
	<input type="hidden" name="productId" id="productId" value="<?php echo $productId?>" />
	<input type="hidden" name="productDesc" id="productDesc" value="<?php echo $productDesc?>" />
	<input type="hidden" name="ext1" id="ext1" value="<?php echo $ext1?>" />
	<input type="hidden" name="ext2" id="ext2" value="<?php echo $ext2?>" />
	<input type="hidden" name="extTL" id="extTL" value="<?php echo $extTL?>" />
	<input type="hidden" name="payType" value="<?php echo $payType?>" />
	<input type="hidden" name="issuerId" value="<?php echo $issuerId?>" />
	<input type="hidden" name="pan" value="<?php echo $pan?>" />
	<input type="hidden" name="tradeNature" value="<?php echo $tradeNature?>" />
	<input type="hidden" name="customsExt" value="<?php echo $customsExt?>" />
	<input type="hidden" name="signMsg" id="signMsg" value="<?php echo $signMsg?>" />
	<div align="center" style="display:none;"><input type="submit" value="确认付款，到通联支付去啦" align=center/></div>
<!--================= post 方式提交支付请求 end =====================-->
</form>

</body>
</html>