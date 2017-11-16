<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<style type="text/css">
/*.category { display:block !important; }*/
</style>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<div class="clear"></div>
<div class="home-focus-layout"> <?php echo $output['web_html']['index_pic'];?>
  <div class="right-sidebar">
    <div class="right-bannder"></div>
    <div class="right-bannder-content"> <?php echo loadadv(1049);?><?php echo loadadv(1050);?></div>
  </div>
</div>
<div class="home-sale-layout wrapper">
  <div class="left-layout"><div class="index-sale"><?php echo $output['web_html']['index_sale'];?></div></div>
    <div class="right-sidebar">
    <div class="title">
      <h3>商城快报</h3>
    </div>
     <div class="news">
     <ul>
      <?php if(!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
          <?php foreach($output['show_article']['notice']['list'] as $val) { ?>
          <li><a target="_blank" href="<?php echo empty($val['article_url']) ? urlMember('article', 'show',array('article_id'=> $val['article_id'])):$val['article_url'] ;?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],24);?> </a>
            <time>(<?php echo date('m-d',$val['article_time']);?>)</time>
          </li>
          <?php } ?>
          <?php } ?>
    </ul>
      </div>
      <div class="ntrance">
      <ul>
        <!-- <li><a rel="nofollow" href="<?php echo urlShop('invite', 'index');?>" target="_self"><i class="i_ico01"></i>推广返利</a></li> -->
    <!-- <li><a rel="nofollow" href="<?php echo BASE_SITE_URL;?>/other/service/index.html" target="_blank"><i class="i_ico02"></i>7大服务</a></li> -->
    <!-- <li><a rel="nofollow" href="<?php echo BASE_SITE_URL;?>/other/guide/index.html" target="_blank"><i class="i_ico03"></i>导购流程</a></li> -->
    <!-- <li><a rel="nofollow" href="<?php echo DELIVERY_SITE_URL;?>" target="_self"><i class="i_ico04"></i>物流自提</a></li> -->
      <li><a rel="nofollow" href="<?php echo urlShop('show_joinin', 'index');?>" target="_self"><i class="i_ico05"></i>招商入驻</a></li>
      <li><a rel="nofollow" href="<?php echo urlShop('seller_login','show_login');?>" target="_self"><i class="i_ico06"></i>商家管理</a></li>
      
      </ul>
    </div>
</div>
</div>
<div class="wrapper">
  <div class="mt10">
    <div class="mt10"><?php echo loadadv(11);?></div>
  </div>
</div>
<?php echo $output['web_html']['index'];?> 

</div>
<!-- <div class="wrapper index-brand">
<div class="brand-title">
<a href="<?php echo SHOP_SITE_URL;?>/index.php?act=brand&op=index">更多品牌&nbsp;&nbsp;&gt;</a>
<h3>推荐品牌<span>品牌汇集，一站购齐</span></h3>
</div>
  <ul class="logo-list">
    <?php if(!empty($output['brand_r'])){?>
    <?php foreach($output['brand_r'] as $key=>$brand_r){?>
    <li> <a target="_blank" href="<?php echo urlShop('brand', 'list',array('brand'=>$brand_r['brand_id']));?>" alt="<?php echo $brand_r['brand_name'];?>" title="<?php echo $brand_r['brand_name'];?>"><img width="120" height="40" src="<?php echo brandImage($brand_r['brand_pic']);?>"><span><?php echo $brand_r['brand_name'];?></span></a></li>
    <?php } }?>
  </ul>
</div> -->
<div class="clear"></div>
<div class="wrapper">
  <div class="mt20"><?php echo loadadv(9,'html');?></div>
</div>
<!-- <div class="index-link wrapper">
  <dl class="website">
    <dt>合作伙伴 | 友情链接<b></b></dt>
    <dd>
      <?php 
		  if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] == ''){
		  ?>
      <a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],15);?></a>
      <?php
		  		}
		 	}
		 }
		 ?>
    </dd>
  </dl>
</div> -->
<div id="nav_box">
  <ul>
<?php if (is_array($output['lc_list']) && !empty($output['lc_list'])) {$i=0 ?>
<?php foreach($output['lc_list'] as $v) { $i++?>
<li class="nav_h_<?php echo $i;?> <?php if($i==1) echo 'hover'?>"><a href="javascript:;" class="num"><?php echo $v['value']?></a> <a href="javascript:;" class="word"><?php echo $v['name']?></a></li>
<?php }} ?>
  </ul>
</div>


