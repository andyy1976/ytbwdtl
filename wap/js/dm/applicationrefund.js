var order_id,order_goods_id,goods_pay_price;
$(function(){
	var key = getCookie('key');
	if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
	}else{
		$('#key').val(getCookie('key'));
		var order_id = getQueryString('order_id');
		var order_goods_id=getQueryString('order_goods_id');
		$('#order_id').val(getQueryString('order_id'));
		$('#order_goods_id').val(getQueryString('order_goods_id'));
		 $.getJSON(ApiUrl + '/index.php?act=applicationrefund&op=refund_form',{key:key,order_id:order_id,order_goods_id:order_goods_id}, function(result) {
		  $('#refund').html('现金：'+result.datas.order.order_amount+'元');
		  $('#order_amount').val(result.datas.order.order_amount);
		  $('#goods_num').val(result.datas.goods.goods_num);
		 
			  for(var i = 0; i<result.datas.reason_list.length;i++){
		   var  src='<dd>'+result.datas.reason_list[i].reason_info+'<input type="radio" class="tk_dx" name="tk_dx_reason" value='+result.datas.reason_list[i].reason_id+'"></dd>';
		    $('#tkreason').append(src);
			 
		  }
			  });
		}
});
$('#tk_dx0').click(function(){
	window.location.href='/wap/tmpl/member/member.html';
	});
$('.dm_fanh').click(function(){
	var val=$("input[name='tk_dx_reason']:checked").val();
	//var buyer_message =$("input[name='tk_dx_reason']:checked").text();
	if(val==undefined){
		alert('请选择退款原因！');
		return false;
		}else{
	  var order_id=$('#order_id').val();
      var order_goods_id=$('#order_goods_id').val();
      var order_amount=$('#order_amount').val();
      var tk_dx_reason=val;
	  var key = $('#key').val();
	  var goods_num=$('#goods_num').val();
	  
	   // 退款申请提交
            $.ajax({
                type:'post',
                url:ApiUrl+'/index.php?act=applicationrefund&op=refund_post',
                data:{order_id:order_id,order_goods_id:order_goods_id,reason_id:tk_dx_reason,refund_amount:order_amount,goods_num:goods_num,key:key},
                dataType:'json',
                async:false,
                success:function(result){
                    checkLogin(result.login);
                    if (result.datas.error) {
                       
                          
                           alert(result.datas.error);
                           
                      
                        return false;
                    }
                    window.location.href = WapSiteUrl + '/tmpl/member/member_refund.html';
                }
            });
			}
	});