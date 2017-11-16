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
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/busi.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/css/TouchSlide.1.1.js"></script>
<!--[if lte IE 8]>
<script src="js/respond.min.js"></script>
<script src="js/html5shiv.min.js"></script>
<![endif]-->
</head>

<body>
<div class="busi_nav">
  <div class="busi_1200"><a href="<?php echo urlShop('seller_help', 'business');?>#1F" >首页</a><a href="<?php echo urlShop('seller_help', 'business');?>#2F">云托邦商学院</a><a href="<?php echo urlShop('seller_help', 'business');?>#3F">学院讲师</a><a href="<?php echo urlShop('seller_help', 'business');?>#4F">认证考试</a><a href="<?php echo urlShop('seller_help', 'business');?>#5F">学院风采</a><a href="<?php echo urlShop('seller_help', 'business');?>#6F">云托邦精神</a></div>
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
    <div class="busi_infor">
    	<h6>
          <a href="javascript:void(0);" onclick="SetHome(this,'https://www.ytbwdtl.com/index.php?act=seller_help&op=business');">设为首页</a>
          <em>|</em>
          <a href="javascript:void(0);" onclick="AddFavorite('我的网站','https://www.ytbwdtl.com/index.php?act=seller_help&op=business')">收藏本站</a>
      </h6>
    	<h1><?php echo $output['data']['article_title']?></h1>
        <div class="news_infor"><?php echo $output['data']['article_content']?></div>
        <div class="busi_sx">
          <?php if ($output['data']['shang_id'] !='') {?>
          <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$output['data']['shang_id']));?>">上一篇</a>
          <?php } ?>
          <?php if ($output['data']['xia_id'] !='') {?>
          <a href="<?php echo urlShop('seller_help', 'business_infor', array('article_id'=>$output['data']['xia_id']));?>">下一篇</a>
          <?php } ?>
        </div>
    </div>
  </div>
</div>
<script  type="text/javascript">
//设为首页
function SetHome(obj,url){
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage(url);
    }catch(e){
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }catch(e){
                alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
            }
        }else{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
        }
    }
}
//收藏本站
function AddFavorite(title, url) {
    try {
        window.external.addFavorite(url, title);
    }
    catch (e) {
        try {
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) {
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//保存到桌面
function toDesktop(sUrl,sName){
try {
    var WshShell = new ActiveXObject("WScript.Shell");
    var oUrlLink =          WshShell.CreateShortcut(WshShell.SpecialFolders("Desktop")     + "\\" + sName + ".url");
    oUrlLink.TargetPath = sUrl;
    oUrlLink.Save();
    }  
catch(e)  {  
          alert("当前IE安全级别不允许操作！");  
}
}    
</script>
</body>
</html>
