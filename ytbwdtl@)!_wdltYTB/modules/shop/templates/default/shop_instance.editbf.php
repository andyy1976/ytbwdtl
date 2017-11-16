<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
	display: inline;
}
</style>

<div class="page">
<div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['store_help1']  ." 所以一旦允许商家申请后，请立刻设置以下参数,默认参数为8%，即0.08。</span>";?></li>
    </ul>
  </div>
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=shop_instance&op=store" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?> - 编辑会员“<?php echo $output['joinin_detail']['member_name'];?>”的店铺信息</h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="homepage-focus" nctype="editStoreContent">
  <div class="title">
  <h3>编辑实体店铺信息</h3>
    <ul class="tab-base nc-row">
      <!--<li><a class="current" href="javascript:void(0);">实体店铺信息</a></li>-->
      <li><a href="javascript:void(0);">商家云豆兑换比例设置</a></li>
     <!--<li><a href="javascript:void(0);">生成二维码</a></li>-->
    </ul>
    </div>
    
    <form id="store_form" method="post" action="">
       <input type="hidden" name="store_id" id="store_id" value="<?php echo $output['store_array']['store_id']; ?>"/>
     <div class="ncap-form-default">
        <dl class="row">
          <dt class="tit">
            <label><?php echo $lang['store_user_name'];?></label>
          </dt>
          <dd class="opt"><?php echo $output['store_array']['member_name'];?><span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <!--<dl class="row">
        
           <dt class="tit">
            <label for="store_name"><em>*</em>1云豆比1人民币比例基数：</label>
          </dt>
          <dd class="opt">
          
               <input type="text" value="" onkeyup="value=value.replace(/[^\d\.]/g,'')" onblur="value=value.replace(/[^\d\.]/g,'')" id="custom_point" name="custom_point" class="input-txt"/>
               
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
         
          </dl>-->
            
            <dl class="row">
        
           <dt class="tit">
            <label for="store_name"><em>*</em>云豆转换手续费收点：</label>
          </dt>
          <dd class="opt">
          <input type="text" value="<?php echo $output['store_array']['custom_pointis'];?>" onkeyup="value=value.replace(/[^\d\.]/g,'')" onblur="value=value.replace(/[^\d\.]/g,'')" id="custom_pointis" name="custom_pointis" class="input-txt"/>如果不作设置,系统默认为0.08
          <p class="notic"> </p>
          </dd>
         
          </dl>
      
     
        <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
      </div>
    </form>
 
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
$('#submitBtn').click(function(){
	var custom_point= $('#custom_point').val();

	var custom_pointis =$('#custom_pointis').val();
	
	 if(custom_pointis==''){
		 alert('手续费点数没有设置！');
		return false;
		 }
	   submitit();
	});
function submitit(){
	var custom_point= $('#custom_point').val();
	var custom_pointis =$('#custom_pointis').val();
	var store_id = $('#store_id').val();
	   $.ajax({
        type: "get",
        dataType: "json",
        url: "index.php?act=shop_instance&op=edit_ydset&custom_point="+custom_point+"&store_id="+store_id+"&custom_pointis="+custom_pointis,
        success: function(data){
            if(data==1){
				 alert('设置成功！');
		    }else{
				 alert('设置不成功！');
			}
        }
    });
	}
</script>