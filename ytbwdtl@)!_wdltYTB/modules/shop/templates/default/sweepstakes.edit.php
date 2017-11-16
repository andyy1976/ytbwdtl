<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"> <a class="back" href="<?php echo urlAdminShop('sweepstakes', 'index'); ?>" title="返回列表"> <i class="fa fa-arrow-circle-o-left"></i> </a>
      <div class="subject">
        <h3>抽奖活动 - 编辑抽奖活动“<?php echo $output['prod_info']['sweepstakes_name']; ?>”</h3>
        <h5>编辑商城会员可参与的抽奖活动</h5>
      </div>
    </div>
  </div>
  <form id="pointprod_form" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <div class="title">
        <h3>抽奖基本信息</h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label for="sweepstakes_name"><em>*</em>抽奖活动名称</label>
        </dt>
        <dd class="opt">
          <input type="text" name="sweepstakes_name" id="sweepstakes_name" class="input-txt" value="<?php echo $output['prod_info']['sweepstakes_name']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sweepstakes_cons"><em>*</em>消耗云豆数/次</label>
        </dt>
        <dd class="opt">
          <input type="text" name="sweepstakes_cons" id="sweepstakes_cons" class="input-txt" value="<?php echo $output['prod_info']['sweepstakes_cons']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="praise_count"><em>*</em>设置奖项数量</label>
        </dt>
        <dd class="opt">
          <input type="text" name="praise_count" id="praise_count" class="input-txt" value="<?php echo $output['prod_info']['praise_count']; ?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for=""><em>*</em>抽奖转盘图片</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo $output['prod_info']['sweepstakes_bgimg'];?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['prod_info']['sweepstakes_bgimg'];?>>')" onMouseOut="toolTip()" onerror="this.src='<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_goods_image');?>'"nc_type="goods_image" /></i></a></span><span class="type-file-box">
            <input name="sweepstakes_bgimg" type="file" class="type-file-file" id="sweepstakes_bgimg" size="30" hidefocus="true" nc_type="change_goods_image">
            </span></div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label><em>*</em>抽奖开始时间</label></dt>
        <dd class="opt">
          <input type="text" name="start_time" id="start_time" class="input-txt" style="width:100px;" value="<?php echo $output['prod_info']['start_time']? @date('Y-m-d',$output['prod_info']['start_time']):@date('Y-m-d',time());?>"/>
          <?php echo $lang['admin_pointprod_time_day']; ?>
          <select id="starthour" name="starthour" style="width:50px; margin-left: 8px; _margin-left: 4px;">
            <?php foreach ($output['hourarr'] as $item){ ?>
            <option value="<?php echo $item; ?>" <?php if ($item == @date('H',$output['prod_info']['start_time'])){ echo 'selected'; } ?>><?php echo $item; ?></option>
            <?php }?>
          </select>
          <?php echo $lang['admin_pointprod_time_hour']; ?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>抽奖结束时间</label>
        </dt>
        <dd class="opt">
          <input type="text" name="end_time" id="end_time" class="input-txt" style="width:100px;" value="<?php echo $output['prod_info']['end_time']? @date('Y-m-d',$output['prod_info']['end_time']):@date('Y-m-d',time()); ?>"/>
          <?php echo $lang['admin_pointprod_time_day']; ?>
          <select id="endhour" name="endhour" style="width:50px; margin-left: 8px; _margin-left: 4px;">
            <?php foreach ($output['hourarr'] as $item){ ?>
            <option value="<?php echo $item; ?>" <?php if ($item == @date('H',$output['prod_info']['end_time'])){ echo 'selected'; } ?>><?php echo $item; ?></option>
            <?php }?>
          </select>
          <?php echo $lang['admin_pointprod_time_hour']; ?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_stateinfo'];?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
           <label>是否开启</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="showstate_1" class="cb-enable <?php if ($output['prod_info']['sweepstakes_state'] == '1'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="showstate_0" class="cb-disable <?php if ($output['prod_info']['sweepstakes_state'] == '0'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="showstate_1" name="sweepstakes_state" <?php if ($output['prod_info']['sweepstakes_state'] == '1'){echo 'checked=checked';}?> value="1" type="radio">
            <input id="showstate_0" name="sweepstakes_state" <?php if ($output['prod_info']['sweepstakes_state'] == '0'){echo 'checked=checked';}?> value="0" type="radio">
          </div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>
$(function(){
  // 点击查看图片
	$('.nyroModal').nyroModal();
	// 模拟上传input type='file'样式
  $(function(){
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传' class='type-file-button' />"
    $(textButton).insertBefore("#sweepstakes_bgimg");
    $("#sweepstakes_bgimg").change(function(){
      $("#textfield1").val($("#sweepstakes_bgimg").val());
    });
  });

	// $('input[nc_type="change_goods_image"]').change(function(){
	// 	var src = getFullPath($(this)[0]);
	// 	$('img[nc_type="goods_image"]').attr('src', src);
	// 	$('input[nc_type="change_goods_image"]').removeAttr('name');
	// 	$(this).attr('name', 'goods_image');
	// });


	$('#start_time').datepicker({dateFormat: 'yy-mm-dd'});
	$('#end_time').datepicker({dateFormat: 'yy-mm-dd'});
	//按钮先执行验证再提交表单
  $(function(){$("#submitBtn").click(function(){
      if($("#pointprod_form").valid()){
        // alert(111);
        $("#pointprod_form").submit();
      }
  	})
  });

  $('#pointprod_form').validate({
      errorPlacement: function(error, element){
        var error_td = element.parent('dd').children('span.err');
        error_td.append(error);
      },
      rules : {
        sweepstakes_name : {
            required  : true
          },
        sweepstakes_cons : {
            required  : true,
            number    : true,
            min       : 1
          },
        praise_count : {
            required  : true,
            digits    : true,
            min       :2
          },
        start_time  : {
            required  : true,
            date      : false
          },
        end_time    : {
            required  : true,
            date      : false
          }
      },
      messages : {
        sweepstakes_name  : {
              required : '<i class="fa fa-exclamation-circle"></i>请添加抽奖活动名称'
          },
        sweepstakes_cons : {
              required : '<i class="fa fa-exclamation-circle"></i>请添加参加抽奖活动每次消耗云豆数',
              number   : '<i class="fa fa-exclamation-circle"></i>参加抽奖活动每次消耗云豆数必须为数字',
              min     : '<i class="fa fa-exclamation-circle"></i>参加抽奖活动每次消耗云豆数最少为1个'
          },
        praise_count     : {
              required : '<i class="fa fa-exclamation-circle"></i>请添加抽奖活动奖项数量',
              digits   : '<i class="fa fa-exclamation-circle"></i>抽奖活动奖项数量必须为正整数',
              min      : '<i class="fa fa-exclamation-circle"></i>抽奖活动奖项数量最少为2项'
          },
        start_time  : {
              required : '<i class="fa fa-exclamation-circle"></i>请填写抽奖活动开始时间'
          },
        end_time  : {
              required : '<i class="fa fa-exclamation-circle"></i>请填写抽奖活动结束时间'
          }
      }
  });
});
</script> 
