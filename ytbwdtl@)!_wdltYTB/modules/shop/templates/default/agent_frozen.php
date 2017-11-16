<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?></h3>
        <h5><?php echo $lang['member_shop_manage_subhead']?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul class="tab-base nc-row">
        <li><a href="index.php?act=predeposit&op=predeposit"><?php echo $lang['admin_predeposit_rechargelist']?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_cash_list"><?php echo $lang['admin_predeposit_cashmanage']; ?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_log_list"><?php echo $lang['nc_member_predepositlog'];?></a></li>
        <li><a href="index.php?act=predeposit&op=account_info">账户明细</a></li>
        <li><a href="index.php?act=predeposit&op=agent_count">代理团队人数</a></li>
        <li><a href="JavaScript:void(0);" class="current">各级代理冻结金额</a></li>
      </ul>
  </div>
  <div id="flexigrid"></div>
    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>代理名称</dt>
            <dd>
              <label>
                <input type="text" value="" name="member_name" id="goods_name" class="s-input-txt" placeholder="输入会员全称或关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>代理ID</dt>
            <dd>
              <label>
                <input type="text" value="" name="member_id" id="member_id" class="s-input-txt" placeholder="输入会员ID">
              </label>
            </dd>
          </dl>
          <dd>
                <select name="query_year">
                <option value=""> 年份&nbsp;&nbsp;</option>
                <?php for($i=date('Y',time())-4;$i<=date('Y',time())+4;$i++) { ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php } ?>
                </select> - 
                <select name="query_month">
                <option value=""> 月份&nbsp;&nbsp;</option>
                <?php for($i=1;$i<=12;$i++) { ?>
                <option value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT);?>"><?php echo $i;?></option>
                <?php } ?>
                </select>
          </dd>
          <dl>
            <dt>代理级别</dt>
            <dd>
              <label>
                <select name="predeposit_type" class="s-select">
                  <option value="5">省级代理</option>
                </select>
              </label>
            </dd>
          </dl>

        </div>
      </div>
      <div class="bottom"><a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=agentfrozen_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=predeposit&op=agentfrozen_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 160, sortable : false, align: 'center', className: 'handle-s'},
            {display: '会员ID', name : 'member_id', width : 100, sortable : true, align: 'center'},
            {display: '省代理', name : 'member_name', width : 200, sortable : true, align: 'left'},
            ],
        
        searchitems : [
            {display: '会员ID', name : 'member_id'},
            {display: '会员名称', name : 'member_name'}
            ],
        buttons : [
               {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出Excel文件', onpress : fg_operation }
           ],
        sortname: "member_id",
        sortorder: "desc",
        title: '商城会员列表'
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_frozen&id=' + id;
}
</script> 

