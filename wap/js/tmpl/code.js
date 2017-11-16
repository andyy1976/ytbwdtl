$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }
    //获取get值
    var string =location.search ;

    
    if(string!=""){
        //获取手机号码
      var  phone = string.split('&')[0];
        phone = phone.split('=')[1];  
        //获取订单号
      var  merchantOrderNo = string.split('&')[1];
        merchantOrderNo = merchantOrderNo.split('=')[1];  
        //获取平台订单号 
       var orderNo = string.split('&')[2]; 
        orderNo = orderNo.split('=')[1];          
    }
    var captcha = $.trim($("#captcha").val());
    var codekey = $.trim($("#codekey").val());
    var em = $('.code-countdown').find('em');
    $('#send').hide();
    $('.code-countdown').show().find('em').html(60);
    var times_Countdown = setInterval(function(){
        
        var t = parseInt(em.html() - 1);
        if (t == 0) {
            $('#send').show();
            $('.code-countdown').hide();
            clearInterval(times_Countdown);
        } else {
            em.html(t);
        }
    },1000);
    $('#send').click(function(){
            var captcha = $.trim($("#captcha").val());
            var codekey = $.trim($("#codekey").val());
            var type = 'code_agen';
            $.ajax({
                type:'post',
                url:ApiUrl+"/api/payment/lepay/lepay.php",
                data:{key:key,merchantOrderNo:merchantOrderNo,orderNo:orderNo,type:type},
                dataType:'json',
                success:function(result){
                    // if(result.code == 200){
                        if (result.message) {
                            $.sDialog({
                                 skin:"red",
                                 content:result.message,
                                 okBtn:false,
                                 cancelBtn:false
                            });
                            return false;
                        }
                        $('#send').hide();
                        $('.code-countdown').show().find('em').html(60);
                        $.sDialog({
                            skin:"block",
                            content:'短信验证码已发出',
                            okBtn:false,
                            cancelBtn:false
                        });
                        var times_Countdown = setInterval(function(){
                            var em = $('.code-countdown').find('em');
                            var t = parseInt(em.html() - 1);
                            if (t == 0) {
                                $('#send').show();
                                $('.code-countdown').hide();
                                clearInterval(times_Countdown);
                            } else {
                                em.html(t);
                            }
                        },1000);
                    // }else{
                    //     errorTipsShow('<p>' + result.datas.error + '</p>');
                    // }
                }
            });
    });

    $('#nextform').click(function(){
        // if (!$(this).parent().hasClass('ok')) {
        //     return false;
        // }
        $('#e__mask').show()
        var auth_code = $.trim($("#auth_code").val());
        var paypwd = $.trim($("#paypwd").val());
        var type = 'code';
        if (auth_code) {
            $.ajax({
                type:'post',
                url:ApiUrl+"/api/payment/lepay/lepay.php",
                data:{key:key,auth_code:auth_code,paypwd:paypwd,type:type,merchantOrderNo:merchantOrderNo,orderNo:orderNo},
                dataType:'json',
                success:function(result){
                    if (result.message) {
                        $.sDialog({
                             skin:"red",
                             content:result.message,
                             okBtn:false,
                             cancelBtn:false
                        });
                        $('#e__mask').hide()
                        return false;
                    }
                    window.location.href='https://ytbwdtl.com/wap/tmpl/member/rechargecardlog_list.html';
                    // if(result.code == 200){
                    //     $.sDialog({
                    //         skin:"block",
                    //         content:'手机验证成功，正在跳转',
                    //         okBtn:false,
                    //         cancelBtn:false
                    //     });
                    //  setTimeout("location.href = WapSiteUrl+'/tmpl/member/member_password_step2.html'",1000);
                    // }else{
                    //     errorTipsShow('<p>' + result.datas.error + '</p>');
                    // }
                }
            });
        }
    });
});
