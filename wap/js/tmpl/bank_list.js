var order_sn;
$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }
    //获取get值
    var string =location.search ;

    
    if(string!=""){
        order_sn = string.split('&')[0];
        order_sn = order_sn.split('=')[1];
        amount   = string.split('&')[1];
        amount   = amount.split('=')[1];              
    }
    //获取绑定银行卡
    $.ajax({
         type:'post',
         url:ApiUrl+'/index.php?act=member_bankcard&op=getCardList',
         dataType:'json',
         data:{key:key},
         success: function(result){
            if (result) {

                var list = result

                card_order_data = list
                console.log(card_order_data)

                var html  = ''
                var acc_no = ''  
                for (var i=0, L=list.length; i<L; i++) {
                   
                    html += "<div class='payment' id='lepay' onclick=btn('"+list[i].acc_no+"')>"
                    html += "<ul >"
                    html += "<li>"
                    html += list[i].bank_name
                    html += "</li>"
                    html += "<li id='acc_no'>"
                    html += list[i].acc_no
                    html += "</li>"
                    html += "</ul>"
                    html += "<input type='hidden' id='phone_"+list[i].acc_no+"' value='"+list[i].phone+"'>"
                    html += "<input type='hidden' id='cardname_"+list[i].acc_no+"' value='"+list[i].name+"'>"
                    html += "<input type='hidden' id='cardid_"+list[i].acc_no+"' value='"+list[i].certif_id+"'>"
                    html += "</div>"
                }
            }
            $('#member_bank_card_list').html(html)
        }
    });
});
function btn(acc_no){
    var acc_no= acc_no.toString();
    var phone = $('#phone_'+acc_no).val();
    var cardname = $("#cardname_"+acc_no).val();
    var cardid = $("#cardid_"+acc_no).val();
    var key   = getCookie('key');
    var type  = 'bankcard'; 
     alert('乐支付升级维护中，请暂时使用随行付支付！！！');   
    $.ajax({
         type:'post',
         url:ApiUrl+'/api/payment/lepay/lepay.php',
         dataType:'json',
         data:{key:key,acc_no:acc_no,order_sn:order_sn,amount:amount,phone:phone,cardname:cardname,cardid:cardid,type:type},
         success:function(result){
              // window.location.href='/wap/tmpl/member/recharge_code.html';
            
             if (result.message) {
                 $.sDialog({
                     skin:"red",
                     content:result.message,
                     okBtn:false,
                     cancelBtn:false
                 });
                 return false;
             }
             if(result.success==true){
                window.location.href='/wap/tmpl/member/recharge_code.html?phone='+phone+'&merchantOrderNo='+result.merchantOrderNo+'&orderNo='+result.orderNo;
             }
             // goToPayment(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
         }
    });
    
}
