$(function(){
	var map_id = getQueryString('map_id');
	var key = getCookie('key');
	var lat = getCookie('lat');
	var lng = getCookie('lng');
	if(key!='' && map_id!=''){
		   $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=dimian_store&op=dimiandetails",
			data:{key:key,map_id:map_id,lat:lat,lng:lng},
			dataType:'json',
            //jsonp:'callback',
            success:function(result){
				var data = result;
			    data.WapSiteUrl = WapSiteUrl;//页面地址
				data.ApiUrl = ApiUrl;
			    data.key = getCookie('key');
			   var html = template.render('order-list-tmpl', data);
			   $("#seller_body").append(html);
				}
		  
		   });
		  }

	});
