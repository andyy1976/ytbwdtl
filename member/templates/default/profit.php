
<?php defined('In33hao') or exit('Access Invalid!');?>

<div id="member_center_box" class="ncm-index-container">
</div>
<style>
 #memberss li{margin:5px ;}
 #tabless span{color: #FA0808; margin: 5px;}
 #tabless{width:100% ;text-align: center; background-color: #AAA; color:#FFF; height:30px;}
 #tables td{width:10%;}
 #tables tbody tr:hover{ background-color: #000000; color: #FFF; font-size: 1.2em; font-weight: bold;}
 .ssss{height:10px ;}
  #trees td ul{width:100% ;margin:0 auto;}
 #trees td li{float: left; margin:10px ;}
</style>
<table style="width:100% ;text-align: center;" id="tables">
    <thead>
        <tr style='border-collapse: collapse;border:solid 1px #aaa;'>
        <td>用户ID</td><td>账号</td><td>时间</td><td>激活会员人数</td><td>代理分成收益</td>
        </tr>
    </thead>
    <tbody>
        <?php           
            foreach($output['pd_info'] as $value){
                echo "<tr style='border-collapse: collapse;border:solid 1px #aaa;'>";
                echo "<td>".$value['lg_member_id']."</td>"."<td>".$value['lg_member_name']."</td>".
                     "<td class='levelcontent'>".date('Y-m-d',$value['lg_add_time'])."</td>"."<td>".$value['total_number']."</td>"."<td>".$value['profit_amount']."</td>";                
                echo "</tr>";
            }           
        ?>  
    </tbody>
    <tfoot>
        <tr id='trees'><td></td><td colspan="3"><?php echo $output['show_page']; ?></td><td></td></tr>
    </tfoot>
</table>
