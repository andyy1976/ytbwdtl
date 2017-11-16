
var intDiff = parseInt(120);//倒计时总秒数量
function timer(intDiff){
    window.setInterval(function(){
    var day=0,
        hour=0,
        minute=0,
        second=0;//时间默认值        
    if(intDiff > 0){
        second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
    }
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    $('#second_show').html('<s></s>'+second+'秒');
    intDiff--;
    }, 1000);
} 
    function get_sms_captcha(type){
        if($("#phone").val().length == 11 && $("#image_captcha").val().length == 4){
            var ajaxurl = 'index.php?act=connect_sms&op=get_captcha&nchash=1&type='+type;
            ajaxurl += '&captcha='+$('#image_captcha').val()+'&phone='+$('#phone').val();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                async: false,
                success: function(rs){
                    if(rs == 'true') {
                        alert('手机验证码发送成功');
                        $("#sms_text").removeAttr("disabled").html('<div class="tiptext" id="sms_text">确保上方验证码输入正确，点击<span><a href="javascript:void(0);" "><i class="icon-mobile-phone"></i>短信验证码已经发送需要等待<strong id="second_show">0秒</strong></a></span>，并将您手机短信所接收到的“6位验证码”输入到下方短信验证，再提交下一步。</div>');
                        timer(intDiff);
                        setTimeout(function(){
                        $("#sms_text").html('<div class="tiptext" id="sms_text">确保上方验证码输入正确，点击<span><a href="javascript:void(0);" onclick="get_sms_captcha(1)"><i class="icon-mobile-phone"></i>发送短信验证</a></span>，并将您手机短信所接收到的“6位验证码”输入到下方短信验证，再提交下一步。</div>');
                         },120000);

                    } else {
                        showError(rs);
                    }
                }
            });
        }
    }
	function check_captcha(){
        if($("#phone").val().length == 11 && $("#sms_captcha").val().length == 6){
            var ajaxurl = 'index.php?act=connect_sms&op=check_captcha';
            ajaxurl += '&sms_captcha='+$('#sms_captcha').val()+'&phone='+$('#phone').val();
			$.ajax({
				type: "GET",
				url: ajaxurl,
				async: false,
				success: function(rs){
            	    if(rs == 'true') {
            	        $.getScript('index.php?act=connect_sms&op=register'+'&phone='+$('#phone').val());
            	        $("#register_sms_form").show();
            	        $("#post_form").hide();
            	    } else {
            	        showError(rs);
            	    }
			    }
			});
    	}
	}