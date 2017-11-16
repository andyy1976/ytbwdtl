﻿$(function (){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html = '<div class="nctouch-footer-wrap posr">'
        +'<div class="nav-text">';
    if(key){
        html += '<a href="'+WapSiteUrl+'/tmpl/member/member.html">我的商城</a>'
            + '<a id="logout" href="javascript:void(0);">退出</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/member_feedback.html">客服</a>'
	    + '<a href="' + WapSiteUrl + '/tmpl/article_list.html?ac_id=2">帮助</a>';
            
    } else {
        html += '<a href="'+WapSiteUrl+'/tmpl/member/login.html">登录</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/register.html">注册</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/login.html">客服</a>'
	    + '<a href="' + WapSiteUrl + '/tmpl/article_list.html?ac_id=2">帮助</a>';
    }
    html += '<a href="javascript:void(0);" class="gotop">返回顶部</a>' + "</div>" + "</div>";
	var fnav = '<div id="footnav" class="footnav clearfix"><ul>'
		+'<li><a href="'+WapSiteUrl+'"><i class="home"></i><p>云品</p></a></li>'
        +'<li><a href="'+WapSiteUrl+'"><i class="categroy"></i><p>云店</p></a></li>'
        +'<li><a href="'+WapSiteUrl+'/tmpl/dm/dmwdtl.html"><i class="search"></i><p>万店通联</p></a></li>'
        +'<li><a href="'+WapSiteUrl+'/tmpl/member/order_list.html?data-state=state_new"><span id="cart_count"><i class="cart"></i></span><p>订单</p></a></li>'
        +'<li><a href="'+WapSiteUrl+'/tmpl/member/member.html"><i class="member"></i><p>我的</p></a></li></ul>'
        +'</div>';
	$("#footer").html(html+fnav);
    var key = getCookie('key');
	$('#logoutbtn').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl;
				}
			}
		});
	});
	$('#logout').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl;
				}
			}
		});
	});
    if(typeof(navigate_id) == 'undefined'){navigate_id="0";}
    //当前页面
    if(navigate_id == "1"){
        $(".footnav .home").parent().addClass("current");
        $(".footnav .home").attr('class','home2');
    }else if(navigate_id == "2"){
        $(".footnav.categroy").parent().addClass("current");
        $(".footnav.categroy").attr('class','categroy2');
    }else if(navigate_id == "3"){
        $(".footnav .search").parent().addClass("current");
        $(".footnav .search").attr('class','search2');
    }else if(navigate_id == "4"){
        $(".footnav .cart").parent().parent().addClass("current");
        $(".footnav .cart").attr('class','cart2');
    }else if(navigate_id == "5"){
        $(".footnav .member").parent().addClass("current");
        $(".footnav .member").attr('class','member2');
    }
});