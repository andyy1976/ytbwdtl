<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('sweepstakes', 'index'); ?>" title="返回列表"> <i class="fa fa-arrow-circle-o-left"></i> </a>
      <div class="subject">
        <h3>抽奖活动 - 新增抽奖</h3>
        <h5>抽奖活动的发布及中奖信息的管理</h5>
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
          <input type="text" name="sweepstakes_name" id="sweepstakes_name" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sweepstakes_cons"><em>*</em>消耗云豆数/次</label>
        </dt>
        <dd class="opt">
          <input type="text" name="sweepstakes_cons" id="sweepstakes_cons" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="praise_count"><em>*</em>设置奖项数量</label>
        </dt>
        <dd class="opt">
          <input type="text" name="praise_count" id="praise_count" class="input-txt"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for=""><em>*</em>抽奖转盘图片</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input name="sweepstakes_bgimg" type="file" class="type-file-file" id="sweepstakes_bgimg" size="30" hidefocus="true" nc_type="change_goods_image">
            </span></div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>抽奖开始时间</label>
        </dt>
        <dd class="opt">
          <input type="text" name="start_time" id="start_time" class="input-txt" style="width:100px;" value="<?php echo @date('Y-m-d',time()); ?>"/>
          <?php echo $lang['admin_pointprod_time_day']; ?>
          <select id="starthour" name="starthour" style="margin-left: 8px; _margin-left: 4px; width:50px;">
            <?php foreach ($output['hourarr'] as $item){ ?>
            <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
            <?php }?>
          </select>
          <?php echo $lang['admin_pointprod_time_hour']; ?>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>抽奖结束时间</label>
        </dt>
        <dd class="opt">
          <input type="text" name="end_time" id="end_time" class="input-txt" style="width:100px;" value="<?php echo @date('Y-m-d',time()); ?>" />
          <?php echo $lang['admin_pointprod_time_day']; ?>
          <select id="endhour" name="endhour"  style="margin-left: 8px; _margin-left: 4px; width:50px;">
            <?php foreach ($output['hourarr'] as $item){ ?>
            <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
            <?php }?>
          </select>
          <?php echo $lang['admin_pointprod_time_hour']; ?>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['admin_pointprod_stateinfo']; ?></h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label>是否开启</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="showstate_1" class="cb-enable selected"><span><?php echo $lang['admin_pointprod_yes']; ?></span></label>
            <label for="showstate_0" class="cb-disable"><span><?php echo $lang['admin_pointprod_no']; ?></span></label>
            <input id="showstate_1" name="sweepstakes_state" checked="checked" value="1" type="radio">
            <input id="showstate_0" name="sweepstakes_state" value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script>
// 模拟上传input type='file'样式
$(function(){
  var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传' class='type-file-button' />"
	$(textButton).insertBefore("#sweepstakes_bgimg");
	$("#sweepstakes_bgimg").change(function(){
    $("#textfield1").val($("#sweepstakes_bgimg").val());
	});
});

//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#pointprod_form").valid()){
     $("#pointprod_form").submit();
	}
	});
});

$(function(){
	// $('input[nc_type="change_goods_image"]').change(function(){
	// 	var src = getFullPath($(this)[0]);
	// 	$('img[nc_type="goods_image"]').attr('src', src);
	// 	$('input[nc_type="change_goods_image"]').removeAttr('name');
	// 	$(this).attr('name', 'goods_image');
	// });

	$('#start_time').datepicker({dateFormat: 'yy-mm-dd'});
	$('#end_time').datepicker({dateFormat: 'yy-mm-dd'});

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
				      min		    :2
            },
          sweepstakes_bgimg : {
              required  : true
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

    // 替换图片
    // $('#fileupload').each(function(){
    //     $(this).fileupload({
    //         dataType: 'json',
    //         url: 'index.php?act=pointprod&op=pointprod_pic_upload',
    //         done: function (e,data) {
    //             if(data != 'error'){
    //             	add_uploadedfile(data.result);
    //             }
    //         }
    //     });
    // });
});
// function add_uploadedfile(file_data)
// {
//     var newImg = '<li id="' + file_data.file_id + '"><input type="hidden" name="file_id[]" value="' + file_data.file_id + '" /><div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_POINTPROD.'/';?>' + file_data.file_name + '" alt="' + file_data.file_name + '"/></a></div><a href="javascript:del_file_upload(' + file_data.file_id + ');" class="del" title="<?php echo $lang['nc_del'];?>">X</a><a href="javascript:insert_editor(\'<?php echo UPLOAD_SITE_URL.'/'.ATTACH_POINTPROD.'/';?>' + file_data.file_name + '\');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a></li>';
//     $('#thumbnails > ul').prepend(newImg);
// }
// function insert_editor(file_path){
// 	KE.appendHtml('pgoods_body', '<img src="'+ file_path + '" alt="'+ file_path + '">');
// }
// function del_file_upload(file_id)
// {
//     if(!window.confirm('<?php echo $lang['nc_ensure_del'];?>')){
//         return;
//     }
//     $.getJSON('index.php?act=pointprod&op=ajaxdelupload&file_id=' + file_id, function(result){
//         if(result){
//             $('#' + file_id).remove();
//         }else{
//             alert('<?php echo $lang['admin_pointprod_delfail'];?>');
//         }
//     });
// }
</script>
