<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="ncc-receipt-info" <?php if(!empty($output['isdmgoods'])){ echo 'style=display:none'; }?>>
  <div class="ncc-receipt-info-title">
    <h3>收货人信息</h3>
    <a href="javascript:void(0)" nc_type="buy_edit" id="edit_reciver">[修改]</a></div>
  <div id="addr_list" class="ncc-candidate-items">
    <ul>
      <li><span class="true-name"><?php echo $output['address_info']['true_name'];?></span><span class="address"><?php echo $output['address_info']['area_info'],$output['address_info']['address'];?></span><span class="phone"><i class="icon-mobile-phone"></i><?php echo $output['address_info']['mob_phone'] ? $output['address_info']['mob_phone'] : $output['address_info']['tel_phone'];?></span></li>
        <input value="0"  id="add_addr" nc_type="addr" type="radio" name="addr">
    <label for="add_addr" id="class_add">确认编辑地址</label>
    <input value="9"  id="add_addr" nc_type="addr" type="radio" name="addr"   >
    <label for="add_addr" id="class_add">取消编辑地址</label>
    </ul>
<div id="add_addr_box"><!-- 存放新增地址表单 -->

</div>
  </div>
</div>
<?php
    if ($output['isCrossBorder']) {
?>
<div class="ncc-receipt-info">
    <div class="ncc-receipt-info-title">
        <h3>购买人身份证号</h3>
    </div>
    <div id="addr_list" class="ncc-candidate-items">
    <ul>
        <li>
        <input id="identity" name="buyer_cardid" type="text" class="text w400" placeholder="请填写正确的身份证号(尾号为‘X’请大写)" />
        </li>
    </ul>
    </div>
</div>
<?php
    }
?>
<script type="text/javascript">
//20170321潘丙福添加验证身份证号
function isCardNo(card) {  
   var panpattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
   return panpattern.test(card);
}
$("#identity").on('blur', function(){
    if($.trim($('#identity').val()).length == 0) {
        $('#identity').attr('placeholder', '身份证号码没有输入');
        $('#identity').focus();
    } else {
        if(!isCardNo($.trim($('#identity').val()))) {
            $('#identity').val('');
            $('#identity').attr('placeholder', '身份证号码格式不正确');
            $('#identity').focus();
        }
    }
    return false;
})

//隐藏收货地址列表
function hideAddrList(addr_id,true_name,address,phone) {
    // $('#edit_reciver').show();
    $("#address_id").val(addr_id);
    $("#addr_list").html('<ul><li><span class="true-name">'+true_name+'</span><span class="address">'+address+'</span><span class="phone"><i class="icon-mobile-phone"></i>'+phone+'</span></li>'
            +'<input value="0"  id="add_addr" nc_type="addr" type="radio" name="addr">'
            +'<label for="add_addr" id="class_add">确认编辑地址</label>'
            +'<input value="9"  id="add_addr" nc_type="addr" type="radio" name="addr">'
            +'<label for="add_addr" id="class_add">取消编辑地址</label>'
        +'</ul><div id="add_addr_box"></div>');
    $('.current_box').removeClass('current_box');
    ableOtherEdit();
  $('#edit_payment').click();
// $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr&type=1');
    
$('input[nc_type="addr"]').on('click',function(){

         $('#input_chain_id').val('');chain_id = '';
         
        if ($(this).val() == '0') {
           
            $('.address_item').removeClass('ncc-selected-item');

    var id = "<?php echo $output["address_info"]["address_id"]?>"; 
        var url = SITEURL+'/index.php?act=buy&op=addr_info';
        $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{address_id:id},
        success: function(result) {

            var lo = result.result;
            // alert(lo);
            $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr&type=1&addr_id='+lo.address_id+'&true_name='+lo.true_name+'&city='+lo.city_id+'&area'+lo.area_id+'&address='+lo.address+'&phone='+lo.mob_phone+'&phone_lo='+lo.tel_phone+'');
        }
    });
        } else {
            if ($(this).val() == '-1') {
                $('.address_item').removeClass('ncc-selected-item');
                $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_chain');
            } else {
                $('.address_item').removeClass('ncc-selected-item');
                $(this).parent().addClass('ncc-selected-item');
                $('#add_addr_box').html('');                
            }
        }
    });

    if ($('input[nc_type="addr"]').size() == 1){
        $('#add_addr').attr('checked',true);
        addAddr();
    }
}
//加载收货地址列表
$('#edit_reciver').on('click',function(){
    $(this).hide();
    disableOtherEdit('如需修改，请先保存收货人信息 ');
    $(this).parent().parent().addClass('current_box');
    var url = SITEURL+'/index.php?act=buy&op=load_addr';
    <?php if ($output['ifshow_chainpay']) { ?>
    url += '&ifchain=1';
    <?php } ?>
    $('#addr_list').load(url);
});

function addAddr() {
        $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr');
    }
