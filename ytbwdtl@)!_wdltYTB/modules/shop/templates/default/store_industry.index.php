<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo '行业分类';?></h3>
        <h5><?php echo $lang['store_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['store_class_help1'];?></li>
      <li><?php echo $lang['store_class_help2'];?></li>
    </ul>
  </div>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['nc_handle'];?></th>
          <th width="150" align="center"><?php echo '扣点数'; ?></th>
          <th width="300" align="left"><?php echo $lang['store_class_name'];?></th>
          <th width="550" align="center"><?php echo '行业二级类目';?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <?php foreach($output['class_list'] as $k => $v){ ?>
        <tr class="edit">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
          <a class="btn red" href="javascript:if(confirm('<?php echo $lang['del_store_class'];?>'))window.location = 'index.php?act=store_industry&op=store_class_del&hy_id=<?php echo $v['hy_id'];?>';"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
          <a class="btn blue" href="index.php?act=store_industry&op=store_class_edit&hy_id=<?php echo $v['hy_id'];?>"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a>
          </td>
          <td class="sort"><?php echo $v['hymc_name'];?></td>
          <td class="name"><?php echo $v['hy_dianshu'];?></td>
          <td><?php echo $v['hy_littlename'];?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no-data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '店铺行业列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [ 
                   {display: '<i class="fa fa-plus"></i>新增行业名称', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
			
               ]
	
		});

    // 修改分佣比例
    $('span[nc_type="inline_edit"]').inline_edit({act: 'store_class',op: 'ajax'});
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=store_industry&op=store_hangye_add';
    }
 
}

</script>