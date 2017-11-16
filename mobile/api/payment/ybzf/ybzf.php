
<?php
header("Content-type: text/html; charset=utf-8"); 
include("yeepay/yeepayMPay.php");
include("config.php");
// //获取访客IP
// public function Getip(){
//    if(!empty($_SERVER["HTTP_CLIENT_IP"])){   
//       $ip = $_SERVER["HTTP_CLIENT_IP"];
//    }
//    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //获取代理ip
//     $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
//    }
//    if($ip){
//       $ips = array_unshift($ips,$ip); 
//    }
   
//    $count = count($ips);
//    for($i=0;$i<$count;$i++){   
//      if(!preg_match("/^(10|172\.16|192\.168)\./i",$ips[$i])){//排除局域网ip
//       $ip = $ips[$i];
//       break;    
//       }  
//    }  
//    $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR']; 
//    if($tip=="127.0.0.1"){ //获得本地真实IP
//       return $this->get_onlineip();   
//    }else{
//       return $tip; 
//    }
// }
$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
$cardno          =  '';
$idcardtype      =  '';
$idcard          =  '';
$owner           =  '';
$order_id        = $pdr_sn;
$transtime       =  time();
$amount          =  $amout*100;
$currency        =  156;
if($type=='real_order'){
	
	$product_catalog =  '7';
    $product_name    =  $goodsname;
}else{
	$product_catalog =  '1';
	if($result['data']['pdr_type']=='1'){
		$moneys=$amout*20;
	}elseif($result['data']['pdr_type']=='2'){
		$moneys=$amout*12.5;
		$moneys=number_format($moneys,2,'--','');

	}else{
		$moneys=ceil($amout);
	}
	$product_name    =  '购买云豆';
}
// if($this->member_info['member_id']=='10088'){
//     echo $goodsname;
//     exit;
// }

$product_desc    =  '';
$identity_type   =  2;
$identity_id     =  $this->member_info['member_id'];
$user_ip         =  $this->Getip();
$paytool         =  '';
$directpaytype   =  0;
$user_ua         =  $_SERVER['HTTP_USER_AGENT'];
$terminaltype    =  1;
$terminalid      =  '44-45-53-54-00-00';
$callbackurl     =  'https://'.$_SERVER['HTTP_HOST'].'/mobile/api/payment/ybzf/notify_'.$type.'.php';

$fcallbackurl     =  'https://'.$_SERVER['HTTP_HOST'].'/mobile/api/payment/ybzf/return_url.php';
$orderexp_date    =  60;
$paytypes        = '';
$version         = 0;
// $data = $yeepay->webPay($order_id,$transtime,$amount,$cardno,$idcardtype,$idcard,$owner,$product_catalog,$identity_id,$identity_type,$user_ip,$paytool,$directpaytype,$user_ua,
// 	$callbackurl,$fcallbackurl,$currency,$product_name,$product_desc,$terminaltype,$terminalid,$orderexp_date,$paytypes,$version);
// print_r($data);exit;

echo "<script>alert('APP充值正在优化中，请暂停使用！！');window.location.href='http://wandiantonglian.com'</script>";exit;
// if( array_key_exists('error_code', $data))	
// return; 
// 	if($paytool =='2')
//  	{}
//  	else{
//  $url=$data['payurl'];
//   header('Location:'.$url);
//   }
  
?>

