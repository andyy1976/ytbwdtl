<?php

include_once __DIR__ . '/sdk/acp_service.php';



function debugLog ( $filename, $contents ) {
    $filename = __DIR__. '/' . $filename;
    file_put_contents($filename, $contents);
}
if (!empty($_POST)) {
    ksort($_POST);
    $filename = time() .'-Return-POST.txt';
} else {
	ksort($_GET);
    $filename = time() .'-Return-GET.txt';
}
$contents = "\nGET\n" . print_r($_GET, true)
		  . "\nPOST\n" . print_r($_POST, true);
debugLog( $filename, $contents );
if($_REQUEST['respCode'] == '00' || $_REQUEST['respCode']=='A6'){
			$_GET['act']    = 'payment';
            $_GET['op']     = 'unionpay_return';
            $_GET['type']   = $_REQUEST['reqReserved'];
            $_GET['out_trade_no'] = $_REQUEST['orderId'];
            $_GET['amount'] = $_REQUEST['txnAmt']/100;
            require_once(dirname(__FILE__).'/../../../index.php');
}else{
	header("location:https://ytbwdtl.com/wap/tmpl/member/member.html");
}

echo 123;

echo '<hr> GET:';
print_r($_GET);
echo '<hr> POST:';
print_r($_POST);

exit;


/*
支付成功
POST:Array
(
    [accessType] => 0
    [bizType] => 000301
    [currencyCode] => 156
    [encoding] => utf-8
    [merId] => 848116048160005
    [orderId] => 20170811110326
    [queryId] => 201708111103262809098
    [respCode] => 00
    [respMsg] => success
    [settleAmt] => 1
    [settleCurrencyCode] => 156
    [settleDate] => 0811
    [signMethod] => 01
    [signPubKeyCert] => -----BEGIN CERTIFICATE-----
MIIEIDCCAwigAwIBAgIFEDRVM3AwDQYJKoZIhvcNAQEFBQAwITELMAkGA1UEBhMC
Q04xEjAQBgNVBAoTCUNGQ0EgT0NBMTAeFw0xNTEwMjcwOTA2MjlaFw0yMDEwMjIw
OTU4MjJaMIGWMQswCQYDVQQGEwJjbjESMBAGA1UEChMJQ0ZDQSBPQ0ExMRYwFAYD
VQQLEw1Mb2NhbCBSQSBPQ0ExMRQwEgYDVQQLEwtFbnRlcnByaXNlczFFMEMGA1UE
Aww8MDQxQDgzMTAwMDAwMDAwODMwNDBA5Lit5Zu96ZO26IGU6IKh5Lu95pyJ6ZmQ
5YWs5Y+4QDAwMDE2NDkzMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
tXclo3H4pB+Wi4wSd0DGwnyZWni7+22Tkk6lbXQErMNHPk84c8DnjT8CW8jIfv3z
d5NBpvG3O3jQ/YHFlad39DdgUvqDd0WY8/C4Lf2xyo0+gQRZckMKEAId8Fl6/rPN
HsbPRGNIZgE6AByvCRbriiFNFtuXzP4ogG7vilqBckGWfAYaJ5zJpaGlMBOW1Ti3
MVjKg5x8t1/oFBkpFVsBnAeSGPJYrBn0irfnXDhOz7hcIWPbNDoq2bJ9VwbkKhJq
Vz7j7116pziUcLSFJasnWMnp8CrISj52cXzS/Y1kuaIMPP/1B0pcjVqMNJjowooD
OxID3TZGfk5V7S++4FowVwIDAQABo4HoMIHlMB8GA1UdIwQYMBaAFNHb6YiC5d0a
j0yqAIy+fPKrG/bZMEgGA1UdIARBMD8wPQYIYIEchu8qAQEwMTAvBggrBgEFBQcC
ARYjaHR0cDovL3d3dy5jZmNhLmNvbS5jbi91cy91cy0xNC5odG0wNwYDVR0fBDAw
LjAsoCqgKIYmaHR0cDovL2NybC5jZmNhLmNvbS5jbi9SU0EvY3JsMjI3Mi5jcmww
CwYDVR0PBAQDAgPoMB0GA1UdDgQWBBTEIzenf3VR6CZRS61ARrWMto0GODATBgNV
HSUEDDAKBggrBgEFBQcDAjANBgkqhkiG9w0BAQUFAAOCAQEAHMgTi+4Y9g0yvsUA
p7MkdnPtWLS6XwL3IQuXoPInmBSbg2NP8jNhlq8tGL/WJXjycme/8BKu+Hht6lgN
Zhv9STnA59UFo9vxwSQy88bbyui5fKXVliZEiTUhjKM6SOod2Pnp5oWMVjLxujkk
WKjSakPvV6N6H66xhJSCk+Ref59HuFZY4/LqyZysiMua4qyYfEfdKk5h27+z1MWy
nadnxA5QexHHck9Y4ZyisbUubW7wTaaWFd+cZ3P/zmIUskE/dAG0/HEvmOR6CGlM
55BFCVmJEufHtike3shu7lZGVm2adKNFFTqLoEFkfBO6Y/N6ViraBilcXjmWBJNE
MFF/yA==
-----END CERTIFICATE-----
    [signature] => R7W/1kR/7M5aOFAzVcd+JtTmmLJY8KYt/Ev0dRmRh2YdzxIN82SYsyl2i05Y/HmmlPXl2ttHf7ED8+AQk935owkHfvFvrJ7f1PBKrNe/g7KFYyxiyDWCIGpIMau9SZKqe4s42npE+0cUauiLuZaHTAXy+iwZ388FJ7RNus4r3EhEwtWMbgLuEw0alTSwGJz+3ztai0WncnQuOp/I1hldOa7dOo6rttRx6Pw1581D5EnK7+qH14MKMj794Kd/P2Gd0EaHSBP5mXltJMEOxXA8JAOSA6uDH86pRkca9gT+lVC8ld8X/mBzhKbaLUivYVRVC/Es35reWT1XkxZINvC6XQ==
    [traceNo] => 280909
    [traceTime] => 0811110326
    [txnAmt] => 1
    [txnSubType] => 01
    [txnTime] => 20170811110326
    [txnType] => 01
    [version] => 5.1.0
)
 */



