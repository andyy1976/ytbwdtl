<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu">
    <ul class="tab pngFix">
      <li class="active"><a href="index.php?act=predeposit&op=give_points">云豆互转</a></li>
      <li class="normal"><a href="index.php?act=predeposit&op=point_info">互转记录</a></li>
    </ul>
  </div>
  <div class="ncm-default-form">
    <div class="ncm-notes">
      <h4>应广大会员要求，为了方便会员间的云豆流通，开通站内转账功能，转账时请务必填写正确对方ID号，如果ID错误云豆可能无法归还，由于错误填写造成的云豆损失，本站概不负责！！！！</h4>
      <p>云豆转账扣除3%手续费</p><br>
    </div>
    <input type='hidden' value='<?php echo $_SESSION['member_id']; ?>' id='memberid'>
    转账云豆：<input type="number" id='money' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g," /><br><br>
    收款人ID：<input type="number" id='member_id'/> <span id='membername' style="color: #f00;"></span><br><br>
    收款人银行卡姓名：<input type="text" id='member_bankname'/> <span id='bankname' style="color: #f00;"></span><br><br>
    支付密码：<input type="password" id='pwd' /><br><br>
    验证码：<input type="text" id='code' /><input type='button' value='获取验证码' id='send'><br><br>
   <button id='go' style="width: 80px;height:27px;background-color: #48CFAE; color: #FFF;">确定</button>
  </div>
</div>
<script type="text/javascript">
$(function(){
  $('#send').click(function(){
      var id=$('#memberid').val();
      // if(phone==''){alert('请输入手机号!!!');return false;}
      $.post('index.php?act=predeposit&op=send',{id:id},function(result){
          if(result==1){
              alert('手机号必须是11位数字');                 
          }else if(result==2){
            alert('验证码已发送您手机，请注意查收'); 
          }else if(result==3){
            alert('短信发送失败');  
          }else if(result==4){
            alert('该手机号码未注册，请重新输入！！！');
          }else if(result==6){
            alert('验证码发送频繁2分钟之后在发送');
          }
      }); 
    });
  $('#go').click(function(){
      var money=$('#money').val();
      var memberid=$('#member_id').val();
      var pwd=$('#pwd').val();
      var id=<?php echo $_SESSION['member_id'] ;?>;
      
      var code=$('#code').val();
      if(code==''){
        showDialog('验证码不能为空！', 'error','','','','','','','','',3);  
        exit();
      }
      if(memberid==id){
        showDialog('不能给自己转账！', 'error','','','','','','','','',3);  
        exit();
      }
      if(money==0 || money=='' ||money<0){
        showDialog('请输入有效数字金额！', 'error','','','','','','','','',3);  
        exit();
      }
      if(money>1000000){
        showDialog('每日最高转账限额一百万云豆！', 'error','','','','','','','','',3);  
        exit();
      }
      if(memberid==0 || memberid=='' || memberid<0){
        showDialog('请输入收款人ID ！', 'error','','','','','','','','',3);  
        exit();
      }
      if(pwd==''){
        showDialog('请输入支付密码！', 'error','','','','','','','','',3);  
        exit();
      }
      var bank_name=$('#member_bankname').val();
      if(bank_name==''){
        showDialog('请输入对方银行卡姓名！', 'error','','','','','','','','',3);  
        exit();
      }
      $.post('index.php?act=predeposit&op=givepoint',{money:money,code:code,pwd:pwd,memberid:memberid,bank_name:bank_name},function(result){
          if(result==1){
              showDialog('转账成功', 'succ','','','','','','','','',3); 
              <?php //sleep(3) ;?>            
              location.reload() ;
          }else if(result==0){
              showDialog('转账失败', 'error','','','','','','','','',3);  
          }else if(result==3){
              showDialog('支付密码错误！', 'error','','','','','','','','',3); 
          }else if(result==4){
              showDialog('您填写的收款人ID不存在！', 'error','','','','','','','','',3); 
          }else if(result==6){
              showDialog('今日转账已达一百万云豆, 无法继续转账', 'error','','','','','','','','',3);
          }else if(result==7){
              showDialog('您的账户云豆低于20万，无法转账', 'error','','','','','','','','',3);
          }else if(result==8){
              showDialog('您输入的银行卡姓名错误!', 'error','','','','','','','','',3);
          }else if(result==9){
              showDialog('您的验证码输入错误，请重新输入', 'error','','','','','','','','',3);
          }else if(result==10){
              showDialog('您的验证码已过期，请重新获取', 'error','','','','','','','','',3);
          }else{
              showDialog('余额不足！请充值', 'error','','','','','','','','',3);
          }
      });
  })
  //失去焦点
  $('#member_id').blur(function(){
      var memberid=$('#member_id').val();
      //alert(memberid);exit;
      $.post('index.php?act=predeposit&op=getname',{memberid:memberid},function(result){
         if(result){
            var result = JSON.parse(result); 
                $('#membername').text('收款人：'+result.member_name);
                $("#bankname").text('收款人银行卡姓名:'+result.member_bankname);
         }
      }); 
  });  
});
</script>