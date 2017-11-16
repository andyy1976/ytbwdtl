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

require_once BASE_PATH."/api/payment/wxpay/lib/WxPay.Api.php";
require_once BASE_PATH."/api/payment/wxpay/WxPay.NativePay.php";
require_once BASE_PATH."/api/payment/wxpay/log.php";

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
$url1 = $notify->GetPrePayUrl("123456789");

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
$input->SetBody($order_info['pay_sn']."订单");
$input->SetAttach($order_info['pay_sn']."订单");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
//$input->SetTotal_fee(intval($order_info['order_amount']));
$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("");
//$input->SetNotify_url( dirname(__FILE__)."/notify.php");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($order_info['order_id']);
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>万店通联微信支付</title>
</head>
<body>
	
	<img alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:300px;height:300px;"/>
     <div id="myDiv"></div><div id="timer">0</div>
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
                     document.getElementById("myDiv").innerHTML='支付成功';  
                   alert(transaction_id);  
                   //延迟3000毫秒执行tz() 方法
                   clearInterval(myIntval);  
                     setTimeout("location.href='success.php'",3000);  
 
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