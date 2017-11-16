$(function() {
	var map_id = getQueryString('map_id');
	if(map_id!=''){
	$('#map_id').val(map_id);
	
	}
    var key = getCookie('key');
    if (!key) {
		if(map_id==''){
        window.location.href = WapSiteUrl+'/tmpl/member/member.html';
		}else{
	    window.location.href = WapSiteUrl+'/tmpl/member/login.html?map_id='+map_id;
			}
        return;
    }else{
	     $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_index",
			data:{key:key,map_id:map_id},
			dataType:'json',
            success:function(result){
				$('#yd_store_name').val(result.datas.member_info.store_name);
				$('#pay_sn').val(result.datas.member_info.pay_sn);
				$('#order_sn').val(result.datas.member_info.order_sn);
				$('#member_id').val(result.datas.member_info.user_id);
				}
			});
		}
    $('#yd_btn').click(function(){
		var yd_name = $('#yd_name').val();
		var yd_store_name = $('#yd_store_name').val();
		var map_id = $('#map_id').val();
		var pay_sn = $('#pay_sn').val();
		var order_sn = $('#order_sn').val();
		var member_id = $('#member_id').val();
		if(yd_name==''){
			alert('请输入消费金额！');
			return;
		}
		$.ajax({      //将数据入库并检测积分
			type:'post',
			url:ApiUrl+"/index.php?act=member_index&op=dmorder_exit",
		    data:{key:key,map_id:map_id,yd_name:yd_name,yd_store_name:yd_store_name,pay_sn:pay_sn,order_sn:order_sn,member_id:member_id},
			dataType:'json',
            success:function(result){
				switch(result){
					case 2:
					window.alert('支付提交成功！即将跳转到支付页！');
					window.location.href = WapSiteUrl+'/tmpl/member/order_listdm.html?data-state=state_new&map_id='+map_id;
					break;
					case 3:
					window.alert('订单已经存在！');
					case 1:
					window.alert('支付提交失败！');
					break;
					}
				  
				}
			  })
		
		})
    
});
