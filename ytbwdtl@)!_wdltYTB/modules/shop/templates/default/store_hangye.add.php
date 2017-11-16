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
          <input type="text" value="" name="sc_name" id="sc_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sc_name"><em>*</em><?php echo '行业点数';?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="sc_bail" id="sc_bail" class="input-txt"  onkeyup="value=value.replace(/[^\d\.]/g,'')" onblur="value=value.replace(/[^\d\.]/g,'')">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
        <dl class="row">
        <dt class="tit">
          <label for="sc_name"><em>*</em><?php echo '细分品类';?></label>
        </dt>
        <dd class="opt">
          <textarea name="hy_littlename" rows="10" class="input-txt" id="hy_littlename"></textarea>
          （注意按原来格式填写，即填写一个品类名称的前面用/分割,譬如添加‘理发’,即基本格式为“/理发”，第一个不要加/ ）
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
     var sc_name=$("#sc_name").val();
     var sc_bail=$("#sc_bail").val();
	 if(sc_name==''||sc_bail==''){
		 alert('必填参数没有填写！');
		 }else{
          $("#store_class_form").submit();
		 }

	});
});


</script>