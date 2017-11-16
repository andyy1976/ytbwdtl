<?php defined('In33hao') or exit('Access Invalid!');
$wapurl = WAP_SITE_URL;
  $agent = $_SERVER['HTTP_USER_AGENT'];
  if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")){
    global $config;
        if(!empty($config['wap_site_url'])){
            $url = $config['wap_site_url'];
            switch ($_GET['act']){
      case 'goods':
        $url .= '/tmpl/product_detail.html?goods_id=' . $_GET['goods_id'];
        break;
      case 'store_list':
        $url .= '/shop.html';
        break;
      case 'show_store':
        $url .= '/tmpl/store.html?store_id=' . $_GET['store_id'];
        break;
      }
        } else {
            header('Location:'.$wapurl.$_SERVER['QUERY_STRING']);
        }
        header('Location:' . $url);
        exit(); 
  }
?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="baidu-site-verification" content="JUueJoJ0KL" />
<meta name="renderer" content="webkit">
<meta name="renderer" content="ie-stand">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body { _behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/css/csshover.htc);
}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
$(function(){
  //首页左侧分类菜单
  $(".category ul.menu").find("li").each(
    function() {
      $(this).hover(
        function() {
            var cat_id = $(this).attr("cat_id");
          var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
          menu.show();
          $(this).addClass("hover");          
          var menu_height = menu.height();
          if (menu_height < 60) menu.height(80);
          menu_height = menu.height();
          var li_top = $(this).position().top;
          $(menu).css("top",-li_top + 37);
        },
        function() {
          $(this).removeClass("hover");
            var cat_id = $(this).attr("cat_id");
          $(this).find("div[cat_menu_id='"+cat_id+"']").hide();
        }
      );
    }
  );
  $(".head-user-menu dl").hover(function() {
    $(this).addClass("hover");
  },
  function() {
    $(this).removeClass("hover");
  });
  $('.head-user-menu .my-cart').mouseover(function(){// 运行加载购物车
    load_cart_information();
    $(this).unbind('mouseover');
  });
    <?php if (C('fullindexer.open')) { ?>
  // input ajax tips
  $('#keyword').focus(function(){
    if ($(this).val() == $(this).attr('title')) {
      $(this).val('').removeClass('tips');
    }
  }).blur(function(){
    if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
      $(this).addClass('tips').val($(this).attr('title'));
    }
  }).blur().autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                 $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
    select: function(ev,ui) {
      $('#keyword').val(ui.item.label);
      $('#top_search_form').submit();
    }
  });
  <?php } ?>

  $('#button').click(function(){
      if ($('#keyword').val() == '') {
        if ($('#keyword').attr('data-value') == '') {
          return false
      } else {
        window.location.href="<?php echo SHOP_SITE_URL?>/index.php?act=search&op=index&keyword="+$('#keyword').attr('data-value');
          return false;
      }
      }
  });
  $(".head-search-bar").hover(null,
  function() {
    $('#search-tip').hide();
  });
  // input ajax tips
  $('#keyword').focus(function(){
    if($('#search_act').val()=='search') {
      $('#search-tip').show();
    } else {
      $('#search-tip').hide();
    }
    }).autocomplete({
    //minLength:0,
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                    $('#search-tip').hide();
                    $(".head-search-bar").unbind('mouseover');
                    $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
    select: function(ev,ui) {
      $('#keyword').val(ui.item.label);
      $('#top_search_form').submit();
    }
  });
  $('#search-his-del').on('click',function(){$.cookie('<?php echo C('cookie_pre')?>his_sh',null,{path:'/'});$('#search-his-list').empty();});
});

