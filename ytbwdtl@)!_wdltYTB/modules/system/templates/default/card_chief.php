<?php defined('In33hao') or exit('Access Invalid!');?> 
<div class="page">
  <div class="fixed-bar">
  	<p style="margin:10px ;"><i>平台->会员->上月卡代分成</i></p>   
  	<hr>    
  </div>
  <style>
  	#card{width: 100%;text-align: center;border-collapse: collapse;border:solid 1px #aaa;}
  	td ,th{width: 20%;text-align: center;border-collapse: collapse;border:solid 1px #aaa;height: 1.5rem;}
  	th{font-size: 1rem;height: 2rem;color: #000000;}
  </style>
  <table id="card"> 	
  	<thead>
  		<tr>
  			<th>用户ID</th>
  			<th>真实姓名</th>  			
  			<th>代理级别</th>
  			<th>银行卡号</th>
  			<th>分成金额</th>
  		</tr>
  	</thead>
  	<tbody>  		
  		<?php
  			foreach($output['content'] as $value){
  				echo '<tr><td>'.$value['member_id'].'</td><td>'.$value['member_truename'].'</td><td>'.$value['member_level'].
  					   '</td><td>'.$value['member_bankcard'].'</td><td>'.$value['member_money'].'</td></tr>';  				
  			}
  		?>  		
  	</tbody>
  </table>
</div>
<script type="text/javascript">
//$(".abc").change(function(){
//		var money=$(this).val();		
//		if(money<=0 || money>=1){
//			showDialog('输入的数字必须是大于零小于一的小数', 'error','','','','','','','','',3);
//			//document.getElementById('available_predeposit').innerHTML='';			
//			return false;
//		}
//		if(isNaN(money)){
//			showDialog('请输入数字', 'error','','','','','','','','',3);
//			//document.getElementById('available_predeposit').innerHTML='';			
//			return false;
//		}
//		
//})
//$(".part").change(function(){
//		var money=$(this).val();				
//		if(isNaN(money) || Math.floor(money)!=money){
//			showDialog('请输入数字并且必须是整数', 'error','','','','','','','','',3);
//			//alert('请输入数字');					
//			return false;
//		}
//		
//})
//$("#submitBtn").click(function(){	
//  $("#points_form").submit();
//})
</script>