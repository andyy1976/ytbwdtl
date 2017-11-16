$(function(){
	Array.prototype.unique = function()
	{
		var n = [];
		for(var i = 0; i < this.length; i++)
		{
			if (n.indexOf(this[i]) == -1) n.push(this[i]);
		}
		return n;
	}
	var keyword = decodeURIComponent(getQueryString('keyword'));
    if (keyword) {
    	$('#keyword').val(keyword);writeClear($('#keyword'));
    }
    /*$('#keyword').on('input',function(){
    	var value = $.trim($('#keyword').val());
    	if (value == '') {
    		$('#search_tip_list_container').hide();
    	} else {
            $.getJSON(ApiUrl + '/index.php?act=goods&op=auto_complete',{term:$('#keyword').val()}, function(result) {
            	if (!result.datas.error) {
                	var data = result.datas;
                	data.WapSiteUrl = WapSiteUrl;
                	if (data.list.length > 0) {
                		$('#search_tip_list_container').html(template.render('search_tip_list_script',data)).show();
                	} else {
                		$('#search_tip_list_container').hide();
                	}
            	}
            })
    	}
    });*/

    $('.input-del').click(function(){
        $(this).parent().removeClass('write').find('input').val('');
    });

    template.helper('$buildUrl',buildUrl);
    $.getJSON(ApiUrl + '/index.php?act=index&op=search_key_list', function(result) {
    	var data = result.datas;
    	data.WapSiteUrl = WapSiteUrl;
    	$('#hot_list_container').html(template.render('hot_list',data));
        $('#search_his_list_container').html(template.render('search_his_list',data));
    })

    $('#header-nav').click(function(){
    	if ($('#keyword').val() == '') {
    		window.location.href = buildUrl('keyword',getCookie('deft_key_value') ? getCookie('deft_key_value') : '');
    	} else {
    		window.location.href = buildUrl('keyword',$('#keyword').val());
    	}
    });



});
	
	
	
	

function knchange(){
    	if ($('#keyword').val() == '') {
    		return false;
    	} else {
			addCookie('keyword',$('#keyword').val());
            var city = getCookie('city');
            var lng = getCookie('lng');
            var lat = getCookie('lat');
            if(city==null){
                window.location.href = WapSiteUrl + '/tmpl/dm/dmcity.html';
                return false;
            }
            var keyword = $('#keyword').val();
            var key = getCookie('key');
                if(!key){
                  window.location.href = WapSiteUrl + '/tmpl/member/login.html';
            }
           $.getJSON(ApiUrl + '/index.php?act=dmstore&op=search_keyword',{key:key,term:keyword,city:city,lng:lng,lat:lat}, function(result) {
                var data = result.datas;
                if(data==0){
                    alert('没有数据');
					window.location.href='dmsearch.html';
                }else{
                   window.location.href = WapSiteUrl + '/tmpl/dm/dmstore_details.html?store_id=' + data; 
                }
            })
    	}
	   }