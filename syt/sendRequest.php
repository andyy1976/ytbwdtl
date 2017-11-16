<?php

include("yeepay/yeepayMPay.php");

include("config.php");

$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
// $cardno          =  trim($_POST['cardno']);
// $idcardtype      =  trim($_POST['idcardtype']);
// $idcard          =  trim($_POST['idcard']);
// $owner           =  trim($_POST['owner']);
// $order_id        =  trim($_POST['orderid']);
// $transtime       =  intval($_POST['transtime']);
// $amount          =  intval($_POST['amount']);
// $currency        =  intval($_POST['currency']);
// $product_catalog =  trim($_POST['productcatalog']);
// $product_name    =  trim($_POST['productname']);
// $product_desc    =  trim($_POST['productdesc']);
// $identity_type   =  intval($_POST['identitytype']);
// $identity_id     =  trim($_POST['identityid']);
// $user_ip         =  trim($_POST['userip']);
// $paytool         =  trim($_POST['paytool']);
// $directpaytype   =  intval($_POST['directpaytype']);
// $user_ua         =  trim($_POST['userua']);
// $terminaltype    =  intval($_POST['terminaltype']);
// $terminalid      =  trim($_POST['terminalid']);
// $callbackurl     =  trim($_POST['callbackurl']);
// $fcallbackurl    =  trim($_POST['fcallbackurl']);
// $orderexp_date   =  intval($_POST['orderexpdate']);
// $paytypes        =  trim($_POST['paytypes']);
// $version         =  intval($_POST['version']);



$orderid = strtotime(date('Y-m-d H:i:s',time())) . rand(1000,9999);
$transtime = time(); //'1498445558';
$amount = '1';
$currency = '156';
$product_catalog = '1';
$product_name = '一键支付-测试';
$product_desc = 'productdesc';
$identity_type = '2';
$identity_id = '123123';
$terminaltype = '1';
$terminalid = '44-45-53-54-00-00';
$paytool = '';
$user_ip = '127.0.0.0';
$directpaytype = '';
$user_ua = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36';
$fcallbackurl = 'http://172.21.0.84/demo/syt/fcallback.php';
$callbackurl = 'http://172.21.0.84/demo/syt/callback.php';
$paytypes = '';
$orderexp_date = '60';
$cardno = '';
$idcardtype = '';
$idcard = '';
$owner = '';
$version = '';


// echo '<pre>';
// print_r($_POST);
// echo '</pre>';
// exit;

$data = $yeepay->webPay(
	$order_id,$transtime,$amount,$cardno,$idcardtype,$idcard,
	$owner,$product_catalog,$identity_id,$identity_type,
	$user_ip,$paytool,$directpaytype,$user_ua,
	$callbackurl,$fcallbackurl,$currency,$product_name,
	$product_desc,$terminaltype,$terminalid,
	$orderexp_date,$paytypes,$version
);

if( array_key_exists('error_code', $data))	
	return; 
	/*if($paytool =='2') {}
 	else{
	 	echo($url);
	  	header('Location:'.$url);
  	}*/
  
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>支付请求成功的响应参数</title>
</head>
	<body>
		<br /> <br />
		<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" 
			style="word-break:break-all; border:solid 1px #107929">
			<tr>
		  		<th align="center" height="30" colspan="5" bgcolor="#6BBE18">
				支付请求成功的响应参数
				</th>
		  	</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商户编号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left">  <?php echo $data['merchantaccount'];?>  </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">merchantaccount</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;商户订单号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['orderid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">orderid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;易宝流水号</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['yborderid'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">yborderid</td> 
			</tr>

			<tr>
				<td width="25%" align="left">&nbsp;支付链接</td>
				<td width="5%"  align="center"> : </td> 
				<td width="50%" align="left"> <?php echo $data['payurl'];?> </td>
				<td width="5%"  align="center"> - </td> 
				<td width="15%" align="left">payurl</td> 
			</tr>



			
	
    	<!--二进制数据生成二维码图片,仅供参考		-->			
			<tr>
				<td width="25%" align="left">&nbsp;二维码图片 </td>
				<td width="5%"  align="center">  ： </td> 
				<td width="50%" align="left"> <?php 
				if(empty($data['imghexstr']))
				{ echo "";} else{
				$img= hex2byte($data['imghexstr']);  
		        $filename = "2weima.png";    // 写入的文件     
		        $file = fopen("./".$filename,"w");// 打开文件准备写入 
		        fwrite($file,$img);// 写入 
		        fclose($file);//关闭  
		        echo "<img src=$filename> ";}
       			?> 
       			</td>
				<td width="5%"  align="center">   </td> 
				<td width="15%" align="left" > </td> 
			</tr>
			
		</table>

	</body>
</html>
