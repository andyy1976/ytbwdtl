<?php defined('In33hao') or exit('Access Invalid!');?>

<div id="member_center_box" class="ncm-index-container">
</div>
<style>
 #tabless tbody tr:hover{ background-color: #000000; color: #FFF; font-size: 1.2em; font-weight: bold;}	
 #tabless thead td{border:solid 1px #aaa;width:20%;}
</style>
<table style="width:100% ;text-align: center;" id="tabless">
	<thead>
		<tr style='border-collapse: collapse;border:solid 1px #aaa;'>
		<td>账号</td><td>手机</td><td>等级</td><td>操作  </td><td>操作者</td>
		</tr>
	</thead>
	<tbody>
		<?php			
			foreach($output['member_info'] as $value){
				echo "<tr style='border-collapse: collapse;border:solid 1px #aaa;'>";
				echo "<td>".$value['member_name']."</td>"."<td>".$value['member_mobile']."</td>"."<td class='levelcontent'>".member_level_name($value['member_level'])."</td>"."<td><span style='display:none;'>"
					 .$value['member_id']."</span><select name='part'><option value='0'>设置</option><option value='1'>给予端口</option><option value='2'>取消端口</option></select></td>"
					 ."<td class='editor'>".$value['editor_name']."</td>";
			    echo "</tr>";
			}			
		?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
<script>
	$('select').change(function(){
		var member_id=$(this).prev().text();		
		var part=$(this).val();	
		//var parent_level=$(this).parent().prev()
		if(part==1 || part==2){
			$.post('index.php?act=member&op=part',{member_id:member_id,part:part},function(data){				
	            showDialog(data, 'succ','','','','','','','','',3);		            	              	                     
	       });	 	             
        }  
        <?php sleep(3) ?>
        location.reload();
	})
</script>