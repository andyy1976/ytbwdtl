// JavaScript Document
$(function(){
	var key = getCookie('key');
	var store_class_id = getQueryString('store_class_id');
	var store_classsj_id = getQueryString('store_classsj_id');
	var lng = getCookie('lng', lng);
	var lat = getCookie('lat', lat);
	var city = getCookie('city',city);
	if(!key){
	    window.location.href = WapSiteUrl+'/tmpl/member/login.html';	
		}
	if(!lng||!city){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmcity.html';
		}
	 if(store_class_id){
		addCookie('store_class_id',store_class_id);
		}
	if(store_classsj_id){
		addCookie('store_classsj_id',store_classsj_id);
		}else{
		window.location.href = WapSiteUrl+'/tmpl/dm/dmxxsc.html';	
	   }
	//获取第一个分类
	$.ajax({
		   type:'post',
		   url:ApiUrl+"/index.php?act=dmallindex&op=getSingleClassName",
		   data:{key:key,city:city,store_classsj_id:store_classsj_id},
		   dataType:"json",
		   success: function(result){
			   var data = result.datas;
			   $('#subclass').html(result.datas.gc_name);
			   }
		});
    //获取所有分类
	$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=dmallindex&op=getAllClassName",
			data:{key:key,city:city,store_class_id:store_class_id,store_classsj_id:store_classsj_id},
			dataType:"json",
			success: function(result){
				var data = result.datas;
				if(data==null){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无分类<div>';
					}else{
					var html = template.render('allsubclass-script',result);		
						}
				$('#allsubclass').html(html);
				}
			});  //获取所有二级分类
				
	$.ajax({   //获取产品
	    type:'post',
		url:ApiUrl+"/index.php?act=allindex&op=getsingleClassProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_classsj_id:store_classsj_id,store_class_id:store_class_id},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	
	
	});
$("#dm_site").click(function(){   //刷新当前页面
	 location.reload();
	});
function changit(obj){         //根据ID获取类名称
    var key = getCookie('key');
    var lng = getCookie('lng', lng);
	var lat = getCookie('lat', lat);
	var city = getCookie('city',city);
	var store_class_id=getCookie('store_class_id');
	$.ajax({  //给banner赋值
		  type:'post',
		  url:ApiUrl+'/index.php?act=dmallindex&op=getById',
		  data:{key:key,obj:obj},
		  dataType:"json",
		  success: function(result){
			  $('#subclass').html(result.datas.gc_name);
			  /*根据分类选取ID值5个分类*/
			  }
		
		});
	/*改变下面的值*/
     $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=allindex&op=getsingleClassProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_classsj_id:obj},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	
	  $('#subclass_nr').fadeOut();
	  $('#goodcover').hide();
}
function  distance(obj){      //根据距离排序
	  var key = getCookie('key');
      var lng = getCookie('lng', lng);
	  var lat = getCookie('lat', lat);
	  var city = getCookie('city',city);
	  var store_classsj_id=getCookie('store_classsj_id');
      if(obj==1){$('#nearby').html('1km');}
	  if(obj==3){$('#nearby').html('3km');}
	  if(obj==5){$('#nearby').html('5km');}
	  if(obj==10){$('#nearby').html('10km');}
	  if(obj==0){$('#nearby').html('全城');}
	  var nextobj = [1, 3, 5, 10,0]; 
	  for(var i=0;i<nextobj.length;i++) {
      if(obj==nextobj[i]){
		$("#dis"+nextobj[i]).addClass("filter_on");
	  }else{
		$("#dis"+nextobj[i]).removeClass("filter_on");	 
			}
         } 
		 /*改变下面的值*/
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=allindex&op=getsingleClassProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,dobj:obj,store_classsj_id:store_classsj_id},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	
	  $('#subclass_nr').fadeOut();
	  $('#goodcover').hide();
	  $('#nearby_nr').fadeOut();
	  $('#goodcover').hide();
	
	}
function sortit(obj){
	     var key = getCookie('key');
         var lng = getCookie('lng', lng);
	     var lat = getCookie('lat', lat);
	     var city = getCookie('city',city);
	     var store_classsj_id=getCookie('store_classsj_id');
		 if(obj==1){$('#mind').html('智能排序');}
		 if(obj==2){$('#mind').html('离我最近');}
		 if(obj==3){$('#mind').html('好评优先');}
		 if(obj==4){$('#mind').html('人气最高');}
		 var nextobj = [1, 2, 3, 4]; 
		 for(var i=0;i<nextobj.length;i++) {
         if(obj==nextobj[i]){
			 $("#disb"+nextobj[i]).addClass("filter_on");
			 }else{
			 $("#disb"+nextobj[i]).removeClass("filter_on");	 
			}
         } 
		  /*改变下面的值*/
		   /*改变下面的值*/
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=allindex&op=getsingleClassProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,sobj:obj,store_classsj_id:store_classsj_id},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
		 $('#mind_nr').fadeOut();
		 $('#goodcover').hide();
	
	}