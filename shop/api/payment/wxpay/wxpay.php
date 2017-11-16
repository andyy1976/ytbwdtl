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
//error_reporting(E_ERROR);

require ("lib/WxPay.Api.php");
require ("WxPay.NativePay.php");
require ("log.php");
$opw=isset($_GET['op'])?$_GET['op']:'';

//模式一
/**
 * 流程：
 * 1、组装包含支付信息的url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
 * 5、支付完成之后，微信服务器会通知支付成功
 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$notify = new NativePay();
//$url1 = $notify->GetPrePayUrl("123456789");

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
/**
判断是实物支付还是充值
*/
if(!empty($opw)&&($opw=='pd_order')){   //虚拟充值
$input->SetBody($pdr_sn);
$input->SetAttach($pdr_sn);
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$num=WxPayConfig::MCHID.date("YmdHis");
$input->SetOut_trade_no($num);
//$input->SetTotal_fee(intval($order_info['order_amount']));
$input->SetTotal_fee(intval($api_pay_amount)*100);
//$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($pdr_member_name);
$input->SetNotify_url(dirname(__FILE__)."/notify.php");
//$input->SetNotify_url("http://www.wandiantonglian.com/shop/api/payment/wxpay/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($pdr_id);
	}elseif(!empty($opw)&&($opw=='real_order')){  //实物购取

$input->SetBody($order_info['pay_sn']);
$input->SetAttach($order_info['order_sn']);
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));

$num=WxPayConfig::MCHID.date("YmdHis");
$input->SetOut_trade_no($num);
//$input->SetTotal_fee(intval($order_info['order_amount']));
$input->SetTotal_fee(intval($order_info['order_amount'])*100);
//$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("".$order_info['buyer_id']."");
$input->SetNotify_url(dirname(__FILE__)."/notify.php");
//$input->SetNotify_url("http://www.wandiantonglian.com/shop/api/payment/wxpay/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("".$order_info['order_id']."");
	}
$resultt = $notify->GetPayUrl($input);
$url2 = $resultt["code_url"];
$payerName=$_SESSION['member_id'];
$orderNo=$pdr_sn;
$orderAmount=$amout*100;
$orderDatetime=date('Ymdhis',time());
$ext1=$type.'|'.$lagc_id;
$ext2=$_SESSION['member_id'];
$orderpoint=$lgoods_pay_points;
 
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>万店通联微信支付</title>
    <script language="javascript" src="../shop/resource/js/Jquery.Query.js"></script>
    <style type="text/css">
