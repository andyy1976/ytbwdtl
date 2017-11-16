<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('sweepstakes', 'award_list', array('pg_id'=>$output['award_info']['sweepstakes_id'])); ?>" title="返回列表"> <i class="fa fa-arrow-circle-o-left"></i> </a>
      <div class="subject">
        <h3>抽奖活动 - 奖项设置</h3>
        <h5>抽奖活动的发布及中奖信息的管理</h5>
      </div>
    </div>
  </div>
  <form id="pointprod_form" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="award_id" value="<?php echo $output['award_info']['id']; ?>" />
    <input type="hidden" name="sweepstakes_id" value="<?php echo $output['award_info']['sweepstakes_id']; ?>" />
    <div class="ncap-form-default">
      <div class="title">
        <h3>奖项修改(1.多角度注意区分英文半角逗号)</h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label for="praise_name" style="color: red;"><em>*</em>奖项名字</label>
        </dt>
        <dd class="opt">
          <input type="text" readonly="readonly" name="praise_name" id="praise_name" value="<?php echo $output['award_info']['praise_name'] ?>" class="input-txt"/>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>是否实物</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="showstate_1" class="cb-enable <?php if ($output['award_info']['is_vr'] == '1'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="showstate_0" class="cb-disable <?php if ($output['award_info']['is_vr'] == '0'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="showstate_1" name="is_vr" <?php if ($output['award_info']['is_vr'] == '1'){echo 'checked=checked';}?> value="1" type="radio">
            <input id="showstate_0" name="is_vr" <?php if ($output['award_info']['is_vr'] == '0'){echo 'checked=checked';}?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>是否必填手机号</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="phone_require_1" class="cb-enable <?php if ($output['award_info']['is_phone_require'] == '1'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="phone_require_0" class="cb-disable <?php if ($output['award_info']['is_phone_require'] == '0'){ ?>selected<?php } ?>"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="phone_require_1" name="is_phone_require" <?php if ($output['award_info']['is_phone_require'] == '1'){echo 'checked=checked';}?> value="1" type="radio">
            <input id="phone_require_0" name="is_phone_require" <?php if ($output['award_info']['is_phone_require'] == '0'){echo 'checked=checked';}?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="min_angle"><em>*</em>最小角度</label>
        </dt>
        <dd class="opt">
          <input type="text" name="min_angle" id="min_angle" value="<?php echo $output['award_info']['min_angle'];?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="max_angle"><em>*</em>最大角度</label>
        </dt>
        <dd class="opt">
          <input type="text" name="max_angle" id="max_angle" value="<?php echo $output['award_info']['max_angle'];?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="praise_number"><em>*</em>奖品数量</label>
        </dt>
        <dd class="opt">
          <input type="text" name="praise_number" id="praise_number" value="<?php echo $output['award_info']['praise_number'];?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="chance"><em>*</em>中奖概率</label>
        </dt>
        <dd class="opt">
          <input type="text" name="chance" id="chance" value="<?php echo $output['award_info']['chance'];?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="praise_content"><em>*</em>奖项内容</label>
        </dt>
        <dd class="opt">
          <input type="text" name="praise_content" id="praise_content" value="<?php echo $output['award_info']['praise_content'];?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script>

//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#pointprod_form").valid()){
      $("#pointprod_form").submit();
    }
	});
});

$(function(){
  $('#pointprod_form').validate({
    errorPlacement: function(error, element){
		var error_td = element.parent('dd').children('span.err');
          error_td.append(error);
      },
      rules : {
      	min_angle : {
            required  : true
          },
        max_angle : {
            required  : true
          },
        praise_number : {
            required  : true,
            digits    : true,
            min       : 1
        },
        chance : {
            required  : true,
            number    : true,
            min       : 0,
            max       : 100
        },
        praise_content : {
            required  : true
        }  
      },
      messages : {
      	min_angle  : {
              required : '<i class="fa fa-exclamation-circle"></i>请填写中奖的最小角度'
          },
        max_angle : {
              required : '<i class="fa fa-exclamation-circle"></i>请填写中奖的最大角度'
          },
        praise_number : {
			        required : '<i class="fa fa-exclamation-circle"></i>请填写奖品数量',
              digits   : '<i class="fa fa-exclamation-circle"></i>奖品数量必须为正整数',
              min     : '<i class="fa fa-exclamation-circle"></i>奖品数量最少为1个'
          },
        chance     : {
			        required : '<i class="fa fa-exclamation-circle"></i>请填写中奖概率',
			        number   : '<i class="fa fa-exclamation-circle"></i>中奖概率必须为数字',
			        min		   : '<i class="fa fa-exclamation-circle"></i>中奖概率填写不正确',
              max      : '<i class="fa fa-exclamation-circle"></i>中奖概率填写不正确'
          },
        praise_content  : {
              required : '<i class="fa fa-exclamation-circle"></i>请填写奖项内容'
          },  
      }
  });
});
</script>
