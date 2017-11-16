$(function (){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html ='<dl>';
	var fnav ='<dt><a href="'+WapSiteUrl+'"><img src="../images/f_01.png"/><span>首页</span></a></dt>'
		+'<dt><a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html"><img src="../images/f_02.png"/><span>分类</span></a></dt>'
		+'<dd><a href="'+WapSiteUrl+'/tmpl/member/member.html"> <img src="../images/f_logo.png"/></a></dd>'
		// +'<dt><a href="'+WapSiteUrl+'/tmpl/search.html"><i class="search"></i><p>搜索</p></a></dt>'
		+'<dt><a href="'+WapSiteUrl+'/tmpl/cart_list.html"><i class="cart"><img src="../images/f_03.png"/><span>购物车</span></a></dt>'
		+'<dt><a href="'+WapSiteUrl+'/tmpl/member/order_list.html?data-state=state_new"><img src="../images/f_04.png"/><span>订单</span></a></dt></dl>'
		+'</dl>';
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
});