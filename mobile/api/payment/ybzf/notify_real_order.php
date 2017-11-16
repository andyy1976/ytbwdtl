<?php
include 'config.php';
include 'yeepay/yeepayMPay.php';
/**
*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理

*/
$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
try {
	if ($_POST['data']=="" || $_POST['encryptkey'] == "")
	{
		echo "参数不正确！";
		return;
	}
	
	$data=$_POST['data'];
	$encryptkey=$_POST['encryptkey'];
	$return = $yeepay->callback($data, $encryptkey); //解密易宝支付回调结果
	if($return['status']==1){
		$_GET['act']	= 'payment';
		$_GET['op']		= 'ybzf_notify';
		$_GET['type']   = 'real_order';
		$_GET['out_trade_no']=$return['orderid'];
		$_GET['amount']=$return['amount']*0.01;
		require_once(dirname(__FILE__).'/../../../index.php');
	}

}catch (yeepayMPayException $e) {
	echo "支付失败！";
	return;
}
?>





	