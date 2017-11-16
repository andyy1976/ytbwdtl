<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
    <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div> 
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl"><?php echo $lang['predeposit_addtime']; ?></th>
        <th class="w150 tl"></th>
        <th class="w150 tl">金额(<?php echo $lang['currency_zh'];?>)</th>
        <th class="tl"><?php echo $lang['predeposit_log_desc'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['rcb'])>0) {
       ?>
      <?php foreach($output['rcb'] as $v) { ?>
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
      <?php  if (count($output['rcb'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
