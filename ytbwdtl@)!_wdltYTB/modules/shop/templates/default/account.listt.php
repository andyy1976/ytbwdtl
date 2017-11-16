<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_member_predepositmanage'];?></h3>
        <h5><?php echo $lang['nc_member_predepositmanage_subhead'];?></h5>
      </div>
    <ul class="tab-base nc-row">
        <li><a href="index.php?act=predeposit&op=predeposit"><?php echo $lang['admin_predeposit_rechargelist']?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_cash_list" ><?php echo $lang['admin_predeposit_cashmanage']; ?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_log_list"><?php echo $lang['nc_member_predepositlog'];?></a></li>
        <li><a href="index.php?act=predeposit&op=account_info">账户明细</a></li>
        <li><a href="index.php?act=predeposit&op=agent_count">代理团队人数</a></li>
        <li><a href="index.php?act=predeposit&op=plcld_cash_list" class="current">财务批次处理提现</a></li>
<!--         <li><a href="index.php?act=predeposit&op=distribution">分销记录</a></li>
        <li><a href="index.php?act=predeposit&op=bonus">各级分成记录</a></li> -->
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
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
           <!--<dl>
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
          <dl>-->
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
          <!-- <dl>
            <dt>类型</dt>
            <dd>
           <label>
                <select name="pdc_payment_state" class="s-select">
                  <option value=""></option>
                  <option value="0">未支付</option>
                  <option value="1">已付款</option>
                  <option value="2">财务处理中</option>
                  <option value="3">其他</option>
                </select>
              </label>         
              </dd>
          </dl>-->
           <dl>
            <dt>提现类别</dt>
            <dd>
              <label>
                <select name="predeposit_type" class="s-select">
                  <option value="2">充值提现</option>
                  <option value="3">分销提现</option>
                  <option value="1">余额提现</option>
                  <option value="4">商家提现</option>
                  <option value="5">省代余额提现</option>
                  <option value="6">代理余额提现</option>
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
    $("input[data-dp='1']").datepicker();
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=accountt_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=accountt_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    
    $("#flexigrid").flexigrid({
        url: 'index.php?act=predeposit&op=accountt_xml',
        colModel : [
		    {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle'},
            {display: '充值ID', name : 'pdr_id', width : 40, sortable : true, align: 'center', hidden:true},
            {display: '会员ID', name : 'pdr_member_id', width : 40, sortable : false, align: 'center'},
            {display: '会员名称', name : 'pdr_member_name', width : 150, sortable : false, align: 'left'},
            {display: '金额（元）', name : 'pdr_amount', width : 100, sortable : false, align: 'center'},
            {display: '时间', name : 'pdr_add_time', width : 400, sortable : false, align: 'center'},
            {display: '说明', name : 'pdr_desc', width : 300, sortable : false, align: 'center'}
            ],
        sortname: "pdr_id",
        sortorder: "desc",
        title: '预存款充值列表'
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

function fg_csv() {
      var member_id=$('#member_id').val();
      var member_name=$('#goods_name').val();
      var start_time=$("input[name='query_start_date']").val();
      var end_time =$("input[name='query_end_date']").val();
      var pdr_payment_state=$("*[name='pdr_payment_state']").val();
      window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_step2&member_id=' + member_id+'&member_name='+member_name+'&start_time='+start_time+'&end_time='+end_time+'&pdr_payment_state='+pdr_payment_state;
}
function chuli(){
   if(confirm('处理之前请核对提现类别！！！不然会引发不必要的麻烦！！确定要批量处理这些数据?处理之后不可恢复！')){
  $.ajax({
  type: 'POST',
  url: 'index.php?act=predeposit&op=pd_clsuoyou',
  data: {mobile_field:$("#allid").val()},
  dataType: "json",
  success:function (result){
   if(result==1){
	   alert('处理完毕，请重新加载此页面！');
	  $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=accountt_xml'}).flexReload();
	   }else{
		alert('处理失败！');
		   }
    }
});
}
	}
</script>  