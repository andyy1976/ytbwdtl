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
// if($_SESSION['member_id']==10088){
    $time=strtotime(date('Y-m-d H:i:s'));
    //限制每天凌晨00：00至00：20这个时间段无法充值购买云豆
    $time_1=strtotime(date('Y-m-d'))-300;
    $time_2=strtotime(date('Y-m-d'))+60*20;
    if($time>$time_1 && $time<$time_2){
        showDialog('00：00至00：20系统每日赠送时间。兑换、充值云豆以及激活会员请于00：20之后进行操作。');
        exit;
    }
    
// }
$mercNo         = '600000000000827'; //$_REQUEST['mercNo'];
$tranCd         = '1001';
$version        = '1.0';
$encodeType     = 'RSA#RSA';

// 1:返回页面 
// 2:返回 json 此参数不参加签名，仅作用于支付接口
$returnType = '1'; //$_REQUEST['type'];
$ip = $_SERVER['REMOTE_ADDR'];

// $orderNo        = date('YmdHis'); //$_REQUEST['orderNo'];
// $tranAmt        = '0.12'; //$_REQUEST['tranAmt'];
// $ccy            = $_REQUEST['ccy'];
// $pname          = $_REQUEST['pname'];
// $pnum           = $_REQUEST['pnum'];
// $pdesc          = $_REQUEST['pdesc'];
// $retUrl         = $_REQUEST['retUrl'];
// $notifyUrl      = $_REQUEST['notifyUrl'];
// $bankWay        = $_REQUEST['bankWay'];
// $period         = $_REQUEST['period'];
// $ip             = $_REQUEST['ip'];
// $desc           = $_REQUEST['desc'];
// $userId         = '123'; //$_REQUEST['userId'];
// $ext            = $_REQUEST['ext'];
// $bankCardNo     = $_REQUEST['bankCardNo'];
// $cvv            = $_REQUEST['cvv'];
// $valid          = $_REQUEST['valid'];
// $accountName    = $_REQUEST['accountName'];
// $certificateNo  = $_REQUEST['certificateNo'];
// $mobilePhone    = $_REQUEST['mobilePhone'];

$data = array(
    'orderNo'       => $pdr_sn,
    'tranAmt'       => $amout,
    'ccy'           => 'CNY',
    'pname'         => '商品',
    'pnum'          => '1',
    'pdesc'         => '1',
    'retUrl'        => 'https://ytbwdtl.com/shop/api/payment/vbill/return_'.$type.'.php',
    'notifyUrl'     => 'https://ytbwdtl.com/shop/api/payment/vbill/notify_'.$type.'.php',
    'bankWay'       => '',
    'period'        => '1440', // 订单有效期-  单位：分。不填默认 1440 
    // 'ip'            => $ip,
    'userId'        => $this->member_info['member_id'],
    'desc'          => '订单描述',
    'ext'           => '123',
    'bankCardNo'    => '',
    'cvv'           => '',
    'valid'         => '',
    'accountName'   => '',
    'certificateNo' => '',
    'mobilePhone'   => ''
);

// $payWay = '1'; // 1 随行付收银台/2 直连/3 移动
// $payChannel = '4';
// 0 收银台显示所有配置
// 1 个人网银
// 2 企业网银
// 3 账户支付
// 4 快捷支付
// 5 支付宝
// 6 微信（收银台模式下可多选）

$data['payWay'] = '1';
// $data['payChannel'] = '4';


// Request DebugLog...
$contents = "\nRAW\n" .print_r($data, true);



$reqData = json_encode($data);

//读取公钥
$pu_key = file_get_contents($publicKeyFilePath);

//读取私钥
$pi_key = file_get_contents($privateKeyFilePath);

//数据加密
$encodeData = encode($reqData, $pu_key);

// echo '<hr>';
// echo 'encodeData<br>'. $encodeData .'<br>';
// echo '<hr>';

$signData = array(
    'mercNo'  => $mercNo,
    'tranCd'  => $tranCd,
    'version' => $version,
    'reqData' => $encodeData,
    'ip'      => $ip
);

// print_r($data);
// print_r($signData);
// exit;

$signData = json_encode($signData);
$signData = stripslashes($signData);

// echo '<hr>';
// echo 'signData<br>'.$signData.'<br>';

//签名加密
$sign = reqsign($signData,$pi_key);


// Request DebugLog...
$contents .= "\nENCODE\n" .print_r([
    'mercNo'     => $mercNo,
    'tranCd'     => $tranCd,
    'version'    => $version,
    'encodeData' => $encodeData,
    'ip'         => $ip,
    'encodeType' => $encodeType,
    'sign'       => $sign,
    'type'       => $returnType,
], true);

// $filename = __DIR__. '/' . time() . '.Request.txt';
// file_put_contents($filename, $contents);


// echo '<hr>';
// echo 'sign<br>'.$sign.'<br>';

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>To SXF Page</title>
</head>
<body onload="document.sxf.submit();">
<form name='sxf' action='<?php echo $payURL; ?>' method='post'>
    <input type='hidden' name='mercNo'     value='<?php echo $mercNo;?>'>
    <input type='hidden' name='tranCd'     value='<?php echo $tranCd;?>'>
    <input type='hidden' name='version'    value='<?php echo $version;?>'>
    <input type='hidden' name='reqData'    value='<?php echo $encodeData;?>'>
    <input type='hidden' name='ip'         value='<?php echo $ip;?>'>
	<input type='hidden' name='encodeType' value='<?php echo $encodeType;?>'>
	<input type='hidden' name='sign'       value='<?php echo $sign;?>'>
	<input type='hidden' name='type'       value='<?php echo $returnType;?>'>
</form>
</body>
</html>
