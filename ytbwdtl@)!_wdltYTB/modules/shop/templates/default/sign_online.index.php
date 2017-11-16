<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员内推升级管理</h3>
        <h5>会员内推申请查看、审核管理中心</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>点击审核按钮可以对申请进行审核，点击查看按钮可以查看申请信息。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=sign_online&op=get_signonline_xml&type=<?php echo $output['type'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '加盟会员ID', name : 'update_member_id', width : 80, sortable : true, align: 'center'},
            {display: '加盟会员姓名', name : 'update_member_truename', width : 80, sortable : false, align: 'left'},
            {display: '加盟会员手机号', name : 'update_member_mobile', width : 100, sortable : true, align: 'left'},
            {display: '加盟等级', name : 'update_level', width: 60, sortable : true, align : 'center'},                        
            {display: '加盟等级详情', name : 'update_level_detail', width : 150, sortable : false, align: 'left'},
            {display: '加盟总费用', name : 'update_amount_total', width : 100, sortable : false, align: 'left'},
            {display: '加盟首期费用', name : 'update_amount_first', width : 100, sortable : false, align: 'left'},
            {display: '加盟剩余费用', name : 'update_amount_last', width : 100, sortable : false, align : 'left'},
            {display: '加盟剩余费用偿还期限', name : 'update_amount_last_date', width : 80, sortable : false, align : 'left'},
            {display: '招商方ID', name : 'submit_member_id', width : 50, sortable : false, align: 'left'},
            {display: '审核状态', name : 'update_status', width : 50, sortable : false, align: 'left'},
            {display: '申请时间', name : 'add_time', width : 80, sortable : false, align: 'left'}
            ],
        searchitems : [
            {display: '加盟会员ID', name : 'update_member_id', isdefault: true},
            {display: '加盟会员姓名', name : 'update_member_truename'},
            {display: '招商方ID', name : 'submit_member_id'}
            ],
        sortname: "add_time",
        sortorder: "asc",
        title: '会员内推升级申请列表'
    });
});

function test(name, bDiv) {
    if (name == 'excel') {
        confirm('Delete ' + $('.trSelected', bDiv).length + ' items?')
    } else if (name == 'Add') {
        alert('Add New Item');
    }
}
</script>