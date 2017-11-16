$(function(){
	var key = getCookie('key');
	var store_class_id = getQueryString('store_class_id');
	if(store_class_id){
		addCookie('store_class_id',store_class_id);
		}
	var lng = getCookie('lng', lng);
	var lat = getCookie('lat', lat);
	var city = getCookie('city',city);
	if(!key){
	window.location.href = WapSiteUrl+'/tmpl/member/login.html';	
		}
	if(!lng||!city){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmcity.html';
		}
	if(store_class_id!=''){
		 addCookie('store_class_id', store_class_id);
		 if(store_class_id=='10505'){    //美食添加菜单
			  $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmallindex&op=dmgoodsclass",  //获取分类及链接
			  data:{key:key,class1:'粤港菜',class2:'川湘味',class3:'自助餐',class4:'小吃快餐',class5:'日韩料理',class6:'烧烤烤肉',class7:'甜点饮品',class8:'其他美食',store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
		      var data = result.datas;
			  var html='<ul><li><a href="allindexdetails.html?store_class_id='+data[0].gc_parent_id+'&store_classsj_id='+data[0].classid+'"><img src="/wap/dmtmpl/img/dm_ms01.png"/><span>粤港菜</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[1].gc_parent_id+'&store_classsj_id='+data[1].classid+'"><img src="/wap/dmtmpl/img/dm_ms02.png"/><span>川湘味</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[2].gc_parent_id+'&store_classsj_id='+data[2].classid+'"><img src="/wap/dmtmpl/img/dm_ms03.png"/><span>自助餐</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[3].gc_parent_id+'&store_classsj_id='+data[3].classid+'"><img src="/wap/dmtmpl/img/dm_ms04.png"/><span>小吃快餐</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[4].gc_parent_id+'&store_classsj_id='+data[4].classid+'"><img src="/wap/dmtmpl/img/dm_ms05.png"/><span>日韩料理</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[5].gc_parent_id+'&store_classsj_id='+data[5].classid+'"><img src="/wap/dmtmpl/img/dm_ms06.png"/><span>烧烤烤肉</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[6].gc_parent_id+'&store_classsj_id='+data[6].classid+'"><img src="/wap/dmtmpl/img/dm_ms07.png"/><span>甜点饮品</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[7].gc_parent_id+'&store_classsj_id='+data[7].classid+'"><img src="/wap/dmtmpl/img/dm_ms08.png"/><span>其他美食</span></a></li></ul>'; 
			$('#dm_meun2').html(html); 
			  }
			  });  //获取分类
		   
			
			 }
		 if(store_class_id=='10494'){
			  $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmallindex&op=dmgoodsclass",  //获取分类及链接
			  data:{key:key,class1:'豪华型酒店',class2:'公寓型酒店',class3:'青年旅社',class4:'公寓民宿',class5:'品牌连锁酒店',class6:'主题酒店',class7:'精品酒店',class8:'其他酒店',store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
		      var data = result.datas;
			  var html='<ul><li><a href="allindexdetails.html?store_class_id='+data[0].gc_parent_id+'&store_classsj_id='+data[0].classid+'"><img src="/wap/dmtmpl/img/btn-5 star h@2x.png"/><span>豪华型酒店</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[1].gc_parent_id+'&store_classsj_id='+data[1].classid+'"><img src="/wap/dmtmpl/img/btn-apartment h@2x.png"/><span>公寓型酒店</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[2].gc_parent_id+'&store_classsj_id='+data[2].classid+'"><img src="/wap/dmtmpl/img/btn-youth h@2x.png"/><span>青年旅社</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[3].gc_parent_id+'&store_classsj_id='+data[3].classid+'"><img src="/wap/dmtmpl/img/btn-b and b h@2x.png"/><span>公寓民宿</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[4].gc_parent_id+'&store_classsj_id='+data[4].classid+'"><img src="/wap/dmtmpl/img/btn-brand h@2x.png"/><span>品牌连锁酒店</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[5].gc_parent_id+'&store_classsj_id='+data[5].classid+'"><img src="/wap/dmtmpl/img/btn-topic h@2x.png"/><span>主题酒店</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[6].gc_parent_id+'&store_classsj_id='+data[6].classid+'"><img src="/wap/dmtmpl/img/btn-boutique h@2x.png"/><span>精品酒店</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[7].gc_parent_id+'&store_classsj_id='+data[7].classid+'"><img src="/wap/dmtmpl/img/btn-more h@2x.png"/><span>其他</span></a></li></ul>'; 
			$('#dm_meun2').html(html);  
			  }
			  });  //获取分类
			 }
		 if(store_class_id=='10475'){
			  $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmallindex&op=dmgoodsclass",  //获取分类及链接
			  data:{key:key,class1:'KTV',class2:'运动健身',class3:'足疗按摩',class4:'洗浴/汗蒸',class5:'中医养生',class6:'棋牌室',class7:'酒吧',class8:'其他休闲娱乐',store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
		      var data = result.datas;
			  var html='<ul><li><a href="allindexdetails.html?store_class_id='+data[0].gc_parent_id+'&store_classsj_id='+data[0].classid+'"><img src="/wap/dmtmpl/img/btn-sports s@2x.png"/><span>运动健身</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[1].gc_parent_id+'&store_classsj_id='+data[1].classid+'"><img src="/wap/dmtmpl/img/btn-bath s@2x.png"/><span>洗浴汗蒸</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[2].gc_parent_id+'&store_classsj_id='+data[2].classid+'"><img src="/wap/dmtmpl/img/btn-foot massage s@2x.png"/><span>足疗按摩</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[3].gc_parent_id+'&store_classsj_id='+data[3].classid+'"><img src="/wap/dmtmpl/img/btn-CN medicine s@2x.png"/><span>中医养生</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[4].gc_parent_id+'&store_classsj_id='+data[4].classid+'"><img src="/wap/dmtmpl/img/btn-bar s@2x.png"/><span>酒吧</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[5].gc_parent_id+'&store_classsj_id='+data[5].classid+'"><img src="/wap/dmtmpl/img/btn-game room s@2x.png"/><span>棋牌室</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[6].gc_parent_id+'&store_classsj_id='+data[6].classid+'"><img src="/wap/dmtmpl/img/btn-other entertainment s@2x.png"/><span>其他休闲娱乐</span></a></li></ul>'; 
			$('#dm_meun2').html(html);  
			  }
			  });  //获取分类
			 }
		  if(store_class_id=='10429'){
			 $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmallindex&op=dmgoodsclass",  //获取分类及链接
			  data:{key:key,class1:'美发',class2:'美容美体',class3:'美甲美睫',class4:'其他丽人',store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
		      var data = result.datas;
			  var html='<ul><li><a href="allindexdetails.html?store_class_id='+data[0].gc_parent_id+'&store_classsj_id='+data[0].classid+'"><img src="/wap/dmtmpl/img/btn-hair s@2x.png"/><span>美发</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[1].gc_parent_id+'&store_classsj_id='+data[1].classid+'"><img src="/wap/dmtmpl/img/btn-face body s@2x.png"/><span>美容美体</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[2].gc_parent_id+'&store_classsj_id='+data[2].classid+'"><img src="/wap/dmtmpl/img/btn-nail s@2x.png"/><span>美甲美睫</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[3].gc_parent_id+'&store_classsj_id='+data[3].classid+'"><img src="/wap/dmtmpl/img/btn-more h copy@2x.png"/><span>其他丽人</span></a></li></ul>'; 
			$('#dm_meun2').html(html);  
			  }
			  });  //获取分类
			  }
		   if(store_class_id=='10432'){
			  $.ajax({
			  type:'post',
			  url:ApiUrl+"/index.php?act=dmallindex&op=dmgoodsclass",  //获取分类及链接
			  data:{key:key,class1:'汽车服务',class2:'鲜花',class3:'母婴亲子',class4:'家政服务',class5:'健康服务',class6:'摄影写真',class7:'宠物服务',class8:'其他生活',store_class_id:store_class_id},
			  dataType:"json",
			  success: function(result){
		      var data = result.datas;
			  var html='<ul><li><a href="allindexdetails.html?store_class_id='+data[0].gc_parent_id+'&store_classsj_id='+data[0].classid+'"><img src="/wap/dmtmpl/img/btn-car s@2x.png"/><span>汽车服务</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[1].gc_parent_id+'&store_classsj_id='+data[1].classid+'"><img src="/wap/dmtmpl/img/btn-flower s@2x.png"/><span>鲜花</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[2].gc_parent_id+'&store_classsj_id='+data[2].classid+'"><img src="/wap/dmtmpl/img/btn-mom kid s@2x.png"/><span>母婴亲子</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[3].gc_parent_id+'&store_classsj_id='+data[3].classid+'"><img src="/wap/dmtmpl/img/btn-house s@2x.png"/><span>家政服务</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[4].gc_parent_id+'&store_classsj_id='+data[4].classid+'"><img src="/wap/dmtmpl/img/btn-health s@2x.png"/><span>健康服务</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[5].gc_parent_id+'&store_classsj_id='+data[5].classid+'"><img src="/wap/dmtmpl/img/btn-photo s@2x.png"/><span>摄影写真</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[6].gc_parent_id+'&store_classsj_id='+data[6].classid+'"><img src="/wap/dmtmpl/img/btn-pet s@2x.png"/><span>宠物服务</span></a></li><li><a href="allindexdetails.html?store_class_id='+data[7].gc_parent_id+'&store_classsj_id='+data[7].classid+'"><img src="/wap/dmtmpl/img/btn-more life service@2x.png"/><span>其他生活</span></a></li></ul>'; 
			$('#dm_meun2').html(html);  
			  }
			});
			   }
        }else{
	    window.location.href = WapSiteUrl+'/tmpl/dm/dmxxsc.html';		
			}
	if(!key){
		window.location.href = WapSiteUrl+'/tmpl/dm/dmlogin.html';
		}else{
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
	 if(store_class_id=='10505'){    //美食添加菜单
     $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,class1:'火锅',class2:'西餐',class3:'东北菜',class4:'香锅烤鱼',class5:'中式烧烤/烤串',class6:'京菜鲁菜',class7:'东南亚菜',class8:'台湾/客家菜'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	    $.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=dmallindex&op=getAllClassName",
			data:{key:key,city:city,store_class_id:store_class_id},
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
	
	 }
	if(store_class_id=='10494'){    //酒店住宿
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,class1:'经济型酒店',class2:'舒适型酒店',class3:'度假酒店/度假村',class4:'客栈'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
		  $.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=dmallindex&op=getAllClassName",
			data:{key:key,city:city,store_class_id:store_class_id},
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
	}
	if(store_class_id=='10475'){    //休闲娱乐
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,class1:'私人影院',class2:'茶馆',class3:'桌游',class4:'轰趴馆',class5:'采摘/农家乐',class6:'DIY手工坊',class7:'密室逃脱',class8:'VR体验'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			 $.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=dmallindex&op=getAllClassName",
			data:{key:key,city:city,store_class_id:store_class_id},
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
	}
	if(store_class_id=='10429'){    //丽人
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,class1:'瑜伽舞蹈',class2:'瘦身纤体',class3:'韩式定妆',class4:'祛痘'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			   $.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=dmallindex&op=getAllClassName",
			data:{key:key,city:city,store_class_id:store_class_id},
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
	}
	if(store_class_id=='10432'){    //生活服务
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,class1:'培训课程',class2:'体检/齿科',class3:'配镜',class4:'商场购物卡',class5:'商务服务',class6:'搬家',class7:'居家维修',class8:'婚庆'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			 $.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=dmallindex&op=getAllClassName",
			data:{key:key,city:city,store_class_id:store_class_id},
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
	
	}
	$('#subclass').html("全部");
	
	
	
	
		}
			
});
   	function itCheck(){
		$('#topmeun').show();
		}
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
	if(store_class_id=='10505'){    //美食添加菜单
     $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,obj:obj,class1:'火锅',class2:'西餐',class3:'东北菜',class4:'香锅烤鱼',class5:'中式烧烤/烤串',class6:'京菜鲁菜',class7:'东南亚菜',class8:'台湾/客家菜'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	    
	
	 }
	if(store_class_id=='10494'){    //酒店住宿
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,obj:obj,class1:'经济型酒店',class2:'舒适型酒店',class3:'度假酒店/度假村',class4:'客栈'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
		
		
	}
	if(store_class_id=='10475'){    //休闲娱乐
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,obj:obj,class1:'私人影院',class2:'茶馆',class3:'桌游',class4:'轰趴馆',class5:'采摘/农家乐',class6:'DIY手工坊',class7:'密室逃脱',class8:'VR体验'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			
			
	}
	if(store_class_id=='10429'){    //丽人
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,obj:obj,class1:'瑜伽舞蹈',class2:'瘦身纤体',class3:'韩式定妆',class4:'祛痘'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			 
	}
	if(store_class_id=='10432'){    //生活服务
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,obj:obj,class1:'培训课程',class2:'体检/齿科',class3:'配镜',class4:'商场购物卡',class5:'商务服务',class6:'搬家',class7:'居家维修',class8:'婚庆'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			
	
	}
	  $('#subclass_nr').fadeOut();
	  $('#goodcover').hide();
}
    function sortDistance(obj){
		  var key = getCookie('key');
          var lng = getCookie('lng', lng);
	      var lat = getCookie('lat', lat);
	      var city = getCookie('city',city);
	      var store_class_id=getCookie('store_class_id');
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
	if(store_class_id=='10505'){    //美食添加菜单
     $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,store_class_id:store_class_id,lat:lat,dobj:obj,class1:'火锅',class2:'西餐',class3:'东北菜',class4:'香锅烤鱼',class5:'中式烧烤/烤串',class6:'京菜鲁菜',class7:'东南亚菜',class8:'台湾/客家菜'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	    
	
	 }
	if(store_class_id=='10494'){    //酒店住宿
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,dobj:obj,class1:'经济型酒店',class2:'舒适型酒店',class3:'度假酒店/度假村',class4:'客栈'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
		
		
	}
	if(store_class_id=='10475'){    //休闲娱乐
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,dobj:obj,class1:'私人影院',class2:'茶馆',class3:'桌游',class4:'轰趴馆',class5:'采摘/农家乐',class6:'DIY手工坊',class7:'密室逃脱',class8:'VR体验'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			
			
	}
	if(store_class_id=='10429'){    //丽人
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,dobj:obj,class1:'瑜伽舞蹈',class2:'瘦身纤体',class3:'韩式定妆',class4:'祛痘'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			 
	}
	if(store_class_id=='10432'){    //生活服务
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,dobj:obj,class1:'培训课程',class2:'体检/齿科',class3:'配镜',class4:'商场购物卡',class5:'商务服务',class6:'搬家',class7:'居家维修',class8:'婚庆'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			
	
	}
		  $('#nearby_nr').fadeOut();
		  $('#goodcover').hide();
		
		}
	function DisDistance(obj){
		 var key = getCookie('key');
         var lng = getCookie('lng', lng);
	     var lat = getCookie('lat', lat);
	     var city = getCookie('city',city);
	     var store_class_id=getCookie('store_class_id');
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
	if(store_class_id=='10505'){    //美食添加菜单
     $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,store_class_id:store_class_id,lat:lat,sobj:obj,class1:'火锅',class2:'西餐',class3:'东北菜',class4:'香锅烤鱼',class5:'中式烧烤/烤串',class6:'京菜鲁菜',class7:'东南亚菜',class8:'台湾/客家菜'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
	    
	
	 }
	if(store_class_id=='10494'){    //酒店住宿
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,sobj:obj,class1:'经济型酒店',class2:'舒适型酒店',class3:'度假酒店/度假村',class4:'客栈'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
		
		
	}
	if(store_class_id=='10475'){    //休闲娱乐
	  $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,sobj:obj,class1:'私人影院',class2:'茶馆',class3:'桌游',class4:'轰趴馆',class5:'采摘/农家乐',class6:'DIY手工坊',class7:'密室逃脱',class8:'VR体验'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			
			
	}
	if(store_class_id=='10429'){    //丽人
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,sobj:obj,class1:'瑜伽舞蹈',class2:'瘦身纤体',class3:'韩式定妆',class4:'祛痘'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			 
	}
	if(store_class_id=='10432'){    //生活服务
	    $.ajax({
	    type:'post',
		url:ApiUrl+"/index.php?act=dmallindex&op=getAllProduct",  //获取地面商家产品
		data:{key:key,city:city,lng:lng,lat:lat,store_class_id:store_class_id,sobj:obj,class1:'培训课程',class2:'体检/齿科',class3:'配镜',class4:'商场购物卡',class5:'商务服务',class6:'搬家',class7:'居家维修',class8:'婚庆'},
		dataType:"json",
		success: function(result){
			 var data = result.datas;
					if(data.goodname.length==0){
					var html='<div style="height:20px;width:100%; padding-top:20px; margin-left:auto; margin-right:auto;text-align:center">暂无产品<div>';
					}else{
					var html = template.render('topp-script',result);	
						}
					$('#topp').html(html);
					}
			});
			
	
	}
		 $('#mind_nr').fadeOut();
		 $('#goodcover').hide();
		}

function seacheOp(){
		window.location.href='dmsearch.html';
		}