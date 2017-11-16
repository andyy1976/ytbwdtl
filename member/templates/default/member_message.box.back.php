<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <ul class="tab"> 
      <?php if(is_array($output['member_menu']) and !empty($output['member_menu'])) {
	foreach ($output['member_menu'] as $key => $val) {
		$classname = 'normal';
		if($val['menu_key'] == $output['menu_key']) {
			$classname = 'active';
		}
		if ($val['menu_key'] == 'message'){
			echo '<li class="'.$classname.'"><a href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newcommon'].'</span>)</a></li>';
		}elseif ($val['menu_key'] == 'system'){
			echo '<li class="'.$classname.'"><a href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newsystem'].'</span>)</a></li>';
		}elseif ($val['menu_key'] == 'close'){
			echo '<li class="'.$classname.'"><a href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newpersonal'].'</span>)</a></li>';
		}else{
			echo '<li class="'.$classname.'"><a href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
		}
	}
}?>
    </ul>
   
  </div>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="ncm-default-table">
   <tr>
    <td align="center" valign="middle">序号</td>
    <td align="center" valign="middle">反馈内容</td>
    <td align="center" valign="middle">反馈时间</td>
    <td align="center" valign="middle">回答时间</td>
    <td align="center" valign="middle">回答内容</td>
    <td align="center" valign="middle">是否回复</td>
   </tr>
    <?php if (!empty($output['message_array'])) { ?>      
    <?php foreach($output['message_array'] as $k => $v){ ?>
    <tr>
    <td align="center" valign="middle"><?php  echo $k+1; ?></td>
    <td align="center" valign="middle"><?php  echo $v['content']; ?></td>
    <td align="center" valign="middle"><?php  echo date('Y-m-d',$v['ftime']); ?></td>
    <td align="center" valign="middle"><?php  if(empty($v['bftime'])){}else{ echo date('Y-m-d',$v['bftime']);} ?></td>
    <td align="center" valign="middle"><?php  echo $v['bcontent']; ?></td>
    <td align="center" valign="middle"><?php   if(empty($v['bftime'])){ echo "否";}else{ echo "是";} ?></td>
   </tr>
   <?php } ?>
   
   <tr><td colspan="6"><div class="pagination"><?php echo $output['show_page']; ?></div></td></tr>
   <?php }else{ ?>
   <tr>  <td colspan="6" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td></tr>
   <?php } ?>
   </table>
</div>
