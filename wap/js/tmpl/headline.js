$(function(){
    var article_id = getQueryString('article_id')
    if (article_id=='') {
        window.location.href = WapSiteUrl + '/index.html';
        return;
    }
    else {
        $.ajax({
            url:ApiUrl+"/index.php?act=article&op=headline",
            type:'get',
            data:{article_id:article_id},
            jsonp:'callback',
            dataType:'jsonp',
            success:function(result){
                var data = result.datas;
                data.WapSiteUrl = WapSiteUrl;
                var html = template.render('headline', data);
                $("#article-content").html(html);
            }
        });
    }   
});