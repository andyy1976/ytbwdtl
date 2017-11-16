// JavaScript Document
$(function(){
	var key = getCookie('key');
        if(!key){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
		}else{
		geolocation.getLocation(showPosition, showErr, options);       //获取地图
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
	    var geolocation = new qq.maps.Geolocation("HYNBZ-SHB3P-W52D5-L4GKP-V7TOF-JIBHS", "myapp");
         var positionNum = 0;
        var options = {timeout: 8000};
        function showPosition(position) {
			if(position){
				alert('here');
				}
			var nation=position["nation"];
			var province=position["province"];
			var city = position["city"];
			var district = position["district"];
			var addr = position["addr"];
			var lat= position["lat"];
            var lng= position["lng"];
			if(nation=='' || nation=='undefined' ){
				nation='';
				}else{
					$('#nation').val(nation);
					}
			if(province=='' || province=='undefined' ){
				province='';
				}else{
					$('#province').val(province);
					}
		     if(city=='' || city=='undefined' ){
				city='';
				}else{
					$('#city').val(city);
					}
			if(district=='' || district=='undefined' ){
				district='';
				}else{
					$('#district').val(district);
					}
			if(addr=='' || addr=='undefined' ){
				addr='';
				}else{
				$('#addr').val(addr);	
			}
			$('#demo').html(city);
			if(lat != "" && lng != ""){
				 addCookie('lat', lat);
				 addCookie('lng', lng);
				$('#lat').val(lat);
				$('#lng').val(lng);
				 $.ajax({
		     type:'post',
			 url:ApiUrl+"/index.php?act=dimian_store&op=shangjia_list",
			data:{lat:lat,lng:lng,key:key,city:city,district:district,addr:addr,province:province},
			dataType:'json',
               success: function(result){
                 checkLogin(result.login); 
				 if(result.datas.addresslist.length<=0){
					 $("#seller_body").html('<div style="text-align:center">没有找到相关的实体店铺</div>');
				}else{
					var data = result;
					data.WapSiteUrl = WapSiteUrl;//页面地址
				    data.ApiUrl = ApiUrl;
			        data.key = getCookie('key');
					var html = template.render('order-list-tmpl', data);
				    $("#seller_body").append(html);
						 } 
      
                  }
            });
				}
			
           
        };
 
 function showErr() {
            positionNum ++;
        };
 
        function showWatchPosition() {
            document.getElementById("demo").innerHTML += "开始监听位置！<br /><br />";
            geolocation.watchPosition(showPosition);
            document.getElementById("pos-area").scrollTop = document.getElementById("pos-area").scrollHeight;
        };
 
        function showClearWatch() {
            geolocation.clearWatch();
            document.getElementById("demo").innerHTML += "停止监听位置！<br /><br />";
            document.getElementById("pos-area").scrollTop = document.getElementById("pos-area").scrollHeight;
        };
function autoScroll(obj) {
       $(obj).find("ul").animate({
//marginTop: "-39px"
        }, 500, function() {
        $(this).css({
         marginTop: "0px"
         }).find("li:first").appendTo(this);
         })
    }
$(function() {
setInterval('autoScroll(".dm_banner")', 2000);
})
function scroll(){
        var top=document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
        var header=document.getElementById("dm_top");
        console.log(top);
       if(top>0){
        header.style.background="#eac22b";
        }else {
        header.style.background="transparent";
      }	
   }