$(function(){
    var key = getCookie('key');
    if (key) {
        window.location.href = WapSiteUrl+'/tmpl/member/member.html';
        return;
    }
    var string =location.search ;

       // string=string.split(str)[0]
    if(string!=""){
    var vpid = string.split('&')[0];
    var pid  =vpid.split('=')[1];
    if(pid!='undefined'){
        $('#member_pid').val(pid);
        $('#member_pid').attr('readonly','readonly');
    }
}
    $.getJSON(ApiUrl + '/index.php?act=connect&op=get_state&t=connect_sms_reg', function(result){
        if (result.datas != '0') {
            $('.register-tab').show();
        }
    });
    
	$.sValid.init({//注册验证
        rules:{
        	username:"required",
            userpwd:"required",            
            password_confirm:"required",
            email:{
            	required:true,
            	email:true
            }
        },
          messages:{
            username:"用户名必须填写！",
            userpwd:"密码必填!", 
            password_confirm:"确认密码必填!",
            member_pid:{
                required:"推荐人必填!",
                member_pid:"推荐人不能空",
            tel:"手机号码不能为空",
                auth_code:"验证码不能空"
            }
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }  
    });
    //查询上级ID的姓名以及代理商
    $('#member_pid').bind('input propertychange', function(){
            var pid=$('#member_pid').val();
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=login&op=checkpid",
                data: {pid:pid},
              
                success:function(result){
                  var datas=eval('('+result+')');
             
              $('#member_pname').html("<p style='float: left'>推荐人用户名：</p><h1 style='float: left;color: red; margin-top: 3px;font-size:15px;'>"+datas.member_name+"</h1>");
  
                }
            });
    }); 
    $('#send').click(function(){

        var phone=$('#mobile').val();

        if(phone.length!=11){alert('手机号必须是11位数字');  exit();}

        //alert(phone);exit();

        $.post(ApiUrl+'/index.php?act=connect&op=mobile_bling',{key:key,mobile:phone},function(result){

            if(result==2){

                alert('验证码已发送您手机，请注意查收');

            }else if(result==3){

                alert('验证码发送失败');

            }else if(result==4){

                alert('该手机已被使用');

            }

        });

    });
    $('#registerbtn').click(function(){
        // if (!$(this).parent().hasClass('ok')) {
        //     return false;
        // }
        var username = $("input[name=username]").val();
        var pwd = $("input[name=pwd]").val();
        var password_confirm = $("input[name=password_confirm]").val();
        var email = $("input[name=email]").val();
        var member_pid = $("input[name=member_pid]").val();
        var client = 'wap';
        var mobile = $("input[name=mobile]").val();
        var auth_code = $("input[name=auth_code]").val();

        if($.sValid()){
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=login&op=register",
                data:{
                    username:username,
                    password:pwd,
                    password_confirm:password_confirm,
                    email:email,
                    client:client,
                    member_pid:member_pid,
                    mobile:mobile,
                    auth_code:auth_code,
                    key:key
                },
                dataType:'json',
                success:function(result){
                    if(!result.datas.error){
                        if(typeof(result.datas.key)=='undefined'){
                            return false;
                        }else{
                            // 更新cookie购物车
                            updateCookieCart(result.datas.key);
                            addCookie('username',result.datas.username);
                            addCookie('key',result.datas.key);
                            location.href = WapSiteUrl+'/tmpl/member/member.html';
                        }
                        errorTipsHide();
                    }else{
                        errorTipsShow("<p>"+result.datas.error+"</p>");
                    }
                }
            });
        }
    });
});