<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncap-form-default">
  <dl class="row">
    <dt class="tit">
      <label>订单编号：</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['openshop_order_sn']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>充值单号：</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['openshop_pay_sn']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>用户ID：</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['member_id']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>缴费金额(<?php echo $lang['currency_zh'];?>)：</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pay_amount']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>添加时间：</label>
    </dt>
    <dd class="opt"> <?php echo @date('Y-m-d H:i:s',$output['info']['add_time']); ?> </dd>
  </dl>
  <?php if (intval($output['info']['pay_time'])) {?>
  <dl class="row">
    <dt class="tit">
      <label>支付方式：</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pay_style']; ?> </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>支付时间：</label>
    </dt>
    <dd class="opt">
      <?php if (date('His',$output['info']['pay_time']) == 0) {?>
      <?php echo date('Y-m-d',$output['info']['pay_time']);?>
      <?php } else {?>
      <?php echo date('Y-m-d H:i:s',$output['info']['pay_time']);?>
      <?php } ?>
    </dd>
  </dl>
<!--   <dl class="row">
    <dt class="tit">
      <label>第三方支付平台交易号</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['pdr_trade_sn'];?> </dd>
  </dl> -->
  <?php } ?>
  <!-- 显示管理员名称 -->
  <?php if (trim($output['info']['oper_admin']) != ''){ ?>
  <dl class="row">
    <dt class="tit">
      <label>操作人：</label>
    </dt>
    <dd class="opt"> <?php echo $output['info']['oper_admin']; ?> </dd>
  </dl>
  <?php }?>
  <?php if (!intval($output['info']['pdr_payment_state'])) {?>
  <div class="bot"><a  class="ncap-btn-big ncap-btn-green" href="index.php?act=store&op=recharge_edit&id=<?php echo $output['info']['id']; ?>"><span>更改交易状态</span></a></div>
  <?php } ?>
</div>
