// JavaScript Document
$(function(){
	var key = getCookie('key');
        if(!key){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
		}else{
			 $.ajax({
		     type:'get',
			 url:ApiUrl+"/index.php?act=index&op=rec",     //获取焦点图片
			 data:{key:key,rec_id:6},
			 dataType:'json',
			 success: function(result){
				 var data = result.datas;
				 if(data){
				for(var i=0;i<data.body.length;i++){
		    var jsrs="<li>"+i+"</li>";
            var  src="<li>"+"<a href='"+data.body[i]['url']+"'><img src='/data/upload/"+data.body[i]['title']+"' /></a>"+"</li>";
			$(".ul-main").append(src);
	
				}
				TouchSlide({ 
slideCell:"#slideBox",
titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
mainCell:".bd ul", 
effect:"leftLoop", //左循环滚动
autoPlay:true,//自动播放
autoPage:true, //自动分页
});
          

			
			 }
				 }
		  });
	
		var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
		   var city =r.address.city;
		   var district=r.address.district;
           var  province = r.address.province;
           var street = r.address.street;
		   var lng = r.point.lng;
		   var lat = r.point.lat;
		   if(city!=''){
			   $('#demo').html(city);
			   $('#city').val(city);
			   }
		   if(district!=''){
				$('#district').val(district);
				}
		   if(province!=''){
				$('#province').val(province);
				}
			if(street!=''){
				$('#addr').val(street);
				}
			 addCookie('lat', lat);
			 addCookie('lng', lng);
			 $('#lat').val(lat);
			 $('#lng').val(lng);
		   
		}
		else {
			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})
		  $.ajax({
		     type:'get',
			 url:ApiUrl+"/index.php?act=dmshop&op=pic",     //获取焦点图片
			 data:{key:key},
			 dataType:'json',
			 success: function(result){
				  checkLogin(result.login); 
			 }
		  })
		}
	
	});
	   
	   
	
	
