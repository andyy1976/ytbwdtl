// JavaScript Document
$(function(){
	var key = getCookie('key');
	var goods_id =  getQueryString('goods_id');
	 if(!key){
	   window.location.href = WapSiteUrl+'/tmpl/dm/dmcity.html';
	   }
	if(!goods_id){
	  window.location.href=WapSiteUrl+'/tmpl/dm/dmxxsc.html';
		}else{
			$('#ljspxq').html('<a href=dmgoods_details.html?goods_id='+goods_id+'>商品详情</a>');
			$.ajax({
		 url:ApiUrl+"/index.php?act=dmgoodsdetails&op=goods_detail",
         type:"post",
         data:{goods_id:goods_id,key:key},
         dataType:"json",
         success:function(result){
			 // $('#lj_b').html('<img src="'+result.datas.goods_image_url+'"/>');
			  var html = template.render('allmessage-script', result);
			  $("#allmessage").html(html);
			 }
			 });
		$.ajax({
		 url:ApiUrl+"/index.php?act=dmgoodsdetails&op=tuijian_detail",
		 type:"post",
		 data:{key:key,goods_id:goods_id},
		 dataType:"json",
		 success: function(result){
		 var html = template.render('otherdz-script',result);  
			$("#otherdz").html(html);
		 }
				 });
		}
});