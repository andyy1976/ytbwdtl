var key = getCookie('key');
var password,rcb_pay,pd_pay,payment_code,poi_pay;
var card_order_data;

 // 现在支付方式
 function toPay(pay_sn,act,op) {
    $.ajax({
         type:'post',
         url:ApiUrl+'/index.php?act='+act+'&op='+op,
         data:{key:key,pay_sn:pay_sn},
         dataType:'json',
         success: function(result){
			 $('#pay_sn').val(pay_sn);
			 $('#act').val(act);
			 }
	});
 }
 function toPayyundou(){
                 payment_code='alipay';
				 pd_pay = 1;
				 rcb_pay=0;
				 var password = $('#safetypass').val();
				 var key = getCookie('key');
				 var pay_sn = $('#pay_sn').val();
				 var act = $('#act').val();
				 if(password==''){
					 alert('请输入安全密码');
					 return false;
					 }
				if(password){
					 $.ajax({
                         type:'post',
                         url:ApiUrl+'/index.php?act=member_buy&op=check_pd_pwd',
                         dataType:'json',
                         data:{key:key,password:password},
                         success:function(result){
							if (result.datas.error){
						    alert('支付密码错误');
							return false;
								}
							 }});
					}
			  goToPayment(rcb_pay,payment_code,password,pay_sn,act == 'member_buy' ? 'pay_new' : 'member_buy');
 }
 function goToPayment(rcb_pay,payment_code,password,pay_sn,op) {

    location.href = ApiUrl+'/index.php?act=member_payment&op='+op+'&key=' + key + '&pay_sn=' + pay_sn + '&password=' + password + '&rcb_pay=' + rcb_pay + '&pd_pay=' + pd_pay + '&payment_code=' + payment_code+ '&poi_pay=' + poi_pay;
    
 }
