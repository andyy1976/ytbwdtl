<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=sign_online&op=sign_online_uncheck" title="返回会员内推升级待审核列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>会员内推升级申请 - 查看加盟会员“<?php echo $output['joinin_detail']['update_member_truename'];?>”的升级代理信息</h3>
        <h5>会员内推升级申请的查看、审核操作</h5>
      </div>
    </div>
  </div>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="15">招商方(推荐人)信息表</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">招商方(推荐人)ID：</th>
        <td><?php echo $output['joinin_detail']['submit_member_id'];?></td>
      </tr>
      <tr>
        <th>招商方(推荐人)姓名：</th>
        <td><?php echo $output['joinin_detail']['submit_member_truename'];?></td>
      </tr>
      <tr>
        <th>招商方(推荐人)电话：</th>
        <td><?php echo $output['joinin_detail']['submit_member_mobile'];?></td>
      </tr>
      <tr>
        <th>招商方(推荐人)授权书：</th>
        <td><a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['authorization_image'];?>"> <img src="<?php echo $output['joinin_detail']['authorization_image'];?>" alt="招商方(推荐人)授权书" /> </a></td>
      </tr>
    </tbody>
  </table>
  <form id="form_store_verify" action="index.php?act=sign_online&op=store_joinin_verify" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="id" type="hidden" value="<?php echo $output['joinin_detail']['id'];?>" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="15">升级会员(加盟方)信息表</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">加盟方ID：</th>
          <td><?php echo $output['joinin_detail']['update_member_id'];?></td>
        </tr>
        <tr>
          <th class="w150">加盟方姓名：</th>
          <td><?php echo $output['joinin_detail']['update_member_truename'];?></td>
        </tr>
        <tr>
          <th>加盟方手机号：</th>
          <td><?php echo $output['joinin_detail']['update_member_mobile'];?></td>
        </tr>
        <tr>
          <th class="w150">加盟方原级别：</th>
          <td><?php echo $output['joinin_detail']['old_level'];?></td>
        </tr>
        <tr>
          <th class="w150">加盟方加盟级别：</th>
          <td><?php echo $output['joinin_detail']['update_level'];?></td>
        </tr>
        <tr>
          <th>加盟方原区域详情：</th>
          <td><?php echo $output['joinin_detail']['old_level_detail'];?></td>
        </tr>
        <tr>
          <th>加盟方加盟区域详情：</th>
          <td><?php echo $output['joinin_detail']['update_level_detail'];?></td>
        </tr>
        <tr>
          <th>加盟总费用：</th>
          <td><?php echo $output['joinin_detail']['update_amount_total'];?>元</td>
        </tr>
        <tr>
          <th>加盟首期费用：</th>
          <td><?php echo $output['joinin_detail']['update_amount_first'];?>元</td>
        </tr>
        <tr>
          <th>加盟剩余费用偿还期限：</th>
          <td><?php echo $output['joinin_detail']['update_amount_last_date'];?>天</td>
        </tr>
        <tr>
          <th>加盟方证件信息：</th>
          <td><?php echo $output['joinin_detail']['document_number'];?> -（<?php echo $output['joinin_detail']['document_type'] ==1?'身份证':'护照';?>）</td>
        </tr>
        <tr>
          <th>加盟方身份证信息：</th>
          <td><a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['idcard_positive_image'];?>"> <img src="<?php echo $output['joinin_detail']['idcard_positive_image'];?>" alt="加盟方身份证正面信息" /></a>&nbsp;&nbsp;&nbsp;<a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['idcard_opposite_image'];?>"> <img src="<?php echo $output['joinin_detail']['idcard_opposite_image'];?>" alt="加盟方身份证正面信息" /></a></td>
        </tr>
        <tr>
          <th>加盟方打款凭证：</th>
          <td><a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['payment_image'];?>"> <img src="<?php echo $output['joinin_detail']['payment_image'];?>" alt="加盟方打款凭证" /> </a></td>
        </tr>
        <?php if ($output['joinin_detail']['business_licence_image']) {?>
        <tr>
          <th>加盟方营业执照：</th>
          <td><a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['business_licence_image'];?>"> <img src="<?php echo $output['joinin_detail']['business_licence_image'];?>" alt="加盟方营业执照" /> </a></td>
        </tr>
        <?php }?>
        <?php if ($output['joinin_detail']['organization_code_image']) {?>
        <tr>
          <th>加盟方组织机构代码证：</th>
          <td><a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['organization_code_image'];?>"> <img src="<?php echo $output['joinin_detail']['organization_code_image'];?>" alt="加盟方组织机构代码证" /> </a></td>
        </tr>
        <?php }?>
        <?php if ($output['joinin_detail']['tax_registration_image']) {?>
        <tr>
          <th>加盟方税务登记证：</th>
          <td><a nctype="nyroModal"  href="<?php echo $output['joinin_detail']['tax_registration_image'];?>"> <img src="<?php echo $output['joinin_detail']['tax_registration_image'];?>" alt="加盟方税务登记证" /> </a></td>
        </tr>
        <?php }?>
        <?php if($output['joinin_detail']['update_status'] == 0) { ?>
        <tr>
          <th>审核意见：</th>
          <td colspan="2"><textarea id="joinin_message" name="joinin_message"></textarea></td>
        </tr>
        <?php } else { ?>
        <tr>
          <th>审核意见：</th>
          <td colspan="2"><textarea id="joinin_message" name="joinin_message"><?php echo $output['joinin_detail']['operation_message'];?></textarea></td>
        </tr>        
        <?php }?>
      </tbody>
    </table>
    <?php if($output['joinin_detail']['update_status'] == 0) { ?>
    <div id="validation_message" style="color:red;display:none;"></div>
    <div class="bottom"><a id="btn_pass" class="ncap-btn-big ncap-btn-green mr10" href="JavaScript:void(0);">通过</a><a id="btn_fail" class="ncap-btn-big ncap-btn-red" href="JavaScript:void(0);">拒绝</a> </div>
    <?php } ?>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();

        $('#btn_fail').on('click', function() {
            if($('#joinin_message').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认拒绝申请？')) {
                $('#verify_type').val('fail');
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function() {
          if(confirm('确认通过申请？')) {
              $('#verify_type').val('pass');
              $('#form_store_verify').submit();
          }
        });
    });
</script>