var goods_id = getQueryString("goods_id");
var map_list = [];
var map_index_id = '';
var store_id;
var card_order_data;
$(function (){
    var key = getCookie('key');

    var unixTimeToDateString = function(ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };

    var buyLimitation = function(a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };

    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

     // 图片轮播
    function picSwipe(){
      var elem = $("#mySwipe")[0];
      window.mySwipe = Swipe(elem, {
        continuous: false,
        // disableScroll: true,
        stopPropagation: true,
        callback: function(index, element) {
          $('.goods-detail-turn').find('li').eq(index).addClass('cur').siblings().removeClass('cur');
        }
      });
    }
    get_detail(goods_id);
  //点击商品规格，获取新的商品
  function arrowClick(self,myData){
    $(self).addClass("current").siblings().removeClass("current");
    //拼接属性
    var curEle = $(".spec").find("a.current");
    var curSpec = [];
    $.each(curEle,function (i,v){
        // convert to int type then sort
        curSpec.push(parseInt($(v).attr("specs_value_id")) || 0);
    });
    var spec_string = curSpec.sort(function(a, b) { return a - b; }).join("|");
    //获取商品ID
    goods_id = myData.spec_list[spec_string];
    get_detail(goods_id);
  }

  function contains(arr, str) {//检测goods_id是否存入
	    var i = arr.length;
	    while (i--) {
           if (arr[i] === str) {
	           return true;
           }
	    }
	    return false;
	}
  $.sValid.init({
        rules:{
            buynum:"digits"
        },
        messages:{
            buynum:"请输入正确的数字"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $.sDialog({
                    skin:"red",
                    content:errorHtml,
                    okBtn:false,
                    cancelBtn:false
                });
            }
        }
    });
  //检测商品数目是否为正整数
  function buyNumer(){
    $.sValid();
  }
  
  function get_detail(goods_id) {
      //渲染页面
      $.ajax({
         url:ApiUrl+"/index.php?act=goods&op=goods_detail",
         type:"get",
         data:{goods_id:goods_id,key:key},
         dataType:"json",
         success:function(result){
            var data = result.datas;
            if(!data.error){
              //商品图片格式化数据
              if(data.goods_image){
                var goods_image = data.goods_image.split(",");
                data.goods_image = goods_image;
              }else{
                 data.goods_image = [];
              }
              //商品规格格式化数据
              if(data.goods_info.spec_name){
                var goods_map_spec = $.map(data.goods_info.spec_name,function (v,i){
                  var goods_specs = {};
                  goods_specs["goods_spec_id"] = i;
                  goods_specs['goods_spec_name']=v;
                  if(data.goods_info.spec_value){
                      $.map(data.goods_info.spec_value,function(vv,vi){
                          if(i == vi){
                            goods_specs['goods_spec_value'] = $.map(vv,function (vvv,vvi){
                              var specs_value = {};
                              specs_value["specs_value_id"] = vvi;
                              specs_value["specs_value_name"] = vvv;
                              return specs_value;
                            });
                          }
                        });
                        return goods_specs;
                  }else{
                      data.goods_info.spec_value = [];
                  }
                });
                data.goods_map_spec = goods_map_spec;
              }else {
                data.goods_map_spec = [];
              }

              // 虚拟商品限购时间和数量
              if (data.goods_info.is_virtual == '1') {
                  data.goods_info.virtual_indate_str = unixTimeToDateString(data.goods_info.virtual_indate, true);
                  data.goods_info.buyLimitation = buyLimitation(data.goods_info.virtual_limit, data.goods_info.upper_limit);
              }

              // 预售发货时间
              if (data.goods_info.is_presell == '1') {
                  data.goods_info.presell_deliverdate_str = unixTimeToDateString(data.goods_info.presell_deliverdate);
              }

              //渲染模板
              var html = template.render('product_detail', data);
              $("#product_detail_html").html(html);

              if (data.goods_info.is_virtual == '0') {
            	  $('.goods-detail-o2o').remove();
              }
    
              //渲染模板
              var html = template.render('product_detail_sepc', data);
              $("#product_detail_spec_html").html(html);

              //渲染模板
              var html = template.render('voucher_script', data);
              $("#voucher_html").html(html);

              if (data.goods_info.is_virtual == '1') {
            	  store_id = data.store_info.store_id;
            	  virtual();
              }
  
              // 购物车中商品数量
              if (getCookie('cart_count')) {
                  if (getCookie('cart_count') > 0) {
                      $('#cart_count,#cart_count1').html('<sup>'+getCookie('cart_count')+'</sup>');
                  }
              }

              //图片轮播
              picSwipe();
              //商品描述
              $(".pddcp-arrow").click(function (){
                $(this).parents(".pddcp-one-wp").toggleClass("current");
              });
              //规格属性
              var myData = {};
              myData["spec_list"] = data.spec_list;
              $(".spec a").click(function (){
                var self = this;
                arrowClick(self,myData);
              });
              //购买数量，减
              $(".minus").click(function (){
                 var buynum = $(".buy-num").val();
                 if(buynum >1){
                    $(".buy-num").val(parseInt(buynum-1));
                 }
              });
              //购买数量加
              $(".add").click(function (){
                 var buynum = parseInt($(".buy-num").val());
                 if(buynum < data.goods_info.goods_storage){
                    $(".buy-num").val(parseInt(buynum+1));
                 }
              });
              // 一个F码限制只能购买一件商品 所以限制数量为1
              if (data.goods_info.is_fcode == '1') {
                  $('.minus').hide();
                  $('.add').hide();
                  $(".buy-num").attr('readOnly', true);
              }
              //收藏
              $(".pd-collect").click(function (){
                  if ($(this).hasClass('favorate')) {
                      if (dropFavoriteGoods(goods_id)) $(this).removeClass('favorate');
                  } else {
                      if (favoriteGoods(goods_id)) $(this).addClass('favorate');
                  }
              });
              //加入购物车
              $("#add-cart").click(function (){
                var key = getCookie('key');//登录标记
                var quantity = parseInt($(".buy-num").val());
                 if(!key){
                     var goods_info = decodeURIComponent(getCookie('goods_cart'));
                     if (goods_info == null) {
                         goods_info = '';
                     }
                     if(goods_id<1){
                         show_tip();
                         return false;
                     }
                     var cart_count = 0;
                     if(!goods_info){
                         goods_info = goods_id+','+quantity;
                         cart_count = 1;
                     }else{
                         var goodsarr = goods_info.split('|');
                         for (var i=0; i<goodsarr.length; i++) {
                             var arr = goodsarr[i].split(',');
                             if(contains(arr,goods_id)){
                                 show_tip();
                                 return false;
                             }
                         }
                         goods_info+='|'+goods_id+','+quantity;
                         cart_count = goodsarr.length;
                     }
                     // 加入cookie
                     addCookie('goods_cart',goods_info);
                     // 更新cookie中商品数量
                     addCookie('cart_count',cart_count);
                     show_tip();
                     getCartCount();
                     $('#cart_count,#cart_count1').html('<sup>'+cart_count+'</sup>');
                     return false;
                 }else{
                    $.ajax({
                       url:ApiUrl+"/index.php?act=member_cart&op=cart_add",
                       data:{key:key,goods_id:goods_id,quantity:quantity},
                       type:"post",
                       success:function (result){
                          var rData = $.parseJSON(result);
                          if(checkLogin(rData.login)){
                            if(!rData.datas.error){
                                show_tip();
                                // 更新购物车中商品数量
                                delCookie('cart_count');
                                getCartCount();
                                $('#cart_count,#cart_count1').html('<sup>'+getCookie('cart_count')+'</sup>');
                            }else{
                              $.sDialog({
                                skin:"red",
                                content:rData.datas.error,
                                okBtn:false,
                                cancelBtn:false
                              });
                            }
                          }
                       }
                    })
                 }
              });

              //立即购买
              if (data.goods_info.is_virtual == '1') {
                  $("#buy-now").click(function() {
                  	
                      var key = getCookie('key');//登录标记
                      if (!key) {
                          window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                          return false;
                      }

                      var buynum = parseInt($('.buy-num').val()) || 0;

                      if (buynum < 1) {
                            $.sDialog({
                                skin:"red",
                                content:'参数错误！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }
                      if (buynum > data.goods_info.goods_storage) {
                            $.sDialog({
                                skin:"red",
                                content:'库存不足！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }

                      // 虚拟商品限购数量
                      if (data.goods_info.buyLimitation > 0 && buynum > data.goods_info.buyLimitation) {
                            $.sDialog({
                                skin:"red",
                                content:'超过限购数量！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }

                      var json = {};
                      json.key = key;
                      json.cart_id = goods_id;
                      json.quantity = buynum;
                      $.ajax({
                          type:'post',
                          url:ApiUrl+'/index.php?act=member_vr_buy&op=buy_step1',
                          data:json,
                          dataType:'json',
                          success:function(result){
                              if (result.datas.error) {
                                  $.sDialog({
                                      skin:"red",
                                      content:result.datas.error,
                                      okBtn:false,
                                      cancelBtn:false
                                  });
                              } else {
                                  location.href = WapSiteUrl+'/tmpl/order/vr_buy_step1.html?goods_id='+goods_id+'&quantity='+buynum;
                              }
                          }
                      });
                  });
              } else {
                  $("#buy-now").click(function (){
                  	//alert('你好');exit;
                     var key = getCookie('key');//登录标记
                      //加入的限制
                      if(data.goods_info.gc_id =='10351' && data.goods_info.goods_id!='30587' ){
                          if(data.goods_info.member_level>0){
                              $.sDialog({
                                  skin:"red",
                                  content:'会员只能购买一次',
                                  okBtn:false,
                                  cancelBtn:false
                              });return false;}
                      }
                     if(!key){
                        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                     }else{
                         var buynum = parseInt($('.buy-num').val()) || 0;

                      if (buynum < 1) {
                            $.sDialog({
                                skin:"red",
                                content:'参数错误！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }
                      if (buynum > data.goods_info.goods_storage) {
                            $.sDialog({
                                skin:"red",
                                content:'库存不足！',
                                okBtn:false,
                                cancelBtn:false
                            });
                          return;
                      }

                        var json = {};
                        json.key = key;
                        json.cart_id = goods_id+'|'+buynum;
                        $.ajax({
                            type:'post',
                            url:ApiUrl+'/index.php?act=member_buy&op=buy_step1',
                            data:json,
                            dataType:'json',
                            success:function(result){
                                if (result.datas.error) {
                                    $.sDialog({
                                        skin:"red",
                                        content:result.datas.error,
                                        okBtn:false,
                                        cancelBtn:false
                                    });
                                }else{
                                    location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+buynum;
                                }
                            }
                        });
                     }
                  });

              }

            }else {

              $.sDialog({
                  content: data.error + '！<br>请返回上一页继续操作…',
                  okBtn:false,
                  cancelBtnText:'返回',
                  cancelFn: function() { history.back(); }
              });
            }
            //验证是否是购买云豆产品 z  7/14号 START
            if(result.datas.goods_info.gc_id=='10424' || result.datas.goods_info.gc_id=='10425'){
              $('#goodsEvaluation1').hide();
              $('#goods_spec_selected').hide();            
              $('.goods-detail-item').hide();
               var html = '<div class="goods-detail-price"  style=" padding:0;">'
                    + '<dl>'
                    + '<dt style="font-size:14px;">金额：<input type="text" name="money" id="money" style="height:26px; line-height:26px; border:1px solid #eee; padding:0 10px;"   onkeyup="this.value=this.value.replace(/[^0-9]/g,)" onafterpaste="this.value=this.value.replace(/[^0-9]/g," onblur="give_points()"></dt>'
                    + '</dl>'
                    + '</div>'
                    + '<div class="goods-detail-price" style="height:35px; line-height:35px; padding-bottom:10px;padding-left: 0;">'
                    + '<dl>'
                    + '<dt style="font-size:14px;">云豆：<em id="points"></em> </dt>'
                    + '</dl>'
                    + '</div>';
                    // $(".goods-detail-price").prepend(html);  
              $('.goods-detail-price').html(html);
              if(result.datas.goods_info.gc_id=='10424'){
                var html1='<input type="hidden" value="1" id="type">';
                $('.goods-type').html(html1);
              }else if(result.datas.goods_info.gc_id=='10425'){
                var html1='<input type="hidden" value="2" id="type">';
                $('.goods-type').html(html1);
              }
              
              var html2='<div class="form-btn">'
                     + '<a href="javascript:void(0);" class="btn" id="saveform" style="line-height: 30px;font-size:16px;background: #ed5564 none repeat scroll 0 0;color: #fff !important; width: 100%; height: 40px;" id="saveform">'
                     + '确认提交'
                     + '</a>'
                     + '</div>';
              $('.goods-detail-foot').html(html2);
            }
            $('#saveform').click(function(){
              var money=$('#money').val();
              var odd = Math.floor(Math.random()*10+1); //生成1-10的随机数
              money = (money*1) - odd
              var type=$('#type').val();
  
              $.ajax({
                  type:'post',
                  url:ApiUrl+'/index.php?act=member_fund&op=rechargecard_add&type='+type,
                  data:{
                      key:key,
                      money:money

                      },
                  dataType:'json',
                  success: function(result){
                      if(result==6){alert('每日云豆限额100万！！！');}
                      if(result==5){alert('每日购买至少50金额！！！');}
                      if(result==1){alert('农行卡信息未完善，请重新绑定！！！');}
                      if(result==2){alert('请先激活会员，再充值！！！');}
                      if(result==3){alert('每日限购1千金额！！！');}
                
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
                      $('#a11').addClass('nctouch-bottom-mask up');

                      $('#onlineTotal').html(money);
                      
                      $('#tlzf').click(function(){
                          $('#wxpay').removeClass('using');
                          $('#tlzf').addClass('using');
                          $('#payment_code').val('tlzf');
                      });
                      $('#ybzf').click(function(){
                          $('#tlzf').removeClass('using');
                          $('#ybzf').addClass('using');
                          $('#payment_code').val('ybzf');
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
                 $('#lepay').click(function(){

                    var pdr_sn=$('#pdr_sn').val();
                    var payment_code="lepay";
                    var type=$('#type').val();
                    location.href = ApiUrl+'/index.php?act=member_payment&op=pd_order'+'&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code+'&type='+type;

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
                          var password=$('#paymentPassword').val();
                          var pdr_sn=$('#pdr_sn').val();
                          var payment_code=$('#payment_code').val();
                          var type=$('#type').val();
                          var payment_code='rcb_pay';
                          if (password == '') {
                             $.sDialog({
                                 skin:"red",
                                 content:'请填写支付密码',
                                 okBtn:false,
                                 cancelBtn:false
                             });
                             return false;
                          }
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
                                   location.href = ApiUrl+'/index.php?act=member_payment&op=pd_order'+'&pay_sn='+pdr_sn+'&key='+key+'&payment_code='+payment_code+'&type='+type;
                                   // goToPayment(pay_sn,act == 'member_buy' ? 'pay_new' : 'vr_pay_new');
                               }
                          });               
                      });               
                  }
              });      
            });
            if(result.datas.goods_info.gc_id!='10424' || result.datas.goods_info.gc_id!='10425'){
              $('#voucher_html').on('click', '.btn', function(){
                  getFreeVoucher($(this).attr('data-tid'));
              });
            }
            $('.nctouch-bottom-mask-close').click(function(){
              $("#a11").attr("class", " nctouch-bottom-mask down");
                // $('#a11').addClass('nctouch-bottom-mask down');
            });
            $('.nctouch-bottom-mask-tip').click(function(){
              $("#a11").attr("class", " nctouch-bottom-mask down");
                // $('#a11').addClass('nctouch-bottom-mask down');
            });
           // 验证是否是购买云豆产品 z  7/14号 END
            //验证购买数量是不是数字
            $("#buynum").blur(buyNumer);
            

            // 从下到上动态显示隐藏内容
            $.animationUp({
                valve : '.animation-up,#goods_spec_selected',          // 动作触发
                wrapper : '#product_detail_spec_html',    // 动作块
                scroll : '',     // 滚动块，为空不触发滚动
                start : function(){       // 开始动作触发事件
                    $('.goods-detail-foot').addClass('hide').removeClass('block');
                },
                close : function(){        // 关闭动作触发事件
                    $('.goods-detail-foot').removeClass('hide').addClass('block');
                }
            });
            
            $.animationUp({
                valve : '#getVoucher',          // 动作触发
                wrapper : '#voucher_html',    // 动作块
                scroll : '',     // 滚动块，为空不触发滚动
            });

            $('#voucher_html').on('click', '.btn', function(){
                getFreeVoucher($(this).attr('data-tid'));
            });
            
            // 联系客服
            $('.kefu').click(function(){
                window.location.href = WapSiteUrl+'/tmpl/member/chat_info.html?goods_id=' + goods_id + '&t_id=' + result.datas.store_info.member_id;
            })
         }
      });
  }
  
  // $.scrollTransparent();
  $('#product_detail_html').on('click', '#get_area_selected', function(){
      $.areaSelected({
          success : function(data){
              $('#get_area_selected_name').html(data.area_info);
              var area_id = data.area_id_2 == 0 ? data.area_id_1:data.area_id_2;
              $.getJSON(ApiUrl + '/index.php?act=goods&op=calc', {goods_id:goods_id,area_id:area_id},function(result){
                  $('#get_area_selected_whether').html(result.datas.if_store_cn);
                  $('#get_area_selected_content').html(result.datas.content);
                  if (!result.datas.if_store) {
                      $('.buy-handle').addClass('no-buy');
                  } else {
                      $('.buy-handle').removeClass('no-buy');
                  }
              });
          }
      });
  });
  
  $('body').on('click', '#goodsBody,#goodsBody1', function(){
      window.location.href = WapSiteUrl+'/tmpl/product_info.html?goods_id=' + goods_id;
  });
  $('body').on('click', '#goodsEvaluation,#goodsEvaluation1', function(){
      window.location.href = WapSiteUrl+'/tmpl/product_eval_list.html?goods_id=' + goods_id;
  });

  $('#list-address-scroll').on('click','dl > a',map);
  $('#map_all').on('click',map);
});


function show_tip() {
    var flyer = $('.goods-pic > img').clone().css({'z-index':'999','height':'3rem','width':'3rem'});
    flyer.fly({
        start: {
            left: $('.goods-pic > img').offset().left,
            // top: $('.goods-pic > img').offset().top-$(window).scrollTop()
        },
        end: {
            left: $("#cart_count1").offset().left+40,
            // top: $("#cart_count1").offset().top-$(window).scrollTop(),
            width: 0,
            height: 0
        },
        onEnd: function(){
            flyer.remove();
        }
    });
}

function virtual() {
	$('#get_area_selected').parents('.goods-detail-item').remove();
    $.getJSON(ApiUrl + '/index.php?act=goods&op=store_o2o_addr', {store_id:store_id},function(result){
    	if (!result.datas.error) {
    		if (result.datas.addr_list.length > 0) {
    	    	$('#list-address-ul').html(template.render('list-address-script',result.datas));
    	    	map_list = result.datas.addr_list;
    	    	var _html = '';
    	    	_html += '<dl index_id="0">';
    	    	_html += '<dt>'+ map_list[0].name_info +'</dt>';
    	    	_html += '<dd>'+ map_list[0].address_info +'</dd>';
    	    	_html += '</dl>';
    	    	_html += '<p><a href="tel:'+ map_list[0].phone_info +'"></a></p>';
    	    	$('#goods-detail-o2o').html(_html);

    	    	$('#goods-detail-o2o').on('click','dl',map);

    	    	if (map_list.length > 1) {
    	    		$('#store_addr_list').html('查看全部'+map_list.length+'家分店地址');
    	    	} else {
    	    		$('#store_addr_list').html('查看商家地址');
    	    	}
    	    	$('#map_all > em').html(map_list.length);    			
    		} else {
    			$('.goods-detail-o2o').hide();
    		}
    	}
    });
    $.animationLeft({
        valve : '#store_addr_list',
        wrapper : '#list-address-wrapper',
        scroll : ''
    });
}

function map() {
	  $('#map-wrappers').removeClass('hide').removeClass('right').addClass('left');
	  $('#map-wrappers').on('click', '.header-l > a', function(){
		  $('#map-wrappers').addClass('right').removeClass('left');
	  });
	  $('#baidu_map').css('width', document.body.clientWidth);
	  $('#baidu_map').css('height', document.body.clientHeight);
	  map_index_id = $(this).attr('index_id');
	  if (typeof map_index_id != 'string'){
		  map_index_id = '';
	  }
	  if (typeof(map_js_flag) == 'undefined') {
	      $.ajax({
	          url: WapSiteUrl+'/js/map.js',
	          dataType: "script",
	          async: false
	      });
	  }
	if (typeof BMap == 'object') {
	    baidu_init();
	} else {
	    load_script();
	}
}