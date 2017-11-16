var pdr_sn;
$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }

    $('#saveform').click(function(){
        var money=$('#rc_sn').val();
        if(money<1000){
            alert('输入金额请大于1000元！！！');
            return false;
        }
                $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_fund&op=rechargecard_add',
            data:{
                key:key,
                money:money

                },
            dataType:'json',
            success: function(result){
                if(result==1){
                    alert('农行卡信息未完善，请重新绑定！！！');
                    return false;
                }
                checkLogin(result.login);
                if (result.datas.error) {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                $('#pdr_sn').val(result.datas);
                $.animationUp({valve:'',scroll:''});
                $('#onlineTotal').html(money);
                
                $('#tlzf').click(function(){
                    $('#wxpay').removeClass('using');
                    $('#tlzf').addClass('using');
                    $('#payment_code').val('tlzf');
                });
                // $('#wxpay').click(function(){
                //     $('#tlzf').removeClass('using');
                //     $('#wxpay').addClass('using');
                //     $('#payment_code').val('wxpay');
                // });
                $('#toPay').click(function(){
                    var pdr_sn=$('#pdr_sn').val();
                    var payment_code=$('#payment_code').val();
                   location.href = ApiUrl+'/index.php?act=member_payment&op=pd_order&key='+key+'&pay_sn=' + pdr_sn + '&payment_code=' + payment_code;

                   // $.post(ApiUrl+"/index.php?act=member_payment&op=pd_order",{pdr_sn:pdr_sn,payment_code:payment_code,key:key});
                    
                });
                
            }
        });
    	
    });
    
});
