<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage']; ?></h3>
        <h5><?php echo $lang['nc_store_manage_subhead']; ?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['store_help1'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div></div>

<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=shop_instance&op=get_xmlpoint',
        colModel : [
            /*{display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},*/
            {display: '店铺ID', name : 'store_id', width : 200, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 250, sortable : false, align: 'left'},
            {display: '商家电话', name : 'contact_man', width : 150, sortable : false, align : 'left'},
			{display: '支付时间', name : 'add_time', width : 150, sortable : true, align: 'center'},
			{display: '订单套餐', name : 'order_suite', width : 100, sortable : true, align: 'center'},
			{display: '订单号', name : 'order_sn', width : 300, sortable : true, align: 'center'},
			{display: '交易云豆数量', name : 'need_points', width : 120, sortable : true, align: 'center'},
			{display: '商家支付金额', name : 'need_money', width : 120, sortable : true, align: 'center'},
			{display: '购买者ID', name : 'buyer_id', width : 150, sortable : false, align: 'left'},
            ],
        <!--buttons : [
            <!--{display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }	,
			<!--{display: '<i class="fa fa-plus"></i>新增数据', name : 'haoshop_add', bclass : 'add', title : '添加一条新数据到列表', onpress : fg_operations }				
       <!-- ],-->	
        searchitems : [
            {display: '店铺名称', name : 'store_name', isdefault: true},
            {display: '购买者ID', name : 'buyer_id'}
            ],
        sortname: "point_id",
        sortorder: "asc",
        title: '云豆交易记录列表'
    });

    // 高级搜索提交
    /*$('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=shop_instance&op=get_xmlpoint&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=shop_instance&op=get_xmlpoint'}).flexReload();
        $("#formSearch")[0].reset();
    });
    // //给推荐人提成
    // $('#chief').click(function(){
    //     $.post('index.php?act=store&op=chief',{member_id:1,part:0},function(data){				
	   //          showDialog(data, 'succ','','','','','','','','',3);		            	              	                     
	   //    });
    // });*/
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
function fg_operations(name, bDiv) {
    if (name == 'haoshop_add') {
        window.location.href = 'index.php?act=shop_instance&op=haoshop_add';
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
}
</script>