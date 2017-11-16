<?php
if($_SESSION['member_id']==10088){
	include 'unionpay.class.php';

if ($amout==1234567) {
	$amout = 0.1;
}

// var_dump($amout);

com\unionpay\acp\sdk\Unionpay::getB2cForm(array(
    'orderId' => $pdr_sn, // 订单号，8-32位数字字母
    'txnAmt' => $amout * 100, // 单位分
    'FRONT_NOTIFY_URL' => 'http://'. $_SERVER['HTTP_HOST'] . '/shop/api/payment/unionpay/return_'.$type.'.php',
    'BACK_NOTIFY_URL' => 'https://'. $_SERVER['HTTP_HOST'] . '/shop/api/payment/unionpay/notify_'.$type.'.php'
), true);
}else{
	echo "<script>alert('银联支付正在升级中！！！');window.location.href='https://ytbwdtl.com';</script>";
}

// 