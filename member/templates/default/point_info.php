<?php defined('In33hao') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
   <div class="tabmenu">
    <ul class="tab pngFix">
      <li class="normal"><a href="index.php?act=predeposit&op=port_points">云豆互转</a></li>
      <li class="active"><a href="index.php?act=predeposit&op=point_info">互转记录</a></li>
    </ul>
  </div>
  <form method="get" action="index.php">
    <table class="ncm-search-table">
      <input type="hidden" name="act" value="member_points" />
      <tr><td class="w10">&nbsp;</td>
       
        <th><?php echo $lang['points_addtime'] ?></th>
        <td class="w240"><input type="text" id="stime" name="stime" class="text w70" value="<?php echo $_GET['stime'];?>"><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input type="text" id="etime" name="etime" class="text w70" value="<?php echo $_GET['etime'];?>"><label class="add-on"><i class="icon-calendar"></i></label></td>
        <th><?php echo $lang['points_stage']; ?></th>
        <td class="w70 tc"><label class="submit-border">
            <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" />
          </label></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w200">添加时间</th>
        <th class="w150">云豆变更</th>
        <th class="w300">操作</th>
        <th class="tl">   描述</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list_log'])>0) { ?>
      <?php foreach($output['list_log'] as $val) { ?>
      <tr class="bd-line">
        <td class="goods-time"><?php echo @date('Y-m-d',$val['pl_addtime']);?></td>
        <td class="goods-price"><?php echo $val['pl_points']; ?></td>
        <td>云豆转账</td>
        <td class="tl"><?php echo $val['pl_desc'];?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record']; ?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['list_log'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script language="javascript">
$(function(){
  $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
  $('#etime').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>