$(function(){
		var key = getCookie('key');
		if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
		}else{
		var city_id = getQueryString('cityid');
		if(city_id==''){
		var geolocation = new BMap.Geolocation();
	    geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
		   var city =r.address.city;
		   var district=r.address.district;
           var  province = r.address.province;
           var street = r.address.street;
		   var lng = r.point.lng;
		   var lat = r.point.lat;
		   if(province!=''){
			$('#provice').val(province.substring(2,0));
			}
		   if(city!=''){
			   addCookie('city', city);
			   var html = city+'全城';
			   $('#demo').html(html);
			   $('#city').val(city);
			   $.ajax({
				  type:'post',
				 url:ApiUrl+"/index.php?act=dmshop&op=ruku",
				 data:{key:key,city:city},
				 dataType:"json",
				 success: function(result){
					 
					 }   
				   });
			   }
			 addCookie('lat', lat);
			 addCookie('lng', lng);
			 addCookie('city',city);
			 $('#lat').val(lat);
			 $('#lng').val(lng);
	          var provice = $('#provice').val();
			  var city = $('#city').val();
	 $.ajax({    //获取城市区县
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=getcity",  
			  data:{key:key,provice:provice,city:city},
			  dataType:"json",
			  success: function(result){
				  var data =result.datas;
					for(var i=0; i < data.length; i++){
					  if(i==0){
				        html='<a onclick= "return beate('+data[i].area_id+')" class="qie_on">'+data[i].area_name+'</a>'
						}else{
				     	html='<a onclick= "return beate('+data[i].area_id+')" >'+data[i].area_name+'</a>'
						   }
							$('#city_qie').append(html);
						}
						
				  }
			 
			 });
		 $.ajax({    //获取热门城市
		      type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=gethotcity",
			  data:{key:key,provice:provice},
			  dataType:"json",
			  success: function(result){
				  var data = result.datas;
				  for(var i=0; i < data.length; i++){
						//html='<a href="dmxxsc.html?cityid='+data[i].area_id+'" onclick=return addc('+data[i].cityname+')>'+data[i].area_name+'</a>'
						html='<a onclick="return addc('+data[i].area_id+')">'+data[i].area_name+'</a>';
						
						  $('#hotcity').append(html);
						}
			      }
			 });
			  $.ajax({      //最近访问的城市
				  type:'post',
				  data:{key:key},
				  url:ApiUrl+"/index.php?act=dmshop&op=getrecentcity",
				  dataType:"json",
				  success: function(result){
					   var data = result.datas;
					  for(var i = 0; i<data.length; i++){
					  //html='<a href="dmxxsc.html?cityid='+data[i].area_id+'" id="ok'+i+'" onclick=return addc('+data[i].cityname+') >'+data[i].cityname+'</a>';
					  html='<a onclick="return addc('+data[i].area_id+')">'+data[i].cityname+'</a>';
					  $('#recentcity').append(html);
						  }
					  //$('#ok0').addClass("hot_on");
					  }
					 });    
			
			     $.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'A'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#A').append(html);
					 } 
			  }
				 });
	     	    $.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'B'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#B').append(html);
					 } 
			  
			  }
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'C'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#C').append(html);
					 } 
			  
			  }
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'D'},
			 dataType:"json", 
			  success: function(result){
               var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#D').append(html);
					 } 
			  
			  }				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'E'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#E').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'F'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#F').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'G'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#G').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'H'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#H').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'I'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#I').append(html);
					 } 
			  
			  }				
				 });
				$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'J'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#J').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'K'},
			 dataType:"json", 
			  success: function(result){
			 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#K').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'L'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#L').append(html);
					 } 
			  
			  }				
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'N'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#N').append(html);
					 } 
			  
			  }				
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'M'},
			 dataType:"json", 
			  success: function(result){
				   var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#M').append(html);
					 } 
			  
			  }				
			 
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'O'},
			 dataType:"json", 
			  success: function(result){
				   var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#O').append(html);
					 } 
			  
			  }				
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'P'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#P').append(html);
					 } 
			  
			  }				
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'Q'},
			 dataType:"json", 
			  success: function(result){
			 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#Q').append(html);
					 } 
			  
			  }			
				 });
		        		 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'U'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#U').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'V'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#V').append(html);
					 } 
			  
			  }			
			  
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'W'},
			 dataType:"json", 
			  success: function(result){
					 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#W').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'X'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#X').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'Y'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#Y').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'Z'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#Z').append(html);
					 } 
			  
			  }			
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'R'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#R').append(html);
					 } 
			  
			  }			
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'S'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#S').append(html);
					 } 
			  
			  }			
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'T'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#T').append(html);
					 } 
			  
			  }			
				 });
				   	 
			 
		   }
		else {
			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})}else{ //根据ID虚拟定位城市
		 $('#cityid').val(city_id);
		 var city_id = $('#cityid').val();
		  $.ajax({      
				  type:'post',
				  data:{key:key,city_id:city_id},
				  url:ApiUrl+"/index.php?act=dmshop&op=getvrcity",
				  dataType:"json",
				  success: function(result){
					  var data = result.datas.hotcity;
					  var dataq = result.datas.quxian;
					 $('#demo').html(result.datas.benshi.area_name+'全市');
					 $('#provice').val(result.datas.provice.area_name);
					 $('#city').val(result.datas.benshi.area_name);
					 addCookie('city', result.datas.benshi.area_name);
				  var city = $('#city').val(); 
				  $.ajax({
				  type:'post',
				  url:ApiUrl+"/index.php?act=dmshop&op=ruku",
				  data:{key:key,city:city},
				  dataType:"json",
				  success: function(result){}});
				  searchByStationName(); 
					 if(data.length>0){   //热门城市
						 for(var i=0; i < data.length; i++){
						//html='<a href="dmxxsc.html?cityid='+data[i].area_id+'" onclick=return addc('+data[i].area_name+')>'+data[i].area_name+'</a>';
						 html='<a onclick="return addc('+data[i].area_id+')">'+data[i].area_name+'</a>';
						  $('#hotcity').append(html);
						}
					}
					if(dataq.length>0){
						for(var i=0; i < dataq.length; i++){
					  if(i==0){
				        html='<a onclick= "return beate('+dataq[i].area_id+')" class="qie_on">'+dataq[i].area_name+'</a>';
						}else{
				     	html='<a onclick= "return beate('+dataq[i].area_id+')" >'+dataq[i].area_name+'</a>';
						   }
							$('#city_qie').append(html);
						}
						}
					
					  }
					 });
					  $.ajax({      //最近访问的城市
				  type:'post',
				  data:{key:key},
				  url:ApiUrl+"/index.php?act=dmshop&op=getrecentcity",
				  dataType:"json",
				  success: function(result){
					  var data = result.datas;
					  for(var i = 0; i<data.length; i++){
					  html='<a href="dmcity.html?cityid='+data[i].area_id+'" id="ok'+i+'" >'+data[i].cityname+'</a>';
					  $('#recentcity').append(html);
						  }
					  //$('#ok0').addClass("hot_on");
					  }
					 });    
					     $.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'A'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#A').append(html);
					 } 
			  }
				 });
	     	    $.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'B'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#B').append(html);
					 } 
			  
			  }
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'C'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 //html='<dd><a href="dmcity.html?cityid='+data[i].area_id+'">'+data[i].area_name+'</a></dd>';
					 $('#C').append(html);
					 } 
			  
			  }
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'D'},
			 dataType:"json", 
			  success: function(result){
               var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#D').append(html);
					 } 
			  
			  }				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'E'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#E').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'F'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#F').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'G'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#G').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'H'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#H').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'I'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#I').append(html);
					 } 
			  
			  }				
				 });
				$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'J'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#J').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'K'},
			 dataType:"json", 
			  success: function(result){
			 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#K').append(html);
					 } 
			  
			  }				
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'L'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#L').append(html);
					 } 
			  
			  }				
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'N'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#N').append(html);
					 } 
			  
			  }				
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'M'},
			 dataType:"json", 
			  success: function(result){
				   var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#M').append(html);
					 } 
			  
			  }				
			 
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'O'},
			 dataType:"json", 
			  success: function(result){
				   var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#O').append(html);
					 } 
			  
			  }				
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'P'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#P').append(html);
					 } 
			  
			  }				
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'Q'},
			 dataType:"json", 
			  success: function(result){
			 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#Q').append(html);
					 } 
			  
			  }			
				 });
		        		 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'U'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#U').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'V'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#V').append(html);
					 } 
			  
			  }			
			  
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'W'},
			 dataType:"json", 
			  success: function(result){
					 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#W').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'X'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#X').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'Y'},
			 dataType:"json", 
			  success: function(result){
				 var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#Y').append(html);
					 } 
			  
			  }			
				 });
				 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'Z'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#Z').append(html);
					 } 
			  
			  }			
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'R'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#R').append(html);
					 } 
			  
			  }			
				 });
					$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'S'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#S').append(html);
					 } 
			  
			  }			
				 });
			    	 	$.ajax({     //A城市跳动
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getchar",
			 data:{key:key,char:'T'},
			 dataType:"json", 
			  success: function(result){
				  var data = result.datas;
				 for(var i=0; i< data.length; i++){
					 html='<a onclick="return addc('+data[i].area_id+')"><dd>'+data[i].area_name+'</dd></a>';
					 $('#T').append(html);
					 } 
			  
			  }			
				 });  
					
		
		
		}
			
			}
			 
		
	         
				   
	
});
function Change(obj){
	      switch(obj){
			  case 'A':
			  var t = $('#A').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;
		      case 'B':
			  var t = $('#B').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;  
			  case 'C':
			  var t = $('#C').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;  
			  case 'D':
			  var t = $('#D').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break; 
			  case 'E':
			  var t = $('#E').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
		     case 'F':
			  var t = $('#F').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			  case 'G':
			  var t = $('#G').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			  case 'H':
			  var t = $('#H').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			   case 'I':
			  var t = $('#I').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break; 
			   case 'J':
			  var t = $('#J').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			   case 'K':
			  var t = $('#K').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;
			    case 'L':
			  var t = $('#L').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;        
			   case 'N':
			  var t = $('#N').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			   case 'M':
			  var t = $('#M').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			    case 'O':
			  var t = $('#O').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break; 
			    case 'P':
			  var t = $('#P').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;  
			    case 'Q':
			  var t = $('#Q').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			   case 'R':
			  var t = $('#R').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;    
			    case 'S':
			  var t = $('#S').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;  
			    case 'T':
			  var t = $('#T').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;     
			     case 'U':
			  var t = $('#U').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			   case 'V':
			  var t = $('#V').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			    case 'W':
			  var t = $('#W').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;   
			   case 'X':
			  var t = $('#X').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;  
			   case 'Y':
			  var t = $('#Y').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break; 
			  case 'Z':
			  var t = $('#Z').offset().top;//  获取需要跳转到标签的top值
              $("html,body").animate({ scrollTop: t}, 500); // 动态跳转到指定位置（数值越大滚动速度越慢）
              break;                                                                                    
		  }
		
	}
	function beate(obj){
        $('#city_qie a').on('click',function(){  
        $(this).siblings().removeClass('qie_on')  
        $(this).addClass('qie_on');
		addCookie('diqu',obj);
		//getQuXian(obj);
		}) ;
		}
		
	/*获取经度和纬度的代码*/	
	var map = new BMap.Map("container"); 
