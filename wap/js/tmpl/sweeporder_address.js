var key = getCookie('key');
var sweeporder_id = getQueryString('sweeporder_id');
var pay_name = 'online';
var invoice_id = 0;
var address_id,vat_hash,offpay_hash,offpay_hash_batch,voucher,pd_pay,password,fcode='',rcb_pay,rpt,payment_code;
var message = {};
// change_address 使用变量
var freight_hash,city_id,area_id
// 其他变量
var area_info;
var goods_id;
$(function() {
    // 地址列表
    $('#list-address-valve').click(function(){
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_address&op=address_list", 
            data:{key:key},
            dataType:'json',
            async:false,
            success:function(result){
                // checkLogin(result.login);
                if(result.datas.address_list==null){
                    return false;
                }
                var data = result.datas;
                data.address_id = address_id;
                var html = template.render('list-address-add-list-script', data);
                $("#list-address-add-list-ul").html(html);
            }
        });
    });
    $.animationLeft({
        valve : '#list-address-valve',
        wrapper : '#list-address-wrapper',
        scroll : '#list-address-scroll'
    });
    
    // 地区选择
    $('#list-address-add-list-ul').on('click', 'li', function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        eval('address_info = ' + $(this).attr('data-param'));
        _init(address_info.address_id);
        $('#list-address-wrapper').find('.header-l > a').click();
    });
    
    // 地址新增
    $.animationLeft({
        valve : '#new-address-valve',
        wrapper : '#new-address-wrapper',
        scroll : ''
    });
    // 地区选择
    $('#new-address-wrapper').on('click', '#varea_info', function(){
        $.areaSelected({
            success : function(data){
                city_id = data.area_id_2 == 0 ? data.area_id_1 : data.area_id_2;
                area_id = data.area_id;
                area_info = data.area_info;
                $('#varea_info').val(data.area_info);
            }
        });
    });
    
    
    template.helper('isEmpty', function(o) {
        var b = true;
        $.each(o, function(k, v) {
            b = false;
            return false;
        });
        return b;
    });
    
    template.helper('pf', function(o) {
        return parseFloat(o) || 0;
    });

    template.helper('p2f', function(o) {
        return (parseFloat(o) || 0).toFixed(2);
    });

    var _init = function (address_id) {
        var totals = 0;
        var totals_points = 0;
        // 购买第一步 提交
        $.ajax({//提交订单信息
            type:'post',
            url:ApiUrl+'/index.php?act=sweeporder_address&op=show_address',
            dataType:'json',
            data:{key:key,sweeporder_id:sweeporder_id,address_id:address_id},
            success:function(result){
                // checkLogin(result.login);
                if (result.datas.error) {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                // 商品数据
                result.datas.WapSiteUrl = WapSiteUrl;
                var html = template.render('goods_list', result.datas);
                $("#deposit").html(html);

                // 默认地区相关
                if ($.isEmptyObject(result.datas.address_info)) {
                    $.sDialog({
                        skin:"block",
                        content:'请添加地址',
                        okFn: function() {
                            $('#new-address-valve').click();
                        },
                        cancelFn: function() {
                            history.go(-1);
                        }
                    });
                    return false;
                }
                // 输入地址数据
                insertHtmlAddress(result.datas.address_info, result.datas.address_api);
            }
        });
    }
    
    rcb_pay = 0;
    pd_pay = 0;
    // 初始化
    _init();

    // 插入地址数据到html
    var insertHtmlAddress = function (address_info) {
        address_id = address_info.address_id;
        $('#true_name').html(address_info.true_name);
        $('#mob_phone').html(address_info.mob_phone);
        $('#address').html(address_info.area_info + address_info.address);
        area_id = address_info.area_id;
        city_id = address_info.city_id;
        
        if($.trim($('.panidentity').val()).length == 0 && $('.panidentity').length > 0) {
            $('#ToBuyStep2').parent().removeClass('ok');
        } else {
            $('#ToBuyStep2').parent().addClass('ok');
        }
    }

    $('#ToBuyStep2').click(function(){
         //20171019判断是否填写了手机号码
        var chargephone = 0;
        if ($("#phoneNumber").length > 0) {
            chargephone = $("#phoneNumber").val();
            if (!(/^1(3|4|5|7|8)\d{9}$/.test(chargephone))) {
                alert('请填写您的充值手机号码');
                return false;
            }
        }

        var msg = $('#message').val();
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=sweeporder_address&op=store_info',
            data:{
                key:key,
                sweeporder_id:sweeporder_id,
                address_id:address_id,
                message:msg,
                phone_require:chargephone
                },
            dataType:'json',
            success: function(result){
                // checkLogin(result.login);
                if (result.datas.error) {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                if (result.datas.success == 1) {
                    window.location.href = WapSiteUrl + '/lucky_draw.html?id='+result.datas.sweepstakesId;
                }
            }
        });
    });
    
    // 地址保存
    $.sValid.init({
        rules:{
            vtrue_name:"required",
            vmob_phone:{
                required:true,
                minlength:11,
                maxlength:11
            },
            varea_info:"required",
            vaddress:"required"
        },
        messages:{
            vtrue_name:"姓名必填！",
            vmob_phone:{
                required:'手机号码必填',
                minlength:'手机号码不足11位',
                maxlength:'手机号码超过11位'
            },
            varea_info:"地区必填！",
            vaddress:"街道必填！"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }  
    });
    $('#add_address_form').find('.btn').click(function(){
        if($.sValid()){
            var param = {};
            param.key = key;
            param.true_name = $('#vtrue_name').val();
            param.mob_phone = $('#vmob_phone').val();
            param.address = $('#vaddress').val();
            param.city_id = city_id;
            param.area_id = area_id;
            param.area_info = $('#varea_info').val();
            param.is_default = 0;

            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=member_address&op=address_add",  
                data:param,
                dataType:'json',
                success:function(result){
                    if (!result.datas.error) {
                        param.address_id = result.datas.address_id;
                        _init(param.address_id);
                        $('#new-address-wrapper,#list-address-wrapper').find('.header-l > a').click();
                    }
                }
            });
        }
    });
});