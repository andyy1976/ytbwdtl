<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=predeposit&op=predeposit" title="返回充值管理列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>开通店铺费用 - 处理开通店铺的缴费</h3>
        <h5>会员开通店铺缴费管理</h5>
      </div>
    </div>
  </div>
  <form method="post" name="form1" id="form1" action="index.php?act=store&op=recharge_edit&id=<?php echo intval($_GET['id']);?>">
    <input type="hidden" name="form_submit" value="ok"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>订单编号</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['openshop_order_sn']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>缴费单号</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['openshop_pay_sn']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>缴费金额(<?php echo $lang['currency_zh']; ?>)</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['pay_amount']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>会员ID</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['info']['member_id']; ?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">支付时间</label>
        </dt>
        <dd class="opt">
          <input id="payment_time" class="input-txt" name="payment_time" value="" type="text" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="site_name">支付方式</label>
        </dt>
        <dd class="opt">
          <select name="payment_code" class="s-select">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" id="ncsubmit" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#payment_time').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'});
    $('#ncsubmit').click(function(){
    	if($("#form1").valid()){
        	if (confirm("操作提醒：\n该操作不可撤销\n提交前请务必确认是否已收到付款\n继续操作吗?")){
        	}else{
        		return false;
        	}
        	$('#form1').submit();
    	}
    });
	$("#form1").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	payment_time : {
                required : true
            },
            payment_code : {
                required : true
            }     
        },
        messages : {
        	payment_time : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写付款时间'
            },
            payment_code : {
                required : '<i class="fa fa-exclamation-circle"></i>请选择付款方式'
            }
        }
	});
});
</script>