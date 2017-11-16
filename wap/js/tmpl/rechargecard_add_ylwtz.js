var pdr_sn;

var card_order_data

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
        } else {
            // Math.random(); //该方法产生一个0到1之间的浮点数
            // Math.floor(Math.random()*10);//生成0-9的随机数
            var odd = Math.floor(Math.random()*10+1); //生成1-10的随机数
            money = (money*1) + odd
            // console.log(money)
            // return
        }

        // test...
        // money = '1001'


        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_fund&op=rechargecard_add',
            data:{
                key:key,
                money:money
            },
            dataType:'json',
            success: function(result){

                if(result==1){alert('农行卡信息未完善，请重新绑定！！！');}
                if(result==2){alert('请先激活会员，再充值！！！');}

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


                // $('#ybzf').click(function(){
                //     $('#payment_list_wrapper').show()
                //     $('#member_bank_card_list_box').hide()

                //     $('#payment_list_wrapper li').removeClass('using');
                //     $(this).addClass('using');
                //     $('#payment_code').val('ybzf');
                // });


                document.getElementById('member_bank_card_list')
                .onclick = function(e){
                    var el = e.target
                    if (el.className == 'card-item'){
                        $('#member_bank_card_list li').removeClass('checked')
                        $(el).addClass('checked')
                        // ...
                    }
                }

                $('#mb_card_list_back').click(function(){
                    $('#payment_list_wrapper').show()
                    $('#member_bank_card_list_box').hide()
                })

                $('#add_new_card').click(function(){
                    $('#payment_list_wrapper').hide()
                    $('#member_bank_card_list_box').hide()
                    $('#new_card_add_panel').show()
                })

                $('#back_to_cart_list').click(function(){
                    $('#payment_list_wrapper').hide()
                    $('#new_card_add_panel').hide()
                    $('#member_bank_card_list_box').show()
                })

                $('#sms_back_to_cart_list').click(function(){
                    $('#payment_list_wrapper').hide()
                    $('#new_card_add_panel').hide()
                    $('#input_smscode_panel').hide()
                    $('#member_bank_card_list_box').show()
                })


                ///////////////////////////////////////
                // 选定银行卡后，点击支付
                $('#to_card_pay').click(function(){
                    var idx = $('#member_bank_card_list li.checked').attr('key')
                    if (!idx) {
                        alert('请选择或添加用于支付的银行卡')
                    }

                    var cardInfo = card_order_data[1*idx]
                    console.log(1*idx, cardInfo)

                    if (!cardInfo.acc_no) {
                        alert('卡号错误')
                        return
                    }

                    if (!cardInfo.phone) {
                        alert('手机号错误')
                        return
                    }

                    if (!cardInfo.name) {
                        alert('姓名有误')
                        return
                    }

                    if (!cardInfo.certif_id) {
                        alert('身份证号错误')
                        return
                    }

                    var pdr_sn = $('#pdr_sn').val();
                    var payment_code = $('#payment_code').val();

                    // location.href = ApiUrl
                    // + '/index.php?act=member_payment&op=pd_order'
                    // + '&pay_sn=' + pdr_sn
                    // + '&key=' + key
                    // + '&payment_code=' + payment_code
                    // + '&accNo=' + cardInfo.acc_no
                    // + '&phone=' + cardInfo.phone
                    // + '&name=' + cardInfo.name
                    // + '&certifId=' + cardInfo.certif_id

                    $('#e__mask').show()

                    $.get(
                        ApiUrl
                        + '/index.php?act=member_payment&op=pd_order'
                        + '&pay_sn=' + pdr_sn
                        + '&key=' + key
                        + '&payment_code=' + payment_code
                        + '&accNo=' + cardInfo.acc_no
                        + '&phone=' + cardInfo.phone
                        + '&name=' + cardInfo.name
                        + '&certifId=' + cardInfo.certif_id
                        , function(txt) {
                            if (!txt) {
                                alert('系统繁忙，请稍后重试..')
                                $('#e__mask').hide()
                                return
                            }

                            var arr = txt.split('|')

                            if (arr[0] != 'OK') {
                                alert(txt)
                                $('#e__mask').hide()
                                return
                            }

                            var orderNo = arr[1]
                            var sendSeqId = arr[2]
                            var amt = arr[3]
                            var sign = arr[4]

                            $('#e__orderNo').val(orderNo)
                            $('#e__sendSeqId').val(sendSeqId)
                            $('#e__sign').val(sign)
                            $('#e__amt').val(amt)

                            $('#e__mask').hide()
                            $('#payment_list_wrapper').hide()
                            $('#new_card_add_panel').hide()
                            $('#member_bank_card_list_box').hide()
                            $('#input_smscode_panel').show()
                        }
                    )

                })


                $('#doConfirmPay').click(function(){
                   var orderNo =  $('#e__orderNo').val()
                   var sendSeqId =  $('#e__sendSeqId').val()
                   var smsCode = $('#e__smsCode').val()
                   var sign = $('#e__sign').val()
                   var amt = $('#e__amt').val()

                   if (!orderNo || !sendSeqId || !smsCode) {
                      !orderNo && alert('订单号错误')
                      !sendSeqId && alert('交易流水号错误')
                      !smsCode && alert('请输入短信验证码')
                      return
                   }

                   $('#e__mask').show()

                   $.post(ApiUrl + '/api/payment/ylwtz/confirm_pay.php'
                    , {
                        orderNo: orderNo,
                        sendSeqId: sendSeqId,
                        smsCode: smsCode,
                        sign: sign,
                        amt: amt
                    }
                    , function(txt){
                        if (!txt) {
                            alert('系统繁忙，请稍后重试...')
                        }
                        if (txt == 'OK') {
                            alert('支付成功')
                            location.href = '/wap/tmpl/member/rechargecardlog_list.html'
                        } else {
                            alert(txt)
                            location.href = '/wap/tmpl/member/rechargecardlog_list.html'
                        }

                        $('#e__mask').hide()
                   })
                })


                ///////////////////////////////////////
                // 选择银联支付

                var showCardLlist = function(){
                    $('#payment_list_wrapper').hide()
                    $('#new_card_add_panel').hide()
                    $('#member_bank_card_list_box').show()
                    $.ajax({
                        type: 'get',
                        url: ApiUrl+ '/index.php?act=member_bankcard&op=getCardList' + '&key='+key,
                        success: function(result){
                            if (result) {
                                var list = $.parseJSON(result)
                                card_order_data = list
                                console.log(card_order_data)

                                var html = ''
                                for (var i=0, L=list.length; i<L; i++) {
                                    html += '<li key="'+ i +'" class="card-item">'
                                    html += list[i].bank_name
                                    html += '<br>'
                                    html += list[i].acc_no
                                    html += '<input type="hidden" name="accNo" valve="'+list[i].acc_no+'">'
                                    html += '<input type="hidden" name="phone" valve="'+list[i].phone+'">'
                                    html += '</li>'
                                }
                            }
                            $('#member_bank_card_list').html(html)
                        }
                    })
                }

                $('#ylwtz').click(function(){

                    $('#payment_list_wrapper li').removeClass('using');
                    $(this).addClass('using');
                    $('#payment_code').val('ylwtz');

                    showCardLlist();

                });
                $('#vbill').click(function(){
                    $('#payment_list_wrapper li').removeClass('using');
                    $(this).addClass('using');
                    $('#payment_code').val('vbill');
                    var pdr_sn=$('#pdr_sn').val();
                    var payment_code=$('#payment_code').val();
                    location.href = ApiUrl
                   + '/index.php?act=member_payment&op=pd_order'
                   + '&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code;
                    // showCardLlist();

                });

                ///////////////////////////////////////
                // 添加新卡
                $('#do_add_card').click(function(){

                    var accNo    = $('#card_accNo').val().trim()
                    var phone    = $('#card_phone').val().trim()
                    var name     = $('#card_name').val().trim()
                    var certifId = $('#certif_id').val().trim()

                    if (!accNo || !phone || !name || !certifId) {
                        !accNo && alert('请输入银行卡号')
                        !phone && alert('请输入预留手机号')
                        !name  && alert('请输入开户姓名')
                        !certifId && alert('请输入身份证号')
                        return false
                    }

                    $('#e__mask').show()

                    $.ajax({
                        type: 'post',
                        url: ApiUrl+ '/index.php?act=member_bankcard&op=add' + '&key='+key,
                        data:{
                            accNo: accNo,
                            phone: phone,
                            name: name,
                            certifId: certifId
                        },
                        dataType:'json',
                        success: function(result){
                            
                            $('#card_accNo').val('')
                            $('#card_phone').val('')
                            $('#card_name').val('')
                            $('#certif_id').val('')
                            // alert(result)
                            showCardLlist();

                            $('#e__mask').hide()
                        }
                    })
                })

                $('#toPay').click(function(){
                    
                   var pdr_sn=$('#pdr_sn').val();
                   var payment_code=$('#payment_code').val();
                   location.href = ApiUrl
                   + '/index.php?act=member_payment&op=pd_order'
                   + '&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code;

                   // $.post(ApiUrl+"/index.php?act=member_payment&op=pd_order",{pdr_sn:pdr_sn,payment_code:payment_code,key:key});
                    
                });
                
            }
        });
        
    });
    
});
