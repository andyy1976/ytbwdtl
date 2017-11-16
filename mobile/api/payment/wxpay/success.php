<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>万店通联微信支付成功页面</title>
<style type="text/css">
/*通用*/
body{ font-size: 14px; font-family: "microsoft yahei", Arial; color: #333; margin: 0px; padding: 0px;}
ul, ol, li, p, h1, h2, h3, h4, h5, h6, dl, dt, dd{ margin: 0px; padding: 0px; list-style: none;}
a{ color: #333; text-decoration: none;}
img{ vertical-align: middle;}
input, button, select,textarea{ border: none; outline: 0; font-family: "microsoft yahei";}
button{ cursor: pointer;}
i,em{ font-style: normal;}
div:after{ content: ""; display: block; clear: both;}
div:before{ content: ""; display: table;}

/*完成订单*/
.wx_state{width: 900px; margin: 0 auto; margin-top:50px; background:#fff; padding:15px 50px; box-shadow:0 0 20px rgba(0,0,0,.12);}
.wx_state_t{ color:#69cd8e;}
.wx_state_t span{ font-size:60px; line-height:95px; float:left;}
.wx_state_t h3{ font-size:24px; line-height:100px; float:left;}
.wx_state_t2{ color:#f00;}
.wx_state_n{ line-height:35px; padding-left:42px; border-bottom:1px dashed #e2e2e2; padding-bottom:20px;}
.wx_state_n a{ color:#2ea7e7;}
.wx_state_n em{ color:#ddd; padding:0 5px;}
.wx_state_d{ line-height:60px;}
.wx_state_d em{ color:#ff5d5b;}
</style>
</head>

<body>
<div class="wx_state">
	<div class="wx_state_t"><span>&radic;</span><h3>支付成功！我们会尽快为您发货！</h3></div>
    <!--<div class="wx_state_t wx_state_t2"><span>&times;</span><h3>抱歉，支付失败！</h3></div>-->
    <div class="wx_state_n">
    <p>订单号：<?php echo $_POST['orderNo'] ; ?></p>
    <p>在线支付<?php echo ($_POST['orderAmount']/100); ?>元</p>
    <p><a href="../../../index.php">继续逛逛</a><em>|</em><a href="/shop/index.php?act=member_order">查看订单详情</a></p>
    </div>
    <div class="wx_state_d"><b>重要提示：</b>万店通联平台及销售商不会以<em>订单异常、系统升级</em>为由，要求您点击任何链接进行退款。</div>
</div>
</body>
</html>
<?php           

          
                $_GET['payment_code']   ='wxpay';
                $_GET['act']	= 'payment';
				$_GET['op']		= 'return';
				require_once(dirname(__FILE__).'/../../../index.php');
 ?>