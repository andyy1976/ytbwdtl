<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
	display: inline;
}
</style>

<div class="page">
  
 
 
    <input type="hidden" name="store_id" id="store_id" value="<?php echo $_GET['store_id']; ?>"/>
   <div class="ncap-form-default">

            <dd class="opt">
          
          <label for="store_name"><em>*</em>二维码：</label>
          
           <img src="<?php echo ($output['imgsrc']);?>" alt="" /> 
        
          </dd>
         <hr />
     <input type="button" value="保存此二维码"  onclick="return savaerweima()"/>
     <input type="button" value="返回上一页" onclick="return abc()"/>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript">
  function savaerweima(){
	  var store_id = $('#store_id').val();
	 window.location.href='index.php?act=shop_instance&op=okhop_add&store_id='+store_id;
	  }
  function abc(){
	  window.location.href='index.php?act=shop_instance';
	  }
</script>

