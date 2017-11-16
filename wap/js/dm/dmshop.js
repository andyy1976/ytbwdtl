// JavaScript Document
$(function(){
	var key = getCookie('key');
	var city = getCookie('city');
	var lng = getCookie('lng');
	var lat = getCookie('lat');
        if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
		}else{
		   $.ajax({ 
		     type:'get',
			 url:ApiUrl+"/index.php?act=index&op=rec",     //获取焦点图片
			 data:{key:key,rec_id:9},    //修改广告时改变rec_id的值即可
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
		  var class1 = '美食';
		  var class2 = '酒店住宿';
		  var class3 = '休闲娱乐';
		  var class4 = '丽人';
		  var class5 = '生活服务';
		  $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=dmstoreclass",  //获取分类及链接
			  data:{key:key,class1:class1,class2:class2,class3:class3,class4:class4,class5:class5},
			  dataType:"json",
			  success: function(result){
			   var data = result.datas;
			   var  html='<ul><li><a href="allindex.html?store_class_id='+data[0].sc_id+'"><img src="/wap/dmtmpl/img/dm_m1.png"/><span>美食</span></a></li><li><a href="allindex.html?store_class_id='+data[1].sc_id+'"><img src="/wap/dmtmpl/img/dm_m2.png"/><span>酒店住宿</span></a></li><li><a href="allindex.html?store_class_id='+data[2].sc_id+'"><img src="/wap/dmtmpl/img/dm_m3.png"/><span>休闲娱乐</span></a></li><li><a href="allindex.html?store_class_id='+ data[3].sc_id+'"><img src="/wap/dmtmpl/img/dm_m4.png"/><span>丽人</span></a></li><li><a href="allindex.html?store_class_id='+ data[4].sc_id+'"><img src="/wap/dmtmpl/img/dm_m5.png"/><span>生活服务</span></a></li></ul>';
					$('#dm_meun').html(html);
				  }
			  
			  });  //获取分类
		if(!city){
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
		}else {
			alert('failed'+this.getStatus());
		}        
	    },{enableHighAccuracy: true});
		
		}else{
	     $('#city').val(city);
		 $('#lng').val(lng);
		 $('#lat').val(lat);
		 $('#demo').html(city);
			}
		var latt = $('#lat').val();
		var lngg = $('#lng').val();
		 $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=guessgoods",  //获取猜你喜欢的产品
			  data:{key:key,latt:latt,lngg:lngg},
			  dataType:"json",
			  success: function(result){
				  var data = result.datas;
				      if(data.length>0){
					   var html = template.render('member-evaluation-script', result);
                       }else{
					 var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					   } 
					  $("#member-evaluation-div").html(html);
				  }
			 
			 });
		
		}
		
});
	 
	function itCheck(){
		$('#topmeun').show();
		}
	function seacheOp(){
		window.location.href='dmsearch.html';
		}
	   
	
	
