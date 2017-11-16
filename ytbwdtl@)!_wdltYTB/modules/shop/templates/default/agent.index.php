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
    <ul>
      <li><?php echo $lang['member_index_help1'];?></li>
      <li><?php echo $lang['member_index_help2'];?></li>
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
<!--           <dl>
            <dt>收款人姓名</dt>
            <dd>
              <label>
                <input type="text" value="" name="user_name" id="user_name" class="s-input-txt" placeholder="输入姓名全称或关键字">
              </label>
            </dd>
          </dl> -->
<!--           <dl>
            <dt>时期筛选</dt>
            <dd>
              <label>
                <input type="text" name="stime" data-dp="1" class="s-input-txt" placeholder="请选择开始时间" />
              </label>
              <label>
                <input type="text" name="etime" data-dp="1" class="s-input-txt" placeholder="请选择结束时间"  />
              </label>
            </dd>
          </dl> -->
         <!--  <dl>
            <dt>支付状态</dt>
            <dd>
              <label>
                <select name="pdc_payment_state" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="0">未支付</option>
                  <option value="1">已支付</option>
                </select>
              </label>
            </dd>
          </dl> -->
          <dl>
            <dt>代理级别</dt>
            <dd>
              <label>
                <select name="predeposit_type" class="s-select">
                  <option value="2">端口代理</option>
                  <option value="3">区县代理</option>
                  <option value="4">市级代理</option>
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
        $("#flexigrid").flexOptions({url: 'index.php?act=agent&op=agent_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=agent&op=agent_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 160, sortable : false, align: 'center', className: 'handle-s'},
            {display: '会员ID', name : 'member_id', width : 100, sortable : true, align: 'center'},
            {display: '会员名称', name : 'member_name', width : 200, sortable : true, align: 'left'},
            {display: '会员手机', name : 'member_mobile', width : 100, sortable : true, align: 'center'},
            ],
        
        searchitems : [
            {display: '会员ID', name : 'member_id'},
            {display: '会员名称', name : 'member_name'}
            ],
        sortname: "member_id",
        sortorder: "desc",
        title: '商城会员列表'
    });
	
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=member&op=member_add';
    }
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

