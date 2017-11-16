<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
  <form action="index.php?act=payment&op=real_order" method="POST" id="buy_form">
    <input type="hidden" name="pay_sn" value="<?php echo $output['pay_info']['pay_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <input type="hidden" value="" name="password_callback" id="password_callback">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3>
        <?php echo $output['pay']['order_remind'];?>
        <?php echo "应付：<strong>￥".ncPriceFormat($output['pay']['pay_amount_online'])."</strong>" ;?>
        <?php echo $output['pay']['pay_points'] > 0 ? "<strong>+ ".$output['pay']['pay_points']."</strong>云豆" : null;?>
        </h3>
      </div>
      <table class="ncc-table-style">
        <thead>
          <tr>
            <th class="w50"></th>
            <th class="w200 tl">订单号</th>
            <th class="tl w150">支付方式</th>
            <th class="tl">金额(元)</th>  
            <th class="tl">云豆</th>          
          </tr>
        </thead>
        <tbody>
          <?php if (count($output['order_list']) > 1) { ?>
          <tr>
            <th colspan="20">由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']);?>个不同子订单配送！</th>
          </tr>
          <?php } ?>
          <?php foreach ($output['order_list'] as $key => $order_info) { ?>
          <tr>
            <td></td>
            <td class="tl"><?php echo $order_info['order_sn']; ?></td>
            <td class="tl"><?php echo $order_info['payment_type'];?></td>
            <td class="tl"><?php echo $order_info['order_amount'];?></td> 
            <td class="tl"><?php echo $order_info['order_points'];?></td>            
          </tr>
          <?php } ?>
        </tbody>
      </table>
      
      <!-- S 预存款 & 充值卡 -->
      <dl class="ncc-pd-pay">
      <?php if ($output['gc_id']['gc_id']==10351 && $output['gc_id']['goods_id']!=97414) { ?>
      <dd>您已选择
   <?php echo !empty($output['pay']['payd_rcb_amount']) ? "使用充值余额支付<em>".ncPriceFormat($output['pay']['payd_rcb_amount'])."</em>元；" : null; ?>
   <?php echo !empty($output['pay']['payd_pd_amount']) ? "使用云豆余额支付<em>".ncPriceFormat($output['pay']['payd_pd_amount'])."</em>元；" : null; ?>
   <?php echo !empty($output['pay']['payd_dis_amount']) ? "使用奖金余额支付<em>".ncPriceFormat($output['pay']['payd_dis_amount'])."</em>元；" : null; ?>
   还需在线支付 <strong id="api_pay_amount"><?php echo ncPriceFormat($output['pay']['payd_diff_amount']);?></strong>元。</dd>
      <?php } ?>
        <?php if ($output['gc_id']['gc_id']==10351) { ?>
        <?php 
        if ($output['gc_id']['goods_id']==97414) { ?>
        <dd>
          <label><input type="checkbox" class="checkbox" value="1" name="poi_pay">
          使用云豆支付</label> （可用云豆：<em><?php echo ncPriceFormat($output['pay']['member_poi']);?></em>元）</dd>
          <?php }else{ ?> 
        <dt>使用余额支付</dt>
        <dd>
          <label><input type="checkbox" class="checkbox" value="1" name="rcb_pay">
          使用充值余额支付</label> （可用余额：<em><?php echo ncPriceFormat($output['pay']['member_rcb']);?></em>元）</dd>
        
        <dd>（同时勾选时，系统将优先使用充值余额，不足时扣除云豆余额，目前还需在线支付 <strong id="api_pay_amount"><?php echo ncPriceFormat($output['pay']['pay_amount_online']);?></strong>元。）余额不足？<a href="<?php echo urlMember('predeposit', 'pd_log_list');?>" class="predeposit">马上充值</a></dd>
         <?php } ?>
        <dd id="pd_password" style="display: none">请输入支付密码
          <input type="password" value="" name="password" id="pay-password" maxlength="35" autocomplete="off">
          <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="pd_pay_submit"><i class="icon-shield"></i>确认支付</a>
        <?php if (!$output['pay']['member_paypwd']) {?>
              还未设置支付密码，<a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_paypwd'));?>" target="_blank">马上设置</a>
        <?php } ?>
        </dd>
     
       <?php }else{ ?>
        <dt>使用余额支付</dt>
        <dd>
          <label><input type="checkbox" class="checkbox" value="1" name="pd_pay">
          使用云豆释放余额支付</label> （可用余额：<em><?php echo ncPriceFormat($output['pay']['member_pd']);?></em>元）| （可用云豆：<em><?php $info=get_member_info($_SESSION['member_id']) ; echo $info['member_points'] ;?>）</em></dd>
        <dd id="pd_password" style="display: none">请输入支付密码
          <input type="password" value="" name="password" id="pay-password" maxlength="35" autocomplete="off">
          <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="pd_pay_submit"><i class="icon-shield"></i>确认支付</a>
        <?php if (!$output['pay']['member_paypwd']) {?>
              还未设置支付密码，<a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_paypwd'));?>" target="_blank">马上设置</a>
        <?php } ?>
        </dd>
      <?php } ?>
      </dl>
    </div>
      <?php if ($output['pay']['pay_amount_online'] > 0 && $output['gc_id']['goods_id']!=97414) {?>
          <div class="ncc-receipt-info">
          <div class="ncc-receipt-info-title">
            <h3>选择在线支付</h3>
          </div>
          <ul class="ncc-payment-list">
            <?php foreach($output['payment_list'] as $val) { 

            if ($val['payment_code'] == 'txpay' && $_SESSION['member_name'] != '18975810567') {
              continue;
            }

            ?>
            <li payment_code="<?php echo $val['payment_code']; ?>">
              <label for="pay_<?php echo $val['payment_code']; ?>">
              <i></i>
              <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
              </label>
            </li>
            <?php  } ?>
          </ul>
        </div>
    <?php } ?>
    <?php if ($output['pay']['pay_amount_online'] > 0 || $output['gc_id']['goods_id']==97414) {?>
    <div class="ncc-bottom"><a href="javascript:void(0);" id="next_button" class="pay-btn"><i class="icon-shield"></i>确认支付</a></div>
    <?php }?>
  </form>