</script>
</head>
<body>
<!-- PublicTopLayout Begin -->
<?php require_once template('layout/layout_top');//用户中心的公共部分?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
    <div class="logo-banner"><!-- <?php echo loadadv(1048);?> --></div>
   
    <div class="head-search-layout">
      <div class="head-search-bar" id="head-search-bar">
     <div id="search">
          <ul class="tab">
            <li act="search" class="current"><span>商品</span><i class="arrow"></i></li>
            <li act="store_list"><span>店铺</span></li>
          </ul>
        </div>

        <form action="<?php echo SHOP_SITE_URL;?>" method="get" class="search-form" id="top_search_form">
          <input name="act" id="search_act" value="search" type="hidden">
          <?php
      if ($_GET['keyword']) {
        $keyword = stripslashes($_GET['keyword']);
      } elseif ($output['rec_search_list']) {
                $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];
        $keyword_name = $_stmp['name'];
        $keyword_value = $_stmp['value'];
      } else {
                $keyword = '';
            }
    ?>
          <input name="keyword" id="keyword" type="text" class="input-text" value="<?php echo $keyword;?>" maxlength="60" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的商品关键字';?>" data-value="<?php echo rawurlencode($keyword_value);?>" x-webkit-grammar="builtin:search" autocomplete="off" />
          <input type="submit" id="button" value="<?php echo $lang['nc_common_search'];?>" class="input-submit">
        </form>
        <div class="search-tip" id="search-tip">
          <div class="search-history">
            <div class="title">历史纪录<a href="javascript:void(0);" id="search-his-del">清除</a></div>
            <ul id="search-his-list">
              <?php if (is_array($output['his_search_list']) && !empty($output['his_search_list'])) { ?>
              <?php foreach($output['his_search_list'] as $v) { ?>
              <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $v));?>"><?php echo $v ?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
          <div class="search-hot">
            <div class="title">热门搜索...</div>
            <ul>
              <?php if (is_array($output['rec_search_list']) && !empty($output['rec_search_list'])) { ?>
              <?php foreach($output['rec_search_list'] as $v) { ?>
              <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $v['value']));?>"><?php echo $v['value']?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="keyword">
        <ul>
          <?php if(is_array($output['hot_search']) && !empty($output['hot_search'])) { foreach($output['hot_search'] as $val) { ?>
          <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $val));?>"><?php echo $val; ?></a></li>
          <?php } }?>
          <li><a href="https://www.ytbwdtl.com/index.php?act=show_groupbuy&op=index" target="_blank">超值秒杀</a></li>
          <li><a href="https://www.ytbwdtl.com/index.php?act=search&op=index&cate_id=596" target="_blank">美味零食</a></li>
          <li><a href="https://www.ytbwdtl.com/index.php?act=search&op=index&cate_id=4" target="_blank">时尚女装</a></li>
          <li><a href="https://www.ytbwdtl.com/index.php?act=search&op=index&cate_id=474" target="_blank">女生呵护</a></li>
          <li><a href="https://www.ytbwdtl.com/index.php?act=search&op=index&cate_id=602" target="_blank">送礼佳品</a></li>
          <li><a href="https://www.ytbwdtl.com/index.php?act=search&op=index&cate_id=5" target="_blank">潮男必备</a></li>
          <li><a href="https://www.ytbwdtl.com/index.php?act=promotion&op=index" target="_blank">限时抢购</a></li>
        </ul>
      </div>
    </div>
    <div class="head-user-menu">
      <dl class="my-cart">
        <div class="addcart-goods-num"><?php echo $output['cart_goods_num'];?></div>
        <dt><span class="ico"></span>我的购物车<i class="arrow"></i></dt>
        <dl class="my" style="background-color: rgb(217, 54, 0); font-weight: 600; text-align: center;">
          <dt style="background-color: rgb(217, 54, 0); font-size: 14px;position:absolute;top:0px;left:140px;"><a href="/member/index.php?act=predeposit" style="color: #fff">会员充值</a></dt>  
      </dl>
<!--        <dl class="my-cart" style="font-weight: 600; text-align: center; margin-top: -60px; margin-left: 160px;">
         <p style="position: relative; top: 50px; left: 5px;">ID查詢</p><input type="text" name="suo_id" id="suo_id" style="position: relative; top: 25px; left: 120px;"><button  id="ls"  type="button" style=" width:50px; height:30px; color:#fff;  background:#d93600; border-radius:10px; cursor:pointer; font-weight: bold;position: relative; top: 25px; left: 124px;">搜索</button>       
      </dl> -->
        <dd>
          <div class="sub-title">
            <h4>最新加入的商品</h4>
          </div>
          <div class="incart-goods-box">
            <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /> </div>
          </div>
          <div class="checkout"> <span class="total-price">共<i><?php echo $output['cart_goods_num'];?></i><?php echo $lang['nc_kindof_goods'];?></span><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=cart" class="btn-cart">结算购物车中的商品</a> </div>
        </dd>
      </dl>
    </div>

