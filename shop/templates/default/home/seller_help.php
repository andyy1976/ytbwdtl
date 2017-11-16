<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]> <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]> <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<title>商家帮助</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/css/TouchSlide.1.1.js"></script>
<!--[if lte IE 8]>
<script src="js/respond.min.js"></script>
<script src="js/html5shiv.min.js"></script>
<![endif]-->
<style>
.focus{ width:1000px; height:200px;  margin:0 auto; position:relative; overflow:hidden;   }
.focus .hd{ width:100%; height:5px;  position:absolute; z-index:1; bottom:0; text-align:center;  }
.focus .hd ul{ overflow:hidden; display:-moz-box; display:-webkit-box; display:box; height:5px; background-color:rgba(51,51,51,0.5);  margin-left: 10px; }
.focus .hd ul li{ -moz-box-flex:1; -webkit-box-flex:1; box-flex:1;}
.focus .hd ul .on{ background:#FF4000;  }
.focus .bd{ position:relative; z-index:0; }
.focus .bd li img{ width:100%;  height:200px; }
.focus .bd li a{ -webkit-tap-highlight-color:rgba(0, 0, 0, 0); /* 取消链接高亮 */ }

.tempWrap{
  margin-left: 10px;
}
.tempWrap img{
  height: 200px;
}

 
</style>
</head>

<body>
<div class="seller_nav">
  <div class="seller_1200"><a href="#" target="_blank">首页</a><a href="#" target="_blank">云豆中心</a><a href="#" target="_blank">营销活动规则</a><a href="<?php echo urlShop('seller_help', 'business');?>" target="_blank">商学院</a></div>
</div>
<div class="seller_box">
  <div class="seller_1200">
    <div class="seller_l">
      <h3>商城规则总览</h3>
      <?php foreach ($output['sub_class_list'] as $k=>$v){?>
      <dl>
        <dt><?php echo $v['ac_name']?></dt>
        <?php foreach ($v['children'] as $k1=>$v1){?>
        <dd><a href="<?php echo urlShop('seller_help', 'seller_infor', array('ac_id'=>$v1['ac_id']));?>"><?php echo $v1['ac_name']?></a></dd>
        <?php }?>
      </dl>
     <?php }?>
      <p><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/1111.jpg" alt=""/></p>
      <p>云托邦官方平台</p>
    </div>
    <div class="seller_r">
      <div class="seller_b">
          <!-- <a href="#" target="_blank"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/seller.jpg" alt=""/></a> -->

          <div id="focus" class="focus">
            <div class="hd">
              <ul></ul>
            </div>
            <div class="bd">
              <!-- <ul>
                  <li><a href="#"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/seller.jpg" alt=""/></a></li>
                  <li><a href="#"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/seller.jpg" alt=""/></a></li>
                  <li><a href="#"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/seller.jpg" alt=""/></a></li>
                  <li><a href="#"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/seller.jpg" alt=""/></a></li>
              </ul> -->
              <?php echo rec(6);?>
            </div>
          </div>

      </div>
      <script type="text/javascript">
        TouchSlide({ 
          slideCell:"#focus",
          titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
          mainCell:".bd ul", 
          effect:"leftLoop", 
          autoPlay:true,//自动播放
          autoPage:true //自动分页
        });
      </script>

      <div class="seller_hot_ad">
        <div class="seller_r_l">
          <div class="hot_rule">
            <div class="rule_t"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/hot.png" alt=""/>
              <h3>热门规则</h3>
              <em>|</em><a href="#" target="_blank">万店通联商家总则</a><a href="#" target="_blank">万店通联会员总则</a><a href="#" target="_blank">营销活动规则</a></div>
            <div class="rule_n">
            <?php foreach ($output['sub_class_list1'] as $k=>$v){?>
              <dl>
                <dt><?php echo $v['ac_name']?></dt>
                <?php foreach ($v['children'] as $k1=>$v1){?>
                <dd><a href="<?php echo urlShop('seller_help', 'seller_infor', array('ac_id'=>$v1['ac_id']));?>" target="_blank"><?php echo $v1['ac_name']?></a></dd>
                <?php }?>
              </dl>
            <?php }?>
            </div>
          </div>
          <div class="seller_zt">
            <h3>商家专题</h3>
            <?php foreach ($output['wenzhang'] as $k=>$v){?>
            <dl>
              <dt><a href="#" target="_blank"><img src="data/upload/shop/article/<?php echo $v['article_img']?>" alt=""/></a></dt>
              <dd>
                <h4><a href="<?php echo urlShop('seller_help', 'seller_infor', array('article_id'=>$v['article_id']));?>" target="_blank"><?php echo $v['article_title']?></a></h4>
                <p><?php echo $v['article_blurb']?></p>
              </dd>
            </dl>
            <?php }?>
          </div>
        </div>
        <div class="seller_r_r">
          <ul>
            <li><?php echo loadadv(1053);?></li>
            <li><?php echo loadadv(1055);?></li>
            <li><?php echo loadadv(1056);?></li>
            <li><a href="#" target="_blank"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/seller_p.jpg" alt=""/></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
