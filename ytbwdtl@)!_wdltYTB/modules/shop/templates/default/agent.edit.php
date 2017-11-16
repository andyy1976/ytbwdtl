<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=agent&op=index" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?> - <?php echo $lang['nc_edit']?>会员“<?php echo $output['member_array']['member_name'];?>”</h3>
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
      <li>可从管理平台手动添加一名新会员，并填写相关信息。</li>
      <li>标识“*”的选项为必填项，其余为选填项。</li>
      <li>新增会员后可从会员列表中找到该条数据，并再次进行编辑操作，但该会员名称不可变。</li>
    </ul>
  </div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['member_array']['member_id'];?>" />
    <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_array']['member_avatar'];?>" />
    <input type="hidden" name="member_name" value="<?php echo $output['member_array']['member_name'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['member_index_name']?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['member_array']['member_name'];?>" readonly />
          <p class="notic">会员用户名不可修改。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="frozen_agentotal">应收取总金额</label>
        </dt>
        <dd class="opt">
          <input type="text" id="frozen_agentotal" name="frozen_agentotal" value='<?php echo $output['member_array']['frozen_agentotal'];?>' class="input-txt">
          <span class="err"></span>
         
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="frozen_agentotal">已收取金额</label>
        </dt>
        <dd class="opt">
          <input type="text" id="amount_collect" name="amount_collect" value='' class="input-txt">
          <span class="err"></span>
         
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name"> 收取时间</label>
        </dt>
        <dd class="opt">
          <input id="collect_time" class="input-txt" name="collect_time" value="" type="text" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="frozen_agentotal">备注</label>
        </dt>
        <dd class="opt">
          <!-- <input type="text" id="frozen_agentotal" name="frozen_agentotal" value='' class="input-txt"> -->
          <!-- <span class="err"></span> -->
          <textarea name='desc_collect'></textarea>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="frozen_agentotal">已扣除金额</label>
        </dt>
        <dd class="opt">
          <input type="text" id="amount_buckle" name="amount_buckle" value='' class="input-txt">
          <span class="err"></span>
         
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name"> 扣除时间</label>
        </dt>
        <dd class="opt">
          <input id="buckle_time" class="input-txt" name="buckle_time" value="" type="text" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="frozen_agentotal">备注</label>
        </dt>
        <dd class="opt">
          <!-- <input type="text" id="frozen_agentotal" name="frozen_agentotal" value='<?php echo $output['member_array']['frozen_agentotal'];?>' class="input-txt"> -->
          <!-- <span class="err"></span> -->
          <textarea name='desc_buckle'></textarea>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">

$(function(){  
$('#collect_time').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
$('#buckle_time').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
$("#submitBtn").click(function(){
    if($("#user_form").valid()){
     $("#user_form").submit();
  }
  });
    
});
</script> 
