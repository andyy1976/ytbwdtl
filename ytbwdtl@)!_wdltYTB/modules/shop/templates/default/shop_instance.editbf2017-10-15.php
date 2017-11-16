<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
	display: inline;
}
</style>

<div class="page">
  
 
  <form id="store_form" method="post" action="">
    <input type="hidden" name="store_id" id="store_id" value="<?php echo $output['store_array']['store_id']; ?>"/>
   <div class="ncap-form-default">
<dt>  
 <dd>
         
          <iframe align="middle" width="100%" height="500px" src="https://<?php  echo  $_SERVER['HTTP_HOST']; ?>/ytbwdtl@)!*wdltYTB/erweim/index.php?storeid=<?php echo $output['store_array']['store_id']; ?>" style="display:none;" id="scwemy"></iframe>
          </dd> 
</dt>
<dt class="tit">
            
          </dt>
            <dd class="opt">
            <?php if(empty($output['store_array']['shop_erweima'])){ ?>
           
		   <input class="input-btn" type="button" value="展开生成二维码界面" id="scewm" onclick="return abc()" />
		 <?php }else{ ?>
          <label for="store_name"><em>*</em>二维码：</label>
           <img src="<?php echo "/data/upload/erweima/".($output['store_array']['shop_erweima']);?>" alt="" /> 
         <?php } ?>
          </dd>
         
      
<div class="ncap-form-default">
  </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
function abc(){
	$('#scwemy').show();
}

   
   

</script>