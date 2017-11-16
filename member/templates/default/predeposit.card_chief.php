<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
    <?php $info=get_member_info($_SESSION['member_id']) ;
        if($_SESSION['member_level']>0 || $info['free']=='1'){?>
    <a class="ncbtn ncbtn-bittersweet" title="在线充值" href="index.php?act=predeposit&op=recharge_add" style="right: 207px;"><i class="icon-shield"></i>在线充值</a> 
    <?php } ?>
    <?php 
    	
    	if($info['member_bankcard']){?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=member_security&op=auth&type=pd_cash" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }else{?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=member_redpacket&op=rp_binding" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }?> 
    <!-- <a class="ncbtn ncbtn-bluejeansjeans" href="index.php?act=predeposit&op=rechargecard_add"><i class="icon-shield"></i>站内转账</a> </div> -->
<!--   <div class="alert">
  	 <span class="mr30">上月卡代分成：<strong class="mr5 red" style="font-size: 18px;"><?php echo $output['money']; ?></strong><?php echo $lang['currency_zh']; ?></span>  	 
  </div> -->
  
</div>
