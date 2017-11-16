<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=store_industry" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo '添加行业列表';?></h3>
        <h5><?php echo $lang['store_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="store_class_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="sc_name"><em>*</em><?php echo '品类名称';?></label>
        </dt>
        <dd class="opt">
        <select name="type_id" id="type_id">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php if(!empty($output['type_list']) && is_array($output['type_list'])){ ?>
            <?php foreach($output['type_list'] as $key => $val){ ?>
            <option value="<?php echo $val['hy_id'];?>"><?php echo $val['hymc_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sc_name"><em>*</em><?php echo '行业细分名称';?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="sc_bail" id="sc_bail" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
   
     $("#store_class_form").submit();
	
	});
});


</script>