map.centerAndZoom("北京", 6); 

var localSearch= new BMap.LocalSearch (map, { 
renderOptions: { 
pageCapacity: 8, 
autoViewport: true, 
selectFirstResult: false 
} 
}); 

localSearch.enableAutoViewport(); 
function searchByStationName() 
{ 

var keyword = document.getElementById("city").value; 
localSearch.setSearchCompleteCallback(function(searchResult){ 
var poi = searchResult.getPoi(0); 
addCookie('lat', poi.point.lat);
addCookie('lng', poi.point.lng);
map.centerAndZoom(poi.point, 8); 
}); 
localSearch.search(keyword); 

} 

function searchPlace(){
	var key = getCookie('key');
	var palce = $('#palce').val();
	if(palce!=''){
	  $.ajax({     //查找是否有给城市
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getthiscity",
			 data:{key:key,char:palce},
			 dataType:"json", 
			  success: function(result){
				  if(result==1){
					  window.alert('没有找到这个城市，请重试！');
					  }
				  if(result.datas.area_id!=''){
					  window.location.href='dmcity.html?cityid='+result.datas.area_id;
					  }
				 
			  }
				 });
		}
	}
	function addc(obj){
			var key = getCookie('key');
		if(obj){
			  $.ajax({      //根据ID返回城市名
				  type:'post',
				  data:{key:key,b:obj},
				  url:ApiUrl+"/index.php?act=dmshop&op=getthiscity",
				  dataType:"json",
				  success: function(result){
					 addCookie('city',result.datas.area_name);
					  window.location.href='dmxxsc.html'
					  }
					 });    
			}
		
		//
		}
/*function getQuXian(obj){
	var key = getCookie('key');
	var b = 'quxian';
	if(obj){
		  $.ajax({     //查找是否有给城市
			 type:'post',
			 url:ApiUrl+"/index.php?act=dmshop&op=getthiscity",
			 data:{key:key,char:obj,b:b},
			 dataType:"json", 
			  success: function(result){
				  if(result.datas.area_name!=''){
					  addCookie('city',result.datas.area_name);
					  $('#demo').html(result.datas.area_name);
					  }
				 
			  }
				 });
		
		}
	
	}*/
