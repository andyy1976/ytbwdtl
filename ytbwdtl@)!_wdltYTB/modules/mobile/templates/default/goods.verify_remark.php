<?php defined('In33hao') or exit('Access Invalid!');?>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/admin.js" type="text/javascript"></script>

<form method="post" name="form1" id="form1" class="ncap-form-dialog" action="<?php echo urlAdminMobile('mb_feedback', 'goods_verify');?>">

  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" value="<?php echo $output['common_info']['id'];?>" name="commonid"/>
  <input type="hidden" value="<?php echo($output['admin_info']['name']); ?>" name="admin_name" />
  <input type="hidden" value="<?php echo($output['admin_info']['id']); ?>" name="admin_id"/>
  <input type="hidden" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>" name="server_ip"/>
  <input type="hidden" value="<?php echo $output['common_info']['type']; ?>" name="client_type"/>
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">反馈时间</dt><dd class="opt"><?php echo date('Y-m-d H:i:s', $output['common_info']['ftime']);?></dd></dl>
      <dl class="row">
      <dt class="tit">反馈内容</dt><dd class="opt"><?php echo $output['common_info']['content'];?></dd></dl>
       <dl class="row">
      <dt class="tit">反馈会员ID</dt><dd class="opt"><?php echo $output['common_info']['member_id'];?></dd></dl>
      <dl class="row">
      <dt class="tit">反馈会员</dt><dd class="opt"><?php echo $output['common_info']['member_name'];?></dd></dl>
    <!--<dl class="row">
      <dt class="tit">
        <label>是否回复</label>
      </dt>
      <dd class="opt">
        <div class="onoff">
          <label for="rewrite_enabled"  class="cb-enable selected" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
          <label for="rewrite_disabled" class="cb-disable" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
          <input id="rewrite_enabled" name="verify_state" checked="checked" value="1" type="radio">
          <input id="rewrite_disabled" name="verify_state" value="0" type="radio">
        </div>
        <p class="notic"><?php echo $lang['open_rewrite_tips'];?></p>
      </dd>
    </dl>-->
    <dl class="row" nctype="reason" style="display: block">
      <dt class="tit">
        <label for="verify_reason">回复内容</label>
      </dt>
      <dd class="opt">
        <textarea rows="6" class="tarea" cols="60" name="verify_reason" id="verify_reason"></textarea>
      </dd>
    </dl>
    <div class="bot"><a href="javascript:void(0);" class="ncap-btn-big ncap-btn-green" nctype="btn_submit"><?php echo $lang['nc_submit'];?></a></div>
  </div>
</form>
<script>
$(function(){
    $('a[nctype="btn_submit"]').click(function(){
		var verify_reason = $('#verify_reason').val();
	if(verify_reason==''){
		alert('请输入要回复的内容！');
		return false;
		}
       $('#form1').submit();
		
    });
    
});
</script>