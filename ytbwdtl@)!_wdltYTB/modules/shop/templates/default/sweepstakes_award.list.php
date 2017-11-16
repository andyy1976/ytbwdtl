<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>抽奖活动</h3>
        <h5>抽奖活动的发布及中奖信息的管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">抽奖列表</a></li>
        <li><a href="index.php?act=sweepstakes&op=pointorder_list" >中奖列表</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>使用抽奖功能请先确保此抽奖活动处于开启状态，中奖后，由系统平台进行发货。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>

<!--     <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
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
              <dt>礼品名称</dt>
              <dd>
                <input type="text" name="pgoods_name" class="s-input-txt" placeholder="请输入礼品名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>上架</dt>
              <dd>
                <select name="pgoods_show" class="s-select">
                    <option value="">全部</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>推荐</dt>
              <dd>
                <select name="pgoods_commend" class="s-select">
                    <option value="">全部</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>
          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
        </div>
      </form>
    </div> -->

</div>
<script>
$(function(){
    var flexUrl = 'index.php?act=sweepstakes&op=award_listxml&pg_id=<?php echo $_GET['pg_id']?>';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '奖项名字', name: 'praise_name', width: 100, sortable: false, align: 'left'},
            {display: '奖项内容', name: 'praise_content', width: 280, sortable: false, align: 'left'},
            {display: '库存数量', name: 'praise_number', width: 80, sortable: false, align: 'left'},
            {display: '最小角度', name: 'min_angle', width: 100, sortable: false, align: 'center'},
            {display: '最大角度', name: 'max_angle', width: 100, sortable: false, align: 'center'},
            {display: '中奖概率', name: 'chance', width: 80, sortable: false, align: 'center'},
            {display: '奖品性质', name: 'is_vr', width: 100, sortable: false, align: 'center'},
            {display: '是否必填手机号', name: 'is_phone_require', width: 100, sortable: false, align: 'center'},
        ],
        sortname: "id",
        sortorder: "desc",
        title: '奖项列表'
    });
});
// $('a[data-href]').live('click', function() {
//     // if ($(this).hasClass('confirm-del-on-click') && !confirm('确定?')) {
//     //     return false;
//     // }

//     $.getJSON($(this).attr('data-href'), function(d) {
//         if (d && d.result) {
//             $("#flexigrid").flexReload();
//         } else {
//             alert(d && d.message || '操作失败！');
//         }
//     });
// });
$('a[data-ie-column]').live('click', function() {
    $.get('<?php echo urlAdminShop('sweepstakes', 'ajax'); ?>', {
        column: $(this).attr('data-ie-column'),
        value: $(this).attr('data-ie-value'),
        id: $(this).parents('tr').attr('data-id')
    }, function(d) {
        if (d != 'true') {
            alert('操作失败！');
            return false;
        }
        $("#flexigrid").flexReload();
    });
});
</script>
