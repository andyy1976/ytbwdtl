$(function(){
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    var signonline_id = getQueryString('id');

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
    });

    $.sValid.init({
        rules:{
            submit_member_truename:"required",
            update_member_id:"required",
            update_member_truename:"required",
            update_member_mobile:{
                required:true,
                minlength:11,
                maxlength:11
            },
            update_level:{
                required:true,
                number:true
            },
            update_level_detail:"required",
            document_type:{
                required:true,
                number:true                
            },
            document_number:{
                required:true,
                number:true
            },
            update_amount_total:{
                required:true,
                number:true
            },
            update_amount_first:{
                required:true,
                number:true
            },
            update_amount_last:{
                required:true,
                number:true
            },
            update_amount_last_date:{
                number:true
            }
        },
        messages:{
            submit_member_truename:"招商方姓名必填！",
            update_member_id:"加盟会员ID必填!",
            update_member_truename:"加盟会员真实姓名必填!",
            update_member_mobile:{
                required:'手机号码必填!',
                minlength:'手机号码不足11位!',
                maxlength:'手机号码超过11位!'
            },
            update_level:{
                required:'代理级别必选！',
                number:'代理级别必选！'
            },
            update_level_detail:"加盟区域详情必填！",
            document_type:{
                required:'证件类型必选！',
                number:'证件类型必选！'
            },
            document_number:{
                required:'证件号码必填！',
                number:'证件号码必须为纯数字！'
            },
            update_amount_total:{
                required:'加盟总费用必填！',
                number:'加盟总费用为纯数字！'
            },
            update_amount_first:{
                required:'首期加盟费用必填！',
                number:'首期加盟费用必须为纯数字！'
            },
            update_amount_last:{
                required:'剩余加盟费用必填，可填写“0”!',
                number:'剩余加盟费用必须为纯数字！'
            },
            update_amount_last_date:{
                number:'剩余加盟费用缴费期限必须为纯数字！'
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
                url:ApiUrl+'/index.php?act=member_signonline&op=saveStep1',
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
                    window.location.href = WapSiteUrl + '/tmpl/member/sign_online_step2.html?id=' + result.datas.signonline_id;
                }
            });
        }
    });
});

