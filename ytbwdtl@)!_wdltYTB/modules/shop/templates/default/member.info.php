<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=predeposit&op=error_list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
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
   <!--  <ul>
      <li>可从管理平台手动添加一名新会员，并填写相关信息。</li>
      <li>标识“*”的选项为必填项，其余为选填项。</li>
      <li>新增会员后可从会员列表中找到该条数据，并再次进行编辑操作，但该会员名称不可变。</li>
    </ul> -->
  </div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['member_array']['member_id'];?>" />
    <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_array']['member_avatar'];?>" />
    <input type="hidden" name="member_name" value="<?php echo $output['member_array']['member_name'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>会员目前账上云豆：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['member_info']['member_points']; ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员目前账上充值余额：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['member_info']['member_predeposit']; ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员目前账上云豆余额：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['member_info']['available_predeposit']; ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员总云豆余额提现：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['cash_info']['available_cash']; ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员总充值提现金额：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['cash_info']['predeposit_cash']; ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员充值总金额：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['recharge_info']; ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员充值总手续费：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo ceil($output['points_info']['counter']); ?></p>
        </dd>          
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员充值购买总云豆：</label>
        </dt>
        <dd class="opt">
          <p style="font-size:14px;color:red;font-weight:bold;"><?php echo $output['points_info']['points_amount']; ?></p>
        </dd>          
      </dl>
      
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
  $('#member_avatar').val(picname);
  $('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'?'+Math.random())
     .attr('onmouseover','toolTip("<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'?'+Math.random()+'>")');
}
$(function(){
  $('input[class="type-file-file"]').change(uploadChange);
  function uploadChange(){
    var filepath=$(this).val();
    var extStart=filepath.lastIndexOf(".");
    var ext=filepath.substring(extStart,filepath.length).toUpperCase();
    if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
      alert("file type error");
      $(this).attr('value','');
      return false;
    }
    if ($(this).val() == '') return false;
    ajaxFileUpload();
  }
  function ajaxFileUpload()
  {
    $.ajaxFileUpload
    (
      {
        url : '<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_AVATAR;?>',
        secureuri:false,
        fileElementId:'_pic',
        dataType: 'json',
        success: function (data, status)
        {
          if (data.status == 1){
            ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&filename=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/avatar_<?php echo $_GET['member_id'];?>.jpg&url='+data.url,690);
          }else{
            alert(data.msg);
          }
          $('input[class="type-file-file"]').bind('change',uploadChange);
        },
        error: function (data, status, e)
        {
          alert('上传失败');$('input[class="type-file-file"]').bind('change',uploadChange);
        }
      }
    )
  };
// 点击查看图片
  $('.nyroModal').nyroModal();
  
$("#submitBtn").click(function(){
    if($("#user_form").valid()){
     $("#user_form").submit();
  }
  });
    $('#user_form').validate({
        errorPlacement: function(error, element){
      var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            member_passwd: {
                maxlength: 20,
                minlength: 6
            },
            member_email   : {
                required : true,
                email : true,
        remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_email',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_email').val();
                        },
                        member_id : '<?php echo $output['member_array']['member_id'];?>'
                    }
                }
            }
        },
        messages : {
            member_passwd : {
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
                minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
            },
            member_email  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_null']?>',
                email   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_valid_email']?>',
        remote : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_exists']?>'
            }
        }
    });
});
</script> 
