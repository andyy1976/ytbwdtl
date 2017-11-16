<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-default-form">
    <div class="ncm-notes">
    <!-- <h4>应广大会员要求，为了方便会员间的资金流通，开通站内转账功能，转账时请务必填写正确对方ID号，如果ID错误资金可能无法归还，由于错误填写造成的资金损失，本站概不负责！！！！</h4>
    <p>站内转账无需手续费</p><br> -->
  </div>
	  转账云豆：<input type="number" id='money' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g," /><br><br>
	  收款人手机：<input type="number" id='member_id'/> <span id='membername' style="color: #f00;"></span><br><br>
	  支付方式：<select name='type' id='type'><option value='1'>增加云豆</option><option value='2'>扣除云豆</option></select><br><br>
	  支付密码：<input type="password" id='pwd' /><br><br>
	 <button id='go' style="width: 80px;height:27px;background-color: #48CFAE; color: #FFF;">确定</button>
  </div>
</div>
<script type="text/javascript">
$(function(){
  $('#go').click(function(){
			var money=$('#money').val();
			var memberid=$('#member_id').val();
			var pwd=$('#pwd').val();
			var id=<?php echo $_SESSION['member_id'] ;?>;
			var type=$('#type').val();
			if(memberid==id){
				showDialog('不能给自己转账！', 'error','','','','','','','','',3);	
				exit();
			}
			if(money==0 || money=='' ||money<0){
				showDialog('请输入有效数字金额！', 'error','','','','','','','','',3);	
				exit();
			}
			if(money>100000){
				showDialog('最高转账限额十万云豆！', 'error','','','','','','','','',3);	
				exit();
			}
			if(memberid==0 || memberid=='' || memberid<0){
				showDialog('请输入收款人手机 ！', 'error','','','','','','','','',3);	
				exit();
			}
			if(pwd==''){
				showDialog('请输入支付密码！', 'error','','','','','','','','',3);	
				exit();
			}
		  $.post('index.php?act=member&op=transfer',{money:money,pwd:pwd,memberid:memberid,type:type},function(result){
		      if(result==1){
		          showDialog('操作成功', 'succ','','','','','','','','',3);	
		          <?php sleep(3) ;?>				    
			        location.reload() ;
			    }else if(result==0){
			   	    showDialog('转账失败', 'error','','','','','','','','',3);	
			    }else if(result==3){
			   	    showDialog('支付密码错误！', 'error','','','','','','','','',3);	
			    }else if(result==4){
			   	    showDialog('您填写的收款人手机不存在！', 'error','','','','','','','','',3);	
			    }else if(result==6){
			    	  showDialog('该会员转账云豆已达十万云豆, 无法继续转账', 'error','','','','','','','','',3);
			    }else if(result==7){
			    	  showDialog('该会员不是您伞下会员, 无法继续转账', 'error','','','','','','','','',3);
			    }else{
			    	  showDialog('余额不足！请充值', 'error','','','','','','','','',3);
			    }
			});
	})
	// //失去焦点
 //  $('#member_id').blur(function(){
 //   	  var memberid=$('#member_id').val();
 //   	  //alert(memberid);exit;
 //      $.post('index.php?act=predeposit&op=getname',{memberid:memberid},function(result){
	// 	     if(result){
	// 	     	  $('#membername').text('收款人：'+result);
	// 	     }
	// 		}); 
 //  });  
});
</script>
