<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺审核商品统计</h3>
        <h5>店铺审核商品统计以及具体审核商品详情</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>统计时间：默认为店铺成立时间。</li>
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
              <dt>店铺名称</dt>
              <dd>
                <input type="text" value="" name="store_name" id="store_name" class="s-input-txt">
              </dd>
            </dl>
            <dl>
              <dt>筛选时间</dt>
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
              <dt>店铺类型</dt>
              <dd>
                <select name="is_own_shop" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="1">自营店铺</option>
                  <option value="0">入住店铺</option>
                </select>
              </dd>
            </dl>
            <dl>
            <div class="tDiv2">
              <div class="fbutton">
                <div class="csv" title="导出Excel文件">
                  <span><i class="fa fa-file-excel-o"></i><a href="javascript:void(0);" id='export'>导出数据</a></span>
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
<script type="text/javascript">
$(function(){
    //20171025潘丙福添加开始
    $('#query_start_date').datepicker();
    $('#query_end_date').datepicker();
    $('#export').click(function(){
      var store_name=$("input[name='store_name']").val();
      var query_start_date=$("input[name='query_start_date']").val();
      var query_end_date=$("input[name='query_end_date']").val();
      var is_own_shop=$("select[name='is_own_shop']").val();
      
      window.open("index.php?act=store_checkgoods&op=export_csv&store_name="+store_name+"&query_start_date="+query_start_date+"&query_end_date="+query_end_date+"&is_own_shop="+is_own_shop,"_blank");
    });
    //20171025潘丙福添加结束

    $("#flexigrid").flexigrid({
        url: 'index.php?act=store_checkgoods&op=get_xml',
        colModel : [
            {display: '店铺ID', name : 'store_id', width : 80, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'left'},
            {display: '店铺类型', name : 'is_own_shop', width : 120, sortable : false, align : 'left'},
            {display: '审核商品数量', name : 'totalnum', width : 120, sortable : false, align : 'left'}
        ],
        sortname: "totalnum",
        sortorder: "desc",
        title: '店铺审核商品统计列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=store_checkgoods&op=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=store_checkgoods&op=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
}
</script>