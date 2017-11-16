// JavaScript Document
$(function(){
	var key = getCookie('key');
	
	var flag = getQueryString('flag');                            //识别是一级ID还是二级ID
	var lng = getCookie('lng', lng);
	var lat = getCookie('lat', lat);
	var city = getCookie('city',city);
    if(!lng||!lat||!city){
		window.location.href= WapSiteUrl+'/tmpl/dm/dmcity.html';
		}
	if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
	 }else{
	var store_classsj_id = getQueryString('store_classsj_id');   //三级分类ID
	var store_class_id = getQueryString('store_class_id');       //获取二级分类ID
	//alert(store_classsj_id);
	/*var map = new BMap.Map("allmap");
	var point = new BMap.Point(lng,lat);
	map.centerAndZoom(point,12);
	var geoc = new BMap.Geocoder();    

	      
		var pt = new BMap.Point(lng,lat);
		geoc.getLocation(pt, function(rs){
			var addComp = rs.addressComponents;
			alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
		});   */     
	
		  //$('')
		  	$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=dmshop&op=goods2",
				data:{key:key,store_class_id:store_class_id,lng:lng,lat:lat,city:city},
				dataType:"json",
				success: function(result){
					var data = result.datas;
                                        if(data==null){
                                         var html = '<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
                                        }else{

					var html = template.render('bottomp-script',result);
                                         }
					$('#bottomp').html(html);
					}
				});
		    $.ajax({   //获取首条文字
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=dmgetclassname",
			   data:{key:key,obj:store_classsj_id},
			   dataType:"json",
			   success: function(result){
				   var data = result.datas;
				   $('#subclass').html(data.gc_name);
				   }
				});
		     $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=dmgoodsspclassthree",  //获取分类及链接
			 data:{key:key,store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
				  var data = result.datas;
				  var html = template.render('ejmenu-script', result);
				  $('#subclass_nr').html(html);
				  }
			  
			  });
		
	
 
}
});
function changit(obj){         //根据ID获取类名称
        if(obj==0){
		 $('#subclass').html('全部');
		}else{
	var key = getCookie('key');
	 $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=dmgetclassname", //获取分类及链接
			  data:{key:key,obj:obj},
			  dataType:"json",
			  success: function(result){
			  $('#subclass').html(result.datas.gc_name);
               }
			 });
	 }
	  $('#subclass_nr').fadeOut();
	  $('#goodcover').hide();
}
function distance(obj){
	switch(obj){
		case 1:
		$('#nearby').html('1km');
		break;
		case 3:
		$('#nearby').html('3km');
		break;
		case 5:
		$('#nearby').html('5km');
		break;
		case 10:
		$('#nearby').html('10km');
		break;
		case 9:
		$('#nearby').html('全城');
		}
	 store_class_id=getCookie('store_class_id');
	 var key = getCookie('key');
	 var lng = getCookie('lng', lng);
	 var lat = getCookie('lat', lat);
	 var city = getCookie('city',city);
	$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=dmshop&op=goods2dis",
				data:{key:key,store_class_id:store_class_id,lng:lng,lat:lat,obj:obj,city:city},
				dataType:"json",
				success: function(result){
					var data = result.datas;
					if(data==null){
						var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
						}else{
					var html = template.render('bottomp-script',result);
						}
					$('#bottomp').html(html);
					}
				});
			$('#nearby_nr').hide();
	
	}
function sortit(obj){
	switch(obj){
		case 1:
		$('#mind').html('智能排序');
		break;
		case 2:
		$('#mind').html('离我最近');
		break;
		case 3:
		$('#mind').html('好评优先');
		break;
		case 4:
		$('#mind').html('人气最高');
		break;
		}
	 store_class_id=getCookie('store_class_id');
	 var key = getCookie('key');
	 var lng = getCookie('lng', lng);
	 var lat = getCookie('lat', lat);
	 var city = getCookie('city',city);
	  $.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=dmshop&op=goods3sort",
				data:{key:key,store_class_id:store_class_id,lng:lng,lat:lat,obj:obj,city:city},
				dataType:"json",
				success: function(result){
					var data = result.datas;
					if(data==null){
						var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
						}else{
					var html = template.render('bottomp-script',result);
						}
					$('#bottomp').html(html);
					}
				});
			$('#mind_nr').hide();
	
	}

function seacheOp(){
		window.location.href='dmsearch.html';
		}