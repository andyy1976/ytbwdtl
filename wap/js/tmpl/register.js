$(function(){
        var map_id = getQueryString('map_id');
	$('#map_id').val(map_id); //地面商家
    var key = getCookie('key');
    if (key&&map_id=='') {
        window.location.href = WapSiteUrl+'/tmpl/member/member.html';
        return;
    }
	if(key&&map_id!=''){
	   window.location.href= WapSiteUrl+'/tmpl/member/member.html?map_id='+map_id;	
		}
    var string =location.search ;

       // string=string.split(str)[0]
    if(string!=""){
        var vpid = string.split('&')[0];
        var pid  =vpid.split('=')[1];        
        if(pid!=''){
            $('#member_pid').val(pid);
            $('#member_pid').attr('readonly','readonly');
        }
        
    }
    $.getJSON(ApiUrl + '/index.php?act=connect&op=get_state&t=connect_sms_reg&map_id='+map_id, function(result){
        if (result.datas != '0') {
			$('#login').html('<div class="bar bar-header bar-stable wd-header"><a class="button button-icon icon ion-ios-arrow-left" href="javascript:history.go(-1)"> </a><h1 class="title">会员注册</h1><a class="button button-clear button-positive" id="header-nav" href="ydy.html" class="text">登录</a></div>');
            $('.register-tab').show();
        }
		if(result.datas.map_id!=''){
			$('#login').html('<div class="bar bar-header bar-stable wd-header"><a class="button button-icon icon ion-ios-arrow-left" href="javascript:history.go(-1)"> </a><h1 class="title">会员注册</h1><a class="button button-clear button-positive" id="header-nav" href="login.html?map_id='+result.datas.map_id+'" class="text">登录</a></div>');
			$('.register-tab').show();
			}
    });
    
    $.sValid.init({//注册验证
        rules:{
            mobile:"required",
            pwd:"required",            
            paypwd:"required",
            member_pid:"required",
            auth_code:"required",
            email:{
                required:true,
                email:true
            }
        },
          messages:{
            mobile:"用户名必须填写！",
            pwd:"密码必填!", 
            paypwd:"确认密码必填!",
            member_pid:{
            member_pid:"推荐人不能空",
            mobile:"手机号码不能为空",
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

            }else if(result==6){
            alert('验证码发送频繁2分钟之后在发送');
          }

        });

    });
    $('#registerbtn').click(function(){
        // if (!$(this).parent().hasClass('ok')) {
        //     return false;
        // }
        var username = $("input[name=mobile]").val();
        var pwd = $("input[name=pwd]").val();
        var paypwd = $("input[name=paypwd]").val();
        var member_pid = $("input[name=member_pid]").val();
        var free = $("input[name=free]").val();
        var client = 'wap';
        var mobile = $("input[name=mobile]").val();
        var auth_code = $("input[name=auth_code]").val();
        var map_id = $('#map_id').val();
        if(mobile=="" || auth_code=="" || member_pid=="" || paypwd=="" || pwd ==""){
                return false;
            }else{
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=login&op=register",
                data:{
                    username:username,
                    password:pwd,
                    password_confirm:paypwd,
                    map_id:map_id,
                    client:client,
                    member_pid:member_pid,
                    mobile:mobile,
                    auth_code:auth_code,
                    free:free,
                    key:key
                },
                dataType:'json',
                success:function(result){
                    if(!result.datas.error){
                        if(typeof(result.datas.key)=='undefined'){
                            return false;
                        }else{
							
							if(result.datas.map_id!=''){
								
								// 更新cookie购物车
                            updateCookieCart(result.datas.key);
                            addCookie('username',result.datas.username);
                            addCookie('key',result.datas.key);
                            location.href = WapSiteUrl+'/tmpl/member/member.html?map_id='+result.datas.map_id;
								}else{
                            // 更新cookie购物车
                            updateCookieCart(result.datas.key);
                            addCookie('username',result.datas.username);
                            addCookie('key',result.datas.key);
                            location.href = WapSiteUrl+'/tmpl/member/member.html';
                        }
						}
                        errorTipsHide();
                    }else{
                        alert(result.datas.error);
                    }
                }
            });
        }
    });
});
