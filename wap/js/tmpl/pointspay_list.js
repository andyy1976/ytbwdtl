$(function() {
	var map_id = getQueryString('map_id');
	if(map_id!=''){
	$('#map_id').val(map_id);
	}
    var key = getCookie('key');
    if (!key) {
		if(map_id==''){
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
		}else{
			 window.location.href = WapSiteUrl+'/tmpl/member/login.html?map_id='+map_id;
			}
        return;
    }

    $('#saveform').click(function(){
        var money=$('#rc_sn').val();
		var map_id=$('#map_id').val();
       /* if(money<1000){
            alert('输入金额请大于1000元！！！');
            return false;
        }*/
                $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_fund&op=rechargecard_add',
            data:{
                key:key,
                money:money,
                map_id:map_id
                },
            dataType:'json',
            success: function(result){
                //if(result==1){alert('农行卡信息未完善，请重新绑定！！！');}
                if(result==2){alert('请先激活会员，再扫码消费！！！');}
           
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
                   location.href = ApiUrl+'/index.php?act=member_payment&op=pd_order'+'&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code;

                   // $.post(ApiUrl+"/index.php?act=member_payment&op=pd_order",{pdr_sn:pdr_sn,payment_code:payment_code,key:key});
                    
                });
                
            }
        });
    	
    });
    
});
