// JavaScript Document
$(function(){
	var key = getCookie('key');
	var store_class_id = getQueryString('store_class_id');
	var lng = getCookie('lng', lng);
	var lat = getCookie('lat', lat);
	var city = getCookie('city',city);
	if(!lng||!city){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmcity.html';
		}
	if(store_class_id!=''){
		 addCookie('store_class_id', store_class_id);
        }else{
				 store_class_id=getCookie('store_class_id');
				}
				
        if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
		
		}else{
			$.ajax({          //选出上面的产品3个
				type:'post',
				url:ApiUrl+"/index.php?act=dmshop&op=goods1",
				data:{key:key,store_class_id:store_class_id,lng:lng,lat:lat,city:city},
				dataType:"json",
				success: function(result){
					var data = result.datas;
					if(data==null){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
				});
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=dmshop&op=goods2",
				data:{key:key,store_class_id:store_class_id,lng:lng,lat:lat,city:city},
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
			
			 $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=dmgoodsspclassthree", //获取三级分类及链接
			  data:{key:key,store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
				  var data = result.datas;
				  var html = template.render('ejmenu-script', result);
				  $('#ejmenu').html(html);
				  }
			  
			  });
			 $.ajax({ 
		     type:'get',
			 url:ApiUrl+"/index.php?act=index&op=rec",     //获取焦点图片
			 data:{key:key,rec_id:9},
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
		   
		   $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmshop&op=dmgoodsclass",  //获取分类及链接
			  data:{key:key,class1:'甜点饮品',class2:'自助餐',class3:'小吃快餐',class4:'日韩料理',class5:'西餐',class6:'香锅烤鱼',class7:'粤港菜',class8:'其他美食',store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
			  var data = result.datas;
			  var  html='<ul><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[6].gc_parent_id+'&store_classsj_id='+data[6].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms01.png"/><span>粤港菜</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[4].gc_parent_id+'&store_classsj_id='+data[4].sc_id+'"><img src="/wap/dmtmpl/img/dm_ms02.png"/><span>西餐</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[1].gc_parent_id+'&store_classsj_id='+data[1].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms03.png"/><span>自助餐</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[2].gc_parent_id+'&store_classsj_id='+data[2].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms04.png"/><span>小吃快餐</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[3].gc_parent_id+'&store_classsj_id='+data[3].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms05.png"/><span>日韩料理</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[5].gc_parent_id+'&store_classsj_id='+data[5].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms06.png"/><span>香锅烤鱼</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[0].gc_parent_id+'&store_classsj_id='+data[0].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms07.png"/><span>甜点饮品</span></a></li><li><a href="dmxxsc_foods_drink.html?store_class_id='+data[7].gc_parent_id+'&store_classsj_id='+data[7].sc_id+'&flag=2"><img src="/wap/dmtmpl/img/dm_ms08.png"/><span>其他美食</span></a></li></ul>';
			 var htmll = '<ul><li><a onclick="return changit(0)"><span>全部</span><em>'+data.allcont+'</em></a></li><li><a onclick="return changit('+data[6].sc_id+')"><span>粤港菜</span><em>'+data[6].count+'</em></a></li><li><a  onclick="return changit('+data[4].sc_id+')"><span>西餐</span><em>'+data[4].count+'</em></a></li><li><a onclick="return changit('+data[1].sc_id+')"><span>自助餐</span><em>'+data[1].count+'</em></a></li><li><a onclick="return changit('+data[2].sc_id+')"><span>小吃快餐</span><em>'+data[2].count+'</em></a></li><li><a onclick="return changit('+data[3].sc_id+')"><span>日韩料理</span><em>'+data[3].count+'</em></a></li><li><a onclick="return changit('+data[5].sc_id+')"><span>香锅烤鱼</span><em>'+data[5].count+'</em></a></li><li><a onclick="return changit('+data[0].sc_id+')"><span>甜点饮品</span><em>'+data[0].count+'</em></a></li><li><a onclick="return changit('+data[7].sc_id+')"><span>其他美食</span><em>'+data[7].count+'</em></a></li></ul>';
			        $('#subclass_nr').html(htmll);
					$('#dm_meun').html(html);
					$('#subclass').html('粤港菜');
				  }
			  
			  });  //获取分类
		
		  
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
	  /*$.ajax({
		    type:'post',
			url:ApiUrl+"/index.php?act=dmshop&op=dmgoodsasc", //获取三级分类
			data:{key:key,obj:obj},
			dataType:"json",
			success: function(result){
				var html = template.render('ejmenu-script', result);
				  $('#ejmenu').html(html);
				}
		  });*/
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
				    if(result.datas==null){
						var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
						}else{
					var html = template.render('bottomp-script',result);
						}
					$('#bottomp').html(html);
					}
				});
				
				$.ajax({          //选出上面的产品3个
				type:'post',
				url:ApiUrl+"/index.php?act=dmshop&op=goods1dis",
				data:{key:key,store_class_id:store_class_id,lng:lng,lat:lat,city:city,obj:obj},
				dataType:"json",
				success: function(result){
					  if(result.datas==null){
							var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
						}else{
					var html = template.render('topp-script',result);
						}
					$('#topp').html(html);
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
					if(data.length==0){
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
	   	function itCheck(){
		$('#topmeun').show();
		}