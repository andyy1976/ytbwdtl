<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]> <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]> <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<title>商学院官网</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/school/busi.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/TouchSlide.1.1.js"></script>
<!--[if lte IE 8]>
<script src="js/respond.min.js"></script>
<script src="js/html5shiv.min.js"></script>
<![endif]-->
<style type="text/css">
.jiangshi li h3{
  text-align:center;
}
.jiangshi li p{
  text-align:center;
}
</style>
</head>

<body>
<div class="busi_nav">
  <div class="busi_1200"><a href="#1F">首页</a><a href="#2F">云托邦商学院</a><a href="#3F">学院讲师</a><a href="#4F">认证考试</a><a href="#5F">学院风采</a><a href="#6F">云托邦精神</a></div>
</div>
<div class="busi_box">
  <div class="busi_1200">
    <div class="focus_login">
      <div class="seller_r">
          <div id="focus" class="focus">
            <div class="hd">
              <ul></ul>
            </div>
            <div class="bd">
              <?php echo rec(12);?>
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
      <div class="busi_login">
        <dl>
          <dt><img src="https://www.ytbwdtl.com/data/upload/shop/common/default_user_portrait.gif"/></dt>
          <?php if ($_SESSION['is_login']) {?>
              <ul style="margin-left: -50px;">
                <li>用户名：<?php echo $_SESSION['member_name'];?></li>
                  <li>级别：<?php echo $output['member_level'];?></li>
                  <li>推荐人：<?php echo $output['member_info']['member_pid'];?></li>
              </ul>
          <?php } else {?>
          <!-- <dt><img src="https://www.ytbwdtl.com/data/upload/shop/common/default_user_portrait.gif"/></dt> -->
            <dd><a href="https://www.ytbwdtl.com/member/index.php?act=login&op=index">登录</a>  <a href="https://www.ytbwdtl.com/member/index.php?act=login&op=register">注册</a></dd>
        </dl>
        <?php } ?>
        
      </div>
    </div>
    <a name="1F"><div class="busi_kec">
      <dl>
        <dt><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/kec_01.jpg" alt=""/></dt>
        <dd class="kec_on">空中课堂</dd>
        <dd>官方认证培训课程</dd>
      </dl>
      <dl>
        <dt><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/kec_02.jpg" alt=""/></dt>
        <dd class="kec_on">必修课程</dd>
        <dd>业务指南成功分享</dd>
      </dl>
      <dl>
        <dt><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/kec_03.jpg" alt=""/></dt>
        <dd class="kec_on">在线点播</dd>
        <dd>随时随地随点随学</dd>
      </dl>
    </div>
    </a>
    <div class="busi_jshi">
      <div class="busi_t">企业新闻</div>
      <div class="jshi_box">
        <div class="jshi-box">
          <ul>
            <li>
              <?php foreach ($output['news1'] as $k=>$v){?>
              <dl>
                <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
                <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
                <dd>
                  <h3><?php echo $v['article_title']?></h3>
                  <p><?php echo $v['article_blurb']?></p>
                </dd>
                </a>
              </dl>
              <?php }?>
            </li>
            <li>
              <?php foreach ($output['news2'] as $k=>$v){?>
              <dl>
                <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
                <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
                <dd>
                  <h3><?php echo $v['article_title']?></h3>
                  <p><?php echo $v['article_blurb']?></p>
                </dd>
                </a>
              </dl>
             <?php }?>
            </li>
          </ul>
          <i class="arrow prev"><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/qiye_l.png"></i><i class="arrow next"><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/qiye_r.png"></i> </div>
      </div>
    </div>
    <a name="3F"><div class="busi_jshi">
      <div class="busi_t">名师推荐·特聘讲师</div>
      <div class="jshi_box tepin_box">
        <div class="jshi-box tepin-box">
          <ul class="jiangshi">
            <li>
              <?php foreach ($output['jiangshi1'] as $k=>$v){?>
              <dl>
                <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
                <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
                <dd>
                  <h3><?php echo $v['article_title']?></h3>
                  <p><?php echo $v['article_blurb']?></p>
                </dd>
                </a>
              </dl>
              <?php }?>
            </li>
            <li>
              <?php foreach ($output['jiangshi2'] as $k=>$v){?>
              <dl>
                <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
                <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
                <dd>
                  <h3><?php echo $v['article_title']?></h3>
                  <p><?php echo $v['article_blurb']?></p>
                </dd>
                </a>
              </dl>
              <?php }?>
            </li>
          </ul>
          <i class="arrow prev"><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/qiye_l.png"></i><i class="arrow next"><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/qiye_r.png"></i> </div>
      </div>
    </div>
    </a>
    <a name="5F"><div class="busi_jshi">
      <div class="busi_t">名师推荐·注册讲师</div>
      <div class="jshi_box zhuce_box">
        <div class="jshi-box zhuce-box">
          <ul class="zhuce">
            <li>
              <?php foreach ($output['reg1'] as $k=>$v){?>
              <dl>
                <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
                <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
                <dd>
                  <h3><?php echo $v['article_title']?></h3>
                  <p><?php echo $v['article_blurb']?></p>
                </dd>
                </a>
              </dl>
              <?php }?>
            </li>
            <li>
              <?php foreach ($output['reg2'] as $k=>$v){?>
              <dl>
                <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
                <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
                <dd>
                  <h3><?php echo $v['article_title']?></h3>
                  <p><?php echo $v['article_blurb']?></p>
                </dd>
                </a>
              </dl>
              <?php }?>
            </li>
          </ul>
          <i class="arrow prev"><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/qiye_l.png"></i><i class="arrow next"><img src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/qiye_r.png"></i> </div>
      </div>
    </div>
    </a>
    <div class="busi_dbo_newk">
      <div class="busi_dbo">
        <div class="busi_t">空中课堂·在线点播</div>
        <div class="dbo_box">
          <?php foreach ($output['video'] as $k=>$v){?>
          <dl>
            <div class="video mt">
              <div id="youkuplayer<?php echo $v['article_id']?>" style="width:200px;height:172px"></div>
              <script type="text/javascript" src="//player.youku.com/jsapi"></script>
              <script type="text/javascript">
                  var article_url = '<?php echo $v['article_url']?>';
                  player = new YKU.Player("youkuplayer"+<?php echo $v['article_id']?>,{
                      styleid: '0',
                      client_id: article_url,
                      vid: article_url,
                      newPlayer: true,
                      show_related: false,
                      autoplay: false
                  });
              </script>
            </div>
            
            <a href="<?php echo urlShop('seller_help', 'business_video', array('article_id'=>$v['article_id']));?>" target="_blank">
            <dd><?php echo $v['article_title']?></dd>

            <dd class="dbo_sm"><?php echo $v['article_blurb']?></dd>
            </a>
          </dl>
          <?php }?>
        </div>
      </div>
      <div class="busi_newk">
        <h3>最新课程</h3>
        <ul>
          <?php foreach ($output['now'] as $k=>$v){?>
          <li>
            <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank"><?php echo $v['article_blurb']?></a>
          </li>
          <?php }?>
        </ul>
      </div>
    </div>
    <a name="6F"><div class="busi_dbo_newk">
      <div class="busi_dbo">
        <div class="busi_t">云托邦·企业精神</div>
        <div class="dbo_box zdao_box">
          <?php foreach ($output['jingshen'] as $k=>$v){?>
          <dl>
            <a href="<?php echo urlShop('seller_help', 'business_video', array('article_id'=>$v['article_id']));?>" target="_blank">
            <dt><img src="<?php echo $v['article_img']?>" alt=""/></dt>
            <dd><?php echo $v['article_title']?></dd>
            <dd class="dbo_sm"><?php echo $v['article_blurb']?></dd>
            </a>
          </dl>
          <?php }?>
        </div>
      </div>
      </a>
      <div class="busi_case">
        <h3>最新活动</h3>
        <?php foreach ($output['anli'] as $k=>$v){?>
        <dl>
          <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
            <dt><img src="<?php echo $v['article_img']?>"/></dt>
            <dd><?php echo $v['article_title']?></dd>
          </a>
        </dl>
        <?php }?>
      </div>
    </div>
    <div class="busi_dbo_newk">
      <!-- <div class="busi_dbo">
        <div class="busi_t">精选课程·业务指导</div>
        <div class="dbo_box zdao_box">
          <?php foreach ($output['zhidao'] as $k=>$v){?>
          <dl>
            <div class="video mt">
              <div id="youkuplayer<?php echo $v['article_id']?>" style="width:200px;height:120px"></div>
              <script type="text/javascript" src="//player.youku.com/jsapi"></script>
              <script type="text/javascript">
                  var article_url = '<?php echo $v['article_url']?>';
                  player = new YKU.Player("youkuplayer"+<?php echo $v['article_id']?>,{
                      styleid: '0',
                      client_id: article_url,
                      vid: article_url,
                      newPlayer: true,
                      show_related: false,
                      autoplay: false
                  });
              </script>
            </div>
            <dd><?php echo $v['article_title']?></dd>
            <dd class="dbo_sm"><?php echo $v['article_blurb']?></dd>
          </dl>
          <?php }?>
        </div>
      </div> -->
      <div class="busi_dbo">
        <div class="busi_t">精选课程·业务指导</div>
        <div class="dbo_box zdao_box">
        <?php foreach ($output['zhidao'] as $k=>$v){?>
          <dl>
            <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
            <dt><img src="<?php echo $v['article_img']?>"/><span>初级</span></dt>
            <dd><?php echo $v['article_title']?></dd>
            <dd class="dbo_sm"><?php echo $v['article_blurb']?></dd>
            </a>
          </dl>
          <?php }?>
        </div>
      </div>
      <div class="busi_case">
        <h3>行业解读</h3>
        <?php foreach ($output['jiedu'] as $k=>$v){?>
        <dl>
          <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$v['article_id']));?>" target="_blank">
          <dt><img src="<?php echo $v['article_img']?>"/></dt>
          <dd><?php echo $v['article_title']?></dd>
          </a>
        </dl>
        <?php }?>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/jquery-1.11.3.js"></script> 
<script src="<?php echo SHOP_TEMPLATES_URL;?>/css/school/unslider.min.js"></script>
</body>
</html>
