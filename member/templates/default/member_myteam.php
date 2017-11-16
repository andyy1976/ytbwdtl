
<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="member_center_box" class="ncm-index-container">
</div>
<style>
 #memberss li{margin:5px ;}
 #tabless span{color: #FA0808; margin: 5px;}
 #tabless{width:100% ;text-align: center; background-color: #AAA; color:#FFF; height:30px;}
 #tables td{width:20%;}
 #tables tbody tr:hover{ background-color: #000000; color: #FFF; font-size: 1.2em; font-weight: bold;}
 .ssss{height:10px ;}
  #trees td ul{width:100% ;margin:0 auto;}
 #trees td li{float: left; margin:10px ;}
</style>
<table id="tabless">
  <tr>
     <td>概况  >><span></span></td>
     <td>市代人数：<span><?php if($output['member_city']){echo $output['member_city'];}else{echo '0';}?></span></td>
     <td>区代人数：<span><?php if($output['member_quxian']){echo $output['member_quxian'];}else{echo '0';}?></span></td>
     <td>端口人数：<span><?php if($output['member_part']){echo $output['member_part'];}else{echo '0';}?></span></td>
     <td>VIP会员：<span><?php if($output['member_vip']){echo $output['member_vip'];}else{echo '0';}?></span></td>
     <td>今日新增：<span><?php if($output['member_new']){echo $output['member_new'];}else{echo '0';}?></span></td>
  </tr> 
</table>
<hr>
<select id="bsy">
    <?php 
        $content='';
        switch ($_SESSION['member_level'])
       {
        case 1:
          $content= "<option value='6'>请 选 择</option>
                <option value='10'>见习会员</option>
                <option value='8'>直接下级</option>
                <option value='9'>今日新增</option>";
                break;
        case 2:
          $content= "<option value='6'>请 选 择</option><option value='10'>见习会员</option><option value='1'>VIP会员</option>
                <option value='8'>直接下级</option>
                <option value='9'>今日新增</option>";
                break;

        case 3:
          $content= "<option value='6'>请 选 择</option><option value='7'>全部人员</option><option value='10'>见习会员</option><option value='1'>VIP会员</option>
                <option value='2'>端口代理</option><option value='8'>直接下级</option>
                <option value='9'>今日新增</option>";
          break;
        case 4:
          $content= "<option value='6'>请 选 择</option><option value='7'>全部人员</option><option value='10'>见习会员</option><option value='1'>VIP会员</option>
                <option value='2'>端口代理</option><option value='3'>区县代理</option><option value='8'>直接下级</option>
                <option value='9'>今日新增</option>";
          break;
        case 5:
          $content= "<option value='6'>请 选 择</option><option value='7'>全部人员</option><option value='10'>见习会员</option><option value='1'>VIP会员</option>
                <option value='2'>端口代理</option><option value='3'>区县代理</option><option value='4'>市级代理</option><option value='8'>直接下级</option>
                <option value='9'>今日新增</option>";
          break;
        default:
          $content= "";
        }
        echo $content;          
    ?>
</select>
<?php if($_SESSION['member_level']=='6'){ ?>
<a class="ncbtn ncbtn-bittersweet" title="云豆转账" href="index.php?act=member&op=accounts" style="right: 207px; float: right;"><i class="icon-shield"></i>云豆转账</a>
<?php } ?>
<table style="width:100% ;text-align: center;" id="tables">
    <thead>
        <tr style='border-collapse: collapse;border:solid 1px #aaa;'>
        <td>用户ID</td><td>用户id名字</td><td>等级</td><td>领导人</td>
        </tr>
    </thead>
    <tbody>
        <?php           
            foreach($output['member_infos'] as $value){
                echo "<tr style='border-collapse: collapse;border:solid 1px #aaa;' class='level".$value['member_level']."'>";
                echo "<td>".$value['member_id']."</td>"."<td>".$value['member_truename']."</td>".
                     "<td class='levelcontent'>".member_level_name($value['member_level'])."</td>"."<td>".get_member_name($value['member_pid'])."</td>";
                if($value['member_level']>'1'){
                  echo "<td><a href='/member/index.php?act=member&op=profit&member_id=".$value['member_id']."'>查看代理收益</a></td>";  
                }
                echo "</tr>";
            }           
        ?>  
    </tbody>
    <tfoot>
        <tr id='trees'><td></td><td colspan="3"><?php echo $output['show_page']; ?></td><td></td></tr>
    </tfoot>
</table>
<script>
$(document).ready(function(){  
             $('#bsy').change(function(){
                var x=$(this).val();
                //alert(x);
         
                if(x==1){
                    $('.level1').show().siblings('.level2').hide();
                    $('.level1').siblings('.level3').hide();
                    $('.level1').siblings('.level4').hide();
                }
                if(x==2){
                    $('.level2').show().siblings('.level4').hide();
                    $('.level2').siblings('.level3').hide();
                    $('.level2').siblings('.level1').hide();
                }
                if(x==3){
                    $('.level3').show().siblings('.level1').hide();
                    $('.level3').siblings('.level2').hide();
                    $('.level3').siblings('.level4').hide();
                }
                if(x==4){
                    $('.level4').show().siblings('.level2').hide();
                    $('.level4').siblings('.level1').hide();
                    $('.level4').siblings('.level3').hide();
                }               
                if(x==6){
                    $('tr').hide();
                }
                location.href='index.php?act=member&op=myteam&level='+x;
                if(x==7){location.href='index.php?act=member&op=myteam';}
                /*
                $.post('index.php?act=member&op=myteam',{level:x},function(result){                 
                    location.reload() ;
                });
                */
             })
        })  
</script> 