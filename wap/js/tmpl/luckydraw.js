/**
 * all shop 33hao v4.2
 */
$(function(){

    var key = getCookie('key');
	
    var id = getQueryString('id');
    $.getJSON(ApiUrl + '/index.php?act=lucky_draw&op=index', {key:key,id:id}, function(result){
        if (result.datas.error) {
            alert(result.datas.error);
            window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        }
        var html = template.render('luckydraw-name-script', result.datas.sweepstakesInfo);
        $("#luckydraw-name").html(html);
        var html1 = template.render('awardInfo-script', result.datas);
        $("#awardInfo").html(html1);      
    });

    $("#startbtn").click(function(){
        if (!key) {
            window.location.href = WapSiteUrl + '/tmpl/member/login.html';
            return;
        }
        $.ajax({
            type: 'POST',
            url: ApiUrl+"/index.php?act=lucky_draw&op=run",
            data: {
                key: key,
                id: id
            },
            dataType: 'json',
            success:function(result){
                if (result.datas.error) {
                    alert(result.datas.error);
                    return false;
                } else {
                    var a = parseInt(result.datas.panaward.angle); //角度
                    var p = result.datas.panaward.praise_content;//奖项内容
                    var n = result.datas.panaward.order_type;//奖项是否为虚拟
                    var sweeporder_id = result.datas.panaward.sweeporder_id;//中奖纪录id
                    if(p!="" && a!=0){
                        $("#startbtn").rotate({
                            duration:3000, //转动时间
                            angle: 0, //默认角度
                            animateTo:3600+a, //转动角度
                            easing: $.easing.easeOutSine,
                            callback: function(){
                                if (n == 0) {
                                    var con = confirm(p+'，还要再来一次吗？');
                                    $("#startbtn").rotate({angle:0});
                                    $("#startbtn").css("cursor","pointer");
                                    if(!con){
                                        $("#startbtn").unbind('click').css("cursor","default");
                                    }
                                } else {
                                    var con = alert('恭喜你，中得'+p+'，请填写收件信息！');
                                    window.location.href = WapSiteUrl+'/tmpl/order/sweeporder_address.html?key='+key+'&sweeporder_id='+sweeporder_id;    
                                }
                            }
                        });
                    }
                }
            }
        })
    })
});