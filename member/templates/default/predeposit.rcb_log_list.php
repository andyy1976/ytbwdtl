<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
        <?php 
        $info=get_member_info($_SESSION['member_id']) ;
        if($_SESSION['member_level']>0 || $info['free']=='1'){?>
    <a class="ncbtn ncbtn-bittersweet" title="在线充值" href="index.php?act=predeposit&op=recharge_add" style="right: 207px;"><i class="icon-shield"></i>在线充值</a> 
    <?php } ?>
    <?php 
      
      if($info['member_bankcard']){?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=predeposit&op=pd_cash_add" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }else{?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=member_redpacket&op=rp_binding" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }?> 
    <a class="ncbtn ncbtn-bluejeansjeans" href="index.php?act=predeposit&op=rechargecard_add"><i class="icon-shield"></i>站内转账</a> </div>
 
<!--   <div class="alert">
  <input type='hidden' value='<?php echo $_SESSION['member_id']; ?>' id='member_id'>
     <span class="mr30">充值余额：<strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['member_predeposit']; ?></strong><?php echo $lang['currency_zh']; ?></span>
      
           充值余额转云豆：<input type="number" id='money_to_points' placeholder="输入金额" style="width: 80px;"/> 
     <span  id='abcd'> 支付密码：<input type="password" id='money_pwd' placeholder="支付密码" style="width: 80px;"/></span> 
      <span  id='abcd'> 手机验证码：<input type="text" id='code' placeholder="验证码" style="width: 80px;"/><input value="获取验证码" id="send" type="button"></span> 
     <button style="margin-left:5px;  cursor: pointer;height: 25px; background-color: #4798BF; color: #fff;" id="pointsok">确定转换</button>
     <br>
<h3 style="color:red">注：账户充值2万及2万以下需扣除5%的服务费。充值余额兑换云豆，2万及2万以下的按6%的服务费收取。账户充值和充值余额兑换云豆合计超出2万部分按8%的服务费收取。每天兑换限额100万云豆。请确认无误再转换，转换后云豆只能享受每日赠送。</h3>
  </div> -->
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl"><?php echo $lang['predeposit_addtime']; ?></th>
        <th class="w150 tl">收入(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w150 tl">支出(<?php echo $lang['currency_zh'];?>)</th>
      
        <th class="tl"><?php echo $lang['predeposit_log_desc'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($output['list']) > 0) { ?>
      <?php foreach ($output['list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="goods-time tl"><?php echo @date('Y-m-d H:i:s',$v['add_time']);?></td>
<?php $availableFloat = (float) $v['available_amount']; if ($availableFloat > 0) { ?>
        <td class="tl red">+<?php echo $v['available_amount']; ?></td>
        <td class="tl green"></td>
<?php } elseif ($availableFloat < 0) { ?>
        <td class="tl red"></td>
        <td class="tl green"><?php echo $v['available_amount']; ?></td>
<?php } else { ?>
        <td class="tl red"></td>
        <td class="tl green"></td>
<?php } ?>

        
        <td class="tl"><?php echo $v['description'];?></td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php if (count($output['list']) > 0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<script>
$(function(){
    $('#send').click(function(){
      var id=$('#member_id').val();
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
          }
      }); 
    });
    $('#pointsok').click(function(){
      var money=$('#money_to_points').val();
      var pwd=$('#money_pwd').val();
      var code=$('#code').val();
      if(code==''){
        showDialog('请输入验证码！', 'error','','','','','','','','',3);  
        exit();
      }
      if(money==0 || money=='' ||money<0){
        showDialog('请输入有效数字！', 'error','','','','','','','','',3);  
        exit();
      }
      if(pwd==''){
        showDialog('请输入安全密码！', 'error','','','','','','','','',3);  
        exit();
      }
        $.post('index.php?act=predeposit&op=money_to_points',{money:money,pwd:pwd,code:code},function(result){
           if(result==1){
              showDialog('转换成功', 'succ','','','','','','','','',3); 
              <?php //sleep(3) ;?>            
            location.reload() ;
         }else if(result==0){
            showDialog('转换失败', 'error','','','','','','','','',3);  
         }else if(result==3){
            showDialog('支付密码错误！', 'error','','','','','','','','',3); 
         }else if(result==4){
            showDialog('验证码错误！', 'error','','','','','','','','',3); 
         }else if(result==5){
            showDialog('验证码已过期！', 'error','','','','','','','','',3); 
         }else if(result==6){
            showDialog('每日限额100万！', 'error','','','','','','','','',3); 
         }else{
            showDialog('余额不足！请充值', 'error','','','','','','','','',3);
         }
      });
    })
    $('#money_to_points').focus(function(){
      $("#abcd").show();
    });                
})
</script>