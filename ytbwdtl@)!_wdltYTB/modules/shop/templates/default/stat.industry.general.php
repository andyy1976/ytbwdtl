<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_statindustry'];?></h3>
        <h5>平台根据商品分类对行业进行各项分析</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['stat_validorder_explain'];?></li>
      <!-- <li>列表展示了搜索类目下子分类的商品数和从昨天开始最近30天该子分类有效订单的销售数据，并可以点击列表上方的“导出数据”将列表数据导出为Excel文件</li> -->
      <li>列表展示了搜索类目下子分类的商品数和该子分类有效订单的销售数据，并可以点击列表上方的“导出数据”将列表数据导出为Excel文件</li>
      <li>默认按照“销售额”降序排列</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <!-- 20170808潘丙福添加 -->
      <input type="hidden" name="act" value="stat_industry" />
      <input type="hidden" name="op" value="general" />
      <!-- 20170808潘丙福结束 -->
      <input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按商品分类筛选</dt>
            <dd id="searchgc_td"> </dd>
          </dl>
<!-- 20170808潘丙福添加开始时间搜索字段 -->
          <dl>
            <dt>按时间周期筛选</dt>
            <dd>
              <label>
                <select name="search_type" id="search_type" class="class-select">
                  <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按照天统计</option>
                  <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按照周统计</option>
                  <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按照月统计</option>
                </select>
              </label>
            </dd>
            <dd id="searchtype_day" style="display:none;">
              <label>
                <input class="s-input-txt" type="text" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>" id="search_time" name="search_time">
              </label>
            </dd>
            <dd id="searchtype_week" style="display:none;">
              <label>
                <select name="searchweek_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_week" class="s-select">
                  <?php foreach ($output['week_arr'] as $k => $v){?>
                  <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                  <?php } ?>
                </select>
              </label>
            </dd>
            <dd id="searchtype_month" style="display:none;">
              <label>
                <select name="searchmonth_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchmonth_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
            </dd>
          </dl>
<!-- 20170808潘丙福添加结束 -->
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a> </div>
    </form>
  </div>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
  <script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
</div>
<script>
function update_flex(){
    var choose_gcid = $("#choose_gcid").val();
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_industry&op=get_general_xml&choose_gcid='+choose_gcid+'&t='+<?php echo $output['searchtime_json'];?>,
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '类目名称', name : 'gc_name', width : 150, sortable : false, align: 'center'},
            {display: '平均价格（元）', name : 'priceavg', title : '类目下所有商品的平均单价', width : 120, sortable : true, align: 'center'},
            {display: '有销量商品数', name : 'ordergcount', title : '有销量商品数', width : 120, sortable : true, align: 'center'},
            {display: '销量', name : 'ordergnum', title : '有效订单中商品总售出件数', width : 120, sortable : true, align: 'center'},
            {display: '销售额（元）', name : 'orderamount', title : '类目下有效订单中商品总销售额', width : 120, sortable : true, align: 'center'},
            {display: '商品总数', name : 'goodscount', title : '类目下所有商品的数量', width: 120, sortable : true, align : 'center'},
            {display: '无销量商品数', name : 'unordergcount', title : '类目下无销量的商品总数', width : 120, sortable : true, align : 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "orderamount",
        sortorder: "desc",
        usepager: false,
        rp: 99,
        title: '概况总览'
    });
}
//展示搜索时间框
function show_searchtime(){
  s_type = $("#search_type").val();
  $("[id^='searchtype_']").hide();
  $("#searchtype_"+s_type).show();
}
$(function () {
  $('#ncsubmit').click(function(){
   //  $('.flexigrid').after('<div id="flexigrid"></div>').remove();
    // update_flex();
    $("input[name='act']").attr("disabled",false);
    $("input[name='op']").attr("disabled",false); 
    $('#formSearch').submit(); 
  });

  //统计数据类型
  var s_type = $("#search_type").val();
  $('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

  show_searchtime();

  $("#search_type").change(function(){
    show_searchtime();
  });

  //更新周数组
  $("[name='searchweek_month']").change(function(){
    var year = $("[name='searchweek_year']").val();
    var month = $("[name='searchweek_month']").val();
    $("[name='searchweek_week']").html('');
    $.getJSON('<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=getweekofmonth',{y:year,m:month},function(data){
          if(data != null){
            for(var i = 0; i < data.length; i++) {
              $("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
          }
          }
      });
  });

  $('#searchBarOpen').click();
  //商品分类
  init_gcselect(<?php echo $output['gc_choose_json'];?>,<?php echo $output['gc_json']?>);

  //加载统计列表
  update_flex();
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_industry&op=general_list&t='+<?php echo $output['searchtime_json'];?>;
    $("input[name='act']").attr("disabled",true);
    $("input[name='op']").attr("disabled",true);
    get_search_excel(stat_url,bDiv);
}
</script>