<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="post_form" method="post" action="index.php?act=store_map&op=edit_map&map_id=<?php echo $output['map']['map_id']; ?>" onsubmit="return Sugests()">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><i class="required">*</i>实体店铺名称<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w200" type="text" name="name_info" value="<?php echo $output['map']['name_info']; ?>" />
        <p class="hint">不同地址建议使用不同名称以示区别，如“山西面馆(水游城店)”。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>详细地址<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="text w200" type="text" name="address_info" id="address_info" value="<?php echo $output['map']['address_info']; ?>"  />
        <p class="hint">为了准确定位建议地址加上所在城区名字，如“红桥区大丰路18号水游城”。</p>
      </dd>
    </dl>
   <!-- <dl>
      <dt>联系电话</dt>
      <dd>
        <input class="text w200" type="text" name="phone_info" value=""  />
      </dd>
    </dl>
     <dl>
      <dt><i class="required">*</i>赠送积分设置</dt>
      <dd>
       A套餐（正常）：
       <input type="text" id="points" value="" style="width:20px; height:20px" name="points"  onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" />(文本框内只允许输入1及1以上的整数)<br />  
       B套餐（活动）：
       <input type="text" id="pointsb" name="pointsb" value=""  style="width:20px; height:20px"   onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" />
       (文本框内只允许输入1及1以上的整数)
      </dd>
    </dl>
    <dl>-->
      <dt>公交信息<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <textarea name="bus_info" rows="2" class="textarea w300"><?php echo $output['map']['bus_info']; ?></textarea>
      </dd>
    </dl>
     <dl>
      <dt>店铺简短说明<?php echo $lang['nc_colon'];?></dt>
      <dd> <textarea name="shop_bz" rows="2" class="textarea w300"><?php echo $output['map']['shop_bz']; ?></textarea>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_ok'];?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#post_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
            $('#warning').show();
        },
    	submitHandler:function(form){
    	   ajaxpost('post_form', '', '', 'onerror');
    	},
        rules : {
            name_info : {
                required : true
            },
            address_info : {
                required   : true
            }
        },
        messages : {
            name_info : {
                required : '<i class="icon-exclamation-sign"></i>实体店铺名称不能为空'
            },
            address_info  : {
                required   : '<i class="icon-exclamation-sign"></i>详细地址不能为空'
            }
        }
    });
});
/*function Sugests(){
	  var points=$('#points').val();
      var pointsb = $('#pointsb').val();
	  if(points<1){
		  alert('A套餐金额不能少于1！');
		   return false;
		  }
	  if(pointsb<1){
		  alert('B套餐金额不能少于1！');
		  return false;
		  }
	}*/
</script>
