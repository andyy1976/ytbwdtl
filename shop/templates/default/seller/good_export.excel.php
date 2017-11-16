<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><!-- <a class="back" href="<?php echo $output['murl'];?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a> -->
      <div class="subject">
        <h3><?php echo '数据分段下载';?></h3>
        <h5>导出数据列表到本地时选择分页操作</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo '操作提示';?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>该栏目下要导出的数据内容较多，系统自动设定了数据表格分页以确保导出成功。</li>
      <li>选择对应的分页序号并点击按钮开始下载数据表格。</li>
    </ul>
  </div>
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">选择数据分页</dt>
      <dd class="opt">
        <?php foreach($output['list'] as $k=>$v){?>
        <a href="index.php?<?php echo $_SERVER['QUERY_STRING'].'&curpage='.$k;?>" class="ncap-btn mr10 mb10">下载数据分页<?php echo $k;?> (<?php echo $v;?>条)</a>
        <?php }?>
      </dd>
    </dl>
  </div>
</div>
<style type="text/css">
.tit{ padding: 8px 0; }
.item-title .subject { vertical-align: bottom; display: inline-block; *display: inline;
*zoom: 1;
min-width: 190px; height: 38px; padding: 6px 0; margin-right: 10px; }
.item-title h3 { font-size: 16px; font-weight: normal; line-height: 20px; color: #333; }
.item-title h5 { font-size: 12px; font-weight: normal; line-height: 18px; color: #999; }
.explanation { color: #2CBCA3; background-color: #EDFBF8; display: block; width: 99%; height: 100%; padding: 6px 9px; border-radius: 5px; position: relative; overflow: hidden; }
.explanation .title { white-space: nowrap; margin-bottom: 8px; position: relative; cursor: pointer; }
.explanation .title h4 { font-size: 14px; font-weight: normal; line-height: 20px; height: 20px; display: inline-block; }
.explanation .title i { font-size: 18px; vertical-align: middle; margin-right: 6px; }
.explanation .title span { background: url(../images/combine_img.png) no-repeat -580px -200px; width: 20px; height: 20px; position: absolute; z-index: 1; top: -6px; right: -9px; }
.explanation ul { color: #748A8F; margin-left: 10px; }
.explanation li { line-height: 20px; background: url(../images/macro_arrow.gif) no-repeat 0 10px; padding-left: 10px; margin-bottom: 4px; text-shadow: 1px 1px 0 rgba(255,255,255,0.5); }
a.ncap-btn { font: normal 12px/20px "microsoft yahei"; text-decoration: none; color: #777; background-color: #F5F5F5; text-align: center; vertical-align: middle; display: inline-block; height: 20px; padding: 2px 9px; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; border-radius: 3px; cursor: pointer; }
a:hover.ncap-btn { text-decoration: none; color: #333; background-color: #E6E6E6; border-color: #CFCFCF #CFCFCF #B3B3B3 #CFCFCF; }
</style>
