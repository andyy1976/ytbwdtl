<?php defined('In33hao') or exit('Access Invalid!');?> 
<div class="page">
  <div class="fixed-bar">
  	<p style="margin:10px ;"><i>平台->会员->提成/端口设置</i></p>   
  	<hr>    
  </div>
  <form id="points_form" method="post" name="form1">   
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>上 级  分 成</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="first_chief" id="member_name" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['0']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>上上级分成</label>
        </dt>
        <dd class="opt">
          <input type="text" id="member_passwd" name="second_chief" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['1']['chief'];?>">  
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>省代分成</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_email" name="province_chief" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['2']['chief'];?>">
          <span class="err"></span> 
          <p class="notic"></p>  
        </dd>
        <dt class="tit">
          <label><em>*</em>市代分成</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="municipal_chief" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['3']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p> 
        </dd>
        <dt class="tit">
          <label><em>*</em>区县分成</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="distric_chief" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['4']['chief'];?>">
          <span class="err"></span> 
          <p class="notic"></p> 
        </dd>
        <dt class="tit">
          <label><em>*</em>端口分成</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="part_chief" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['5']['chief'];?>">  
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>店铺总营业额分成</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="store_chief" class="input-txt" placeholder="当前提成为<?php echo $output['content']['6']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>会员 提 现 手续费</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="fee" class="input-txt abc" placeholder="当前提成为<?php echo $output['content']['7']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>省代端口设置数量</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="province_part" class="input-txt  part" placeholder="当前省代可设置端口数为<?php echo $output['content']['8']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>市代端口设置数量</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="city_part" class="input-txt part" placeholder="当前市代可设置端口数为<?php echo $output['content']['9']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
        <dt class="tit">
          <label><em>*</em>区县端口设置数量</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="municipal_part" class="input-txt part" placeholder="当前区代可设置端口数为<?php echo $output['content']['10']['chief'];?>"> 
          <span class="err"></span> 
          <p class="notic"></p>
        </dd>
      </dl>               
      <div class="bot" ><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(".abc").change(function(){
		var money=$(this).val();		
		if(money<=0 || money>=1){
			showDialog('输入的数字必须是大于零小于一的小数', 'error','','','','','','','','',3);
			//document.getElementById('available_predeposit').innerHTML='';			
			return false;
		}
		if(isNaN(money)){
			showDialog('请输入数字', 'error','','','','','','','','',3);
			//document.getElementById('available_predeposit').innerHTML='';			
			return false;
		}
		
})
$(".part").change(function(){
		var money=$(this).val();				
		if(isNaN(money) || Math.floor(money)!=money){
			showDialog('请输入数字并且必须是整数', 'error','','','','','','','','',3);
			//alert('请输入数字');					
			return false;
		}
		
})
$("#submitBtn").click(function(){	
    $("#points_form").submit();
})
</script>