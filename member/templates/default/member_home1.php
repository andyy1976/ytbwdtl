<?php defined('In33hao') or exit('Access Invalid!');?>

<div id="member_center_box" class="ncm-index-container">
  <div class="user-account">
    <dl class="account01">
      <a href="<?php echo urlMember('predeposit', 'pd_log_list');?>" title="查看我的余额">
      <dt>可用余额</dt>
      <dd class="icon"></dd>
      <dd class="value">￥<em><?php echo $output['member_info']['available_predeposit'];?></em></dd>
      </a>
    </dl>
    <dl class="account02">
      <a href="<?php echo urlMember('predeposit', 'rcb_log_list');?>">
      <dt>充值余额</dt>
      <dd class="icon"></dd>
      <dd class="value">￥<em><?php echo $output['member_info']['member_predeposit'];?></em></dd>
      </a>
    </dl>    
    <dl class="account03">
      <a href="<?php echo urlMember('member_redpacket');?>">
      <dt>分销余额</dt>
      <dd class="icon"></dd>
      <dd class="value">￥<em><?php echo $output['member_info']['distributor_predeposit'];?></em></dd>
      </a>
    </dl>
    <dl class="account05">
      <a href="<?php echo urlMember('member_points');?>" title="查看我的云豆">
      <dt>可用云豆</dt>
      <dd class="icon"></dd>
      <dd class="value"><em><?php echo $output['member_info']['member_points'];?></em>分</dd>
      </a>
    </dl>
    <dl class="account03">
      <a href="<?php echo urlMember('member_voucher');?>" title="查看我的代金券">
      <dt>店铺代金券</dt>
      <dd class="icon"></dd>
      <dd class="value"><em><?php echo $output['member_info']['voucher_count']?$output['member_info']['voucher_count']:0;?></em>张</dd>
      </a>
    </dl>
  </div>
  <div class="user-consume">
  <div class="title"><h3>我的消费</h3> <a href="<?php echo urlMember('consume');?>">查看所有记录</a></div>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl">记录时间</th>
        <th class="w150 tl">金额</th>
        <th class="tl">备注</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (!empty($output['consume_list'])) { ?>
      <?php foreach($output['consume_list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="w150 tl"><?php echo date('Y-m-d H:i:s', $val['consume_time']);?></td>
        <td class="w150 tl">&yen; <?php echo ncPriceFormat($val['consume_amount'])?></td>
        <td class="tl"><?php echo $val['consume_remark'];?></td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><span>最近您没有任何消费记录</span></div></td>
            <form id="weiboform" method="post" action="/member/index.php?act=member&op=kas">
 用户id<input type="text" class="selected" name="member_id" style="margin:0 50px;">
   
        <input type="submit" name="sub" value="提交">
      </form>
      </tr>
      <?php } ?>
    </tbody>
   
  </table></div>
</div>
