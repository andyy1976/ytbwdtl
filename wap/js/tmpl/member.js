$(function(){
	var map_id = getQueryString('map_id');
    if (getQueryString('key') != '') {
		document.getElementById('map_id').value=map_id;  
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
		var map_id = getQueryString('map_id'); 
		document.getElementById('map_id').value=map_id;  
        var key = getCookie('key');
		if(document.getElementById('map_id').value!=''){
	   //location.href = '/wap/tmpl/member/pointspay_list.html?map_id='+map_id ;
	    location.href= '/wap/tmpl/member/member_yeepay.html?map_id='+map_id;
    }

	}
   function showLoginPanel(){
	   var map_id = document.getElementById('map_id').value;
       
		   if(map_id==""){
			 var html = '<div class="member-info">'
            + '<a href="login.html" class="default-avatar" style="display:block;"></a>'
            + '<a href="login.html" class="to-login">点击登录</a>'
            + '</div>'
            + ' '
            + ''
            + '</a> </span></div>';
			 //渲染页面
        $(".member-top").html(html);
		   }else{    //为了更好的兼容浏览器  李志军
			var html = '<div class="member-info">'
			+ '<a href="login.html?map_id='+map_id+'" class="default-avatar" style="display:block;"></a>'
            + '<a href="login.html?map_id='+map_id+'" class="to-login">点击登录</a>'
            + '</div>'
            + '<div class="member-collect"><span><a href="login.html?map_id='+map_id+'"><i class="favorite-goods"></i>'
            + '<p>商品收藏</p>'
            + '</a> </span><span><a href="login.html?map_id='+map_id+'"><i class="favorite-store"></i>'
            + '<p>店铺收藏</p>'
            + ' '
            + ''
            + '</a> </span></div>';
			 //渲染页面
        $(".member-top").html(html);
			   }
       
        
        var html = '<li><a href="login.html"><i class="cc-01"></i><p>待付款</p></a></li>'
        + '<li><a href="login.html"><i class="cc-02"></i><p>待收货</p></a></li>'
        + '<li><a href="login.html"><i class="cc-03"></i><p>待自提</p></a></li>'
        + '<li><a href="login.html"><i class="cc-04"></i><p>待评价</p></a></li>'
        + '<li><a href="login.html"><i class="cc-05"></i><p>退款/退货</p></a></li>';
        //渲染页面
        $("#order_ul").html(html);
        var html = '<li><a href="predepositlog_list.html"><i class="cc-06"></i><p>云豆余额</p></a></li>' 
        + '<li><a href="rechargecardlog_list.html"><i class="cc-07"></i><p>充值钱包</p></a></li>' 
        + '<li><a href="fenxiao_list.html"><i class="cc-09"></i><p>分销余额</p></a></li>'
        + '<li><a href="pointslog_list.html"><i class="cc-10"></i><p>云豆</p></a></li>';
        $("#asset_ul").html(html);
        return false;
    }

    if(key){

        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_index&a=1",
           data:{key:key,map_id:map_id},
	   dataType:'json',
            //jsonp:'callback',
            success:function(result){

                // 未登录
                if (result.login == 0) {
					delCookie('key')
                    console.log(getCookie('key'))
                    showLoginPanel()
                    return
                }
                
                // checkLogin(result.login);

                // 已登录
                else {
                    if(result.datas.member_info.member_level=='2'){
                        $('#points').show();
                    }
                    if(result.datas.member_info.user_id=='10088' || result.datas.member_info.user_id=='128013'){
                        $('#cs1').show();
                        $('#cs2').show();
                        // $('#cs3').show();
                        // $('#cs4').show();
                        // $('#cs5').show();
                    }
					if(result.datas.member_info.user_id=='221704'||result.datas.member_info.user_id=='144198'){
                   var html = '<div class="member-info">'
                        + '<div class="user-avatar"> <img onclick="showAvatarList(this)" id="user-avatar-img" src="' + result.datas.member_info.avatar + '"/> </div>'
                        + '<div class="user-name"> <span>'+result.datas.member_info.user_name+ ' - id : '+result.datas.member_info.user_id + '</span> </div>'
                        + '<div class="user-name"> <span>级别：'+result.datas.member_info.user_level+'</span><span style=margin-left:20px;>推荐人：'+result.datas.member_info.member_pid+'</span> </div>'
                        + '</div>'
                        + '<div class="member-collect"><span><a href="favorites.html"><em>' + result.datas.member_info.favorites_goods + '</em>'
                        + '<p>商品收藏</p>'
                        + '</a> </span><span><a href="favorites_store.html"><em>' +result.datas.member_info.favorites_store + '</em>'
                        + '<p>店铺收藏</p>'
						+'</a> </span>'
					    + '<span><a href="../../dimianshop.html"><i class="favorite-store"></i>'
                        + '<p>地面商家</p>'
					    + '</a> </span>'
					    + '</div>';

                    //渲染页面
					}else{
                    var html = '<div class="dm_my">'
                        + '<dl>'
                        + '<dt><img onclick="showAvatarList(this)" id="user-avatar-img" src="' + result.datas.member_info.avatar + '"/></dt>'
                        + '<dd class="my_01">'+result.datas.member_info.user_level+'</dd>'
                        + '<dd class="my_02">'+result.datas.member_info.user_name+ '</dd>'
                        + '<dd>ID：'+result.datas.member_info.user_id + '　　推荐人：'+result.datas.member_info.member_pid+'</dd>'
                        + '</dl>'
                        + '</div>';
					 // var html = '<div class="member-info">'
      //                   + '<div class="user-avatar"> <img onclick="showAvatarList(this)" id="user-avatar-img" src="' + result.datas.member_info.avatar + '"/> </div>'
      //                   + '<div class="user-name"> <span>'+result.datas.member_info.user_name+ ' - id : '+result.datas.member_info.user_id + '</span> </div>'
      //                   + '<div class="user-name"> <span>级别：'+result.datas.member_info.user_level+'</span><span style=margin-left:20px;>推荐人：'+result.datas.member_info.member_pid+'</span> </div>'
      //                   + '</div>'
      //                   + '<div class="member-collect"><span><a href="favorites.html"><em>' + result.datas.member_info.favorites_goods + '</em>'
      //                   + '<p>商品收藏</p>'
      //                   + '</a> </span><span><a href="favorites_store.html"><em>' +result.datas.member_info.favorites_store + '</em>'
      //                   + '<p>店铺收藏</p>'
						// +'</a> </span>'
					 //    + '</div>';
	
						}
                    $(".member-top").html(html);
                    
                    var html = '<li><a href="order_list.html?data-state=state_new">'+ (result.datas.member_info.order_nopay_count > 0 ? '<em></em>' : '') +'<i class="cc-01"></i><p>待付款</p></a></li>'
                        + '<li><a href="order_list.html?data-state=state_send">' + (result.datas.member_info.order_noreceipt_count > 0 ? '<em></em>' : '') + '<i class="cc-02"></i><p>待收货</p></a></li>'
                        // + '<li><a href="order_list.html?data-state=state_notakes">' + (result.datas.member_info.order_notakes_count > 0 ? '<em></em>' : '') + '<i class="cc-03"></i><p>待自提</p></a></li>'
                        + '<li><a href="order_list.html?data-state=state_noeval">' + (result.datas.member_info.order_noeval_count > 0 ? '<em></em>' : '') + '<i class="cc-04"></i><p>待评价</p></a></li>'
                        + '<li><a href="member_refund.html">' + (result.datas.member_info.return > 0 ? '<em></em>' : '') + '<i class="cc-05"></i><p>退款/退货</p></a></li>';
                    //渲染页面
                    
                    $("#order_ul").html(html);
                    
                    var html = '<li><a href="predepositlog_list.html"><i class="cc-06"></i><p>云豆余额</p></a></li>'
                        + '<li><a href="rechargecardlog_list.html"><i class="cc-07"></i><p>充值金额</p></a></li>'                    
                        + '<li><a href="fenxiao_list.html"><i class="cc-09"></i><p>分销奖金</p></a></li>'
                        + ''
                        + '<li><a href="pointslog_list.html"><i class="cc-10"></i><p>云豆</p></a></li>';
                    $('#asset_ul').html(html);
                    
                    if(result.datas.member_info.member_level=='6'){
                        var html = '<li><a href="under_list.html"><p>伞下人员</p></a></li>'
                        + '<li><a href="member_give_points.html"><p>云豆转账</p></a></li>'
                        + '<li><a href="yiji_list.html"><p>一级直推</p></a></li>'
                        + '<li><a href="yiji2_list.html"><p>二级直推</p></a></li>';
                    }else{

                        var html = '<li><a href="city_list.html"><p>市级代理</p></a></li>'
                        + '<li><a href="quxian_list.html"><p>区县代理</p></a></li>'                    
                        + '<li><a href="part_list.html"><p>端口代理</p></a></li>'
                        + '<li><a href="yiji_list.html"><p>一级直推</p></a></li>'
                        + '<li><a href="yiji2_list.html"><p>二级直推</p></a></li>';
                        if(result.datas.member_info.user_id=='10088'){
                            html += '<li><a href="yeepaytest/pay.php"><p>测试APP</p></a></li>';
                        }
                    }
                    $('#team_ul').html(html);
                    return false;

                }


            }
        });

    } else {
        showLoginPanel()
    }

    //滚动header固定到顶部
    $.scrollTransparent();
	});
