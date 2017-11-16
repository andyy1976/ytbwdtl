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
      <li>消耗云豆数量截至时间为当前时间。</li>
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
    var flexUrl = 'index.php?act=sweepstakes&op=sweepstakes_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '抽奖活动名称', name: 'sweepstakes_name', width: 300, sortable: false, align: 'left'},
            {display: '每次消耗云豆', name: 'sweepstakes_cons', width: 80, sortable: 1, align: 'left'},
            {display: '奖项数量', name: 'praise_count', width: 80, sortable: 1, align: 'left'},
            {display: '抽奖转盘图片', name: 'sweepstakes_bgimg', width: 100, sortable: false, align: 'center'},
            {display: '抽奖开启时间', name: 'start_time', width: 150, sortable: 1, align: 'center'},
            {display: '抽奖结束时间', name: 'end_time', width: 150, sortable: 1, align: 'center'},
            {display: '总消耗云豆', name: 'points_total', width: 100, sortable: 1, align: 'center'},
            {display: '抽奖状态', name: 'sweepstakes_state', width: 100, sortable: 1, align: 'center'},
        ],
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增抽奖',
                name: 'add',
                bclass: 'add',
                title: '新增抽奖',
                onpress: function() {
                    location.href = 'index.php?act=sweepstakes&op=sweepstakes_add';
                }
            },
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }

                    var href = '<?php echo urlAdminShop('sweepstakes', 'prod_dropall', array(
                        'pg_id' => '__IDS__',
                    )); ?>'.replace('__IDS__', ids.join(','));

                    $.getJSON(href, function(d) {
                        if (d && d.result) {
                            $("#flexigrid").flexReload();
                        } else {
                            alert(d && d.message || '操作失败！');
                        }
                    });
                }
            }
        ],
        searchitems: [
            {display: '抽奖活动名称', name: 'sweepstakes_name', isdefault: true}
        ],
        sortname: "id",
        sortorder: "desc",
        title: '抽奖活动列表'
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

$('a[data-href]').live('click', function() {
    if ($(this).hasClass('confirm-del-on-click') && !confirm('确定删除?')) {
        return false;
    }

    $.getJSON($(this).attr('data-href'), function(d) {
        if (d && d.result) {
            $("#flexigrid").flexReload();
        } else {
            alert(d && d.message || '操作失败！');
        }
    });
});

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
