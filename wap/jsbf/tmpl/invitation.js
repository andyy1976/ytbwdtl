$(function(){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    if(key){
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_index",
            data:{key:key},
            dataType:'json',
            //jsonp:'callback',
            success:function(result){
                checkLogin(result.login);
                var pid=result.datas.member_info.user_id;
                
             
               //  //渲染页面
                
                $("#pid").html(pid);
                var url=window.location.host;
                jQuery('#output').qrcode({  
                    width   : 300,  
                    height  : 300,  
                    text    : "http://xn--chqr71blpzqgn.xn--fiqs8s/wap/tmpl/member/register.html?pid="+pid  
                }); 
                // jQuery('#output').qrcode(url+"wap/tmpl/member/register.html?pid="+pid);

    //table 模式兼容 IE低版本  
var image = document.getElementById("image");  
var canvas = document.getElementById("output").getElementsByTagName("canvas")[0];  
image.src = canvas.toDataURL("image/png"); 

            }
        });
    } else {
        var html = '<div class="member-info">'
            + '<a href="login.html" class="default-avatar" style="display:block;"></a>'
            + '<a href="login.html" class="to-login">点击登录</a>'
            + '</div>'
            + '<div class="member-collect"><span><a href="login.html"><i class="favorite-goods"></i>'
            + '<p>商品收藏</p>'
            + '</a> </span><span><a href="login.html"><i class="favorite-store"></i>'
            + '<p>店铺收藏</p>'
            + ' '
            + ''
            + '</a> </span></div>';
        //渲染页面
        $(".member-top").html(html);
        
        var html = '<li><a href="login.html"><i class="cc-01"></i><p>待付款</p></a></li>'
        + '<li><a href="login.html"><i class="cc-02"></i><p>待收货</p></a></li>'
        + '<li><a href="login.html"><i class="cc-03"></i><p>待自提</p></a></li>'
        + '<li><a href="login.html"><i class="cc-04"></i><p>待评价</p></a></li>'
        + '<li><a href="login.html"><i class="cc-05"></i><p>退款/退货</p></a></li>';
        //渲染页面
        $("#order_ul").html(html);
     var html = '<li><a href="predepositlog_list.html"><i class="cc-06"></i><p>可用余额</p></a></li>' + '<li><a href="rechargecardlog_list.html"><i class="cc-07"></i><p>充值钱包</p></a></li>' + '<li><a href="voucher_list.html"><i class="cc-08"></i><p>代金券</p></a></li>' + '<li><a href="fenxiao_list.html"><i class="cc-09"></i><p>分销余额</p></a></li>' + '<li><a href="pointslog_list.html"><i class="cc-10"></i><p>云豆</p></a></li>';
        $("#asset_ul").html(html);
        return false;
    }
            
      //滚动header固定到顶部
      // $.scrollTransparent();
});
