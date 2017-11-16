$(function(){
//  //加载验证码
//  loadSeccode();
//  $("#refreshcode").bind('click',function(){
//      loadSeccode();
//  });
//  
//  $.sValid.init({//注册验证
//      rules:{
//          usermobile:{
//              required:true,
//              mobile:true
//          }
//      },
//      messages:{
//          usermobile:{
//              required:"请填写手机号！",
//              mobile:"手机号码不正确"
//          }
//      },
//      callback:function (eId,eMsg,eRules){
//          if(eId.length >0){
//              var errorHtml = "";
//              $.map(eMsg,function (idx,item){
//                  errorHtml += "<p>"+idx+"</p>";
//              });
//              errorTipsShow(errorHtml);
//          }else{
//              errorTipsHide();
//          }
//      }  
//  });
   
	$('#send').click(function(){
	    var phone=$('#mobile').val();
	    if(phone==''){alert('请输入手机号!!!');return false;}
	    $.post(ApiUrl+'/index.php?act=connect&op=get_password',{phone:phone},function(result){
		      if(result==1){
		          alert('手机号必须是11位数字');				          
			    }else if(result==2){
			   	  alert('验证码已发送您手机，请注意查收');	
			    }else if(result==3){
			   	  alert('短信发送失败');	
			    }else if(result==4){
			      alert('该手机号码未注册，请重新输入！！！');
			    }
	    }); 
	});
	$('#find_password_btn').click(function(){
	    var phone=$('#mobile').val();
	    var code=$('#auth_code').val();
	    var userpwd=$('#userpwd').val();
	    var oldpwd=$('#password_confirm').val();
	    var type=$('#type').val();
	    if(phone==''){alert('请输入手机号!!!');return false;}
	    if(code==''){alert('请输入验证码!!!');return false;}
	    if(userpwd==''){alert('请输入新密码!!!');return false;}
	    if(oldpwd==''){alert('请输入确认密码!!!');return false;}
	    if(userpwd!=oldpwd){alert('两次密码输入不一致，请重新输入!!!');return false;}
	    $.post(ApiUrl+'/index.php?act=connect&op=findpassword',{phone:phone,code:code,userpwd:userpwd,type:type},function(result){
		      if(result==1){
		          alert('验证码不正确，请重新输入！');				          
			    }else if(result==2){
			   	  alert('验证码已失效，请重新获取');	
			    }else if(result==3){
			   	  alert('密码找回成功！！！');
			   	  if(type==1){window.location.href='login.html';}
			   	  if(type==2){window.location.href='member.html';}	
			    }else if(result==4){
			   	  alert('密码找回失败');	
			    }else if(result==5){
			    	alert('手机号必须是11位数字');	
			    }
	    }); 
	});
});