<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu" style="height: auto;">
    <?php include template('layout/submenu'); ?>
     <?php 
    $info=get_member_info($_SESSION['member_id']) ;
    if($_SESSION['member_level']>0 || $info['free']=='1'){?>
    <a class="ncbtn ncbtn-bittersweet" title="在线充值" href="index.php?act=predeposit&op=recharge_add" style="right: 207px;"><i class="icon-shield"></i>在线充值</a> 
        <?php } ?>
    <?php 
     
      if($info['member_bankcard']){
    ?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=predeposit&op=pd_cash_add" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }else{?>
    <a class="ncbtn ncbtn-mint" href="index.php?act=member_redpacket&op=rp_binding" style="right: 107px;"><i class="icon-money"></i>申请提现</a>
    <?php }?>
    <!-- <a class="ncbtn ncbtn-bluejeansjeans" href="index.php?act=predeposit&op=rechargecard_add"><i class="icon-shield"></i>站内转账</a> </div> -->
  <div class="alert">
    <?php if($_SESSION['member_level']>=1 && $_SESSION['member_level']<6){ ?>
    <span class="mr30">
      <select id='class_type' name='class_type' class='class_type'>
        <option value='0'>请选择类型</option>
        <option value='2' <?php if($_GET['class_type'] == 2){echo 'selected="selected"';} ?>>卡分润</option>
        <option value='1' <?php if($_GET['class_type'] == 1){echo 'selected="selected"';} ?>>充值分润</option>
        <option value='3' <?php if($_GET['class_type'] == 3){echo 'selected="selected"';} ?>>消费分润</option>
        <option value='6' <?php if($_GET['class_type'] == 6){echo 'selected="selected"';} ?>>分销分润</option>
        <option value='4' <?php if($_GET['class_type'] == 4){echo 'selected="selected"';} ?>>激活端口分润</option>
        <option value='7' <?php if($_GET['class_type'] == 7){echo 'selected="selected"';} ?>>每日赠送</option>
         <?php if($_SESSION['member_level']==2){ ?>
        <option value='5' <?php if($_GET['class_type'] == 5){echo 'selected="selected"';} ?>>下级端口收益分润</option>
        <?php } ?>
      </select>

    </span>
    <?php } ?>
    <span class="mr30"><?php echo '云豆余额'.$lang['nc_colon']; ?>
      <strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['available_predeposit']; ?></strong>
    </span>
    <?php if($_SESSION['member_level']>1 && $_SESSION['member_level']<6){ ?>
    <span class="mr30" >代理钱包：
      <strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['agent_predeposit']; ?></strong>
    </span>
    <span class="mr30" >冻结钱包：
      <strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['frozen_agent']; ?></strong>
    </span>
    <?php } ?>
    <?php if($_SESSION['member_level']=='6'){ ?>
    <span class="mr30" >代理钱包：
      <strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['member_bonus']; ?></strong>
    </span>
    <?php } ?>
    <?php if($_SESSION['member_level']=='5'){ ?>
    <span class="mr30" style="float:right">省代云豆释放钱包：
      <strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['province_predeposit']; ?></strong>
    </span>
    <?php } ?>
  </div>
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
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="goods-time tl"><?php echo @date('Y-m-d H:i:s',$v['lg_add_time']);?></td>
<?php $availableFloat = (float) $v['lg_av_amount']; if ($availableFloat > 0) { ?>
        <td class="tl red">+<?php echo $v['lg_av_amount']; ?></td>
        <td class="tl green"></td>
<?php } elseif ($availableFloat < 0) { ?>
        <td class="tl red"></td>
        <td class="tl green"><?php echo $v['lg_av_amount']; ?></td>
<?php } else { ?>
        <td class="tl red"></td>
        <td class="tl green"></td>
<?php } ?>
       
        <td class="tl"><?php echo $v['lg_desc'];?></td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<script type="text/javascript">
$(document).ready(function(){ 
      $('#class_type').change(function(){
        var class_type=$('#class_type').val();
       
        location.href='index.php?act=predeposit&op=pd_log_list&class_type='+class_type;
      });
  });
</script>