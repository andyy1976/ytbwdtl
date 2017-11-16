<?php defined('In33hao') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!-- 公司信息 -->

<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。</div>
     <?php if($output['flag']==1){ ?>
     <form id="form_company_info" action="index.php?act=store_joininc&op=stepdmstep2" method="post" enctype="multipart/form-data" >
     <?php }else{ ?>
  <form id="form_company_info" action="index.php?act=store_joininc&op=step2" method="post" enctype="multipart/form-data" >
  <?php } ?>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="2">店铺及联系人信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>店铺名称：</th>
          <td>
            <?php if($_SESSION['member_level'] == '5'){?>
            <input name="company_name" type="text" class="w200" value="<?php echo $output['province'];?>旗舰店" readonly="readonly"/>
            <?php }else{?>
            <input name="company_name" type="text" class="w200" value="<?php echo  $output['dm']['company_name']; ?>"/>
            <?php } ?>
            <span></span>
            </td>
        </tr>
        <tr>
          <th><i>*</i>所在地：</th>
          <td><input id="company_address" name="company_address" type="hidden"  value="<?php echo  $output['dm']['company_address']; ?>" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>详细地址：</th>
          <td><input name="company_address_detail" type="text" class="w200" value="<?php echo  $output['dm']['company_address_detail']; ?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人姓名：</th>
          <td><input name="contacts_name" type="text" class="w100" value="<?php echo  $output['dm']['contacts_name']; ?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人电话：</th>
          <td><input name="contacts_phone" type="text" class="w100" value="<?php echo  $output['dm']['contacts_phone']; ?>"/>
            <span></span></td>
        </tr>
        <?php if($output['flag']==1){ ?>
         <input  name="member_id" type="hidden" value="<?php echo $output['dm']['member_id']; ?>" />
        <input type="hidden" name="store_flag" value="2"   /></td>
        
        <?php }else{ ?>
         <input type="hidden" name="store_flag" value="0"   />
        <?php } ?>
        <tr>
          <th><i>*</i>电子邮箱：</th>
          <td><input name="contacts_email" type="text" class="w200" value="<?php echo  $output['dm']['contacts_email']; ?>"+/>
            <span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">身份证信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>姓名：</th>
          <td><input name="business_sphere" type="text" class="w100" value="<?php echo  $output['dm']['member_name']; ?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>身份证号：</th>
          <td><input name="business_licence_number" type="text" class="w200" value="<?php echo  $output['dm']['business_licence_number']; ?>"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>手执身份证照片：</th>
          <td><input name="business_licence_number_elc"  type="file" class="w200" />
          <?php if(!empty($output['dm']['business_licence_number_elc'])){ ?>
            	<img border="0" alt="手执身份证照范例" src="\data\upload\shop\store_joinin\<?php echo $output['dm']['business_licence_number_elc'];?>" style="width:300px;height:210px">    <input type="hidden" value="<?php echo $output['dm']['business_licence_number_elc']; ?>" id='idcard' name="idcard" />
          <?php }else{ ?>
      
          	<img border="0" alt="手执身份证照范例" src="<?php echo SHOP_TEMPLATES_URL;?>/images/example.jpg" style="width:300px;height:210px">
            <?php }  ?>
            <span class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
     <?php if($output['flag']==1){ ?>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">店铺营业执照信息</th>
        </tr>
        <tr>
          <th colspan="20" align="center" valign="middle"><input type="radio" name="ishascert" id="ishascert1" value="0" checked="checked"  />有营业执照
          <input type="radio" name="ishascert" id="ishascert2" value="1"  />正在办理 <span style='color:red; size:12px;'>*为了确保入驻成功,请尽量上传营业执照</span>
          </th>
        </tr>
      </thead>
      <tbody>
    
        <tr id="pic">
          <th><i>*</i>营业执照电子版：</th>
          <td><input name="business_licence_number_elcc" id='business_licence_number_elcc' type="file" class="w200" />
          <?php if(!empty($output['dm']['organization_code_electronic'])){ ?>
          	<img border="0" alt="营业执照" src="\data\upload\shop\store_joinin\<?php echo $output['dm']['organization_code_electronic']; ?>" style="width:300px;height:210px">
            <input type="hidden" value="<?php echo $output['dm']['organization_code_electronic']; ?>" name="yycard" id="yycard" />
            <?php }?>
            <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
            
        </tr>
        <tr id="reseon" style="display:none;">
          <th><i>*</i>营业执照电子版：</th>
          <td><p>
            <textarea name="reason" readonly="readonly" style="width:530px; height:100px">我店/我公司做出如下承诺：
-我使用法定代表人/负责人/合法授权人身份证作为基础资质入驻，承诺在90天内到当地工商局办理营业执照等相关资质。
-办理完成之后，我将在五个工作日内联系万店通联业务员，提交营业执照等相关资质。
-若未按规定提交营业执照等相关资质，产生的一切法律责任和损失由我承担。
          </textarea>
          </p>
          <p>
            <input type="checkbox" name="agree" id="agree" value="1"/> 同意以上条款(<span style="color:red; size:12px">请点击此处同意以上条款。</span>)
            <label for="checkbox"></label>
          </p></td>
            
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <?php } ?>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交财务资质信息</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
   
    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('请阅读并同意协议');
        }
    });

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
		element.nextAll('span').first().after(error);
        },
		
        rules : {
            company_name: {
                required: true,
                maxlength: 50 
            },
            company_address: {
                required: true,
                maxlength: 50 
            },
            company_address_detail: {
                required: true,
                maxlength: 50 
            },
            contacts_name: {
                required: true,
                maxlength: 20 
            },
            contacts_phone: {
                required: true,
                maxlength: 20 
            },
            contacts_email: {
                required: true,
                email: true 
            },
            business_licence_number: {
                required: true,
                maxlength: 20
            },
            business_sphere: {
                required: true,
                maxlength: 500
            },
				<?php if(empty($output['dm']['organization_code_electronic'])){ ?>
            business_licence_number_elc: {
                required: true
            },
			<?php } ?>
			
        },
        messages : {
            company_name: {
                required: '请输入店铺名字',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address: {
                required: '请选择区域地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address_detail: {
                required: '请输入目前详细住址或办公地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_name: {
                required: '请输入联系人姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_phone: {
                required: '请输入联系人电话',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_email: {
                required: '请输入常用邮箱地址',
                email: '请填写正确的邮箱地址'
            },
            business_licence_number: {
                required: '请输入身份证号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_sphere: {
                required: '请填写身份证上姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
			<?php if(empty($output['dm']['organization_code_electronic'])){ ?>
            business_licence_number_elc: {
                required: '请选择上传手执身份证照',
            },
			<?php } ?>
		
        }
    });

    $('#btn_apply_company_next').on('click', function() {
		<?php if($output['flag']==1){ ?>
	   if(!validio()){
			return;
			}
		<?php } ?>
        if($('#form_company_info').valid()) {
            $('#form_company_info').submit();
        }
		
    });
});
$('#ishascert1').click(function(){
	$('#pic').show();
	$('#reseon').hide();
	$('#agree').removeAttr('checked');  
	});
$('#ishascert2').click(function(){
	$('#pic').hide();
	$('#reseon').show();
	});
 function validio(){
	 var chkRadio = $('input:radio[name="ishascert"]:checked').val();
	 if(chkRadio==1){
		<?php if(!empty($output['dm']['organization_code'])){}else{?>
		var val=$('input:checkbox[name="agree"]:checked').val();
		if(val==null){
			alert('您的营业执照正在办理中,请点选同意驻店条款');
			return false;
			}
			<?php } ?>
		 }
	  if(chkRadio==0){
		   <?php if(!empty($output['dm']['organization_code_electronic'])){ }else{ ?>
		  var valid = $('#business_licence_number_elcc').val();
		  if(valid==''){
			alert('请上传您的营业执导！');
			return false;  
			  }
			  <?php } ?>
		  }
		 return true;
	 }
</script>
