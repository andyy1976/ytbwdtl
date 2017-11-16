// JavaScript Document
$(function(){
    var key = getCookie('key');
	var city = getCookie('city');
	var lng = getCookie('lng');
	var lat = getCookie('lat');
	var store_id = getQueryString('store_id');
	$('#store_id').val(store_id);
	if(!store_id){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmxxsc.html';
		}
	  if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
	}else{
	   $.getJSON(ApiUrl + '/index.php?act=dmstore&op=dmstore_details',{key:key,store_id:store_id,city:city}, function(result) {
		   var data = result.datas;
		   $('#dpn').html(data['store_name']);
		   $('#store_id').val(store_id);
		   });
		   $.getJSON(ApiUrl + '/index.php?act=dmstore&op=dmstore_voucher',{key:key,store_id:store_id}, function(result) {
		   var data = result.datas;
		    if(data.length>0){
					   var html = template.render('yd_vochre-script', result);
                       }else{
						var html='<option value=0>暂无代金券</option>';
						   }
				 $("#yd_vochre").html(html);
				 
			
						   
		   /*$('#yd_vochre').val(data);
		   if(data!=0){
			 
			for(var i=0;i<data.length;i++){
			html="<option value="+data[i].voucher_price+">"+data[i].voucher_price+"</option>";
			$('#yd_vochre').append(html);
			
				   }
				
		   }
				*/
				
		 });
	}
	 $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_index",
			data:{key:key,map_id:store_id},
			dataType:'json',
            success:function(result){
				$('#yd_store_name').val(result.datas.member_info.store_name);
				$('#pay_sn').val(result.datas.member_info.pay_sn);
				$('#order_sn').val(result.datas.member_info.order_sn);
				$('#member_id').val(result.datas.member_info.user_id);
				}
			});
	  
	});
$('.dm_btn').click(function(){
	var yd_nameat=$('#yd_nameat').val();
    var yd_vochre=$('#yd_vochre').val();
    var yd_name=$('#yd_name').val();
	var yd_store_name = $('#yd_store_name').val();
	var pay_sn =$('#pay_sn').val();
	var order_sn  = $('#order_sn').val();
	var store_id = $('#store_id').val();
	var member_id = $('#member_id').val();
	if(yd_nameat==''){
		 errorHtml='消费金额不能为空';
		 errorTipsShow(errorHtml);
		 return false;
		}else if(yd_name==''){
		  errorHtml='实付金额不能为空';
		  errorTipsShow(errorHtml);
		 return false;
	   }else{
		 
		$('#maidan_box').center();
        $('#goodcover').show();
        $('#maidan_box').fadeIn();
		$('#price').html(yd_name);
			 
			 }
	
	})
function fzsh(){
	var dt  =$('#yd_nameat').val();
	$('#yd_name').val(dt);
	var yd_nameat = dt;
	}
	$('#yd_vochre').change(function(){
		var key = getCookie('key');
		var city = getCookie('city');
		var store_id = $('#store_id').val();
		var yd_nameat = $('#yd_nameat').val();
		   $.getJSON(ApiUrl + '/index.php?act=dmstore&op=dmstore_voucher',{key:key,store_id:store_id,yd_nameat:yd_nameat}, function(result) {
		   var data = result.datas;
		   if(data==1){
			  $('#yd_name').val($('#yd_nameat').val()-$('#yd_vochre').val()); 
			   }
		if(data==0){
				  errorHtml='代金券不可用，因为您的消费额度没有达到商家要求！';
		  errorTipsShow(errorHtml);
		 return false;  
				   }
		   
		   });
		});
	$('.maidan_03').click(function(){
    var key = getCookie('key');
    var yd_name=$('#yd_name').val();
	var yd_store_name = $('#yd_store_name').val();
	var pay_sn =$('#pay_sn').val();
	var order_sn  = $('#order_sn').val();
	var store_id = $('#store_id').val();
	var member_id = $('#member_id').val();
			$.ajax({      //将数据入库并检测积分
			type:'post',
			url:ApiUrl+"/index.php?act=member_index&op=dmorder_exit",
		    data:{key:key,map_id:store_id,yd_name:yd_name,yd_store_name:yd_store_name,pay_sn:pay_sn,order_sn:order_sn,member_id:member_id,flag:1},
			dataType:'json',
            success:function(result){
				
				  switch(result){
					case 2:
					window.alert('支付提交成功！即将跳转到支付页！');
					window.location.href = WapSiteUrl+'/tmpl/member/order_listdm.html?data-state=state_new&map_id='+store_id;
					break;
					case 3:
					window.alert('订单已经存在！');
					case 1:
					window.alert('支付提交失败！');
					break;
					}
				
			  }
		
		});
		})