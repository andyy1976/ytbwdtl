$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }
    
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?act=member_account&op=get_mobile_info",
        data:{key:key},
        dataType:'json',
        success:function(result){
            if(result.code == 200){
            	if (result.datas.state) {
            		$('#mobile').html(result.datas.mobile);
            	} else {
            		location.href = WapSiteUrl+'/tmpl/member/member_mobile_bind.html';
            	}
            }
        }
    });
  
    $('#nextform').click(function(){
        var member_oldpaypwd=$('#member_oldpaypwd').val();
        var member_newpaypwd=$('#member_newpaypwd').val();
        //alert(phone);exit();
	    $.post(ApiUrl+'/index.php?act=connect&op=paypaswd',{member_oldpaypwd:member_oldpaypwd,member_newpaypwd:member_newpaypwd,key:key},function(result){
		      if(result==1){
		          alert('您的旧安全密码输入不正确，请重新输入!!!');				          
			    }else if(result==2){
			   	  alert('您输入的旧安全密码和新密码相同，请重新输入!!!');	
			    }else if(result==3){
			   	  alert('密码修改成功！！！');
                  window.location.href="member.html";	
			    }else{
                    alert('修改失败！！！');
                }
	    }); 
    });
});
