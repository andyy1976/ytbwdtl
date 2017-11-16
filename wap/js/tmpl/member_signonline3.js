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

    $.getJSON(ApiUrl + '/index.php?act=member_signonline&op=signOnlineStep3', {key:key, id:signonline_id}, function(result){
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
        var html1 = template.render('member-signonline1-script', result.datas);
        $("#member-signonline1-div").html(html1);
        var html2 = template.render('member-signonline2-script', result.datas);
        $("#member-signonline2-div").html(html2);  
    });

    $('.qy_btn').live("click", function(){
        if ($('#is_alreadyread').is(':checked')) {
            var _form_param = $('form').serializeArray();
            var param = {};
            param.key = key;
            param.signonline_id = signonline_id;
            for (var i=0; i<_form_param.length; i++) {
                param[_form_param[i].name] = _form_param[i].value;
            }
            $.ajax({
                type:'post',
                url:ApiUrl+'/index.php?act=member_signonline&op=checkid&id=' + signonline_id,
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
                    window.location.href = WapSiteUrl + '/tmpl/member/sign_online_step4.html?id=' + signonline_id;
                }
            });
        } else {
            $.sDialog({
                skin:"red",
                content:'请点击"我同意"!',
                okBtn:false,
                cancelBtn:false
            });
            return false;
        }
    });
});

