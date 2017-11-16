// JavaScript Document
$(function(){
	  var key = getCookie('key');
	  var goods_id = getQueryString('goods_id'); 
        if(!key){
          window.location.href = WapSiteUrl + '/tmpl/dm/dmlogin.html';
        }else{          
		 $.getJSON(ApiUrl + '/index.php?act=dmstore&op=dm_banklist',{key:key}, function(result) {    //选取银行卡列表
               var html = template.render('banklist-script', result);
			   $("#banklist-div").html(html);
          });
		  $.getJSON(ApiUrl + '/index.php?act=dmstore&op=dm_goods_details',{key:key,goods_id:goods_id}, function(result) {
			   var html = template.render('goods_details-script', result);
			   $("#goods_details").html(html);
			   if(result.datas.isdmgoods==1){
				   $('#flag').val(1);
				   }
			   $(".kyye").html(parseFloat(result.datas.member_available_rcb));
			  
			  
		  });
		
		  
          }
	});
$('#maidan1').click(function(){   //添加银行卡
	    var bankcard = $("input[name='bankcard']").val();
        var bankname = $("input[name='bankname']").val();
        var identity = $("input[name='identity']").val();
        var mobile   = $("input[name='mobile']").val();
        if(bankcard ==''){
          alert('银行卡号不能为空');
          return false;
        }
        if(bankname ==''){
          alert('开户姓名不能为空');
          return false;
        }
        if(identity ==''){
          alert('身份证号不能为空');
          return false;
        }
		if(identity.length<14){
			alert('身份证格式错误');
			return false;
			}
        if(mobile ==''){
          alert('预留手机号不能为空');
          return false;
        }
		if(mobile.length!=11) 
       { 
           alert('请输入有效的手机号码！'); 
          
           return false; 
       } 
       var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
       if(!myreg.test(mobile)) 
       { 
           alert('请输入有效的手机号码！'); 
           return false; 
       } 
	   var key = getCookie('key');
      $.getJSON(ApiUrl + '/index.php?act=dmstore&op=add_bankcard',{key:key,bankcard:bankcard,bankname:bankname,identity:identity,mobile:mobile}, function(result) {
		     var data = result.datas;
            if(data == 'ok'){
              alert('添加成功');
			  return backstep();
            }else if(data=='no'){
			  alert('此卡已经添加');
			  return backstep();
				}
			else{
              alert('添加失败');
			  return backstep();
            }
				  
				  });
			
			
	
	})
$('#ToBuyStep1').click(function(){
	var key = getCookie('key');
	var goods_id = getQueryString('goods_id'); 
	var pay_name = 'online';
	var ifcart = "";
	var cart_id=goods_id+"|"+ $(".num").val();
	
	$.ajax({//提交订单信息第一步
            type:'post',
            url:ApiUrl+'/index.php?act=member_buy&op=buy_step1',
            dataType:'json',
            data:{key:key,cart_id:cart_id,ifcart:ifcart},
            success:function(result){
				$('#ToBuyStep1').hide();
	            $('#ToBuyStep2').show();
				$('#jia').hide();
				$('#jian').hide();
				$('#djq').attr("disabled",true);
				//alert(result.datas.address_api.offpay_hash);
				$('#offpay_hash').val(result.datas.address_api.offpay_hash);
				$('#offpay_hash_batch').val(result.datas.address_api.offpay_hash_batch);
			}
				});
	
	
	});
