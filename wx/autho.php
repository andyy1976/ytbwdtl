<?php
$appid = 'wxf5d107dd7e58f2dd';
$appsecret = '1c7bfcdc4e72b8d4a22497470dcca79f';
$code = $_GET["code"]; 
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_token_url); 
curl_setopt($ch,CURLOPT_HEADER,0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
$res = curl_exec($ch); 
curl_close($ch); 
$json_obj = json_decode($res,true); 
//根据openid和access_token查询用户信息 
$access_token = $json_obj['access_token']; 
$openid = $json_obj['openid']; 
session_start(); 
$pay_sn = $_SESSION['pay_sn'];
var_dump($pay_sn);
$key =$_SESSION['key'];
$payment_code = $_SESSION['payment_code'];

/*if(!empty($openid)&&!empty($pay_sn)&&!empty($payment_code)&&!empty($key)){
      header("location:http://".$_SERVER['HTTP_HOST']."/wap/index.php?act=member_payment&op=pd_order'+'&pay_sn=".$pay_sn."&key=".$key."&payment_code=".$payment_code."&openid=".$openid.""); 
}*/
/*echo "<script>window.location.href='mobile/api/payment/wxpay/wxpay.php?openid='".$openid."';</script>";*/   //传值openid到支付接口

//var_dump($openid);

$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN'; 
 
$ch = curl_init(); 
curl_setopt($ch,CURLOPT_URL,$get_user_info_url); 
curl_setopt($ch,CURLOPT_HEADER,0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
$res = curl_exec($ch); 
curl_close($ch); 
 
//解析json 
$user_obj = json_decode($res,true); 
$_SESSION['user'] = $user_obj; 
print_r($user_obj);
?>