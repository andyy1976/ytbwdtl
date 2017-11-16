<?php
session_start();     //产生session 把值传入
$pay_sn = isset($_GET['pay_sn'])?$_GET['pay_sn']:'';
$key = isset($_GET['key'])?$_GET['key']:'';
$payment_code = isset($_GET['payment_code'])?$_GET['payment_code']:'';
if(empty($payment_code) && empty($pay_sn) && empty($key)){
	header('index.php');
}else{
$_SESSION['pay_sn']=$pay_sn;
$_SESSION['key']=$key;
$_SESSION['payment_code']=$payment_code;
$appid = 'wxf5d107dd7e58f2dd';
header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http://www.wandiantonglian.com/wx/autho.php&response_type=code&scope=snsapi_userinfo&state=123&connect_redirect=1#wechat_redirect');
}
?>