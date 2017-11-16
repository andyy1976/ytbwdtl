$(function(){
	var key = getCookie('key');
	var goods_id =  getQueryString('goods_id');
   if(!key){
	   window.location.href = WapSiteUrl+'/tmpl/dm/dmcity.html';
	   }
	if(!goods_id){
		window.location.href=WapSiteUrl+'/tmpl/dm/dmxxsc.html';
		}else{  //获取产品
			$.ajax({
			 url:ApiUrl+"/index.php?act=dmgoodsdetails&op=getGoodsArray",
         type:"post",
         data:{goods_id:goods_id,key:key},
         dataType:"json",
         success:function(result){
			 $('goodsname').html(result.datas.goods_name);
			 $('#ydz').html(result.datas.yundou);
			 }
			});
		
		}
});