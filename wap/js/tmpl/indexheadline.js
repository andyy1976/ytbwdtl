$(function(){
    $.ajax({
        url:ApiUrl+"/index.php?act=article&op=headline_new",
        type:'get',
        jsonp:'callback',
        dataType:'jsonp',
        success:function(result){
            var data = result.datas;
            data.WapSiteUrl = WapSiteUrl;
            var html = template.render('headline_list', data);
            $("#idScrollMidPan").html(html);
              new Scroller(
              "idScrollerPan", 
              "idScrollMidPan",
              { 
                Side:["up",""], 
                PauseHeight:46,
                PauseWidth:"100%",
                timer:2000
              });
        }
    })
});