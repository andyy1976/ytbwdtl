<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert alert-success">
    <h4>操作提示：</h4>
    <ul>
      <li style="color:red">（1）为保障会员帐号数据安全，开户人姓名填写时请确认无误，填写完毕后开户人姓名将无法更改！</li>
      <li style="color:red">（2）只能绑定农业银行卡，且银行卡号只能更改三次！</li>
      <li style="color:red">（3）如需更改姓名必须提供原银行卡本人手持身份证清晰照片以及原卡号本人身份证和银行卡合照，两者同时提交至云托邦公众号，客服审核通过清空姓名后会员重新登陆可重新填写！</li>
    </ul>
  </div>
  <div class="ncm-default-form">

      <dl style="overflow: visible;">
        <dt><i class="required">*</i>银行卡号：</dt>
        <dd>
            <div class="parentCls">
                <input type="text" class="inputElem text w160" value="" name="pwd_code" id="pwd_code" autocomplete="off" autofocus="autofocus" maxlength="19"/>
            </div>
            <span class="" style="color: #999">原银行卡号：<?php echo $output['member_info']['member_bankcard']?></span>
        </dd>
      </dl>
      <dl style="overflow: visible;">
        <dt><i class="required">*</i>姓名：</dt>
        <dd>
            <div class="parentCls">
                <input type="text" class="inputElem text w160" value="<?php echo $output['member_info']['member_bankname']?>" name="card_name" id="pwd_co" autocomplete="off" autofocus="autofocus" maxlength="19"   <?php if($output['member_info']['member_bankname']){echo "readonly='readonly'";}?> />
            </div>
            <span class="error_span"></span>
        </dd>
      </dl>
      <!--<dl style="overflow: visible;">
        <dt><i class="required">*</i>支付密码：</dt>
        <dd>
            <div class="parentCls">
                <input type="password" class="inputElem text w160" value="" name="pwd" id="pwd" autocomplete="off" autofocus="autofocus" maxlength="19"/>
            </div>
            <span class="error_span"></span>
        </dd>
      </dl>-->
      <dl class="bottom">
        <dt>&nbsp;</dt>
        <dd>
          <label class="submit-border">          
            <button id="sub" style="background-color: #48CFAE; width: 60px; height: 25px;">确定</button>
          </label>
        </dd>
      </dl>

  </div>
</div>
<script type="text/javascript">
//input内容放大
$(function(){
  $('#sub').click(function(){
			var card=$('#pwd_code').val();			
			var pwd=$('#pwd').val();
      var cardname=$('#pwd_co').val();					
			if(isNaN(card)){
         showDialog('银行卡号必须是数字！', 'error','','','','','','','','',3);	
				 exit();
      }
			if(card.length!=19){
				showDialog('银行卡号必须是19位数字！', 'error','','','','','','','','',3);	
				exit();
			}
      if(cardname==''){
        showDialog('请输入开户人姓名！', 'error','','','','','','','','',3);  
        exit();
      }
			
		  $.post('index.php?act=member_redpacket&op=blind_card',{card:card,card_name:cardname},function(result){
		      if(result==1){
		          showDialog('支付密码错误', 'error','','','','','','','','',3);		
		          
			    }else if(result==2){
			   	    showDialog('设置成功', 'succ','','','','','','','','',3);	
              window.location.href='/member/index.php?act=predeposit&op=index';
			    }else if(result==3){
			   	    showDialog('设置失败！', 'error','','','','','','','','',3);	
			    }else if(result==4){
			   	    showDialog('您填写的银行卡已被其它账户绑定，设置失败！', 'error','','','','','','','','',3);	
			    }else if(result==5){
			   	    showDialog('您修改银行卡次数已达3次，设置失败！', 'error','','','','','','','','',3);	
			    }
			}); 
	});
});
</script> 