/**
 * 交易说明：	前台类交易成功才会发送后台通知。后台类交易（有后台通知的接口）交易结束之后成功失败都会发通知。
 *              为保证安全，涉及资金类的交易，收到通知后请再发起查询接口确认交易成功。不涉及资金的交易可以以通知接口respCode=00判断成功。
 *              未收到通知时，查询接口调用时间点请参照此FAQ：https://open.unionpay.com/ajweb/help/faq/list?id=77&level=0&from=0
 */

if($_SERVER['REQUEST_METHOD'] == "POST"){
	doPost();
} else {
	doGet(); 
}

function doGet(){
	// 无跳转产品的前台开通失败会get方式请求，不带报文数据。
	// 其他产品不会get方式访问，不用处理get的内容。
	echo "开通失败";
}

function doPost(){
	$logger = com\unionpay\acp\sdk\LogUtil::getLogger();
	$logger->LogInfo("receive front notify: " . com\unionpay\acp\sdk\createLinkString ( $_POST, false, true ));
	
?>

<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>响应页面</title>

<style type="text/css">
body table tr td {
	font-size: 14px;
	word-wrap: break-word;
	word-break: break-all;
	empty-cells: show;
}
</style>
</head>
<body>
	<table width="800px" border="1" align="center">
		<tr>
			<th colspan="2" align="center">响应结果</th>
		</tr>
	
			<?php
			foreach ( $_POST as $key => $val ) {
				?>
			<tr>
			<td width='30%'><?php echo isset($mpi_arr[$key]) ?$mpi_arr[$key] : $key ;?></td>
			<td><?php echo $val ;?></td>
		</tr>
			<?php }?>
			<tr>
			<td width='30%'>验证签名</td>
			<td><?php			
			if (isset ( $_POST ['signature'] )) {
				echo com\unionpay\acp\sdk\AcpService::validate ( $_POST ) ? '验签成功' : '验签失败';
			} else {
				echo '签名为空';
			}
			?></td>
		</tr>
	</table>
	<?php 
		
		//后面是获取各域的值的参考代码
		$orderId = $_POST ['orderId']; //其他字段也可用类似方式获取
		$respCode = $_POST ['respCode'];
        //判断respCode=00、A6后，对涉及资金类的交易，请再发起查询接口查询，确定交易成功后更新数据库。
		
		//如果卡号我们业务配了会返回且配了需要加密的话，请按此方法解密
		// if(array_key_exists ("accNo", $_POST)){
		// 	$accNo = com\unionpay\acp\sdk\AcpService::decryptData($_POST["accNo"]);
		// 	echo  "accNo=" . $accNo . "<br>\n";
		// }
		
		//customerInfo子域的获取
		if (array_key_exists("customerInfo", $_POST)) {
            echo "customerInfo子域：<br>\n";
			$customerInfo = com\unionpay\acp\sdk\AcpService::parseCustomerInfo($_POST["customerInfo"]);
			if (array_key_exists("phoneNo", $customerInfo)) {
				$phoneNo = $customerInfo["phoneNo"]; //customerInfo其他子域均可参考此方式获取
			}
			foreach ($customerInfo as $key => $value) {
				echo  $key . "=" . $value . "<br>\n";
			}
		}
		
		//$tokenPayData子域的获取
		if (array_key_exists("tokenPayData", $_POST)) {
            echo "tokenPayData子域：<br>\n";
            $tokenPayData = $_POST["tokenPayData"];
			$tokenPayData = com\unionpay\acp\sdk\parseQString(substr($tokenPayData, 1, strlen($tokenPayData)-2));
			if (array_key_exists("token", $tokenPayData)) {
				$token = $tokenPayData["token"]; //customerInfo其他子域均可参考此方式获取
			}
			foreach ($tokenPayData as $key => $value) {
				echo  $key . "=" . $value . "<br>\n";
			}
		}
	?>
</body>
</html>
<?php 
}
?>