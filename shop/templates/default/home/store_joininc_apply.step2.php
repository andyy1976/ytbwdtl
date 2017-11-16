<?php defined('In33hao') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){
    var use_settlement_account = true;


    $("#is_settlement_account").on("click", function() {
        if($(this).prop("checked")) {
            use_settlement_account = false;  
            $("#div_settlement").hide();
            $("#settlement_bank_account_name").val("");
            $("#settlement_bank_account_number").val("");
        } else {
            use_settlement_account = true;  
            $("#div_settlement").show();
        }
    });

    $('#form_credentials_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {

            settlement_bank_account_name: {
                required: function() { return use_settlement_account; },    
                maxlength: 50 
            },
            settlement_bank_account_number: {
                required: function() { return use_settlement_account; },
                maxlength: 20 
            },

        },
        messages : {

            settlement_bank_account_name: {
                required: '请填写支付宝姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            settlement_bank_account_number: {
                required: '请填写支付宝账号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },


        }
    });

    $('#btn_apply_credentials_next').on('click', function() {
		<?php if($output['flag']==1){ ?>
		if(!validio()){
			return;
			}
	  <?php } ?>
        if($('#form_credentials_info').valid()) {
            $('#form_credentials_info').submit();
        }
    });

});
function validio(){
	  var tax_registration_certif_elc = $('#tax_registration_certif_elc').val();
	  var bank_licence_electronic = $('#bank_licence_electronic').val();
	  var fidcard=$('#fidcard').val();
      var zidcard=$('#zidcard').val();
	  var bank_account_number = $('#bank_account_number').val();
	  if(tax_registration_certif_elc=='' && zidcard==''){
		  alert('请上传身份证正面图像');
		  $('#tax_registration_certif_elc').addClass('w200 error');
		  return false;
		  }
	  if(bank_licence_electronic==''&& fidcard==''){
		  alert('请上传身份证反面图像');
		  $('#bank_licence_electronic').addClass('w200 error');
		  return false;
		  }
	   if(bank_account_number==''){
		    $('#bank_account_number').addClass('w200 error');
		  return false;
		   }
		  return true;
	 }
</script>
<!-- 公司资质 -->

<div id="apply_credentials_info" class="apply-credentials-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。</div>
    <?php if($output['flag']==1){ ?>
     <form id="form_credentials_info" action="index.php?act=store_joininc&op=stepdmstep3" method="post" enctype="multipart/form-data" >
     <?php }else{ ?>
  <form id="form_credentials_info" action="index.php?act=store_joininc&op=step3" method="post" enctype="multipart/form-data" >
  <?php } ?>
    <div id="div_settlement">
      <table border="0" cellpadding="0" cellspacing="0" class="all">
        <thead>
          <tr>
            <th colspan="20">结算（支付宝）账号信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150"><i>*</i>支付宝姓名：</th>
            <td><input id="settlement_bank_account_name" name="settlement_bank_account_name" type="text" class="w200" value="<?php echo $output['dm']['settlement_bank_account_name']; ?>"/>
              <span></span></td>
          </tr>
          <tr>
            <th><i>*</i>支付宝账号：</th>
            <td><input id="settlement_bank_account_number" name="settlement_bank_account_number" type="text" class="w200" value="<?php echo $output['dm']['settlement_bank_account_number']; ?>"/>
              <span></span></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="20">&nbsp;</td>
          </tr>
        </tfoot>
      </table>
      <?php if($output['flag']==1){?>
         <table border="0" cellpadding="0" cellspacing="0" class="all">
        <thead>
          <tr>
            <th colspan="20">财务负责人（银行账号信息）</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th rowspan="2" class="w150"><i>*</i>身份证正面：</th>
            
            <td><input name="tax_registration_certif_elc"  type="file" class="w200" id='tax_registration_certif_elc' />
             </td>
          </tr>
          <tr>
            <td> <input type="hidden" value="<?php echo $output['dm']['tax_registration_certif_elc']; ?>" id='zidcard' name="zidcard" /><?php if(!empty($output['dm']['tax_registration_certif_elc'])){ ?>
            	<img border="0" alt="手执身份证照范例" src="\data\upload\shop\store_joinin\<?php echo $output['dm']['tax_registration_certif_elc'];?>" style="width:300px;height:210px">   
          <?php }else{ ?>
            <?php }  ?>
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span></td>
          </tr>
          <tr>
            <th rowspan="2"><i>*</i>身份证反面：</th>
            <td><span>
              <input name="bank_licence_electronic"  type="file" class="w200" id="bank_licence_electronic" />
            </span></td>
          </tr>
          <tr>
            <td>  <input type="hidden" value="<?php echo $output['dm']['bank_licence_electronic']; ?>" id='fidcard' name="fidcard" /><?php if(!empty($output['dm']['bank_licence_electronic'])){ ?>
            	<img border="0" alt="手执身份证照范例" src="\data\upload\shop\store_joinin\<?php echo $output['dm']['bank_licence_electronic'];?>" style="width:300px;height:210px">  
          <?php }else{ ?>
            <?php }  ?>
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span></td>
          </tr>
             <tr>
            <th><i>*</i>银行卡号码：</th>
            <td><input id="bank_account_number" name="bank_account_number" type="text" class="w200" value='<?php echo $output['dm']['bank_account_number']; ?>'/>
              <span></span></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="20">&nbsp;</td>
          </tr>
        </tfoot>
      </table>
      <?php } ?>
    </div>
  </form>
  <div class="bottom"><a id="btn_apply_credentials_next" href="javascript:;" class="btn">下一步，提交店铺经营信息</a></div>
</div>
