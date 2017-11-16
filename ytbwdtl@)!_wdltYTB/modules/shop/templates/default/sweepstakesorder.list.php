<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>云豆抽奖</h3>
        <h5>平台会员中奖信息管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=sweepstakes&op=sweepstakes" ><span>抽奖列表</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>中奖列表</span></a></li>
      </ul>
    </div>
  </div>

<div id="flexigrid"></div>

<div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <input type="hidden" name="advanced" value="1" />
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>活动名称</dt>
            <dd>
              <input type="text" name="sweepstakes_name" class="s-input-txt" placeholder="请输入活动名称" />
            </dd>
          </dl>
          <dl>
            <dt>中奖ID</dt>
            <dd>
              <input type="text" name="id" class="s-input-txt" placeholder="请输入中奖id" />
            </dd>
          </dl>
          <dl>
            <dt>会员ID</dt>
            <dd>
              <input type="text" name="member_id" class="s-input-txt" placeholder="请输入会员id" />
            </dd>
          </dl>
          <dl>
            <dt>抽奖时间</dt>
            <dd>
              <label>
                <input readonly id="query_start_date" placeholder="请选择起始时间" name="query_start_date" value="" type="text" class="s-input-txt" />
              </label>
              <label>
                <input readonly id="query_end_date" placeholder="请选择结束时间" name="query_end_date" value="" type="text" class="s-input-txt" />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>订单状态</dt>
            <dd>
              <select name="order_state" class="s-select">
                  <option value="">-请选择-</option>
                  <?php foreach ((array) $output['states'] as $k => $v) { ?>
                  <option value="<?php echo $v[0]; ?>" ><?php echo $v[1]; ?></option>
                  <?php } ?>
              </select>
            </dd>
          </dl>
          <dl>
            <div class="tDiv2">
              <div class="fbutton">
                <div class="csv" title="导出Excel文件">
                  <span><i class="fa fa-file-excel-o"></i><a href="javascript:void(0);" id='export'>导出数据(已取消=未中奖)</a></span>
                </div>
              </div>
            </div>
          </dl>
        </div>
      </div>
      <div class="bottom">
        <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
        <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
      </div>
    </form>
  </div>
</div>

<script>
$(function() {

    //20171021潘丙福添加开始--增加导出功能
    $('#export').click(function(){
        var sweepstakes_name=$("input[name='sweepstakes_name']").val();
        var id=$("input[name='id']").val();
        var member_id=$("input[name='member_id']").val();
        var query_start_date=$("input[name='query_start_date']").val();
        var query_end_date=$("input[name='query_end_date']").val();
        var order_state=$("select[name='order_state']").val();
        
        window.open("index.php?act=sweepstakes&op=export_csv&sweepstakes_name="+sweepstakes_name+"&id="+id+"&member_id="+member_id+"&query_start_date="+query_start_date+"&query_end_date="+query_end_date+"&order_state="+order_state,"_blank");
    });
    //20171021潘丙福添加结束

    $('#query_start_date').datepicker();
    $('#query_end_date').datepicker();
    var flexUrl = 'index.php?act=sweepstakes&op=sweepstakesorder_list_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '中奖单号', name: 'id', width: 100, sortable: false, align: 'left'},
            {display: '会员ID', name: 'member_id', width: 100, sortable: false, align: 'left'},
            {display: '抽奖时间', name: 'add_time', width: 100, sortable: false, align: 'center'},
            {display: '奖品信息', name: 'award_content', width: 150, sortable: false, align: 'center'},
            {display: '活动名称', name: 'sweepstakes_name', width: 120, sortable: false, align: 'center'},
            {display: '订单类型', name: 'order_type', width: 100, sortable: false, align: 'center'},
            {display: '买家留言', name: 'message', width: 200, sortable: false, align: 'center'},
            {display: '手机号码', name: 'phone_require', width: 100, sortable: false, align: 'center'},
            {display: '订单状态', name: 'order_state', width: 80, sortable: false, align: 'center'},
            {display: '收货人信息', name: 'order_state', width: 400, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '中奖单号', name: 'id', isdefault: true},
            {display: '会员ID', name: 'member_id'}
        ],
        sortname: "id",
        sortorder: "desc",
        title: '中奖信息列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl + '&' + $("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl}).flexReload();
        $("#formSearch")[0].reset();
    });

});

$('a.confirm-on-click').live('click', function() {
    return confirm('确定"'+this.innerHTML+'"?');
});
</script>
