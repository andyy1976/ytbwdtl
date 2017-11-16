$(function(){
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    var signonline_id = getQueryString('id');
    if (!signonline_id) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    $.getJSON(ApiUrl + '/index.php?act=member_signonline&op=signOnlineStep1', {key:key, id:signonline_id}, function(result){
        if (result.datas.error) {
            $.sDialog({
                skin:"red",
                content:result.datas.error,
                okBtn:false,
                cancelBtn:false
            });
            return false;
        }
        var html = template.render('member-signonline-script', result.datas);
        $("#member-signonline-div").html(html);
        var html2 = template.render('member-signonline2-script', result.datas);
        $("#member-signonline2-div").html(html2);
        
        $('input[name="file"]').ajaxUploadImage({
            url : ApiUrl + "/index.php?act=sns_album&op=file_upload",
            data:{key:key},
            start :  function(element){
                element.parent().after('<div class="upload-loading"><i></i></div>');
                element.parent().siblings('.pic-thumb').remove();
            },
            success : function(element, result){
                checkLogin(result.login);
                if (result.datas.error) {
                    element.parent().siblings('.upload-loading').remove();
                    $.sDialog({
                        skin:"red",
                        content:'图片尺寸过大！',
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                element.parent().after('<div class="pic-thumb"><img src="'+result.datas.file_url+'"/></div>');
                element.parent().siblings('.upload-loading').remove();
                element.parents('a').next().val(result.datas.pan_file_url);
            }
        });       
    });

    $.sValid.init({
        rules:{
            idcard_positive_image:"required",
            idcard_opposite_image:"required",
            authorization_image:"required",
            payment_image:"required"
        },
        messages:{
            idcard_positive_image:"加盟人身份证正面照片必填！",
            idcard_opposite_image:"加盟人身份证反面照片必填！",
            authorization_image:"招商方授权书照片必填！",
            payment_image:"加盟人打款凭证照片必填！"
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

    $('.qy_btn').live("click", function(){
        if ($.sValid()) {
            var _form_param = $('form').serializeArray();
            var param = {};
            param.key = key;
            param.signonline_id = signonline_id;
            for (var i=0; i<_form_param.length; i++) {
                param[_form_param[i].name] = _form_param[i].value;
            }
            $.ajax({
                type:'post',
                url:ApiUrl+'/index.php?act=member_signonline&op=saveStep4&id='+signonline_id,
                data:param,
                dataType:'json',
                async:false,
                success:function(result){
                    if (result.datas.error) {
                        $.sDialog({
                            skin:"red",
                            content:result.datas.error,
                            okBtn:false,
                            cancelBtn:false
                        });
                        return false;
                    }
                    window.location.href = WapSiteUrl + '/tmpl/member/sign_online_step5.html';
                }
            });
        }
    });
});