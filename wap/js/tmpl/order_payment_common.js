var key = getCookie('key');
var password,rcb_pay,pd_pay,payment_code,poi_pay;
var card_order_data;

 // 现在支付方式
 function toPay(pay_sn,act,op) {
     $.ajax({
         type:'post',
         url:ApiUrl+'/index.php?act='+act+'&op='+op,
         data:{
             key:key,
             pay_sn:pay_sn
             },
         dataType:'json',
         success: function(result){
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
             // 从下到上动态显示隐藏内容
             $.animationUp({valve:'',scroll:''});

            //20170923潘丙福添加开始-重新定义pay_sn
            pay_sn = result.datas.pay_info.pay_sn;
            //20170923潘丙福添加结束
             
             // 需要支付金额
             $('#onlineTotal').html(result.datas.pay_info.pay_amount);
             //$('#onlineTotalPoints').html($result['data']['api_pay_amount_points']);加入的。。。。
             // 是否设置支付密码
             if (!result.datas.pay_info.member_paypwd) {
                 $('#wrapperPaymentPassword').find('.input-box-help').show();
             }
             
             // 支付密码标记
             var _use_password = false;
             if (parseFloat(result.datas.pay_info.payed_amount) <= 0) {
                 if (parseFloat(result.datas.pay_info.member_available_pd) == 0 && parseFloat(result.datas.pay_info.member_available_rcb) == 0 && parseFloat(result.datas.pay_info.member_points_poit) == 0) {
                     $('#internalPay').hide();
                 } else {
                     $('#internalPay').show();
                     $('#pointsRcBalance').html(parseFloat(result.datas.pay_info.member_points_poit).toFixed(2));
                     // 充值卡
                     if (parseFloat(result.datas.pay_info.member_available_pd) != 0) {
                         $('#wrapperUseRCBpay').show();
                         $('#availableRcBalance').html(parseFloat(result.datas.pay_info.member_available_pd).toFixed(2));
                     } else {
                         $('#wrapperUseRCBpay').hide();
                     }
                       
                     // 预存款
                     if (parseFloat(result.datas.pay_info.member_available_pd) != 0 && parseFloat(result.datas.pay_info.member_points_poit) != 0) {
                        var string =location.search ;
                     if(string!=""){
                            var vgoods_id = string.split('&')[0];
                             
                            var goods_id  =$('#goods_id').val();
                           
                            if(goods_id=='30587' || goods_id=='97414'){
                                 
                                $('#wrapperUsePDpy').hide;
                                   
                            }else{
                                $('#wrapperUsePDpy').show();
                            }
                            var gc_id     =$('#gc_id').val();
                            if(gc_id=='10351'){
                                  $('#wrapperUsePDpy').hide();
                            }                          
                      }
                         
                         $('#availablePredeposit').html(parseFloat(result.datas.pay_info.member_available_pd).toFixed(2));
                     } else {
                         $('#wrapperUsePDpy').hide();
                     }
                 }
             } else {
                 $('#internalPay').hide();
             }
             
             password = '';
             $('#paymentPassword').on('change', function(){
                 password = $(this).val();
             });

             rcb_pay = 0;
             $('#useRCBpay').click(function(){
                 if ($(this).prop('checked')) {
                     _use_password = true;
                     $('#wrapperPaymentPassword').show();
                     rcb_pay = 1;
                 } else {
                     if (pd_pay == 1) {
                         _use_password = true;
                         $('#wrapperPaymentPassword').show();
                     } else {
                         _use_password = false;
                         $('#wrapperPaymentPassword').hide();
                     }
                     rcb_pay = 0;
                 }
             });
             $('#usePOpay').click(function(){
                 if ($(this).prop('checked')) {
                     _use_password = true;
                     $('#wrapperPaymentPassword').show();
                     $('#pay_btn').show();
                     $('.nctouch-pay').hide();
                     poi_pay = 1;
                 } 
             });
             pd_pay = 0;
             $('#usePDpy').click(function(){
                 if ($(this).prop('checked')) {
                     _use_password = true;
                     $('#wrapperPaymentPassword').show();
                     $('#pay_btn').show();
                     $('.nctouch-pay').hide();
                     pd_pay = 1;
                 } else {
                     if (rcb_pay == 1) {
                         _use_password = true;
                         $('#wrapperPaymentPassword').show();
                     } else {
                         _use_password = false;
                         $('#wrapperPaymentPassword').hide();
                     }
                     pd_pay = 0;
                 }
             });

             payment_code = '';
             if (!$.isEmptyObject(result.datas.pay_info.payment_list)) {
                 var readytoWXPay = false;
                 var readytoAliPay = false;
                 var m = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
                 if (parseInt(m && m[1] || 0) >= 5) {
                     // 微信内浏览器
                     readytoWXPay = true;
                 } else {
                     readytoAliPay = true;
                 }
                 for (var i=0; i<result.datas.pay_info.payment_list.length; i++) {
                     var _payment_code = result.datas.pay_info.payment_list[i].payment_code;
                     if (_payment_code == 'alipay' && readytoAliPay) {
                         $('#'+ _payment_code).parents('label').show();
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                     if (_payment_code == 'wxpay_jsapi' && readytoWXPay) {
                         $('#'+ _payment_code).parents('label').show();
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                 }
             }

             $('#tlzf').click(function(){
                 payment_code = 'tlzf';
                 $('#tlzf').addClass('using');
                 $('#wxpay').removeClass('using');
                 $('#ybzf').removeClass('using');
             });
             $('#wxpay').click(function(){
                  payment_code = 'wxpay';
                  $('#tlzf').removeClass('using');
                  $('#wxpay').addClass('using');
                  $('#ybzf').removeClass('using');
             });
            $('#ybzf').click(function(){
                  payment_code = 'ybzf';
                  $('#tlzf').removeClass('using');
                  $('#wxpay').removeClass('using');
                  $('#ybzf').addClass('using');
            });
            $('#vbill').click(function(){
                    $('#payment_list_wrapper li').removeClass('using');
                    $(this).addClass('using');
                    $('#payment_code').val('vbill');
                    var pdr_sn=$('#pdr_sn').val();
                    var payment_code=$('#payment_code').val();
                    location.href = ApiUrl
                   + '/index.php?act=member_payment&op=pay_new'
                   + '&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code;
                    // showCardLlist();

            });
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
                        + '/index.php?act=member_payment&op=pay_new'
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

                   $.post(ApiUrl + '/api/payment/ylwtz/confirm_order.php'
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
                // $('#vbill').click(function(){
                //     $('#payment_list_wrapper li').removeClass('using');
                //     $(this).addClass('using');
                //     $('#payment_code').val('vbill');
                //     var pdr_sn=$('#pdr_sn').val();
                //     var payment_code=$('#payment_code').val();
                //     location.href = ApiUrl
                //    + '/index.php?act=member_payment&op=pd_order'
                //    + '&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code;
                //     // showCardLlist();

                // });

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
                 if (payment_code == '') {
                     $.sDialog({
                         skin:"red",
                         content:'请选择支付方式',
                         okBtn:false,
                         cancelBtn:false
                     });
                     return false;
                 }
                 if (_use_password) {
                     // 验证支付密码是否填写
                     if (password == '') {
                         $.sDialog({
                             skin:"red",
                             content:'请填写支付密码',
                             okBtn:false,
                             cancelBtn:false
                         });
                         return false;
                     }
                     // 验证支付密码是否正确
                     $.ajax({
                         type:'post',
                         url:ApiUrl+'/index.php?act=member_buy&op=check_pd_pwd',
                         dataType:'json',
                         data:{key:key,password:password},
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
                             goToPayment(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
                         }
                     });
                 } else {
                     goToPayment(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
                 }
             });
         }
     });
 }

 function goToPayment(pay_sn,op) {
    location.href = ApiUrl+'/index.php?act=member_payment&op='+op+'&key=' + key + '&pay_sn=' + pay_sn + '&password=' + password + '&rcb_pay=' + rcb_pay + '&pd_pay=' + pd_pay + '&payment_code=' + payment_code+ '&poi_pay=' + poi_pay;
    
 }
