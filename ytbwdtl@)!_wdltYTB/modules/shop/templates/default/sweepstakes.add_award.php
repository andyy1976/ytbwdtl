<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('sweepstakes', 'index'); ?>" title="返回列表"> <i class="fa fa-arrow-circle-o-left"></i> </a>
      <div class="subject">
        <h3>抽奖活动 - 奖项设置</h3>
        <h5>抽奖活动的发布及中奖信息的管理</h5>
      </div>
    </div>
  </div>
  <form id="pointprod_form" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="sweepstakes_id" value="<?php echo $output['add_awardinfo']['id']; ?>" />
    <input type="hidden" name="praise_count" value="<?php echo $output['add_awardinfo']['praise_count']; ?>" />
    <div class="ncap-form-default">
      <div class="title">
        <h3>奖项设置(1.多角度注意区分英文半角逗号。2.全部奖项中奖概率相加必须为100)</h3>
      </div>
      <?php for ($i=0;$i < $output['add_awardinfo']['praise_count'];$i++) { ?>
      <dl class="row">
        <dt class="tit">
          <label for="<?php echo 'praise_name'.$i;?>" style="color: red;"><em>*</em>奖项名字</label>
        </dt>
        <dd class="opt">
          <input type="text" readonly="readonly" name="<?php echo 'praise_name'.$i;?>" id="<?php echo 'praise_name'.$i;?>" value="<?php echo $output['award_zh'][$i] ?>" class="input-txt"/>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>是否实物</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="showstate_1" class="cb-enable selected"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="showstate_0" class="cb-disable"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="showstate_1" name="<?php echo 'is_vr'.$i;?>" checked="checked" value="1" type="radio">
            <input id="showstate_0" name="<?php echo 'is_vr'.$i;?>" value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="<?php echo 'min_angle'.$i;?>"><em>*</em>最小角度</label>
        </dt>
        <dd class="opt">
          <input type="text" name="<?php echo 'min_angle'.$i;?>" id="<?php echo 'min_angle'.$i;?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="<?php echo 'max_angle'.$i;?>"><em>*</em>最大角度</label>
        </dt>
        <dd class="opt">
          <input type="text" name="<?php echo 'max_angle'.$i;?>" id="<?php echo 'max_angle'.$i;?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="<?php echo 'praise_number'.$i;?>"><em>*</em>奖品数量</label>
        </dt>
        <dd class="opt">
          <input type="text" name="<?php echo 'praise_number'.$i;?>" id="<?php echo 'praise_number'.$i;?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="<?php echo 'chance'.$i;?>"><em>*</em>中奖概率</label>
        </dt>
        <dd class="opt">
          <input type="text" name="<?php echo 'chance'.$i;?>" id="<?php echo 'chance'.$i;?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="<?php echo 'praise_content'.$i;?>"><em>*</em>奖项内容</label>
        </dt>
        <dd class="opt">
          <input type="text" name="<?php echo 'praise_content'.$i;?>" id="<?php echo 'praise_content'.$i;?>" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script>

//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    $("#pointprod_form").submit();
    // if($("#pointprod_form").valid()){
    //  $("#pointprod_form").submit();
    // }
	});
});

$(function(){
  $('#pointprod_form').validate({
    errorPlacement: function(error, element){
		var error_td = element.parent('dd').children('span.err');
          error_td.append(error);
      },
      rules : {
      	min_angle0 : {
            required  : true
          }
      },
      messages : {
      	min_angle0  : {
              required : '<i class="fa fa-exclamation-circle"></i>请填写中奖的最小角度'
          },
        sweepstakes_bgimg : {
              required : '<i class="fa fa-exclamation-circle"></i>请上传抽奖活动的转盘图片'
          },
        sweepstakes_cons : {
			        required : '<i class="fa fa-exclamation-circle"></i>请添加参加抽奖活动每次消耗云豆数',
              number   : '<i class="fa fa-exclamation-circle"></i>参加抽奖活动每次消耗云豆数必须为数字',
              min     : '<i class="fa fa-exclamation-circle"></i>参加抽奖活动每次消耗云豆数最少为1个'
          },
        praise_count     : {
			        required : '<i class="fa fa-exclamation-circle"></i>请添加抽奖活动奖项数量',
			        digits   : '<i class="fa fa-exclamation-circle"></i>抽奖活动奖项数量必须为正整数',
			        min		   : '<i class="fa fa-exclamation-circle"></i>抽奖活动奖项数量最少为2项'
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