</div>
<script type="text/javascript">
var pay_amount_online = <?php echo $output['pay']['pay_amount_online'];?>;
var member_rcb = <?php echo $output['pay']['member_rcb'];?>;
var member_pd = <?php echo $output['pay']['member_pd'];?>;
var member_dis = <?php echo $output['pay']['member_dis'];?>;
<?php if(!empty($output['pay']['member_poi'])){ ?>
var member_poi = <?php echo $output['pay']['member_poi'];?>;
<?php } ?>
var pay_diff_amount = <?php echo $output['pay']['pay_amount_online'] ? $output['pay']['pay_amount_online'] : $output['pay']['payd_diff_amount'];?>;
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
      $('.ncc-payment-list > li').removeClass('using');
      if ($('#payment_code').val() != $(this).attr('payment_code')) {
        $('#payment_code').val($(this).attr('payment_code'));
        $(this).addClass('using');
        } else {
            $('#payment_code').val('');
        }
    });
    $('#next_button').on('click',function(){
      if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked') || $('input[name="dis_pay"]').attr('checked') || $('input[name="poi_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
        showDialog('使用充值余额/云豆余额支付，需输入支付密码并确认  ', 'error','','','','','','','',2);
        return;
      }
      if($('input[name="poi_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked') || $('input[name="pd_pay"]').attr('checked') || $('input[name="dis_pay"]').attr('checked')){
        if($('input[name="poi_pay"]').attr('checked')){
          if(member_poi<10000 ){
            showDialog('您的云豆不足！！', 'error','','','','','','','',2);
            return;
          }
        }
        
      }else{
        if ($('#payment_code').val() == '') {
            showDialog('请选择一种在线支付方式', 'error','','','','','','','',2);
            return;
        }
      }  
        $('#buy_form').submit();
      

    });

    <?php if ($output['pay']['if_show_pdrcb_select']) { ?>
        function showPaySubmit() {
            if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked') || $('input[name="dis_pay"]').attr('checked') || $('input[name="poi_pay"]').attr('checked')) {
              
              $('#pay-password').val('');
              $('#password_callback').val('');
              $('#pd_password').show();
            } else {
              $('#pd_password').hide();
            }
            var _diff_amount = pay_diff_amount;
          if ($('input[name="rcb_pay"]').attr('checked')) {
            _diff_amount -= member_rcb;
            }
          if ($('input[name="poi_pay"]').attr('checked')) {
            _diff_amount -= member_poi;
            }
          _diff_amount = parseFloat(_diff_amount.toFixed(2));
          if ($('input[name="dis_pay"]').attr('checked')) {
            _diff_amount -= member_dis;
            }
          _diff_amount = parseFloat(_diff_amount.toFixed(2));
          if ($('input[name="pd_pay"]').attr('checked')) {
            _diff_amount -= member_pd;
            }
          _diff_amount = parseFloat(_diff_amount.toFixed(2));
            if (_diff_amount < 0) {
              _diff_amount = 0;
            }
            $('#api_pay_amount').html(_diff_amount.toFixed(2));
        }
    
        $('#pd_pay_submit').on('click',function(){           
            if ($('#pay-password').val() == '') {
              showDialog('请输入支付密码', 'error','','','','','','','',2);return false;
            }
            $('#password_callback').val('');
            $.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
                if (data == '1') {
                  $('#password_callback').val('1');
                  $('#pd_password').hide();
                } else {
                  $('#pay-password').val('');
                  showDialog('支付密码错误', 'error','','','','','','','',2);
                }
            });
        });
    
        $('input[name="rcb_pay"]').on('change',function(){
          showPaySubmit();
          if ($(this).attr('checked') && !$('input[name="pd_pay"]').attr('checked')) {
              if (member_rcb >= pay_amount_online) {
                  $('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
                  $('input[name="dis_pay"]').attr('checked',false).attr('disabled',true);
              }
          } else {
            $('input[name="pd_pay"]').attr('disabled',false);
            $('input[name="dis_pay"]').attr('disabled',false);
          }
        });
        $('input[name="poi_pay"]').on('change',function(){
          showPaySubmit();
          if ($(this).attr('checked') && !$('input[name="poi_pay"]').attr('checked')) {
              if (member_rcb >= pay_amount_online) {
                  $('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
                  $('input[name="dis_pay"]').attr('checked',false).attr('disabled',true);
              }
          } else {
            $('input[name="pd_pay"]').attr('disabled',false);
            $('input[name="dis_pay"]').attr('disabled',false);
          }
        });                
        $('input[name="pd_pay"]').on('change',function(){
          showPaySubmit();
          if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
              if (member_pd >= pay_amount_online) {
                  $('input[name="rcb_pay"]').attr('checked',false).attr('disabled',true);
                  $('input[name="dis_pay"]').attr('checked',false).attr('disabled',true);
              }
          } else {
            $('input[name="rcb_pay"]').attr('disabled',false);
            $('input[name="dis_pay"]').attr('disabled',false);
          }
        });
        
        $('input[name="dis_pay"]').on('change',function(){
          showPaySubmit();
          if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
              if (member_pd >= pay_amount_online) {
                  $('input[name="rcb_pay"]').attr('checked',false).attr('disabled',true);
                  $('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
              }
          } else {
            $('input[name="rcb_pay"]').attr('disabled',false);
            $('input[name="pd_pay"]').attr('disabled',false);
          }
        });
    <?php } ?>
});
</script>