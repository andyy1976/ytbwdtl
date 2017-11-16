<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
  </div>
  <form action="index.php?act=payment&op=panreal_order" method="POST" id="buy_form">
    <input type="hidden" name="pay_sn" value="<?php echo $output['pay_info']['openshop_pay_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <input type="hidden" value="" name="password_callback" id="password_callback">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3>
        <?php echo '尊敬的会员ID:'.$output['pay_info']['member_id']." 开通此店铺应付：<strong>￥".ncPriceFormat($output['pay_info']['pay_amount'])."元</strong>";?>
        </h3>
      </div>
      <table class="ncc-table-style">
        <thead>
          <tr>
            <th class="w50"></th>
            <th class="w200 tl">订单号</th>
            <th class="tl w150">支付方式</th>
            <th class="tl">金额(元)</th>         
          </tr>
        </thead>
        <tbody>
          <tr>
            <td></td>
            <td class="tl"><?php echo $output['pay_info']['openshop_order_sn']; ?></td>
            <td class="tl"><?php echo '在线支付';?></td>
            <td class="tl"><?php echo $output['pay_info']['pay_amount'];?></td>            
          </tr>
        </tbody>
      </table>
    </div>
          <div class="ncc-receipt-info">
          <div class="ncc-receipt-info-title">
            <h3>选择在线支付</h3>
          </div>
          <ul class="ncc-payment-list">
<!--             <?php foreach($output['payment_list'] as $val) { 
                if($val['payment_code']=='vbill'){
            ?>
            <li payment_code="<?php echo $val['payment_code']; ?>">
              <label for="pay_<?php echo $val['payment_code']; ?>">
              <i></i>
              <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
              </label>
            </li>
            <?php } }?> -->
          </ul>
        </div>
    <!-- <div class="ncc-bottom"><a href="javascript:void(0);" id="next_button" class="pay-btn"><i class="icon-shield"></i>确认支付</a></div> -->
  </form>
</div>
<script type="text/javascript">
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
    	if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked') || $('input[name="dis_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
    		showDialog('使用充值余额/云豆余额支付，需输入支付密码并确认  ', 'error','','','','','','','',2);
    		return;
    	}
        if ($('#payment_code').val() == '' && parseFloat($('#api_pay_amount').html()) > 0) {
        	showDialog('请选择一种在线支付方式', 'error','','','','','','','',2);
        	return;
        }
        $('#buy_form').submit();
    });

    <?php if ($output['pay']['if_show_pdrcb_select']) { ?>
        function showPaySubmit() {
            if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked') || $('input[name="dis_pay"]').attr('checked')) {
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