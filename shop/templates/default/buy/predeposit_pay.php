<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>查看充值记录可以通过<a href="<?php echo urlMember('predeposit');?>" target="_blank">我的充值列表 </a>进行查看。</h5>
  </div>
  <?php if ($_SESSION['member_name'] == '18975810567') { ?>
  <form action="index.php?act=payment&op=pd_order" method="POST" id="buy_form">
  <?php }else{ ?>
  <form action="index.php?act=payment&op=pd_order" method="POST" id="buy_form">
  <?php } ?>
    <input type="hidden" name="pdr_sn" value="<?php echo $output['pdr_info']['pdr_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <input type="hidden" id="txpay_bank_id" name="txpay_bank_id" value="">
    <div class="ncc-receipt-info">
    <div>充值单号 : <?php echo $output['pdr_info']['pdr_sn'];?></div>
      <div class="ncc-receipt-info-title">
        <h3>您已申请账户余额充值，请立即在线支付！
          充值金额：<strong>￥<?php echo $output['pdr_info']['pdr_amount'];?></strong> </h3>
      </div>
    </div>
    <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (!empty($output['payment_list'])){ ?>
      <div class="ncc-receipt-info-title">
        <h3>支付选择</h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>

          <?php if ($val['payment_code'] == 'txpay' && $_SESSION['member_name'] != '18975810567') {
            continue;
          } ?>

          <?php if ($val['payment_code'] != 'unionpay') { ?>
            <li payment_code="<?php echo $val['payment_code']; ?>">
              <label for="pay_<?php echo $val['payment_code']; ?>">
              <i></i>
              <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
              <div class="predeposit" nc_type="predeposit" style="display:none">
                <?php if ($val['payment_code'] == 'predeposit') {?>
                    <?php if ($output['available_predeposit']) {?>
                    <p>当前预存款余额<br/>￥<?php echo $output['available_predeposit'];?><br/>不足以支付该订单<br/><a href="<?php echo MEMBER_SITE_URL.'/index.php?act=predeposit';?>" class="predeposit">马上充值</a></p>
                    <?php } else {?>
                    <input type="password" class="text w120" name="password" maxlength="40" id="password" value="">
                    <p>使用站内预存款进行支付时，需输入您的登录密码进行安全验证。</p>
                    <?php } ?>
                <?php } ?>
              </div>
              </label>
            </li>
          <?php }?>
        <?php  }?>

      </ul>
      <?php } ?>
      <input type='hidden' id='pdr_type' value='<?php echo $output['pdr_info']['pdr_type']; ?>'>
    </div>
    
    <style type="text/css">
      #bank-list-panel {
        padding: 0 20px;
      }
      #bank-list-panel li {
        width: 150px; height: 40px;
        float: left;
        cursor: pointer;
        text-align: center;
        position: relative;
        margin: 5px;
      }
      #bank-list-panel li span{
        display: block;
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border: 2px solid #ddd; 
        font-size: 0;
        line-height: 100px;
        overflow: hidden;
      }
      #bank-list-panel li.using span {
        border: solid 2px #52A452;
      }
    </style>
    <div id="bank-list-panel" style="display: none">
      <h4>--请选择银行--</h4>
      <ul>
      <li bank="1001"><span style="background: url(/shop/templates/default/images/payment/bank/gs.jpg) no-repeat center center">工商银行</span></li>
      <li bank="1002"><span style="background: url(/shop/templates/default/images/payment/bank/ny.jpg) no-repeat center center">农业银行</span></li>
      <li bank="1003"><span style="background: url(/shop/templates/default/images/payment/bank/zg.jpg) no-repeat center center">中国银行</span></li>
      <li bank="1004"><span style="background: url(/shop/templates/default/images/payment/bank/js.jpg) no-repeat center center">建设银行</span></li>
      <li bank="1008"><span style="background: url(/shop/templates/default/images/payment/bank/gd.jpg) no-repeat center center">光大银行</span></li>
      <li bank="1011"><span style="background: url(/shop/templates/default/images/payment/bank/pa.jpg) no-repeat center center">平安银行</span></li>
      <li bank="1006"><span style="background: url(/shop/templates/default/images/payment/bank/yc.jpg) no-repeat center center">邮储银行</span></li>
      <li bank="1007"><span style="background: url(/shop/templates/default/images/payment/bank/zx.jpg) no-repeat center center">中信银行</span></li>
      <li bank="1012"><span style="background: url(/shop/templates/default/images/payment/bank/zs.jpg) no-repeat center center">招商银行</span></li>
      <li bank="1010"><span style="background: url(/shop/templates/default/images/payment/bank/ms.jpg) no-repeat center center">民生银行</span></li>
      <li bank="1017"><span style="background: url(/shop/templates/default/images/payment/bank/gf.jpg) no-repeat center center">广发银行</span></li>
      </ul>
    </div>


    <div class="ncc-bottom"><a href="javascript:void(0);" id="next_button" class="pay-btn"><i class="icon-shield"></i>确认支付</a></div>
  </form>
</div>
<script type="text/javascript">
$(function(){

    $('#bank-list-panel li').on('click', function(){
        $('#txpay_bank_id').val($(this).attr('bank'));
        $('#bank-list-panel li').removeClass('using');
        $(this).addClass('using');
    })

    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        var paymentCode = $(this).attr('payment_code');
        $('#payment_code').val(paymentCode);
        if (paymentCode == 'txpay') {
          $('#bank-list-panel').show();
        } else {
          $('#bank-list-panel').hide();
        }
    });

    $('#next_button').on('click',function(){
        var val = $('#payment_code').val();
        var type= "<?php echo $output['pdr_info']['pdr_type']; ?>";
        if (val != '') {
            // if(val =='unionpay' && type==""){
            //     alert('充值暂未开放');
            //     return false;              
            // }

            if (val == 'txpay') {
               if(!$('#txpay_bank_id').val()){
                  alert('请选择银行')
                  return false
               }
            }

            $('#buy_form').submit();
        }
    });
});
</script>