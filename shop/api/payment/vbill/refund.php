<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      order.php
 * @package ${NAMESPACE}
 * @author    ma_chao <ma_chao@suixingpay.com>
 */

include 'sxfCommon.php';

header("Content-type: text/html; charset=utf-8");

$mercNo         = $_REQUEST['mercNo'];
$tranCd         = $_REQUEST['tranCd'];
$version        = $_REQUEST['version'];
$ip             = $_REQUEST['ip'];
$payOrderId     = $_REQUEST['payOrderId'];
$tranAmt        = $_REQUEST['tranAmt'];
$ccy             = $_REQUEST['ccy'];
$desc             = $_REQUEST['desc'];
$encodeType     = $_REQUEST['encodeType'];

$data = array('payOrderId' => $payOrderId,'tranAmt' => $tranAmt,'ccy' => $ccy,'desc' => $desc);

$reqData = json_encode($data);

//读取公钥
$pu_key = file_get_contents($publicKeyFilePath);
//读取私钥
$pi_key = file_get_contents($privateKeyFilePath);
//数据加密
$encodeData = encode($reqData,$pu_key);

echo 'encodeData<br>'.$encodeData.'<br>';

$signData = array(
    'mercNo'  => $mercNo,
    'tranCd'  => $tranCd,
    'version' => $version,
    'reqData' => $encodeData,
    'ip'      => $ip
);

$signData = json_encode($signData);
$signData = stripslashes($signData);

echo 'signData<br>'.$signData.'<br>';
//签名加密
$sign = reqsign($signData,$pi_key);
echo 'sign<br>'.$sign.'<br>';

$reqData= array(
    'mercNo'  => $mercNo,
    'tranCd'  => $tranCd,
    'version' => $version,
    'reqData' => $encodeData,
    'ip'      => $ip,
	'encodeType' => $encodeType,
	'sign' => $sign
);
$reqData = json_encode($reqData);
$reqData = stripslashes($reqData);
print_r('数据发送前--><br/>'.$reqData.'<br/>');
$reqData=URLencode($reqData);
print_r('数据发送前URLencode--><br/>'.$reqData.'<br/>');
$pageContents = httpRequestPost($refundURL,'_t='.$reqData);

print_r('http返回结果--><br/>'.$pageContents.'<br/>');

//解析json
$de_json = json_decode($pageContents,TRUE);

$mercNo=$de_json['mercNo'];
$tranCd=$de_json['tranCd'];
$orderNo=$de_json['orderNo'];
$resCode=$de_json['resCode'];
$resMsg=$de_json['resMsg'];
if($resCode=='000000'){
	print_r('发送成功'.'<br/>');
}else{
	print_r('发送失败'.'<br/>');
	return;
}


$resData=$de_json['resData'];
$sign=$de_json['sign'];

//验签 准备数据
$result= array(
    'mercNo'  => $mercNo,
    'orderNo'  => $orderNo,
    'tranCd' => $tranCd,
    'resCode' => $resCode,
    'resMsg'      => $resMsg,
	'resData' => $resData
);

$result=json_encode($result,JSON_UNESCAPED_UNICODE);
$result = stripslashes($result);
print_r('验签内容-->'.'<br/>'.$result.'<br/>');

$sign_result= openssl_verify($result, base64_decode($sign), $pu_key); 

print_r('验签结果--><br/>'.$sign_result.'<br/>');

if(!$sign_result=='1'){
	print_r('验签失败'.'<br/>');
	return;
}
print_r('验签成功'.'<br/>');

//加密加签方式RSA#RSA
$encodeType=$de_json['encodeType'];
//RSA解密
$de_data=base64_decode(decode($resData,$pi_key));
print_r('解密resData结果-->'.$de_data.'<br/>');
?>


