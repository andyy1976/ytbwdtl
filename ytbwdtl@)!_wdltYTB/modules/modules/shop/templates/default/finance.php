<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>财务管理</h3>
       
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=member&op=index">会员管理</a></li>
        <li><a href="JavaScript:void(0);" class="current">财务管理</a></li>
        <li><a href="index.php?act=member&op=recharge_buy">会员购买</a></li>
        <li><a href="index.php?act=member&op=pd_cash_list">提款管理</a></li>
        <li><a href="index.php?act=member&op=pd_cash_list">当天数据管理</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['admin_predeposit_log_help1'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=predeposit&op=get_log_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '日志ID', name : 'lg_id', width : 40, sortable : true, align: 'center'},
            {display: '会员ID', name : 'lg_member_id', width : 40, sortable : true, align: 'center'},
            {display: '会员名称', name : 'lg_member_name', width : 80, sortable : true, align: 'center'},
            {display: '可用金额（元）', name : 'lg_av_amount', width : 100, sortable : true, align: 'left'},
            {display: '冻结金额（元）', name : 'lg_freeze_amount', width : 100, sortable : true, align: 'left'},
            {display: '添加时间', name : 'lg_add_time', width : 100, sortable : true, align: 'left'},
            {display: '日志描述', name : 'lg_desc', width : 300, sortable : false, align: 'left'},
            {display: '管理员', name : 'lg_admin_name', width : 60, sortable : true, align: 'left'}
            ],
        buttons : [
                   {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出Excel文件', onpress : fg_operation }
               ],
        searchitems : [
            {display: '日志ID', name : 'lg_id'},
            {display: '会员ID', name : 'lg_member_id'},
            {display: '会员名称', name : 'lg_member_name'},
            {display: '管理员', name : 'lg_admin_name'}
            ],
        sortname: "lg_id",
        sortorder: "desc",
        title: '充值明细列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_mx_step1&id=' + id;
}
</script> 