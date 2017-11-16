<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员购买</h3>
       
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=member&op=index">会员管理</a></li>
        <li><a href="index.php?act=member&op=finance">财务管理</a></li>
        <li><a href="JavaScript:void(0);" class="current">会员购买</a></li>
        <li><a href="index.php?act=member&op=pd_cash_list">提款管理</a></li>
        <li><a href="index.php?act=member&op=pd_cash_today">当天数据管理</a></li>
      </ul>
    </div>
  </div>
  <form id="points_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>会员名称</label>
        </dt>
        <dd class="opt">
          <input type="text" name="member_name" id="member_name" class="input-txt" onchange="javascript:checkmember();">
          <input type="hidden" name="member_id" id="member_id" value='0'/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="tr_memberinfo">
        <dt class="tit">符合条件的会员</dt>
        <dd class="opt" id="td_memberinfo"></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>购买类型</label>
        </dt>
        <dd class="opt">
          <select id="operatetype" name="operatetype" >
            <option value="1">红苹果</option>
            <option value="2">黄苹果</option>
            <option value="3">蓝苹果</option>
            <option value="4">绿苹果</option>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>充值金额</label>
        </dt>
        <dd class="opt">
          <input type="text" id="pointsnum" name="pointsnum" class="input-txt" onBlur='javascript:apptype();'>
          <span class="err"></span>
         
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>描述</label>
        </dt>
        <dd class="opt">
          <textarea name="pointsdesc" rows="6" class="tarea"></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_points_pointsdesc_notice'];?></p>
        </dd>
      </dl>
      <div class="bot" ><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
function checkmember(){
	var membername = $.trim($("#member_name").val());
	if(membername == ''){
		$("#member_id").val('0');
		alert(<?php echo $lang['admin_points_addmembername_error']; ?>);
		return false;
	}
	$.getJSON("index.php?act=points&op=checkmember", {'name':membername}, function(data){
	        if (data)
	        {
		        $("#tr_memberinfo").show();
				var msg= "<?php echo $lang['admin_points_member_tip']; ?> "+ data.name + "<?php echo $lang['admin_points_member_tip_2']; ?>" + data.points;
				$("#member_name").val(data.name);
				$("#member_id").val(data.id);
		        $("#td_memberinfo").text(msg);
	        }
	        else
	        {
	        	$("#member_name").val('');
	        	$("#member_id").val('0');
		        alert("<?php echo $lang['admin_points_userrecord_error']; ?>");
	        }
	});
}
function apptype(){
  var type=$.trim($("#operatetype").val());
  var pointsnum=$.trim($("#pointsnum").val());
  if(type==1){
    if(pointsnum!=500){
      alert('请输入500的金额');
      return false;
    }
  }
  if(type==2){
    if(pointsnum<1000 || pointsnum>5000){
      alert('请输入1000-5000的金额');
      return false;
    }
  }
  if(type==3){
    if(pointsnum<5500 || pointsnum>10000){
      alert('请输入5500-10000的金额');
      return false;
    }
  }
  if(type==4){
    if(pointsnum<10500 || pointsnum>50000){
      alert('请输入10500-50000的金额');
      return false;
    }
  }
}
$(function(){
	$("#tr_memberinfo").hide();
	
    $('#points_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	member_name: {
				required : true
			},
			member_id: {
				required : true
            },
            pointsnum   : {
                required : true,
                min : 1
            }
        },
        messages : {
			member_name: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_points_addmembername_error'];?>'
			},
			member_id : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_points_member_error_again'];?>'
            },
            pointsnum  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_points_points_null_error']; ?>',
                min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_points_points_min_error']; ?>'
            }
        }
    });
});
</script>