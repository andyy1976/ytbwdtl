<?php
/**
 * 微信扫码支付
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */

defined('In33hao') or exit('Access Invalid!');
ini_set('date.timezone','Asia/Shanghai');
require  "lib/WxPay.Api.php";
require  "WxPay.JsApiPay.php";
require_once "log.php";

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Logs::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();


$input->SetBody($order_info['pay_sn']);
$input->SetAttach($order_info['order_sn']);
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));

$num=WxPayConfig::MCHID.date("YmdHis");
$input->SetOut_trade_no($num);

$input->SetTotal_fee(intval($order_info['order_amount'])*100);

$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("".$order_info['buyer_id']."");
$input->SetNotify_url(dirname(__FILE__)."/notify.php");
$input->setTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
echo '<font color="#f00"><b>万店通联支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();
$payerName=$member_info['member_id'];
$orderNo=$pdr_sn;
$orderAmount=$amout*100;
$orderDatetime=date('Ymdhis',time());
$ext2=$type.'|'.$lagc_id.'|'.$member_info['member_id'];
$ext1=$member_info['member_id'];
$orderpoint=$lgoods_pay_points;

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>万店通联微信支付</title>
   
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
			  if(res.err_msg == "get_brand_wcpay_request:ok"){
					  document.getElementById('sucid').submit();
				}else{
					  alert('支付失败！！请重新支付！');
					  window.location='/wap/tmpl/member/member.html'
					}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				
				//alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}
	
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}
	};
	
	</script>
</head>
<body>
     <form action="/mobile/api/payment/wxpay/success.php" method="post" id="sucid">
 <input type="hidden" name="payerName" id="payerName" value="<?php echo $payerName?>"/>
 <input type="hidden" name="orderNo" id="orderNo" value="<?php echo $orderNo?>" />
 <input type="hidden" name="orderAmount" id="orderAmount" value="<?php echo $orderAmount ?>"/>
 <input type="hidden" name="orderDatetime" id="orderDatetime" value="<?php echo $orderDatetime?>" />
 <input type="hidden" name="ext2" id="ext2" value="<?php echo $ext2?>" />
 <input type="hidden" name="ext1" id="ext1" value="<?php echo $ext1?>" />
 <input type="hidden" name="pay_type" id="pay_type" value="wxpay"/>
 <input type="hidden" name="trade_no" id="trade_no" value="<?php echo $num; ?>"/>
 <input type="hidden" name="orderpoint" id="orderpoint" value="<?php echo $orderpoint; ?>" />
  </form> 
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $order_info['order_amount']; ?></span></b></font><br/><br/>
	<div align="center">
		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onClick="callpay()" >立即支付</button>
	</div>
</body>
</html>