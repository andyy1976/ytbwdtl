<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
<div class="tabmenu" style="color: red; margin-left: 70px;">
  <div>
    <div>
      <div>
        <p>注：成功注册会员后，扣除您的分销奖金500元，奖励100元，奖励您的上级150元。<br />
        </p>
      </div>
    </div>
  </div>
</div>
  </div>
  <div class="ncm-default-form">
    <form method="post" id="cash_form" action="<?php echo MEMBER_SITE_URL;?>/index.php?act=member&op=registerr">
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required">*</i>手机号码：</dt>
        <dd>
          <input name="member_name" type="text" class="text w100" id="member_name" maxlength="20"/><span id='check_name' style='color:red;'></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>登陆密码：</dt>
        <dd>
          <input name="member_passwd" type="password" class="text w100" id="member_passwd" maxlength="20"/><span></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>安全密码：</dt>
        <dd>
          <input name="member_paypwd" type="password" class="text w100" id="member_paypwd" maxlength="20"/><span></span>
        </dd>
      </dl>
      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="submit"  class="submit" value="确认注册" /></label></dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
    //检测账号
    $('#member_name').bind('input propertychange', function(){
        var member_name=$('#member_name').val();
        
        $.post("index.php?act=member&op=check",{
          member:member_name
        },function(data){
          if(data=='1'){
            $('#check_name').html('该账号已被注册！');
          }else{
            $('#check_name').html('该账号可以注册！');
          }
        });
    });
});
</script>