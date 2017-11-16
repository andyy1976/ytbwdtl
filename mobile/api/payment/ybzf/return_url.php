<?php
	include 'config.php';
include 'yeepay/yeepayMPay.php';
/**
*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理

*/
$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
try {
	if ($_GET['data']=="" || $_GET['encryptkey'] == "")
	{
		echo "参数不正确！";
		return;
	}
	//echo "success";
	$data=$_GET['data'];
	$encryptkey=$_GET['encryptkey'];
	$return = $yeepay->callback($data, $encryptkey); //解密易宝支付回调结果
	
	$keyname=array_keys($return);
	
	for ($i=0;$i<count($return);$i++)
	{
		echo $keyname[$i].":".$return[$keyname[$i]]."<br>";
	}
	
	if($return['status']==1){
		$_GET['act']	= 'payment';
		$_GET['op']		= 'ybzf_return';
		$_GET['type']   = 'pd_order';
		$_GET['out_trade_no']=$return['orderid'];
		$_GET['amount']=$return['amount']*0.01;
		require_once(dirname(__FILE__).'/../../../index.php');
	}
	// echo "data:".$data."<br>";
	
	
}catch (yeepayMPayException $e) {
	echo "支付失败！";
	return;
}
	
		
?>
	
