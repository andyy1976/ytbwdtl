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
				    if(result.datas.member_info.member_predeposit==0 || result.datas.member_info.member_points==0){
				    alert('您的积分余额和充值余额不足！请先充值最扫描支付！');
					window.location.href= WapSiteUrl + '/tmpl/member/rechargecard_add.html';
                      }else{
				    $('#kyye').html(result.datas.member_info.member_predeposit);
					$('#kyyee').val(result.datas.member_info.member_predeposit);
					$('#custom_money').html(result.datas.member_info.custom_money);
					$('#custom_point').html(result.datas.member_info.custom_point);
					$('#points').html(result.datas.member_info.points);
					$('#pdr_store_id').val(result.datas.member_info.store_id);
					$('#pdr_st_shop').val(result.datas.member_info.pdr_st_shop);
					$('#flag').val(result.datas.member_info.flag);
					}
				}
			});
		}
    $('#yd_btn').click(function(){
		var yd_name = $('#yd_name').val();
		var kyyee = $('#kyyee').val();
		var yd_password = $('#yd_password').val();
		var map_id = $('#map_id').val();
		var pdr_store_id = $('#pdr_store_id').val();
		var pdr_st_shop = $('#pdr_st_shop').val();
		var flag = $('#flag').val();
		if(yd_name==''){
			alert('请输入消费金额！');
			return;
		}
		if(kyyee<yd_name){
		   alert('您的充值余额不足！请先充值最扫描支付！');
			window.location.href= WapSiteUrl + '/tmpl/member/rechargecard_add.html';
		}
		if(yd_password==''){
			alert('支付密码不能为空！');
			return;
		}
		$.ajax({      //将数据入库并检测积分
			type:'post',
			url:ApiUrl+"/index.php?act=member_index&op=ruku",
		    data:{key:key,map_id:map_id,yd_name:yd_name,yd_password:yd_password,pdr_store_id:pdr_store_id,pdr_st_shop:pdr_st_shop,flag:flag},
			dataType:'json',
            success:function(result){
				switch(result){
					case 1:
					$('#tip').html('请输入消费金额.');
					break;
					case 2:
					$('#tip').html('请输入支付密码.');
					break;
					case 3:
					$('#tip').html('您输入的支付密码不正确.');
					break;
					case 4:
					$('#tip').html('您的充值金额余额不足，请充值.');
					break;
					case 5:
					$('#tip').html('您的积分不足以平台扣除，请充值.');
					break;
					case 6:
					$('#tip').html('您的支付已经完成.');
					break;
					case 7:
					$('#tip').html('您的支付已经完成，请不要重复提交订单.');
					break;
					case 8:
					$('#tip').html('商家积分余额不够,请商家充值.');
					break;
					
				  }
				}
			  })
		
		})
    
});
