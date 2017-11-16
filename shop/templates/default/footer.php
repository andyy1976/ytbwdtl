<?php defined('In33hao') or exit('Access Invalid!');?>
<?php echo getChat($layout);?>
<script language="javascript">
function fade() {
	$("img[rel='lazy']").each(function () {
		var $scroTop = $(this).offset();
		if ($scroTop.top <= $(window).scrollTop() + $(window).height()) {
			$(this).hide();
			$(this).attr("src", $(this).attr("data-url"));
			$(this).removeAttr("rel");
			$(this).removeAttr("name");
			$(this).fadeIn(500);
		}
	});
}
if($("img[rel='lazy']").length > 0) {
	$(window).scroll(function () {
		fade();
	});
};
fade();
</script>

<div id="cti">
  <div class="wrapper">
    <ul>
      <?php if ($output['contract_list']) {?>
      <?php foreach($output['contract_list'] as $k=>$v){?>
        <?php if($v['cti_descurl']){ ?>
            <li><span class="line"></span><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li>
        <?php }else{ ?>
            <li><span class="line"></span> <span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li>
        <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
<div id="faq">
  <div class="wrapper">
 <div class="wrapper">
<!--       <p style="text-align:center">

Copyright © 2003-2017 MYTOPIA Corporation, All Rights Reserved 广州市云托邦商务服务有限公司 粤ICP备17063513号-1</p> -->
      <p style="text-align:center">

Copyright © 2003-2017 MYTOPIA Corporation, All Rights Reserved 广州市云托邦商务服务有限公司 <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备17063513号-1</a></p>
<p style="text-align:center">公司地址：广州市天河区华夏路30号1510,1511,1512</p>
<p style="text-align:center">热线电话：400-066-1366


              </p>
  <p>
  </div> 
  </div>
</div>
<div id="footer">
  <p>
  <a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlMember('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
      </p>
    <p>
      <?php 
  $host=$_SERVER["HTTP_HOST"];
  
  if($host=='www.wandiantonglian.com' || $host=='wandiantonglian.com'){
    echo '<a  key ="595df7dbefbfb05875bebb50"  logo_size="124x47"  logo_type="realname"  href="http://www.anquan.org" ><script src="//static.anquan.org/static/outer/js/aq_auth.js"></script></a>';
  }else{
    echo '<a  key ="595df79eefbfb04e24635775"  logo_size="124x47"  logo_type="realname"  href="http://www.anquan.org" ><script src="//static.anquan.org/static/outer/js/aq_auth.js"></script></a>';
  }

  ?>
    </p>
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?>  <a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备17063513号-1</a><?php //echo $output['setting_config']['icp_number']; ?></div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<!-- 对比 --> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/compare.js"></script> 
<script type="text/javascript">
$(function(){
	// Membership card
	$('[nctype="mcard"]').membershipCard({type:'shop'});
});
</script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?03464ad6586b89ef20d9728f1b74324c";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>