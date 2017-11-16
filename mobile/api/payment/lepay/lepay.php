<?php
namespace alleria\test;
	
		// echo '乐支付升级维护中，请暂时使用随行付支付！！！';
		echo "<script>alert('乐支付升级维护中，请暂时使用随行付支付！！！');</script>";
		exit;
include_once "src/alleria/test/SdkTest.php";
$time=strtotime(date('Y-m-d H:i:s'));
//限制每天凌晨00：00至00：20这个时间段无法充值购买云豆
$time_1=strtotime(date('Y-m-d'))-300;
$time_2=strtotime(date('Y-m-d'))+60*20;
if($time>$time_1 && $time<$time_2){
    echo "<script>alert('00：00至00：20系统每日赠送时间。兑换、充值云豆以及激活会员请于00：20之后进行操作。');history.go(-1);</script>";
    exit;
}
$sdk=new SdkTest();

// $array=$sdk->testCreatePay();
// var_dump($array);

//类别
$type=$_REQUEST['type'];
if($type=='bankcard'){
	//订单号
	$request['order_sn']=$_REQUEST['order_sn'];
	//身份证号码
	$request['cardid']=strtolower($_REQUEST['cardid']);
	//姓名
	$request['cardname']=$_REQUEST['cardname'];
	//银行卡号
	$request['acc_no']=$_REQUEST['acc_no'];
	//金额
	$request['amount']=$_REQUEST['amount'];
	//手机号码
	$request['phone']=$_REQUEST['phone'];

	$array=$sdk->testCreatePay($request);

	$data = json_encode($array, JSON_UNESCAPED_UNICODE);
	echo $data;
}
if($type=='code'){
	//订单号
	$request['merchantOrderNo']=$_REQUEST['merchantOrderNo'];
	//平台订单号
	$request['orderNo']=$_REQUEST['orderNo'];
	//验证码
	$request['auth_code']=$_REQUEST['auth_code'];
	//密码
	$request['paypwd']=$_REQUEST['paypwd'];
	

	$array=$sdk->testConfirmPay($request);
	// print_r($array);
	// if($array['success']==true){
	// 	$_GET['act']    = 'payment';
 //        $_GET['op']     = 'lepay_return';
 //        $_GET['type']   = 'pd_order';
 //        $_GET['out_trade_no'] = $array['merchantOrderNo'];
 //        $_GET['amount'] = $array['amount'];
 //        $_GET['time'] = time();
 //        $key  = '@)!&wdtlytb20171';
 //        $_GET['sign'] = md5($_GET['act'].$_GET['op'].$_GET['type'].$_GET['out_trade_no'].$_GET['amount'].$_GET['time'].$key);
 //        require_once(dirname(__FILE__).'/../../../index.php');		
	// }	
	$data = json_encode($array, JSON_UNESCAPED_UNICODE);
	echo $data;
}
// return $data
// $data = json_decode($array1, true);
if($type=='code_agen'){
	//订单号
	$request['merchantOrderNo']=$_REQUEST['merchantOrderNo'];
	//平台订单号
	$request['orderNo']=$_REQUEST['orderNo'];	
	$array=$sdk->testQueryOrder($request);

	$data = json_encode($array, JSON_UNESCAPED_UNICODE);
	echo $data;
}
// $sdk->testConfirmPay();
// $sdk->testQueryOrder();
?>