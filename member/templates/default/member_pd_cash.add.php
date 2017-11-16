<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <div class="tabmenu">
    

</div>
  </div>
  <div class="ncm-default-form">
    <form method="post" id="cash_form" action="<?php echo MEMBER_SITE_URL;?>/index.php?act=predeposit&op=pd_cash_add">
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required">*</i>提现类型：</dt>
        <dd>
          <select name='predeposit_type' id='predeposit_type'>
          <?php 
            $result=province_cash();
            if($_SESSION['member_level']==0){ 
             echo "<option value='2' selected='selected'>充值余额</option><option value='3'>分销余额</option>";
            }elseif($_SESSION['member_level']==5){
                echo "<option value='6' selected='selected'>代理钱包</option><option value='5' selected='selected'>省代云豆释放钱包</option><option value='2'>充值余额</option><option value='3'>分销余额</option>";
            }elseif( $_SESSION['member_level']<5 && $_SESSION['member_level']>1){
             echo "<option value='6' selected='selected'>代理钱包</option><option value='1' >云豆余额</option><option value='2'>充值余额</option><option value='3'>分销余额</option>";
            }
            else{
             echo "<option value='1' selected='selected'>云豆余额</option><option value='2'>充值余额</option><option value='3'>分销余额</option>";
            }
          ?>
          </select>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>提现金额：</dt>
        <dd style="height:100%;"><input name="pdc_amount" type="text" class="text w50" id="pdc_amount" maxlength="6" ><em class="add-on">
<i class="icon-renminbi"></i></em> （当前可用金额：<strong class="orange" id="moneys"><?php $output['member_info']['available_predeposit']; ?></strong>&nbsp;&nbsp;元）
<!-- <span style='color:red'>--><p style='color:red'> 

      （1）“已释放云豆”提现：每月提现次数不限，超过4次，每次加收5元成本费。<br/>
      （2）云豆提现需账户上“已释放的云豆”满100或100的倍数才可以，提现扣除13%的服务费<br/>
      （3）分销余额提现为100的倍数，手续费为1%！<br/>
      <!-- （4）充值余额提现为100的倍数。<br/> -->
      （4）充值提现到账时间为T+1个工作日！<br/>
      （5）云豆余额提现到账时间为T+3个工作日！<br/>
      （6）分销奖金提现到账时间为T+3个工作日！<br/>
      （7）每笔提现金额不超过20万，且充值提现每日只能提现5次！</p>
      <!-- </span> -->
          <p class="hint mt5"></p>
        </dd>
      </dl>
     
      <dl>
        <dt><i class="required">*</i>支付密码：</dt>
        <dd><input name="password" type="password" class="text w100" id="password" maxlength="20"/><span></span>
        <p class="hint">
              <?php if (!$output['member_info']['member_paypwd']) {?>
              <strong class="red">还未设置支付密码</strong><a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_security&op=auth&type=modify_paypwd" class="ncm-btn-mini ncm-btn-acidblue vm ml10" target="_blank">马上设置</a>
              <?php } ?>
        </p>
          </dd>
      </dl>
      <input type="hidden" name="agreement_id" id="agreement_id" value="">
      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="button" id='sub'  class="submit" value="确认提现" /></label><a class="ncm-btn ml10" href="javascript:history.go(-1);">取消并返回</a></dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
  $("#sub").click(function(){
      var member_level = <?php echo $output['member_info']['member_level']; ?>;
      var agreement_id = <?php echo $output['member_info']['agreement_id']; ?>;
      if(member_level>0 && agreement_id==0){
        if(confirm('请确认是否成为业务员')){
          $("#agreement_id").val('1');
          document.getElementById("cash_form").submit();
        }else{
          document.getElementById("cash_form").submit();
        }              
      }else{
        document.getElementById("cash_form").submit();
      }    
            
  });
  $('#cash_form').validate({
      submitHandler:function(form){
      ajaxpost('cash_form', '', '', 'onerror')
    },
         errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
            var type =  $('#predeposit_type option:selected') .val();
        },
        rules : {
          pdc_amount      : {
            required  : true,
              number    : true,
              min       : 0.01,
              max       : 200000

            },
            pdc_bank_name :{
              required  : true
            },
          password : {
            required  : true
          }
        },
        messages : {
          pdc_amount    : {
              required  :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
              number    :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
              min       :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
              max       :'<i class="icon-exclamation-sign"></i>每笔提现金额不超过20万'
            },
            pdc_bank_name :{
              required   :'<i class="icon-exclamation-sign"></i>请输入收款银行'
            },
          password : {
            required : '<i class="icon-exclamation-sign"></i>请输入支付密码'
        }
        }
    });
    var predeposit_type=$('#predeposit_type').val();
    $.post('index.php?act=member_security&op=predeposit',{predeposit_type:predeposit_type},function(result){
    if(result){
        $('#moneys').text(result);
    }
    }); 
    $('#predeposit_type').change(function(){
        var predeposit_type=$(this).val();
        $.post('index.php?act=member_security&op=predeposit',{predeposit_type:predeposit_type},function(result){
          if(result){
            $('#moneys').text(result);
          }
        }); 
    });
});
</script>