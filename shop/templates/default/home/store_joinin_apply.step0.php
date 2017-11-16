<?php defined('In33hao') or exit('Access Invalid!');?>

<!-- 协议 -->

<div id="apply_agreement" class="apply-agreement">
  <div class="title"><h3>入驻协议</h3></div>
  <div class="apply-agreement-content"> <?php echo $output['agreement'];?> </div>
  <div class="apple-agreement">
    <input id="input_apply_agreement" name="input_apply_agreement" type="checkbox" checked />
    <input name="flag" id="flag" value="<?php echo $output['flag']; ?>" type="hidden" />
    <label for="input_apply_agreement">我已阅读并同意以上协议</label>
  </div>
  <div class="bottom">
      <?php if(!empty($output['flag'])){ ?> 
        <a id="btn_dm_agreecbc_next" href="javascript:;" class="btn">地面商家入驻</a>  
      <?php }else{  ?>
          <?php if($_SESSION['member_level'] == '5'){?>
            <a id="btn_apply_agreecbc_next" href="javascript:;" class="btn">省旗舰店</a>
          <?php }else{?>
        <a id="btn_apply_agreecbc_next" href="javascript:;" class="btn">个人入驻</a>
        <a style=" margin-left:15px;" id="btn_apply_agreement_next" href="javascript:;" class="btn">企业入驻</a>
          <?php } ?>
      <?php }  ?>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#btn_dm_agreecbc_next').on('click',function(){
		 if($('#input_apply_agreement').prop('checked')) {
		  window.location.href = "index.php?act=store_joininc&op=stepdmstep1";
		} else {
            alert('请阅读并同意协议');
        }
		});
	$('#btn_apply_agreecbc_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
		  window.location.href = "index.php?act=store_joininc&op=step1";
			} else {
            alert('请阅读并同意协议');
        }
    });
    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
		window.location.href = "index.php?act=store_joinin&op=step1";
	   } else {
      alert('请阅读并同意协议');
        }
    });
});
</script>