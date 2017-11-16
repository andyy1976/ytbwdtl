<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <div class="tabmenu">
    

</div>
  </div>
  <div class="ncm-default-form">
    <!-- <form method="post" id="cash_form" > -->
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required">*</i>ID查询：</dt>
        <dd>
          <input name="member_id" type="text" class="text w100" id="member_id" maxlength="20"/><span id='check_name' style='color:red;'></span>
        </dd>
      </dl>
      <!-- <dl>
        <dt><i class="required">*</i>登陆密码：</dt>
        <dd>
          <input name="member_passwd" type="password" class="text w100" id="member_passwd" maxlength="20"/><span></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>安全密码：</dt>
        <dd>
          <input name="member_paypwd" type="password" class="text w100" id="member_paypwd" maxlength="20"/><span></span>
        </dd>
      </dl>-->
      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="submit"  class="submit" id="submit" value="确认查询" /></label></dd>
      </dl> 
<!--     </form> -->
  </div>
</div>
<script type="text/javascript">
$(function(){
    //检测账号
    $('#submit').bind('click', function(){
        var member_id=$('#member_id').val();
        
        $.post("index.php?act=member&op=userid",{
          member_id:member_id
        },function(data){
          
         var  strs=data.split("+"); //字符分割
                for (i=0;i<strs.length ;i++ ){
                      strs[i] //分割后的字符输出
                } 
                
              alert(strs[0],strs[1],strs[2]+strs[3]+strs[4]);
        });
    });
    
});
//重写alert();
 
    window.alert = function(txt,txt1,txt2)
    {
     var shield = document.createElement("DIV");
     shield.id = "shield";
     shield.style.position = "absolute";
     shield.style.left = "0px";
     shield.style.top = "0px";
     shield.style.width = "100%";
     shield.style.height = document.body.scrollHeight+"px";
     shield.style.background = "#333";
     shield.style.textAlign = "center";
     shield.style.zIndex = "10000";
     shield.style.filter = "alpha(opacity=0)";
     var alertFram = document.createElement("DIV");
     alertFram.id="alertFram";
     alertFram.style.position = "absolute";
     alertFram.style.left = "50%";
     alertFram.style.top = "50%";
     alertFram.style.marginLeft = "-225px";
     alertFram.style.marginTop = "-75px";
     alertFram.style.width = "450px";
     alertFram.style.height = "150px";
     alertFram.style.background = "#ccc";
     alertFram.style.textAlign = "center";
     alertFram.style.lineHeight = "150px";
     alertFram.style.zIndex = "10001";
     strHtml = "<ul style='list-style:none;margin:0px;padding:0px;width:100%'> ";
     strHtml += " <li style='background:#E61127 ;text-align:left;padding-left:20px;font-color:#fff;font-size:14px;font-weight:bold;height:25px;line-height:25px;border:1px solid #F9CADE;'><h3 style='color:#fff'>[万店通联提示您，您查询的会员信息如下：]<h3></li> ";
     strHtml += " <li style='background:#fff;text-align:center;font-size:12px;height:50px;line-height:50px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'><div style='width:120px;height:50px;text-align:cenetr;float:left;'>会员ID：</div><div style='width:220px;height:50px;text-align:cenetr;float:left;'>"+txt+"</div></li> ";
      strHtml += " <li style='background:#fff;text-align:center;font-size:12px;height:50px;line-height:50px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'><div style='width:120px;height:50px;text-align:cenetr;float:left;'>会员等级：</div><div style='width:220px;height:50px;text-align:cenetr;float:left;'>"+txt1+"</div></li> ";
      strHtml += " <li style='background:#fff;text-align:center;font-size:12px;height:50px;line-height:50px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'><div style='width:120px;height:50px;text-align:cenetr;float:left;'>所属省市区：</div><div style='width:220px;height:50px;text-align:cenetr;float:left;'>"+txt2+"</div></li> ";
     strHtml += " <li style='background:#E61127;text-align:center;font-weight:bold;height:25px;line-height:25px; border:1px solid #F9CADE;'><input type='button' value='确 定' onclick='doOk()' /></li> ";
     strHtml += "</ul> ";
     alertFram.innerHTML = strHtml;
     document.body.appendChild(alertFram);
     document.body.appendChild(shield);
     var c = 0;
     this.doAlpha = function(){
         if (c++ > 20){clearInterval(ad);return 0;}
         shield.style.filter = "alpha(opacity="+c+");";
     }
     var ad = setInterval("doAlpha()",5);
     this.doOk = function(){
         alertFram.style.display = "none";
         shield.style.display = "none";
     }
     alertFram.focus();
     document.body.onselectstart = function(){return false;};
    }
</script>