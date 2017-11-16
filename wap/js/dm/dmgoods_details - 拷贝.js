$(function(){
	var key = getCookie('key');
	var goods_id =  getQueryString('goods_id');
   if(!key){
	   window.location.href = WapSiteUrl+'/tmpl/dm/dmcity.html';
	   }
	if(!goods_id){
		window.location.href=WapSiteUrl+'/tmpl/dm/dmxxsc.html';
		}else{  //获取产品
		 $('#anniu').html('<!--<img src="/wap/dmtmpl/img/dm_j.png"/><u>随时退</u>--><img src="/wap/dmtmpl/img/dm_j.png"/><u>有效期内退</u><a href=dm_addBank.html?goods_id='+goods_id+'>立即购买</a>');
		 
			$.ajax({
			 url:ApiUrl+"/index.php?act=dmgoodsdetails&op=goods_detail",
         type:"post",
         data:{goods_id:goods_id,key:key},
         dataType:"json",
         success:function(result){
			 var data = result.datas.txtDanj;
				      if(data.length>0){
					   var html = template.render('sutitconetnt-scirpt', result);
                       }else{
					 var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无内容<div>';
					   } 
					  $("#sutitconetnt").html(html);
				  
			$('#dm_xq02').html('<img src="'+result.datas.goods_image_url+'"/>');
			var html='<li><span>'+result.datas.wzvalidate+'</span>'+result.datas.validate+'</li>'
                     +'<li><span>'+result.datas.wzusertime+'</span>'+result.datas.usertime+'</li>'
                     +'<li><span>'+result.datas.wzattationpeople+'</span>'+result.datas.attationpeople+'</li>'
                     +'<li><span>'+result.datas.wzotherfree+'</span>'+result.datas.otherfree+'</li>'
				     +'<li><span>'+result.datas.wzothercoupon+'</span>'+result.datas.othercoupon+'</li>'
					 +'<li><span>'+result.datas.wzotherglue+'</span>'+result.datas.otherglue+'</li>';
				 /* 	 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
			  var html='<dl><dt>'+result.datas.wzvalidate+'</dt><dd>'+result.datas.validate+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzusertime+'</dt><dd>'+result.datas.usertime+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzattationpeople+'</dt><dd>'+result.datas.attationpeople+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzotherfree+'</dt><dd>'+result.datas.otherfree+'</dd></dl>'
				     +'<dl><dt>'+result.datas.wzothercoupon+'</dt><dd>'+result.datas.othercoupon+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzotherglue+'</dt><dd>'+result.datas.otherglue+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
			   if(result.datas.ruzhutime=='' && result.datas.shopknow=='' &&result.datas.suitpepole=='' && result.datas.otherpeole=='' ){
					alert('1');
	                var html='<dl><dt>'+result.datas.wzvalidate+'</dt><dd>'+result.datas.validate+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzusertime+'</dt><dd>'+result.datas.usertime+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzattationpeople+'</dt><dd>'+result.datas.attationpeople+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzotherfree+'</dt><dd>'+result.datas.otherfree+'</dd></dl>'
				     +'<dl><dt>'+result.datas.wzothercoupon+'</dt><dd>'+result.datas.othercoupon+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzotherglue+'</dt><dd>'+result.datas.otherglue+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
					 }
			if(result.datas.ruzhutime==''&&result.datas.shopknow==''&&result.datas.otherpeole==''){
					alert('2');
                   var html='<dl><dt>'+result.datas.wzvalidate+'</dt><dd>'+result.datas.validate+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzusertime+'</dt><dd>'+result.datas.usertime+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzattationpeople+'</dt><dd>'+result.datas.attationpeople+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitpepole+'</dt><dd>'+result.datas.suitpepole+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzotherfree+'</dt><dd>'+result.datas.otherfree+'</dd></dl>'
				     +'<dl><dt>'+result.datas.wzothercoupon+'</dt><dd>'+result.datas.othercoupon+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzotherglue+'</dt><dd>'+result.datas.otherglue+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
                    }
			if(result.datas.suitpepole==''&&result.datas.otherfree==''&&result.datas.othercoupon==''&&result.datas.otherpeole=='') {
				alert('3');
		           var html='<dl><dt>'+result.datas.wzvalidate+'</dt><dd>'+result.datas.validate+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzusertime+'</dt><dd>'+result.datas.usertime+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzattationpeople+'</dt><dd>'+result.datas.attationpeople+'</dd></dl>'
				     +'<dl><dt>'+result.datas.wzruzhutime+'</dt><dd>'+result.datas.ruzhutime+'</dd></dl>'
					  +'<dl><dt>'+result.datas.wzshopknow+'</dt><dd>'+result.datas.shopknow+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzotherglue+'</dt><dd>'+result.datas.otherglue+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
		             }
			 if(result.datas.ruzhutime==''&&result.datas.shopknow==''){
				 alert('4');
					 var html='<dl><dt>'+result.datas.wzvalidate+'</dt><dd>'+result.datas.validate+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzusertime+'</dt><dd>'+result.datas.usertime+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzattationpeople+'</dt><dd>'+result.datas.attationpeople+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitpepole+'</dt><dd>'+result.datas.suitpepole+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzotherfree+'</dt><dd>'+result.datas.otherfree+'</dd></dl>'
				     +'<dl><dt>'+result.datas.wzothercoupon+'</dt><dd>'+result.datas.othercoupon+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzotherglue+'</dt><dd>'+result.datas.otherglue+'</dd></dl>'
					  +'<dl><dt>'+result.datas.wzotherpeole+'</dt><dd>'+result.datas.otherpeole+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
					 }
		    if(result.datas.ruzhutime==''&&result.datas.shopknow==''&&result.datas.otherpeole==''){
					 alert('5');
		          var html='<dl><dt>'+result.datas.wzvalidate+'</dt><dd>'+result.datas.validate+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzusertime+'</dt><dd>'+result.datas.usertime+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzattationpeople+'</dt><dd>'+result.datas.attationpeople+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitpepole+'</dt><dd>'+result.datas.suitpepole+'</dd></dl>'
                     +'<dl><dt>'+result.datas.wzotherfree+'</dt><dd>'+result.datas.otherfree+'</dd></dl>'
				     +'<dl><dt>'+result.datas.wzothercoupon+'</dt><dd>'+result.datas.othercoupon+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzotherglue+'</dt><dd>'+result.datas.otherglue+'</dd></dl>'
					 +'<dl><dt>'+result.datas.wzsuitcontent+'</dt><dd>'+result.datas.suitcontent+'</dd></dl>';
	   
	      }*/
	   
	   
	       $('#detailscontent').html(html);
		        $('#goods_name').html(result.datas.goods_name);
			    $('#goods_all').html('<td>'+result.datas.goods_name+'</td><td>'+result.datas.goods_price+'元</td><td>1</td><td>'+result.datas.goods_price+'元</td>');  //赋值价格
				$('#price').html(result.datas.goods_price);
				$('#goods_name').html(result.datas.goods_name);
				$('#ydz').html(result.datas.yundou);
			}
			});
		$.ajax({
			url:ApiUrl+"/index.php?act=dmgoodsdetails&op=getMember",
			type:"post",
			data:{key:key},
			dataType:"json",
			success: function(result){
				$('#sms').html('<a href="sms:'+result.datas.member_mobile+'"><img src="/wap/dmtmpl/img/dm_fx5.png"/><span>短信</span></a>');
				}
			});
		}
	});
	
	
	function copyToClipboard(text) {
		var goods_id =  getQueryString('goods_id');
		var text = WapSiteUrl+'/tmpl/dm/dmgoods_details.html?goods_id='+goods_id;
         window.prompt("确定复制?", text);

}