$('input[nc_type="addr"]').on('click',function(){

        $('#input_chain_id').val('');chain_id = '';
        if ($(this).val() == '0') {
            $('.address_item').removeClass('ncc-selected-item');
            $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr&type=2'
                +'&addr_id=<?php echo $output["address_info"]["address_id"]?>'
                +'&true_name=<?php echo $output['address_info']['true_name'];?>'
                +'&phone=<?php echo $output['address_info']['mob_phone'];?>'
                +'&phone_ol=<?php echo $output['address_info']['tel_phone'];?>'
                +'&address=<?php echo $output['address_info']['address'];?>'
                +'&city=<?php echo $output['address_info']['city_id'];?>'
                +'&area=<?php echo $output['address_info']['area_id'];?>'
                );
        } else {
            if ($(this).val() == '-1') {
                $('.address_item').removeClass('ncc-selected-item');
                $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_chain');
            } else {
                $('.address_item').removeClass('ncc-selected-item');
                $(this).parent().addClass('ncc-selected-item');
                $('#add_addr_box').html('');                
            }
        }s
    });   
 $('#hide_addr_list').on('click',function(){
        // alert(1);
        if ($('input[nc_type="addr"]:checked').val() == '0' || $('input[nc_type="addr"]:checked').val() == '1' || $('input[nc_type="addr"]:checked').val() == '9'){
      
            submitAddAddr();
        } else {
            if ($('input[nc_type="addr"]:checked').size() == 0) {
                return false;
            }
            var city_id = $('input[name="addr"]:checked').attr('city_id');
            var area_id = $('input[name="addr"]:checked').attr('area_id');
            var addr_id = $('input[name="addr"]:checked').val();
            var true_name = $('input[name="addr"]:checked').attr('true_name');
            var address = $('input[name="addr"]:checked').attr('address');
            var phone = $('input[name="addr"]:checked').attr('phone');
            if (chain_id != '') {
                showProductChain(city_id ? city_id : area_id);
            } else {
                showShippingPrice(city_id,area_id);
            }
            hideAddrList(addr_id,true_name,address,phone);
        }
    });
    if ($('input[nc_type="addr"]').size() == 1){
        $('#add_addr').attr('checked',true);
        addAddr();
    }
function submitAddAddr(){

    $('#input_chain_id').val('');chain_id = '';
    if ($('#addr_form').valid()){
        $('#buy_city_id').val($('#region').fetch('area_id_2'));
        var datas=$('#addr_form').serialize();
        $.post('index.php',datas,function(data){
            if (data.state){
                var true_name = $.trim($("#true_name").val());
                var tel_phone = $.trim($("#tel_phone").val());
                var mob_phone = $.trim($("#mob_phone").val());
                var area_info = $.trim($("#region").val());
                var address = $.trim($("#address").val());
                var addr_id = $.trim($("#address_id").val());
                showShippingPrice($('#region').fetch('area_id_2'),$('#region').fetch('area_id'));
                hideAddrList(data.addr_id,true_name,area_info+'&nbsp;&nbsp;'+address,(mob_phone != '' ? mob_phone : tel_phone));
                addAddr();
            }else{
                alert(data.msg);
            }
        },'json');
    }else{
        return false;
    }
}
    
//异步显示每个店铺运费 city_id计算运费area_id计算是否支持货到付款
function showShippingPrice(city_id,area_id) {
    $('#buy_city_id').val('');
    $.post(SITEURL + '/index.php?act=buy&op=change_addr', {'freight_hash':'<?php echo $output['freight_hash'];?>',city_id:city_id,'area_id':area_id}, function(data){
        if(data.state == 'success') {
            $('#buy_city_id').val(city_id ? city_id : area_id);
            $('#allow_offpay').val(data.allow_offpay);
            if (data.allow_offpay_batch) {
                var arr = new Array();
                $.each(data.allow_offpay_batch, function(k, v) {
                    arr.push('' + k + ':' + (v ? 1 : 0));
                });
                $('#allow_offpay_batch').val(arr.join(";"));
            }
            $('#offpay_hash').val(data.offpay_hash);
            $('#offpay_hash_batch').val(data.offpay_hash_batch);
            var content = data.content;var tpl_ids = data.no_send_tpl_ids;
            no_send_tpl_ids = [];no_chain_goods_ids = [];
            for(var i in content){
                if (content[i] !== false) {
                   $('#eachStoreFreight_'+i).html(number_format(content[i],2));
                } else {
                    no_send_store_ids[i] = true;
                }
            }
            for(var i in tpl_ids){
                no_send_tpl_ids[tpl_ids[i]] = true;
            }
            calcOrder();
        } else {
            showDialog('系统出现异常', 'error','','','','','','','','',2);
        }

    },'json');
}
//根据门店自提站ID计算商品是否有库存（有库存即支持自提）
function showProductChain(city_id) {
    $('#buy_city_id').val('');
    var product = [];
    $('input[name="goods_id[]"]').each(function(){
        product.push($(this).val());
    });
    $.post(SITEURL+'/index.php?act=buy&op=change_chain',{chain_id:chain_id,product:product.join('-')},function(data){
        if (data.state == 'success') {
            $('#buy_city_id').val(city_id);
            $('em[nc_type="eachStoreFreight"]').html('0.00');
            no_send_tpl_ids = [];no_chain_goods_ids = [];
            if (data.product.length > 0) {
                for (var i in data.product) {
                    no_chain_goods_ids[data.product[i]] = true;
                }
            }
            calcOrder();
        } else {
            showDialog('系统出现异常', 'error','','','','','','','','',2);
        }
    },'json');
}
$(function(){
    <?php if (!empty($output['address_info']['address_id'])) {?>
    showShippingPrice(<?php echo $output['address_info']['city_id'];?>,<?php echo $output['address_info']['area_id'];?>);
    <?php } else {?>
    $('#edit_reciver').click();
    <?php }?>
});
</script>