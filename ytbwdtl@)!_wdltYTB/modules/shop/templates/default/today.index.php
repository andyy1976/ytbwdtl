<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?></h3>
        <h5><?php echo $lang['member_shop_manage_subhead']?></h5>
    </div>
    <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">当天数据</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['member_index_help1'];?></li>
      <li><?php echo $lang['member_index_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=today&op=every_one',
        colModel : [
            {display: '会员ID', name : 'member_id', width : 40, sortable : true, align: 'center'},
            {display: '充值总金额', name : 'today_mon', width : 100, sortable : true, align: 'center', },
            {display: '打款总金额', name : 'today_money', width : 100, sortable : true, align: 'center',},
            {display: '会员数量', name : 'today_shuju', width : 100, sortable : true, align: 'center', },
            {display: '1级代理数量', name : 'count_one', width : 100, sortable : true, align: 'center',},
            {display: '2级代理数量', name : 'count_two', width : 100, sortable : true, align: 'center'},
            {display: '3级代理数量', name : 'count_threev', width : 100, sortable : true, align: 'center'},
            {display: '4级代理数量', name : 'count_fous', width : 100, sortable : true, align: 'center'}
            ],
        // buttons : [
        //     {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
        //     {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }		
        //     ],
        // searchitems : [
        //     {display: '会员ID', name : 'member_id'},
        //     {display: '会员名称', name : 'member_name'}
        //     ],
        sortname: "list",
        // sortorder: "desc",
        title: '今天数据'
    });
	
});

// function fg_operation(name, bDiv) {
//     if (name == 'add') {
//         window.location.href = 'index.php?act=today&op=every_one';
//     }
//     if (name == 'csv') {
//         if ($('.trSelected', bDiv).length == 0) {
//             if (!confirm('您确定要下载全部数据吗？')) {
//                 return false;
//             }
//         }
//         var itemids = new Array();
//         $('.trSelected', bDiv).each(function(i){
//             itemids[i] = $(this).attr('data-id');
//         });
//         fg_csv(itemids);
//     }
// }

// function fg_csv(ids) {
//     id = ids.join(',');
//     window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
// }
</script> 

