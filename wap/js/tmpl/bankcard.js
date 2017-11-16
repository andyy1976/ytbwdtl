$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }


    $('#nextform').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }

        var acc_no = $.trim($("#acc_no").val());
        var phone = $.trim($("#phone").val());
        var name = $.trim($("#name").val());
        var certif_id = $.trim($("#certif_id").val());

        var type = 'code';
        // if (auth_code) {
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=member_bankcard&op=add",
                data:{key:key,accNo:acc_no,phone:phone,name:name,certifId:certif_id},
                dataType:'json',
                success:function(result){
                    if(result){alert('绑定成功！！');window.history.go(-1);}
                    
                    else{alert('绑定失败！！');}
                }
            });
        // }
    });
});
