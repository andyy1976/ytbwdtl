<form class="api-form" method="post" action="../../demo/api_03_wtz/Form_6_7_Consume.php" target="_blank">
<p>
<label>商户号：</label>
  <input id="merId" pattern="\d{15}" type="text" name="merId" placeholder="" value="848116048160005" title="默认商户号仅作为联调测试使用，正式上线还请使用正式申请的商户号" required="required"/>
</p>
<p>
  <label>订单发送时间：</label>
  <input id="txnTime" pattern="\d{14}" type="text" name="txnTime" placeholder="订单发送时间" 
  value="<?php echo isset($_GET['txnTime']) ? $_GET['txnTime'] : ''?>" 
  title="取北京时间，YYYYMMDDhhmmss格式。如之前调用获取验证码接口，填写获取验证码时的txnTime。" required="required"/>
</p>
<p>
  <label>商户订单号：</label>
  <input id="orderId" pattern="[0-9a-zA-Z]{8,32}" type="text" name="orderId" placeholder="商户订单号" 
  value="<?php echo isset($_GET['orderId']) ? $_GET['orderId'] : ''?>" 
  title="8-32位数字字母，自行定义内容。如之前调用获取验证码接口，填写获取验证码时的orderId。" required="required"/>
</p>
<p>
  <label>交易金额：</label>
  <input id="txnAmt" pattern="\d{1,12}" type="text" name="txnAmt" placeholder="交易金额" 
  value="<?php echo isset($_GET['txnAmt']) ? $_GET['txnAmt'] : '1'?>"
  title="单位为分，正数" required="required"/>
</p>
<p>
  <label>短信验证码：</label>
  <input id="smsCode" pattern="\d{1,12}" type="text" name="smsCode" placeholder="短信验证码：" 
  value=""
  title="单位为分，正数" required="required"/>
</p>
<p>
<label>&nbsp;</label>
<input type="submit" class="button" value="提交" />
<input type="button" class="showFaqBtn" value="遇到问题？"  />
</p>
</form>

<div class="question" >
<hr />
<h4>消费您可能会遇到...</h4>
<p class="faq">
<a href="https://open.unionpay.com//ajweb/help/respCode/respCodeList?respCode=6100030" target="_blank">6100030</a><br>
<br>
</p>
<hr />
<?php include $_SERVER ['DOCUMENT_ROOT'] . '/upacp_demo_wtz/pages/more_faq.php';?>
</div>