$('#ToBuyStep2').click(function(){  //提交订单入库并进行处理
     var goods_id = getQueryString('goods_id');
     var cart_id=goods_id+"|"+ $(".num").val();
	 window.location.href='/wap/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+$(".num").val();
	
	/* var flag  = $('#flag').val();
     var key = getCookie('key');
	 var pay_name =$('#pay_name').val();
     var offpay_hash=$('#offpay_hash').val();
     var offpay_hash_batch=$('#offpay_hash_batch').val();
	 var address_id=48980;
	 var invoice_id=249;
	 var vat_hash='C97qDbR0GMKvh40B5Y5fXjyWTOjzfbQRRup';

        /*var msg = '';
        for (var k in message) {
            msg += k + '|' + message[k] + ',';
        }
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_buy&op=buy_step2',
            data:{
                key:key,
               ifcart:'',
                cart_id:cart_id,
                address_id:address_id,
                vat_hash:vat_hash,
                offpay_hash:offpay_hash,
                offpay_hash_batch:offpay_hash_batch,
                pay_name:pay_name,
                invoice_id:invoice_id,
                voucher:'',
                pd_pay:0,
                password:'',
                fcode:'',
                rcb_pay:0,
                rpt:'',
                buyer_cardid:'',
                pay_message:'',
				flag:flag
                },
            dataType:'json',
            success: function(result){
				
				var ts=result.datas.mid;
				 if(ts=='144198'|| ts=='10585'){
				 $("#wxpay").show();
				   }else{
				$("#wxpay").hide();   
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
                $('#pdr_sn').val(result.datas.pay_sn);
                if (result.datas.payment_code == 'offline') {
                    window.location.href = WapSiteUrl + '/tmpl/member/order_list.html';
                } else {
                    delCookie('cart_count');
                    toPay(result.datas.pay_sn,'member_buy','pay');
                }
            }
        });
  

	
	
	$('#step1').show();
	$('#yundostep1').hide();
	$('.input-box').show();
    $('#xfstep1').show();
	$('#step2').hide();
	$('#step3').hide();
	$('#step4').hide(); */
	});
  function cclose(obj){
	  switch(obj){
		  case 1:
		  $('#step1').hide();
		  break;
		  case 2:
		  $('#step2').hide();
		  break;
		  case 3:
		  $('#step3').hide();
		  break;
		  case 4:
		  $('#step4').hide();
		  break;
		  case 5:
		  $('#step5').hide();
		  break;
		  }
	  }
	function nextit(obj){
		switch(obj){
			case 'ylwtz':
			$('#ylwtzimg').addClass('addit');
			$('#vbillimg').removeClass('addit');
			break;
			case 'vbill':
			$('.input-box').hide();
			$('#xfstep1').hide();
			$('#vbillimg').addClass('addit');
			$('#ylwtzimg').removeClass('addit');
			$('#xfstep1').hide();
			$('#step3').show();
			$('#step1').hide();
			break;
			}
		/*$('#step1').hide();
	    $('#step2').show();
	    $('#step3').hide();
	    $('#step4').hide();*/
		}
   $(".power").click(function(){
     if(!$('#usePOpay1').is(':checked')) {
         $('#xfstep1').hide();
		 $('#yundostep1').show();
		 $('#step3').hide();
		 $('#pay_name').val('在线支付');
		
	}else{
		 $('#pay_name').val('online');
		$('#xfstep1').show();
	    $('#yundostep1').hide();
		}
    });
	function tianjia(){
		$('.input-box').hide();
		$('#step3').hide();
		$('#step2').show();
		$('#step1').hide();
		}
	function backstep(){
		    $('#vbillimg').addClass('addit');
			$('#ylwtzimg').removeClass('addit');
			$('#xfstep1').hide();
			$('#step3').show();
			$('#step2').hide();
			
		}
	
	$('#dm_payc8').click(function(){
		
	   $('.input-box').hide();
	        $('#step1').hide();
			$('#xfstep1').hide();
			$('#vbillimg').addClass('addit');
			$('#ylwtzimg').removeClass('addit');
			$('#xfstep1').hide();
			$('#step3').hide();
			$('#step5').show();
	});
	function jyselect(obj){
		
  
    
		
	}
	 //加数量
		 
             function jiafun(){
                    var num = $(".num").val();
                    var goods_price = parseInt($(".price").val());
                    var yundou = parseInt($(".yundou1").val());
                    num =num*1 +1;
                    goods_price = goods_price*num;
                    yundou = yundou*num;
                    $(".num").val(num);
                    $(".xiaoji").text(goods_price);
                    $(".goods_price").text(goods_price);
                    $(".yundou").text(yundou);
                    $("#total").val(goods_price);
				     $(".kyye").text($("#total").val());

                };

                //减数量
              function jianfun(){
                    var num = $(".num").val();
                    var goods_price = parseInt($(".price").val());
                    var yundou = parseInt($(".yundou1").val());
                    if(num ==1){
                      num = 1;
                    }else{
                      num =num*1 -1;
                    }
                    goods_price = goods_price*num;
                    yundou = yundou*num;
                    $(".num").val(num);
                    $(".xiaoji").text(goods_price);
                    $(".goods_price").text(goods_price);
                    $(".yundou").text(yundou);
					$("#total").val(goods_price);
                    $(".kyye").text($("#total").val());
                };
	
	    