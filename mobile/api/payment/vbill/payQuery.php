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
//读取公钥
$pu_key = file_get_contents($publicKeyFilePath);

//读取私钥
$pi_key = file_get_contents($privateKeyFilePath);

$mercNo         = $_REQUEST['mercNo'];
$tranCd         = $_REQUEST['tranCd'];
$version        = $_REQUEST['version'];
$orderNo        = $_REQUEST['orderNo'];
$ip             = $_REQUEST['ip'];
$encodeType     = $_REQUEST['encodeType'];

$arrayData = array('orderNo' => $orderNo);
$data = json_encode($arrayData);

//数据加密
$encodeData = encode($data,$pu_key);



$signArray = array(
    'mercNo'  => $mercNo,
    'tranCd'  => $tranCd,
    'version' => $version,
    'reqData' => $encodeData,
    'ip'      => $ip
);

$signArray = json_encode($signArray);
$signArray = stripslashes($signArray);

//签名加密
$sign = reqsign($signArray,$pi_key);

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
$pageContents = httpRequestPost($payQueryURL,'_t='.$reqData);

print_r('http返回结果--><br/>'.$pageContents.'<br/>');

//解析json
$de_json = json_decode($pageContents,TRUE);

$mercNo=$de_json['mercNo'];
$tranCd=$de_json['tranCd'];
$orderNo=$de_json['orderNo'];
$resCode=$de_json['resCode'];
$resMsg=$de_json['resMsg'];



if('000000'==$resCode){
	print_r('查询成功'.'<br/>');	
}else{
	print_r('查询失败'.'<br/>');
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

