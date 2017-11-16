<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
       <?php 
    $info=get_member_info($_SESSION['member_id']) ;
    if($_SESSION['member_level']>0 || $info['free']=='1'){?>
    <a class="ncbtn ncbtn-bittersweet" title="在线充值" href="index.php?act=predeposit&op=recharge_add" style="right: 207px;"><i class="icon-shield"></i>在线充值</a> 
    <?php }?>
    <?php 
    
    	if($info['member_bankcard']){?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=predeposit&op=pd_cash_add" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }else{?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=member_redpacket&op=rp_binding" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }?> 
    <!-- <a class="ncbtn ncbtn-bluejeansjeans" href="index.php?act=predeposit&op=rechargecard_add"><i class="icon-shield"></i>站内转账</a> </div> -->
  <div class="alert">
  	 <span class="mr30">分销余额：<strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['distributor_predeposit']; ?></strong><?php echo $lang['currency_zh']; ?></span>
   
       <!--    分销余额转云豆：<input type="number" id='money_to_points' placeholder="输入金额" style="width: 80px;"/> 
     <span style="display: none;" id='abcd'> 支付密码：<input type="number" id='money_pwd' placeholder="支付密码" style="width: 80px;"/></span>
     <button style="margin-left:5px;  cursor: pointer;height: 25px; background-color: #4798BF; color: #fff;" id="pointsok">确定转换</button> -->
  </div>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl"><?php echo $lang['predeposit_addtime']; ?></th>
        <th class="w150 tl">收入(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w150 tl">支出(<?php echo $lang['currency_zh'];?>)</th>
        <th class="w150 tl">冻结(<?php echo $lang['currency_zh'];?>)</th>
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

        <td class="tl blue"><?php echo floatval($v['freeze_amount']) ? (floatval($v['freeze_amount']) > 0 ? '+' : null ).$v['freeze_amount'] : null;?></td>
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
	  $('#pointsok').click(function(){
			var money=$('#money_to_points').val();
			var pwd=$('#money_pwd').val();
			if(money==0 || money=='' ||money<0){
				showDialog('请输入有效数字！', 'error','','','','','','','','',3);	
				exit();
			}
			if(pwd==''){
				showDialog('请输入支付密码！', 'error','','','','','','','','',3);	
				exit();
			}
		    $.post('index.php?act=predeposit&op=fenxiao_to_points',{money:money,pwd:pwd},function(result){
		       if(result==1){
		          showDialog('转换成功', 'succ','','','','','','','','',3);	
		          <?php //sleep(3) ;?>				    
			      location.reload() ;
			   }else if(result==0){
			   	  showDialog('转换失败', 'error','','','','','','','','',3);	
			   }else if(result==3){
			   	  showDialog('支付密码错误！', 'error','','','','','','','','',3);	
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