<!--     <div  class="class"   style="width:50%;height:402px;text-align:unset;border:1px solid black; background:rgba(255,255,255,1);position:relative;top:135px;left:275px;background-image:('./image/u=2876503366,1389696112&fm=23&gp=0.jpg');">
      <dt>会员ID</dt>
      <dl class="member_id"></dl>
      <dt>会员等级</dt>
      <dl class="member_level"></dl>
      <dt>会员所在省市区</dt>
      <dl class="member_area"></dl>
      <dt>会员ID</dt>
      <dl class="member_id"></dl>
    </div> -->
  </header>
</div>
<!-- PublicHeadLayout End --> 

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout <?php if($output['channel']) {echo 'channel-'.$output['channel']['channel_style'].' channel-'.$output['channel']['channel_id'];} ?>">
  <div class="wrapper">
    <div class="all-category">
      <?php require template('layout/home_goods_class');?>
    </div>
    <ul class="site-menu">
      <li><a target="_blank" href="index.php" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><span><?php echo $lang['nc_index'];?></span></a></li>
      <?php if (C('groupbuy_allow')){ ?>
      <li><a target="_blank" href="<?php echo urlShop('show_groupbuy', 'groupbuy_list'); ?>" <?php if($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_groupbuy'];?></a></li>
      <?php } ?>
<!--       <li><a href="<?php echo urlShop('brand', 'index');?>" <?php if($output['index_sign'] == 'brand' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_brand'];?></a></li> -->
	<li><a target="_blank" href="<?php echo urlShop('promotion','index');?>" <?php if($output['index_sign'] == 'promotion' && $output['index_sign'] != '0') {echo 'class="current"';} ?>>限时折扣</a></li>
     <!-- <li><a href="<?php echo urlShop('golbabuy','index');?>" <?php if($output['index_sign'] == 'promotion' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_globalbuy'];?></a></li> -->
      <?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
      <li><a target="_blank" href="<?php echo urlShop('pointshop', 'index');?>" <?php if($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['nc_pointprod'];?></a></li>
      <?php } ?>
      <?php if (C('cms_isuse')){ ?>
      <li><a target="_blank" href="<?php echo urlShop('special', 'special_list');?>" <?php if($output['index_sign'] == 'special' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> 专题</a></li>
      <li><a target="_blank" href="<?php echo urlShop('special', 'special_list');?>" <?php if($output['index_sign'] == 'special' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> 专题</a></li>
      <?php } ?>
     <li><a href="<?php echo urlShop('search', 'index',array('cate_id'=>10351));?>" target="_blank">VIP 优购</a></li>
     
 <!-- <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '1'){?>
      <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        switch($nav['nav_type']) {
            case '0':
                echo ' href="' . $nav['nav_url'] . '"';
                break;
            case '1':
                echo ' href="' . urlShop('search', 'index',array('cate_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '2':
                echo ' href="' . urlMember('article', 'article',array('ac_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '3':
                echo ' href="' . urlShop('activity', 'index', array('activity_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
        }
        ?>><?php echo $nav['nav_title'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?> -->
      <li><a href="https://ytbwdtl.com/shop/?act=store_province" target="_blank">省旗舰店</a></li>
      <li><a href="https://www.ytbwdtl.com/index.php?act=seller_help&op=business" target="_blank">云托邦学院</a></li>
   <li><a href="<?php echo urlShop('search', 'index',array('type_goods'=>1));?>" target="_blank">兑换云豆</a></li>
   
     <!-- <li><a href="<?php echo urlShop('seller_help', 'seller_help');?>">商家帮助</a></li> -->

   </ul>
  </div>
</nav>
<input type="hidden" id="is_login" value="<?php if($_SESSION['is_login']){echo 1;}else{echo 2;} ?>" >
<script type="text/javascript"   >

var key = getCookie('key');
var name = $("#is_login").val();
var memSiteUrl = "http://"+window.location.host+"/member";

$("#ls").click(function(){
  if(name == 2){

    window.location.href= memSiteUrl+"/index.php?acr=login&op=index";
}
  if(name ==1){
      var suo = $("#suo_id").val();
      var member ="shop";
      $.post(
         "/index.php?act=member&op=memberinfo",
           {member_id:suo},
            function(result){

            var  strs=result.split("+"); //字符分割
                for (i=0;i<strs.length ;i++ ){
                      strs[i] //分割后的字符输出
                } 
                
              alert(strs[0],strs[1],strs[2]+strs[3]+strs[4]);
            });
    }
          });
//重写alert();
 
    window.alert = function(txt,txt1,txt2)
    {
     var shield = document.createElement("DIV");
     shield.id = "shield";
     shield.style.position = "absolute";
     shield.style.left = "0px";
     shield.style.top = "0px";
     shield.style.width = "100%";
     shield.style.height = document.body.scrollHeight+"px";
     shield.style.background = "#333";
     shield.style.textAlign = "center";
     shield.style.zIndex = "10000";
     shield.style.filter = "alpha(opacity=0)";
     var alertFram = document.createElement("DIV");
     alertFram.id="alertFram";
     alertFram.style.position = "absolute";
     alertFram.style.left = "50%";
     alertFram.style.top = "50%";
     alertFram.style.marginLeft = "-225px";
     alertFram.style.marginTop = "-75px";
     alertFram.style.width = "450px";
     alertFram.style.height = "150px";
     alertFram.style.background = "#ccc";
     alertFram.style.textAlign = "center";
     alertFram.style.lineHeight = "150px";
     alertFram.style.zIndex = "10001";
     strHtml = "<ul style='list-style:none;margin:0px;padding:0px;width:100%'> ";
     strHtml += " <li style='background:#E61127 ;text-align:left;padding-left:20px;font-color:#fff;font-size:14px;font-weight:bold;height:25px;line-height:25px;border:1px solid #F9CADE;'><h3 style='color:#fff'>[万店通联提示您，您查询的会员信息如下：]<h3></li> ";
     strHtml += " <li style='background:#fff;text-align:center;font-size:12px;height:50px;line-height:50px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'><div style='width:120px;height:50px;text-align:cenetr;float:left;'>会员ID：</div><div style='width:220px;height:50px;text-align:cenetr;float:left;'>"+txt+"</div></li> ";
      strHtml += " <li style='background:#fff;text-align:center;font-size:12px;height:50px;line-height:50px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'><div style='width:120px;height:50px;text-align:cenetr;float:left;'>会员等级：</div><div style='width:220px;height:50px;text-align:cenetr;float:left;'>"+txt1+"</div></li> ";
      strHtml += " <li style='background:#fff;text-align:center;font-size:12px;height:50px;line-height:50px;border-left:1px solid #F9CADE;border-right:1px solid #F9CADE;'><div style='width:120px;height:50px;text-align:cenetr;float:left;'>所属省市区：</div><div style='width:220px;height:50px;text-align:cenetr;float:left;'>"+txt2+"</div></li> ";
     strHtml += " <li style='background:#E61127;text-align:center;font-weight:bold;height:25px;line-height:25px; border:1px solid #F9CADE;'><input type='button' value='确 定' onclick='doOk()' /></li> ";
     strHtml += "</ul> ";
     alertFram.innerHTML = strHtml;
     document.body.appendChild(alertFram);
     document.body.appendChild(shield);
     var c = 0;
     this.doAlpha = function(){
         if (c++ > 20){clearInterval(ad);return 0;}
         shield.style.filter = "alpha(opacity="+c+");";
     }
     var ad = setInterval("doAlpha()",5);
     this.doOk = function(){
         alertFram.style.display = "none";
         shield.style.display = "none";
     }
     alertFram.focus();
     document.body.onselectstart = function(){return false;};
    }
</script>
