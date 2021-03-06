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

                var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.datas.member_info.avatar + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.datas.member_info.user_name+ ' - id : '+result.datas.member_info.user_id + '</span> </div>'
                    + '<div class="user-name"> <span>级别：'+result.datas.member_info.user_level+'</span><span style=margin-left:20px;>推荐人：'+result.datas.member_info.member_pid+'</span> </div>'
                    + '</div>'
                    + '<div class="member-collect"><span><a href="favorites.html"><em>' + result.datas.member_info.favorites_goods + '</em>'
                    + '<p>商品收藏</p>'
                    + '</a> </span><span><a href="favorites_store.html"><em>' +result.datas.member_info.favorites_store + '</em>'
                    + '<p>店铺收藏</p>'
                    + ''
                    + ''
                    + '</a> </span></div>';
                //渲染页面
                
                $(".member-top").html(html);
                
                var html = '<li><a href="order_list.html?data-state=state_new">'+ (result.datas.member_info.order_nopay_count > 0 ? '<em></em>' : '') +'<i class="cc-01"></i><p>待付款</p></a></li>'
                    + '<li><a href="order_list.html?data-state=state_send">' + (result.datas.member_info.order_noreceipt_count > 0 ? '<em></em>' : '') + '<i class="cc-02"></i><p>待收货</p></a></li>'
                    // + '<li><a href="order_list.html?data-state=state_notakes">' + (result.datas.member_info.order_notakes_count > 0 ? '<em></em>' : '') + '<i class="cc-03"></i><p>待自提</p></a></li>'
                    + '<li><a href="order_list.html?data-state=state_noeval">' + (result.datas.member_info.order_noeval_count > 0 ? '<em></em>' : '') + '<i class="cc-04"></i><p>待评价</p></a></li>'
                    + '<li><a href="member_refund.html">' + (result.datas.member_info.return > 0 ? '<em></em>' : '') + '<i class="cc-05"></i><p>退款/退货</p></a></li>';
                //渲染页面
                
                $("#order_ul").html(html);
                
                var html = '<li><a href="predepositlog_list.html"><i class="cc-06"></i><p>可用余额</p></a></li>'
                    + '<li><a href="rechargecardlog_list.html"><i class="cc-07"></i><p>充值金额</p></a></li>'                    
                    + '<li><a href="fenxiao_list.html"><i class="cc-09"></i><p>分销奖金</p></a></li>'
                    + ''
                    + '<li><a href="pointslog_list.html"><i class="cc-10"></i><p>云豆</p></a></li>';
                $('#asset_ul').html(html);
                
                var html = '<li><a href="city_list.html"><p>市级代理</p></a></li>'
                    + '<li><a href="quxian_list.html"><p>区县代理</p></a></li>'                    
                    + '<li><a href="part_list.html"><p>端口代理</p></a></li>'
                    + '<li><a href="yiji_list.html"><p>一级直推</p></a></li>'
                    + '<li><a href="yiji2_list.html"><p>二级直推</p></a></li>';
                $('#team_ul').html(html);
                return false;
            }
        });
    } else {
        var html = '<div class="member-info">'
            + '<a href="ydy.html" class="default-avatar" style="display:block;"></a>'
            + '<a href="ydy.html" class="to-login">点击登录</a>'
            + '</div>'
            + '<div class="member-collect"><span><a href="ydy.html"><i class="favorite-goods"></i>'
            + '<p>商品收藏</p>'
            + '</a> </span><span><a href="ydy.html"><i class="favorite-store"></i>'
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
      $.scrollTransparent();
});
