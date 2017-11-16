$(function (){
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
        html += '<a href="javascript:void(0);" class="gotop">返回顶部</a>' + "</div>" + '<!--<div class="copyright">' + 'Copyright&nbsp;&copy;&nbsp;2005-2017 <a href="javascript:void(0);">万店通联 </a>版权所有' + "</div>--></div>";
	var fnav = '<div id="footnav" class="wan_footer" style="background:#fff; border-top:1px solid #dbdbdb; padding:6px 0 4px; position:fixed; left:0; bottom:0;"><ul>'
		+'<li class="footer_red" style="width:20%; float:left; text-align:center;"><a href="'+WapSiteUrl+'" style="color:#BB8F3C;"><img style="width:44%;" src="/wap/images/wan_f2.png"/><span style="width:100%; float:left; font-size:14px;">云品</span></a></li>'
		+'<li style="width:20%; float:left; text-align:center;"><a href="'+WapSiteUrl+'" style="color:#9b9b9b; float:left;"><img style="width:44%;" src="/wap/images/wan_f3.png"/><span style="width:100%; float:left; font-size:14px;">云店</span></a></li>'
		// +'<li style="width:20%; float:left; text-align:center;"><a href="'+WapSiteUrl+'/tmpl/search.html"><i class="search"></i><p>搜索</p></a></li>'
		+'<li style="width:20%; float:left; text-align:center;"><a href="'+WapSiteUrl+'/tmpl/dm/dmwdtl.html" style="color:#9b9b9b; float:left;"><img style="width:44%;" src="/wap/images/wan_f5.png"/><span style="width:100%; float:left; font-size:14px">万店通联</span></a></li>'
		+'<li style="width:20%; float:left; text-align:center;"><a href="'+WapSiteUrl+'/tmpl/member/order_list.html?data-state=state_new" style="color:#9b9b9b; float:left;"><img style="width:44%;" src="/wap/images/wan_f7.png"/><span style="width:100%; float:left; font-size:14px">订单</span></a></li>'
		+'<li style="width:20%; float:left; text-align:center;"><a href="'+WapSiteUrl+'/tmpl/member/member.html" style="color:#9b9b9b; float:left;"><img style="width:44%;" src="/wap/images/wan_f9.png"/><span style="width:100%; float:left; font-size:14px">我的</span></li></ul>'
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
});