<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
    <div class="text-intro" style="float:left;left:0px;">分销信息</div>
  </div>
</div>
<select id="rasko">
<option value="1" >第一级下属</option>
<option value="2" >第二级下属</option>
</select>
<style type="text/css">
 #memberss li{margin:5px ;}
 #tabless span{color: #FA0808; margin: 5px;}
 #tabless{width:100% ;text-align: center; background-color: #fafafa; color:#797979; height:30px;}
 #tables td{width:20%;}
 #tables tbody tr:hover{ background-color: #000000; color: #FFF; font-size: 1.2em; font-weight: bold;}
 .ssss{height:10px ;}
  #trees td ul{width:100% ;margin:0 auto;}
 #trees td li{float: left; margin:10px ;}
</style>
<table id="tabless">
<tr style="border-bottom: 1px solid #c4d5e0;
    color: #5f718b;"><th style="text-align:center;border-bottom: 1px solid #c4d5e0;">下级的信息</th><th style="text-align:center;border-bottom: 1px solid #c4d5e0;">会员id</th><th style="text-align:center;border-bottom: 1px solid #c4d5e0;">姓名</th><th style="text-align:center;border-bottom: 1px solid #c4d5e0;">级别</th><th style="text-align:center;border-bottom: 1px solid #c4d5e0;" >领导人</th></tr>

<?php   if($output['pid']){ 
 foreach($output['pid']   as $k =>$value){?>
<tr class="size" style="border-bottom: 1px solid #c4d5e0;
    color: #5f718b;">

<td><?php if($output['pid']){ echo "第一代信息";}?></td>
<td><?php  echo $value['member_id']?></td>
<td><?php  echo $value['member_truename']?></td>
<td><?php  echo member_level_name($value['member_level'])?></td>
<td><?php  echo $value['member_pid']?></td>
</tr>
<?php }?>
<?php }else{  echo "<tr style='height:150px;'><td  colspan='5'><h3 style='color:#85;position:relative;left:0px;top:0px;text-align:center;font-size:25px;'>暂时没有分销信息</h3></td></tr>"; }?>
<?php    if($output['parent2']){
foreach($output['parent2'] as $ks => $v){ 
  if(empty($v)){
    continue;
  }
  foreach ($v as $k){
  ?>
<tr class="size_one" style="display:none;border-bottom: 1px solid #c4d5e0;
    color: #5f718b;" >

<td><?php  if($k){ echo "第二代信息";}?></td>
<td><?php  echo $k['member_id']?></td>
<td><?php  echo $k['member_truename']?></td>
<td><?php  echo member_level_name($k['member_level'])?></td>
<td><?php  echo $k['member_pid']?></td>
</tr>
<?php } ?>
<?php } ?>
<?php }else{  echo "<tr style='height:150px;'><td  colspan='5'><h3 style='color:#85;position:relative;left:0px;top:-150px;text-align:center;font-size:25px;'>暂时没有分销信息</h3></td></tr>"; }?>
 <tr>
        <td colspan="20"><div class="pagination" ><?php echo $output['show_page']; ?></div></td>
        <td colspan="20"><div class="pagination1" style="display:none;"><?php echo $output['show_page1']; ?></div></td>
      </tr>
</table>
<body> 
<script type="text/javascript">
$(document).ready(function(){  
             $('#rasko').change(function(){
                var x=$(this).val();
            if(x=="true"){
              $(".size").hihe().siblings('.size_one').hide();
            }
            if(x=="false"){
              $(".size").hihe().siblings('.size_one').hide();
            }    
         		if(x==1){
         			$(".size").show().siblings('.size_one').hide();
         		}
         		if(x==2){
         			$(".size_one").show().siblings('.size').hide();
         		}
             })
        })  
</script>