/*通用*/
body{ font-size: 14px; font-family: "microsoft yahei", Arial; color: #333; margin: 0px; padding: 0px;}
ul, ol, li, p, h1, h2, h3, h4, h5, h6, dl, dt, dd{ margin: 0px; padding: 0px; list-style: none;}
a{ color: #333; text-decoration: none;}
img{ vertical-align: middle;}
button{ cursor: pointer;}
i,em{ font-style: normal;}
div:after{ content: ""; display: block; clear: both;}
div:before{ content: ""; display: table;}

/*微信支付*/
.wx_pay{width: 1000px; margin: 0 auto; margin-top:50px; background:#fff; padding:15px 30px; box-shadow:0 0 20px rgba(0,0,0,.12);}
.wx_pay_t{ line-height:60px; color:#666; padding-bottom:20px;}
.wx_pay_t h3{ float:left; font-size:18px;}
.wx_pay_t h4{ float:left; font-size:14px; padding-left:30px; font-weight:normal;}
.wx_pay_t p{ float:right;}
.wx_pay_t p b{ color:#ff5d5b; padding:0 5px;}
.wx_pay_n{ width:700px; margin-left:150px; height:418px; background:url(/shop/api/payment/wxpay/img/wx_pay_n.jpg) no-repeat right;}
.wx_pay_n dl{ width:300px; float:left; overflow:hidden;}
.wx_pay_n dl dt{ width:258px; height:258px; float:left; border:1px solid #ddd; padding:20px;}
.wx_pay_n dl dt img{ width:258px; height:258px; float:left;}
.wx_pay_n dl dd{ width:180px; height:50px; line-height:25px; color:#fff; float:left; margin-top:20px; padding:5px 0 5px 120px; background:url(/shop/api/payment/wxpay/img/saom.png) no-repeat 55px center #ff7674;}
.wx_pay_d{ line-height:60px;}
.wx_pay_d a{ color:#2ea7e7; font-size:16px;}
</style>
</head>
<body>
	<div class="wx_pay">
	<div class="wx_pay_t">
    	<h3>微信支付</h3>
        <h4>订单提交成功，请尽快付款！订单号：<?php if(!empty($order_info['order_sn'])){ echo $order_info['order_sn'];} else{ echo $pdr_sn;} ?>
        <br>
        <span style="color:#F00">
        温馨提示： 付款后，为了确保订单支付成功，请不要关闭本页面，让页面自动跳转，如果没有跳转！请联系网站客服。
        </span>
        </h4>
        <p>应付金额<b><?php if(empty($api_pay_amount)) { echo $order_info['order_amount']; }else{ echo $api_pay_amount; }?></b>元</p>
    </div>
    <div class="wx_pay_n">
    	<dl>
        	<dt><img alt="模式二扫码支付" src="http://www.wandiantonglian.com/shop/api/payment/wxpay/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:258px;height:258px;"/></dt>
            
            <dd><p>请使用微信扫一扫</p><p>扫描二维码支付</p></dd>
        </dl>
    </div>
   
    <div class="wx_pay_d"><a href="/shop/index.php?act=buy&op=pay&pay_sn=<?php if(empty($pdr_sn)){ echo $order_info['pay_sn'];}else{ echo $pdr_sn; } ?>">&lt; 选择其他支付方式</a></div>
</div>
  <form action="/shop/api/payment/wxpay/success.php" method="post" id="sucid">
 <input type="hidden" name="payerName" id="payerName" value="<?php echo $payerName?>"/>
 <input type="hidden" name="orderNo" id="orderNo" value="<?php echo $orderNo?>" />
 <input type="hidden" name="orderAmount" id="orderAmount" value="<?php echo $orderAmount ?>"/>
 <input type="hidden" name="orderDatetime" id="orderDatetime" value="<?php echo $orderDatetime?>" />
 <input type="hidden" name="ext1" id="wext1" value="<?php echo $ext1?>" />
 <input type="hidden" name="ext2" id="ext2" value="<?php echo $ext2?>" />
 <input type="hidden" name="pay_type" id="pay_type" value="wxpay"/>
 <input type="hidden" name="trade_no" id="trade_no" value="<?php echo $num; ?>"/>
 <input type="hidden" name="orderpoint" id="orderpoint" value="<?php echo $orderpoint; ?>" />
  </form> 
  <div id="myDiv" style="display:none;"></div><div id="timer" style="display:none;">0</div>
      <script>  
      //设置每隔1000毫秒执行一次load() 方法  
      var myIntval=setInterval(function(){load()},1000);  
      function load(){  
        document.getElementById("timer").innerHTML=parseInt(document.getElementById("timer").innerHTML)+1; 
         var xmlhttp;    
          if (window.XMLHttpRequest){    
             // code for IE7+, Firefox, Chrome, Opera, Safari    
             xmlhttp=new XMLHttpRequest();    
         }else{    
             // code for IE6, IE5    
             xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");    
        }    
        xmlhttp.onreadystatechange=function(){    
              if (xmlhttp.readyState==4 && xmlhttp.status==200){    
                trade_state=xmlhttp.responseText;  
               if(trade_state=='SUCCESS'){  
				//if(trade_state=='NOTPAY'){ 
                     document.getElementById("myDiv").innerHTML='支付成功';  
                    //alert(transaction_id);  
                   //延迟3000毫秒执行tz() 方法
                   clearInterval(myIntval);  
				  setTimeout(3000);
				  document.getElementById('sucid').submit();
 
               }else if(trade_state=='REFUND'){  
                      document.getElementById("myDiv").innerHTML='转入退款'; 
                     clearInterval(myIntval); 
                 }else if(trade_state=='NOTPAY'){  
                     document.getElementById("myDiv").innerHTML='请扫码支付';  
                       
                 }else if(trade_state=='CLOSED'){  
                  document.getElementById("myDiv").innerHTML='已关闭';  
                  clearInterval(myIntval);
            }else if(trade_state=='REVOKED'){  
                   document.getElementById("myDiv").innerHTML='已撤销';  
                    clearInterval(myIntval);
              }else if(trade_state=='USERPAYING'){  
                 document.getElementById("myDiv").innerHTML='用户支付中';  
             }else if(trade_state=='PAYERROR'){  
                  document.getElementById("myDiv").innerHTML='支付失败'; 
                     clearInterval(myIntval); 
                }  
                 
            }    
       }    
	   var hostname=location.hostname;
       var port =location.port;
        //orderquery.php 文件返回订单状态，通过订单状态确定支付状态  
        xmlhttp.open("POST","http://"+hostname+":"+port+"/shop/api/payment/wxpay/orderquery.php",false);    
      //下面这句话必须有    
       //把标签/值对添加到要发送的头文件。    
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");    
      xmlhttp.send("out_trade_no=<?php echo $num;?>");  
        
    }  
  </script>
</body>
</html>