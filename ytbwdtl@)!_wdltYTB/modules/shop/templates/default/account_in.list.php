<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>云豆互转记录</h3>
        <h5></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=predeposit&op=predeposit"><?php echo $lang['admin_predeposit_rechargelist']?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_cash_list"><?php echo $lang['admin_predeposit_cashmanage']; ?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_log_list"><?php echo $lang['nc_member_predepositlog'];?></a></li>
        <li><a href="index.php?act=predeposit&op=account_info">账户明细</a></li>
        <li><a href="index.php?act=predeposit&op=agent_count">代理团队人数</a></li>
        <li><a href="index.php?act=predeposit&op=account_infoll">云豆互转</a></li>
        <li><a href="JavaScript:void(0);" class="current">云豆互转记录</a></li>

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
      <li><?php echo $lang['admin_predeposit_recharge_help1'];?></li>
      <li><?php echo $lang['admin_predeposit_recharge_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
<!--   <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>会员名称</dt>
            <dd>
              <label>
                <input type="text" value="" name="member_name" id="goods_name" class="s-input-txt" placeholder="输入会员全称或关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>会员ID</dt>
            <dd>
              <label>
                <input type="text" value="" name="member_id" id="member_id" class="s-input-txt" placeholder="输入会员ID">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>时期筛选</dt>
            <dd>
              <label>
                <input type="text" name="query_start_date" data-dp="1" class="s-input-txt" placeholder="请选择开始时间" />
              </label>
              <label>
                <input type="text" name="query_end_date" data-dp="1" class="s-input-txt" placeholder="请选择结束时间"  />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>类型</dt>
            <dd>
              <label>
                <select name="pdr_payment_state" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="0">云豆互转记录</option>
                  <option value="1">消费记录</option>
                </select>
              </label>
            </dd>
          </dl>

        </div>
      </div>
      <div class="bottom"><a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a></div>
    </form>
  </div>  -->
</div>
<script type="text/javascript">
$(function(){
    $("input[data-dp='1']").datepicker();
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=get_account_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=get_account_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    
    $("#flexigrid").flexigrid({
        url: 'index.php?act=predeposit&op=account_in_xml',
        colModel : [
            {display: '充值ID', name : 'pl_id', width : 80, sortable : true, align: 'center'},
            {display: '会员ID', name : 'pl_memberid', width : 80, sortable : true, align: 'center'},
            {display: '会员名称', name : 'pl_membername', width : 150, sortable : true, align: 'left'},
            {display: '云豆', name : 'pl_points', width : 100, sortable : true, align: 'center'},
            {display: '时间', name : 'pl_add_time', width : 100, sortable : true, align: 'center'},
            {display: '说明', name : 'pl_desc', width : 300, sortable : true, align: 'center'},
            {display: '操作', name : 'pl_stage', width : 300, sortable : true, align: 'center'},
            ],
        // buttons : [
        //        {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出Excel文件', onpress : fg_operation }
        //    ],
        searchitems : [
            {display: '充值ID', name : 'pl_id'},
            {display: '会员ID', name : 'pl_memberid'},
            {display: '会员名称', name : 'pl_membername'},
           
            ],
        sortname: "pl_id",
        sortorder: "desc",
        title: '云豆互转记录'
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

// function fg_csv() {
//       var member_id=$('#member_id').val();
//       var member_name=$('#goods_name').val();
//       var start_time=$("input[name='query_start_date']").val();
//       var end_time =$("input[name='query_end_date']").val();
//       var pdr_payment_state=$("*[name='pdr_payment_state']").val();
//       window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_step2&member_id=' + member_id+'&member_name='+member_name+'&start_time='+start_time+'&end_time='+end_time+'&pdr_payment_state='+pdr_payment_state;
// }

function fg_delete(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=predeposit&op=recharge_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg);
            }
        });
    }
}
</script> 