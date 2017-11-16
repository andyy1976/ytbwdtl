<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?></h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
     <!-- <li>点击查看按钮，可以查看完整的记录。</li>-->
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=shop_instance&op=get_consumme_xml',
        colModel : [
            {display: '会员ID', name : 'member_id', width : 60, sortable : true, align: 'center'},
            {display: '会员账号', name : 'member_name', width : 100, sortable : false, align: 'left'},
            {display: '付款金额(元)', name : 'paying_amount', width : 100, sortable : false, align: 'left'}, 
			 {display: '订单号', name : 'pdr_pay_sn', width : 320, sortable : false, align: 'left'},                 
            {display: '订单状态', name : 'joinin_state', width: 80, sortable : true, align : 'center'},
            {display: '实体店名称', name : 'joinin_year', width: 220, sortable : true, align : 'center'},                        
            {display: '订单添加日期', name : 'contacts_name', width : 100, sortable : false, align: 'left'},
            {display: '实体店联系人电话', name : 'contacts_phone', width : 120, sortable : false, align: 'left'},
            {display: '订单支付日期', name : 'contacts_email', width : 120, sortable : false, align: 'left'},
			{display: '平台扣除积分', name : 'conta', width : 120, sortable : false, align: 'left'},
            {display: '商家赠送积分', name : 'contacts', width : 120, sortable : false, align: 'left'}
            ],
        searchitems : [
            {display: '会员ID', name : 'pdr_member_id', isdefault: true},
            {display: '实体店名称', name : 'pdr_st_shop'}
            ],
        sortname: "joinin_state",
        sortorder: "asc",
        title: '消费记录'
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
