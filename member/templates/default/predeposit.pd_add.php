<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
 

<style type="text/css">
#wizard {font-size:12px;height:400px;margin:20px auto;width:800px;overflow:hidden;position:relative;-moz-border-radius:5px;-webkit-border-radius:5px;}
#wizard .items{width:20000px; clear:both; position:absolute;}
#wizard .right{float:right;}
#wizard #status{height:35px;background:#123;padding-left:25px !important;}
#status li{float:left;color:#fff;padding:10px 30px;}
#status li.active{background-color:#369;font-weight:normal;}
.input{width:120px; height:18px; margin:10px auto; line-height:20px; border:1px solid #d3d3d3; padding:2px}
.page{padding:20px 30px;width:750px;float:left;}
.page h3{font-size:12px; border-bottom:1px dotted #ccc; margin-bottom:20px; padding-bottom:5px;line-height: 30px;}
.page h3 em{font-size:12px; font-weight:500; font-style:normal}
.page p{line-height:24px;}
.page p label{font-size:14px; display:block;}
.btn_nav{height:36px; line-height:36px; margin:320px auto;}
.prev,.next{width:100px; height:32px; line-height:32px; background:url(btn_bg.gif) repeat-x bottom; border:1px solid #d3d3d3; cursor:pointer}
</style>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/scrollable.js"></script>

</head>

<body>


<div id="main">
   <form method="post" id="recharge_form" action="index.php">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="act" value="predeposit" />
    <input type="hidden" name="op" value="recharge_add" />
    <input type="hidden" name="le" value="<?php echo $_SESSION['member_level']?>">
    <input type="hidden" name="agreement_id" id="agreement_id" value="">
    <div id="wizard">
    

    <div class="items">
          <!-- <p style="text-align:left;">
          <h3 style="color:red;font-size:24px;">注：会员充值时请尽量避免在高峰时间段进行充值（17：00-23：00）</h3>
          </p>
          </p> -->
      <div class="page">
               <div>
               请填写您的充值金额。
               <br>提示：充值金额会随机加1-10元，您将获得 “实际支付金额对应的云豆”
               <br>注：单笔充值不能低于1千，每日累计充值限额100万。
               <br>当日账户充值和充值余额6%兑换云豆合计2万以下的（含2万）按5%收取，当日账户充值和充值余额6%兑换云豆合计超出2万部分按8%的服务费收取。
               </div>
               <br>
               <p><label><?php echo $lang['predeposit_recharge_price'].$lang['nc_colon']; ?></label><input onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g,'' type="text" class="input" id="pdr_amount" name="pdr_amount" /></p>
              
               <div style="margin: 20px auto;line-height: 36px;height: 36px;">
                  <input type="button" class="next right" value="下一步&raquo;" />
               </div>
            </div>
      <div class="page">
               
               <div style="position:absolute; width: 750px; height: 300px; overflow:auto"><p style="text-align:center;">
          <p style="text-align:center;">
            <strong><span style="font-family:黑体;color:#000000;font-size:26.0000pt;"><span style="font-family:黑体;">万店会员服务协议</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">本协议由通过天津万店网络科技有限公司（以下简称</span>“天津”或“万店”）来往网站、移动客户端软件及其他方式使用万店电商服务的用户（以下简称“用户”或“您”）与天津共同缔结。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">您应于注册及使用本服务前认真阅读全部协议内容，对于协议中粗体字显示的内容应重点阅读。无论您是否实际阅读本协议，当您于网站点击同意接受本协议或已实际使用万店来往服务，本协议即产生法律约束力。</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">一、协议内容和效力</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.1 本协议内容包括本协议正文及所有万店来往已经发布或将来可能发布的隐私政策、各项政策、规则、声明、通知、警示、提示、说明（以下简称“规则”）。前述规则为本协议不可分割的组成部分，与协议正文具有同等法律效力；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.2 万店来往有权根据需要不时制订、修改本协议及相关规则，变更后的协议和规则一经公布，立即取代原协议及规则并自动生效。如您不同意相关变更，应当立即停止使用万店电商往来服务，如您继续使用万店电商来往服务或进行任何网站活动，即表示您已接受经修订的协议和规则。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">二、服务</span> </span></strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;&nbsp;&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.1天津及其关联公司通过万店来往为用户提供店商平台服务，用户可通过万店来进行互联网消费、使用万店赠送的云豆消费等消费活动实现互联网消费对其生活带来的便利和收益；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.2万</span><strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">店通过</span></span></strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">互联网对销售渠道和货源等等与客户利益切身相关事项的处理、组织维护和管理实现客户和天津的共同利益；</span> </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.3万店保留在任何时候自行决定对万店来往服务或致使万店来往服务及其相关功能、应用软件变更、升级、修改、转移的权利。您同意，对于上述行为，万店来往均不需通知，并且对您和任何第三人不承担任何责任。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">三、注册及账号管理</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.1 万店来往致力建设成为真实身份关系的电</span><strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">商服务</span></span></strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">平台，因此，您注册为万店来往用户时应提交真实、准确、完整和反映当前情况的身份及其他相关信息，并在信息发生变化后及时更新；</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2 您承诺不得注册一个以上万店来往账号，不得冒充他人进行注册，不得未经许可为他人注册，不得以可能导致其他用户误认的方式注册账号，不得使用可能侵犯他人权益的用户名进行注册（包括但不限于涉嫌商标权、名誉权侵权等），否则万店来往有权取消该账号；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2.1会员万店账户的注册、使用</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">用户以其真实身份信息注册成为万店会员，并将其名下的农业银行卡（带芯片）绑定银联钱包。注册成为万店会员需支付人民币</span>500元作为会员费用，该费用为一次性会费，不予退还。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2.2注册成为万店会员后需向其名下的上述的农业银行卡充值（T+1到账）；万店将根据客户充值的数额，赠送等数额的万店的云豆（1个万店的云豆=1元人民币）。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">赠送的万店的云豆按每日万分之</span>5左右的比例给付到会员的万店商城的个人账户中；云豆可以在万店覆盖的互联网电商/其他形式的商家使用或根据万店规定的方式使用或提现；会员对万店云豆予以提现的，应按照提现额的13%向万店支付管理和运营及万店的维护费用。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2.3万店依据会员向其上述农业银行卡充值的数额收取手续费，当日充值人民币2万元以下的（含2万元）按5%收取，当日累计超过2万元的部分按8%收取手续费。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2.4上述会员费、提现需支付的费用、手续费等等费用，会员特别准许天津或万店直接从其账户中扣除，且放弃以任何理由要求返还的权利。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2.4万店云豆使用和限制的具体规定和制度以万店公布的通知和解释为准，请会员予以注意和关注。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.3 您了解并同意，万店来往注册账号所有权归属于万</span><strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">店消费</span></span></strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">等相关的使用，您获得账号使用权。但您不得以任何方式转让或被提供予他人使用，否则万店不承担任何责任；</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.4 在您成功注册后，万店来往将根据账号和密码确认您的身份。您应妥善保管账号和密码，并对利用该密码和账号所进行的一切活动负全部责任。您承诺，在密码或账号遭到未获授权的使用，或者发生其他任何安全问题时，将立即通知万店来往，且您同意并确认，除非因万店过错导致账号被盗，万店来往不对上述情形产生的任何直接或间接的遗失或损害承担责任；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.5.1您了解并同意，如</span><strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">您注册</span></span></strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">万店来往账号后长期未正常消费或消费额度达不到万店的规定的，万店为维护电商平台的正常运转、活力及流动性等等，万店可以冻结该账号，相关问题及责任均由您自行承担；</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.5.2采取充值套取万店套取云豆而不消费或消费达不到万店的规定的，万店有权冻结该账户；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">账号因上述原因被冻结后，有权自行对账号相关内容及信息以包括但不限于删除等方式进行处理，且无需就此向用户承担任何责任。</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">四、服务使用规范</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.1用户充分了解并同意，</span><strong><span style="font-family:黑体;color:#008000;font-size:10.5000pt;"><span style="font-family:黑体;">万店仅为</span></span></strong><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">用户提供电商消费平台，您应自行对利用万店来往服务从事的所有行为及结果承担责任。相应地，您应了解，使用万店来往服务可能发生来自他人非法或不当行为（或信息）的风险，您应自行判断及行动，并自行承担相应的风险；</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.2除非另有说明，本协议项下的服务只能用于非商业用途。您承诺不对本服务任何部分或本服务之使用或获得，进行复制、拷贝、出售、转售或用于包括但不限于广告及任何其它商业目的。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3 您承诺不会利用本服务进行任何违法或不当的活动，包括但不限于下列行为∶</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.1 上载、传送或分享含有下列内容之一的信息： </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(a) 反对宪法所确定的基本原则的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(b) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(c) 损害国家荣誉和利益的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(d) 煽动民族仇恨、民族歧视、破坏民族团结的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(e) 破坏国家宗教政策，宣扬邪教和封建迷信的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(f) 散布谣言，扰乱社会秩序，破坏社会稳定的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(g) 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(h) 侮辱或者诽谤他人，侵害他人合法权利的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(i) 含有虚假、诈骗、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它道德上令人反感的内容； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">(j) 含有中国法律、法规、规章、条例以及任何具有法律效力之规范所限制或禁止的其它内容的； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.2冒充任何人或机构，或以虚伪不实的方式陈述或谎称与任何人或机构有关；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.3 伪造标题或以其他方式操控识别资料，使人误认为该内容为万店巴巴或其关联公司所传送；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.4 将依据任何法律或合约或法定关系（例如由于雇佣关系和依据保密合约所得知或揭露之内部资料、专属及机密资料）知悉但无权传送之任何内容加以上载、传送或分享； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.5 将涉嫌侵害他人权利（包括但不限于著作权、专利权、商标权、商业秘密等知识产权）之内容上载、传送或分享； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.6 将任何广告、推广信息、促销资料、“垃圾邮件”、“滥发信件”、“连锁信件”、“直销”或其它任何形式的劝诱资料加以上载、传送或分享；供前述目的使用的专用区域或专用功能除外； &nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.7 将有关干扰、破坏或限制任何计算机软件、硬件或通讯设备功能的软件病毒或其他计算机代码、档案和程序之资料，加以上载、张贴、发送电子邮件或以其他方式传送； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.8 干扰或破坏本服务或与本服务相连线之服务器和网络，或违反任何关于本服务连线网络之规定、程序、政策或规范； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.9 跟踪或以其它方式骚扰他人；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.10以任何方式危害未成年人的利益；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.3.11 从事任何违反中国法律、法规、规章、政策及规范性文件的行为。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.4 您承诺，使用万店来往服务时您将严格遵守本协议(包括本协议第一条所述规则)； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.5 您同意并接受万店来往无须时时监控您上载、传送或分享的资料及信息，但万店来往有权对您使用万店来往服务的情况进行审查、监督并采取相应行动，包括但不限于删除信息、中止或终止服务，及向有关机关报告； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.6 您承诺不以任何形式使用本服务侵犯万店来往的商业利益，或从事任何可能对万店来往造成损害或不利于万店来往的行为；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.7 您了解并同意，在万店来往服务提供过程中，万店及其关联公司或其授权单位和个人有权以各种方式投放各种商业性广告或其他任何类型的推广信息，同时，您同意接受以电子邮件或其他方式向您发送的上述广告或推广信息；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.8 特别授权</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-family:黑体;">当您向万店关联公司作出任何形式的承诺，且相关公司已确认您违反了该承诺，则万店来往有权立即按您的承诺约定的方式对您的账户采取限制措施，包括但不限于中止或终止向您提供服务，并公示相关公司确认的您的违约情况。您了解并同意，万店来往无须就相关确认与您核对事实，或另行征得您的同意，且万店来往无须就此限制措施或公示行为向您承担任何的责任。</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">五、第三方应用</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">5.1 万店来往允许第三方应用接入万店来往社交服务平台，您可以在万店来往直接获得包括但不限于社交游戏的第三方应用服务。您了解并同意，万店仅作为平台提供者，相关服务由该第三方提供，万店来往不对您对该服务的使用承担任何责任；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">5.2 您了解并同意，如万店对万店来往电商、客户服务或其部分做出调整、中止或终止而对第三方应用服务产生影响，万店来往不承担任何责任。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">六、服务中止或终止</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.1 您同意，鉴于互联网服务的特殊性，万店来往有权随时中止、终止或致使中止终止万店服务或其任何部分；对于免费服务之中止或终止，万店来往无需向您发出通知；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.2 您了解并同意，万店来往可能定期或不定期地对提供网络服务的平台设备、设施和软硬件进行维护或检修，如因此类情况而造成收费服务在合理时间内中止，万店无需承担责任，但应尽可能事先进行通告；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.3 如存在下列违约情形之一，万店可立即对用户中止或终止服务，并要求用户赔偿损失：</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.3.1 用户违反注册义务；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.3.2 用户使用收费网络服务时未按规定支付相应服务费；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.3.3 用户违反第四条服务使用规范之规定。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.4万店根据本协议中止或终止后，有权收回该账号云豆、对您账号下的内容及信息以包括但不限于删除等方式进行处理，且无需就此向用户承担任何责任。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">七、隐私政策</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">保护用户隐私信息是万店来往的一项基本政策，您提供的资料及万店来往保留的您的其它资料将受到中国隐私法律和《万店来往隐私政策》的规范。</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">八、知识产权</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">8.1您了解及同意，除非万店另行声明，本协议项下服务包含的所有产品、技术、软件、程序、数据及其他信息（包括但不限于文字、图像、图片、照片、音频、视频、图表、色彩、版面设计、电子文档）的所有知识产权（包括但不限于版权、商标权、专利权、商业秘密等）及相关权利均归万店或其关联公司所有；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">8.2 您应保证，除非取得万店书面授权，对于上述权利您不得（并不得允许任何第三人）实施包括但不限于出租、出借、出售、散布、复制、修改、转载、汇编、发表、出版、还原工程、反向汇编、反向编译，或以其它方式发现原始码等的行为；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">8.3万店服务涉及的Logo、“”、“hualin”、“万店通联”、“wandiantonglian”、“通联”、“tonglian”等文字、图形及其组成，以及万店其他标识、徵记、产品和服务名称均为万店及其关联公司在中国和其它国家的商标，用户未经万店书面授权不得以任何方式展示、使用或作其他处理，也不得向他人表明您有权展示、使用、或作其他处理。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">九、有限责任</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.1 服务将按&quot;现状&quot;和按&quot;可得到&quot;的状态提供。万店在此明确声明对服务不作任何明示或暗示的保证，包括但不限于对服务的可适用性，没有错误或疏漏，持续性，准确性，可靠性，适用于某一特定用途之类的保证，声明或承诺。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.2 万店对服务所涉的技术和信息的有效性，准确性，正确性，可靠性，质量，稳定，完整和及时性均不作承诺和保证。 </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.3 不论在何种情况下，万店均不对由于Internet连接故障，电脑，通讯或其他系统的故障，电力故障，罢工，劳动争议，暴乱，起义，骚乱，生产力或生产资料不足，火灾，洪水，风暴，爆炸，不可抗力，战争，政府行为，国际、国内法院的命令或第三方的不作为而造成的不能服务或延迟服务承担责任。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.4 不论是否可以预见，不论是源于何种形式的行为，万店不对由以下原因造成的任何特别的，直接的，间接的，惩罚性的，突发性的或有因果关系的损害或其他任何损害（包括但不限于利润或利息的损失，营业中止，资料灭失）承担责任。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.4.1 使用或不能使用服务；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.4.2 通过服务购买或获取任何产品，样品，数据，信息或进行交易等，或其他可替代上述行为的行为而产生的费用； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.4.3 未经授权的存取或修改数据或数据的传输； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.4.4 第三方通过服务所作的陈述或行为； </span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.4.5 其它与服务相关事件，包括疏忽等，所造成的损害。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.5 您充分了解并同意，鉴于互联网体制及环境的特殊性，您在万店分享的信息及个人资料有可能会被他人复制、转载、擅改或做其它非法用途；您在此已充分意识此类风险的存在，并确认此等风险应完全由您自行承担，万店来往对此不承担任何责任；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.6 您了解并同意，在使用万店服务过程中可能存在来自任何他人的包括威胁性的、诽谤性的、令人反感的或非法的内容或行为或对他人权利的侵犯（包括知识产权）及匿名或冒名的信息的风险，基于第4.1条所述，该等风险应由您自行承担，万店对此不承担任何责任；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">十、赔偿</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">您同意，由于您通过万店服务上载、传送或分享之信息、使用本服务其他功能、违反本协议、或您侵害他人任何权利因而衍生或导致任何第三人向万店及其关联公司提出任何索赔或请求，或万店及其关联公司因此而发生任何损失，您同意将足额进行赔偿（包括但不限于合理律师费）。</span></span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">十一、有效通知</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">11.1 万店向您发出的任何通知，可采用电子邮件、页面公开区域公告、个人网络区域提示、手机短信或常规信件等方式，且该等通知应自发送之日视为已向用户送达或生效。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">11.2您同意，您向万店发出的任何通知应发至万店对外正式公布或以11.1条所述方式告知用户的电子邮件、通信地址、传真号码等联系信息，或使用其他万店认可的其他通知方式进行送达。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:14.0000pt;">十二、</span><strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">争议解决及其他</span></span></strong>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">12.1 本协议之解释与适用，以及与本协议有关的争议，均应依照中华人民共和国法律予以处理，并以天津市有管辖权的人民法院为第一审管辖法院；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">12.2 如本协议的任何条款被视作无效或无法执行，则上述条款可被分离，其余部份则仍具有法律效力；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">12.3 万店于用户过失或违约时放弃本协议规定的权利的，不得视为其对用户的其他或以后同类之过失或违约行为弃权；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">12.4 本协议应取代双方此前就本协议任何事项达成的全部口头和书面协议、安排、谅解和通信；</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">12.5万店有权根据业务调整情况将本协议项下的全部权利义务一并转移给其关联公司，转让将以本协议规定的方式通知，用户承诺对此不持有异议。</span>
          </p>
          <p style="text-align:left;">
            <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;</span>
          </p>
          <p>
            <span style="font-family:黑体;font-size:10.5000pt;">&nbsp;</span>
          </p>


      </div>
               
               <div class="btn_nav">
                <input name="agree" type="checkbox" checked class="vm ml10" id="clause" value="1"  />

               <span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a href="#"  class="agreement" title="<?php echo "阅读并同意";?>">阅读并同意服务协议</a></span>

                  <input type="button" class="prev" style="float:left" value="&laquo;上一步" />
                  <input type="button" class="next right" value="下一步&raquo;" />
               </div>
            </div>
      <div class="page">
               <div style="position:absolute; width: 750px; height: 300px; overflow:auto">
                  <p style="text-align:center;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:26.0000pt;"><span style="font-family:黑体;">万店隐私政策</span></span></strong>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">天津万店网络科技有限公司（简称</span>“天津”或“万店”）万店非常重视用户的隐私权，以下是我们对您信息收集、使用和保护的政策，请您认真阅读，对于文中加粗字体显示的内容，万店督促您应重点阅读。随着服务范围的扩大和调整，我们可能随时更新我们的隐私政策，恕不另行通知，更新后的隐私政策一旦在网页上公布即有效取代原有隐私政策。</span>
                  </p>
                  <p>
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;">1、信息的收集和使用</span></strong>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.1 当您在万店注册时，万店会要求您提供个人资料；对于您使用万店服务须创建的个人资料，我们可能会向拥有您电子邮件地址或其他身份信息的人士进行展示；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.2在您使用万店服务，或访问万店网页时，万店将自动接收并记录您的浏览器和计算机上的信息，包括但不限于您的IP地址、浏览器的类型、使用的语言、访问日期和时间、软硬件特征信息、您需求的网页记录及万店Cookies信息等；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.3如果您已安装万店移动客户端软件，则万店可能会读取您的移动设备属性及其存储</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.4对于您于万店使用的第三方应用服务，万店将在您启动该应用时收集相关信息，并按照本隐私权政策进行处理；第三方应用服务提供者可能会获取您于万店的个人信息，其对信息的收集受其自身隐私权政策的约束。 </span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.6 万店将对其他用户进行与您相关的操作时收集关于您的信息，亦可能从万店关联公司、合作第三方或通过其他合法途径获取的您的个人数据信息；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.7 万店收集及使用上述信息的目的是为了更好地运作网站和服务（包括但不限于向您提供个性化的服务）；</span>
                  </p>
                  <p>
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;">2、信息的公开和分享</span></strong>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">在未经您同意之前，万店不会向任何第三方提供、出售、出租、分享和交易您的个人资料，但以下情形除外：</span></span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.1 为遵守法律法规之要求，包括在国家有关机关或其授权的单位查询时，向其提供有关资料；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.2 为免除您在生命、身体或财产方面之急迫危险，或为防止他人权益之重大危害；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.3 为保护万店及其关联公司、或其用户的权利及财产；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.4 只有透露您的资料，才能提供您所要求的产品和服务；</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.5 为使万店合作方和万店及其关联公司单独或共同为您提供服务，且在该服务结束后，其将被禁止访问包括其以前能够访问的所有这些资料。</span>
                  </p>
                  <p>
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;">3、信息安全</span></strong>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.1您在万店注册的账户具有密码保护功能，以确保您个人资料的安全，请您妥善保管账户及密码信息，非因万店原因造成您损失的，万店不承担任何责任。</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.2 万店具有相应的技术和安全措施来确保我们掌握的信息不丢失，不被滥用和变造。尽管我们有这些安全措施，但请注意在互联网上不存在“完善的安全措施”。 </span>
                  </p>
                  <p>
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;">4、Cookies</span></strong>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">我们使用</span>cookies储存您访问万店的相关数据，在您访问或再次访问万店时,我们能识别您的身份，并通过分析数据为您提供更好更多的服务。 </span>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;<span style="font-family:黑体;">您有权选择接受或拒绝接受</span>cookies。您可以通过修改浏览器的设置以拒绝接受cookies，但是我们需要提醒您，因为您拒绝接受cookies，您可能无法使用依赖于cookies的万店的部分功能。</span>
                  </p>
                  <p>
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;">5、外部链接</span></strong>
                  </p>
                  <p>
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">万店含有到其他网站的链接。万店对其他网站的隐私保护措施不负任何责任。我们可能在任何需要的时候增加商业伙伴或共用品牌的网站，但是提供给他们的将仅仅是综合信息，我们将不会公开您的身份。</span></span>
                  </p>
                  <p>
                    <span style="font-family:仿宋;color:#000000;font-size:12.0000pt;">&nbsp;&nbsp;</span>
                  </p>
                  <p>
                    <span style="font-family:仿宋;font-size:11.0000pt;">&nbsp;</span>
                  </p>

               </div>
               <div class="btn_nav">
                  <input type="button" class="prev" style="float:left" value="&laquo;上一步" />
                   <input name="agree" type="checkbox" checked class="vm ml10" id="clause" value="1"  />
                  <span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a href="#"  class="agreement" title="<?php echo "阅读并同意";?>">阅读并同意服务协议</a></span>
                  <input type="button" class="next right" value="下一步&raquo;" />
               </div>
      </div>
      <div class="page">
               <div style="position:absolute; width: 750px; height: 300px; overflow:auto">
                
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#333333;font-size:7.5000pt;">&nbsp;</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#333333;font-size:7.5000pt;">&nbsp;</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#333333;font-size:7.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#333333;font-size:7.5000pt;"><span style="font-family:黑体;">在接受本协议之前，请您仔细阅读本协议的全部内容（特别是</span></span><span style="font-family:黑体;color:#333333;font-size:7.5000pt;"><span style="font-family:黑体;">以粗体下划线</span></span><span style="font-family:黑体;color:#333333;font-size:7.5000pt;"><span style="font-family:黑体;">标注的内容，</span></span><span style="font-family:黑体;color:#333333;font-size:7.5000pt;"><span style="font-family:黑体;">包括但不限于免除或者限制万店责任的条款、对用户权利进行限制的条款、司法管辖的条款等</span></span><span style="font-family:黑体;color:#333333;font-size:7.5000pt;"><span style="font-family:黑体;">）。如果您对本协议的条款有疑问的，请通过天津客服渠道进行询问，我们将向您解释条款内容。如果您不同意本协议的任意内容，或者无法准确理解万店对条款的解释，请不要进行任何后续操作。否则，表示您已接受了以下所述的条款和条件，同意受本协议约束。届时您不应以未阅读本协议的内容或者未获得万店对您问询的解答等理由，主张本协议无效，或要求撤销本协议。</span></span>
                  </p>
                  <p style="text-align:center;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:24.0000pt;"><span style="font-family:黑体;">天津万店电商公众平台服务协议</span></span></strong>
                  </p>
                  <p style="text-align:right;">
                    <span style="font-family:黑体;color:#000000;font-size:9.0000pt;"><span style="font-family:黑体;">发布日期：</span>2016年12月 &nbsp;日</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">一</span>. 签约主体</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">本协议是由通过天津万店网络科技有限公司（简称</span>“天津”或“万店”）来往网站及其他方式使用天津公众平台服务的用户（以下简称“用户”或“您”）与万店集团（以下简称）共同缔结。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">二</span>. 协议生效和适用范围</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1. 您通过网络页面点击确认或以其他方式选择接受本协议，包括但不限于未点击确认本协议但使用了天津公众平台服务，即表示您与万店已达成一致并同意接受本协议的全部约定内容。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">本协议自您确认接受之时起或自您使用天津公众平台服务的行为发生之时起（以时间在先者为准）生效。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 本协议内容包括协议正文、《万店会员服务协议》（</span><a href="http://pp.laiwang.com/tos.htm"><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">请点击查看</span></span></a><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">）、《万店隐私政策》（</span></span><a href="http://pp.laiwang.com/policy.htm"><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">请点击查看</span></span></a><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">）以及所有万店针对已经发布或将来可能发布的各项政策、规则、声明、通知、警示、提示、说明（以下合称</span>“规则”）。前述协议、规则为本协议不可分割的组成部分，与协议正文具有同等法律效力。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.&nbsp;万店有权根据需要不时制定、修改本协议和/或相关规则，如有任何变更，万店将以网站公示的方式进行公告，不再单独通知您。变更后的协议和规则一经公布即自动生效，成为本协议的一部分。如您不同意相关变更，应当立即停止使用天津公众平台服务；如您继续使用天津公众平台服务的，则视为您对修改后的协议和规则不持异议并同意遵守。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">三</span>.公众电商平台服务</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1. 万店通过天津公众平台为您提供相应的平台服务和技术支持，您通过前述平台服务可以实现如下功能：</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 您理解并认可，由于产品迭代及业务发展的需要，万店有权根据业务发展的实际情况不时增加、减少、调整前述服务内容。您同意，对于服务内容或公众账号功能的增加、减少或调整，万店无需另行通知，并请对您和任何第三方均不承担任何责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3. 万店免费为您提供本协议项下的服务，但并不排除今后就其提供的服务或其他新增服务收取费用和分享您的收益的可能，届时万店将在页面上提前公布收费政策及规则。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">四</span>. 账号注册与认证</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1. 您在使用公众平台服务前需要注册一个天津公众账号且通过账号认证。天津公众账号目前仅支持申请成为万店会员，请使用您的电子邮箱账号/手机号码注册天津公众账号。万店有权根据用户需求或产品需要对账号注册和绑定的方式进行变更。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 天津致力建设成为真实身份关系的社交服务平台，因此，您注册为天津公众平台用户及申请账号认证时应提交真实、准确、完整和反映当前情况的身份及其他相关信息，并在信息发生变化后及时更新。您只有在账号激活且通过认证后，方可使用公众平台服务。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.&nbsp;您理解并认可，万店及其关联公司仅能以普通或非专业人员的知识水平标准对您提交的认证资料信息进行表面鉴别，且万店及其关联公司保留抽查、要求您补充提交、及时更新资料信息的权利。您应当对所认证账号资料的真实性、合法性、准确性和有效性独立承担责任，与天津无关；如因此给万店或其关联公司或任何第三方造成损害的，您应当依法予以赔偿，且万店有权立即终止本协议。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4. 您承诺不得冒充他人进行注册，不得未经许可为他人注册，不得以可能导致其他用户误认的方式注册账号，不得使用可能侵犯他人权益的用户名进行注册（包括但不限于涉嫌商标权、名誉权侵权等），否则万店有权取消该账号。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">五</span>. 账号管理</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1. 您了解并同意，天津公众账号的所有权归属于万店，注册完成且通过认证后，您仅获得账号使用权。天津公众账号使用权仅归属于初始申请注册人，禁止赠与、借用、租用、转让或售卖；否则，万店有权立即不经通知收回该账号。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 在您成功注册后，万店将根据账号和密码确认您的身份。您应妥善保管账号和密码，并对利用该密码和账号所进行的一切行为承担全部责任。您承诺在密码或账号遭到未获授权的使用，或者发生其他任何安全问题时，将立即通知万店，且您同意并确认，除非因万店过错导致账号被盗，万店不对上述情形产生的任何直接或间接的遗失或损害承担责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3. 您理解并认可，如您注册天津公众账号后长期不登录，万店为网站优化管理之目的有权回收该账号，相关问题及责任均由您自行承担；同时，万店因经营需要或其他需要有权随时收回用户的天津公众账号。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4. 您应遵守本协议的各项条款，正确、适当地使用本服务，如您违反本协议中的任何条款，万店有权终止对违约用户的天津公众账号提供服务。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">5.&nbsp;万店收回或取消账号后，有权自行对账号相关内容及信息以包括但不限于删除等方式进行处理，万店没有义务向您返还任何数据且无需就此向您承担任何责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">六</span>. 服务使用规范</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.&nbsp;您充分了解并同意，您应自行对使用天津公众平台服务及来往公众账号从事的所有行为及结果承担责任。相应地，您应了解，使用天津公众平台服务或来往公众账号可能发生来自他人的包括威胁性的、诽谤性的、令人反感的或非法的内容或行为或对他人权利的侵犯（包括知识产权）及匿名或冒名的信息的风险，该等风险应由您自行承担，万店对此不承担任何责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 除非另有说明，本协议项下的服务用于非商业用途。您承诺不对本服务任何部分或本服务之使用或获得，进行复制、拷贝、出售、转售或用于包括但不限于广告及任何其它商业目的。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3. 您承诺不会利用天津公众账号或天津公众平台服务进行任何违法或不当的活动，包括但不限于下列行为∶</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">制作、复制、发布、上载、传播或分享含有下列内容之一的信息：</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">反对宪法所确定的基本原则的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">损害国家荣誉和利益的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">煽动民族仇恨、民族歧视、破坏民族团结的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">破坏国家宗教政策，宣扬邪教和封建迷信的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">散布谣言，扰乱社会秩序，破坏社会稳定的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">侮辱或者诽谤他人，侵害他人合法权利的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">含有虚假、诈骗、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它道德上令人反感的内容；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:'Courier New';color:#000000;font-size:10.0000pt;">o&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">含有中国法律、法规、规章、条例及任何具有法律效力之规范以及政策所限制或禁止的其他内容。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">冒充任何人或机构，或以虚伪不实的方式陈述或谎称与任何人或机构有关；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">伪造标题或以其他方式操控识别资料，使人误认为该内容为万店或其关联公司所传送；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">将依据任何法律或合约或法定关系（例如由于雇佣关系和依据保密合约所得知或揭露之内部资料、专属及机密资料）知悉但无权传送之任何内容加以复制、发布、上载、传播或分享；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">5.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">将涉嫌侵害他人权利（包括但不限于著作权、专利权、商标权、商业秘密等知识产权）之内容制作、复制、发布、上载、传播或分享；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">将任何广告、推广信息、促销资料、</span>“垃圾邮件”、“滥发信件”、“连锁信件”、“直销”或其它任何形式的劝诱资料加以制作、复制、发布、上载、传播或分享，但供前述目的使用的专用区域或专用功能除外；</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">7.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">将有关干扰、破坏或限制任何计算机软件、硬件或通讯设备功能的软件病毒或其他计算机代码、档案和程序之资料，加以上载、张贴、发送电子邮件或以其他方式传播；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">8.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">干扰或破坏本服务或与本服务相连线之服务器和网络，或违反任何关于本服务连线网络之规定、程序、政策或规范；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">9.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">跟踪或以其他方式骚扰他人；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">10.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">以任何方式危害未成年人的利益；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">11.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">未经万店书面许可使用插件、外挂或其他第三方工具、服务接入本服务和相关系统；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">12.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">利用天津公众帐号或天津公众平台服务从事任何违法犯罪活动的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">13.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">制作、发布与以上行为相关的方法、工具，或对此类方法、工具进行运营或传播，无论这些行为是否为商业目的；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">14.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">其他违反法律法规规定、侵犯其他用户合法权益、干扰产品正常运营或万店未明示授权的行为。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.&nbsp;您理解并认可万店无须时时监控您制作、复制、发布、上载、传播或分享的资料及信息，但万店有权对您使用天津公众平台服务及来往公众账号的情况进行抽查、监督并采取相应行动，包括但不限于删除信息、中止或终止服务、取消或收回账号及向有关机关报告等。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">5. 您承诺不以任何形式使用本服务侵犯万店或其关联公司的合法利益，或从事任何可能对天津造成损害或不利于天津的行为。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6. 您了解并同意，在服务提供过程中，万店及其关联公司或其授权单位和个人有权以各种方式投放各种商业性广告或其他任何类型的推广信息；同时，您同意接受以电子邮件或其他方式向您发送的上述广告或推广信息。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">7. 特别授权</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><br />
                    </span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">当您向万店关联公司作出任何形式的承诺，且相关公司已确认您违反了该承诺，则万店有权立即按您的承诺约定的方式对您的公众账号采取限制措施，包括但不限于中止或终止向您提供服务、取消或收回账号，公示相关公司确认的您的违约情况。您了解并同意，万店无须就相关确认与您核对事实，或另行征得您的同意，且万店无须就此限制措施或公示行为向您承担任何责任。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">七</span>. 服务中止或终止</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.&nbsp;您理解并认可，鉴于互联网服务的特殊性及业务发展需要，万店有权随时中止、终止天津公众平台服务或其任何部分；对于免费服务之中止或终止，万店无需向您发出通知。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.&nbsp;您理解并认可，万店可能定期或不定期地对提供网络服务的平台设备、设施和软硬件进行维护或检修，如因此类情况而造成收费服务在合理时间内中止，万店无需承担责任，但应尽可能事先进行通告。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.&nbsp;如存在下列违约情形之一，万店可立即对用户中止或终止服务，并要求您赔偿损失：</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">您违反本协议中的任何承诺或保证，包括但不限于本协议项下的任何约定；</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">您在使用收费服务时未按规定支付相应费用。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">违发万店或天津的规定，利用本平台套取云豆。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">扰乱本平台秩序或恶意破坏、违反平台天津</span>/万店制定的规则的。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">5.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">给天津华万店造成其他不良影响或损失、经天津或万店通知拒不改正或不予以弥补损失的。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">6.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">损害其他商户合法权益的行为。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">7.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">涉嫌违法或犯罪的情形。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">8.&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">其他有损公众利益或公序良俗的行为。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">4.&nbsp;本协议中止或终止后，万店有权收回您的来往的账号，并有权自行决定对您账号下的内容及信及赠送的云豆等以包括但不限于删除等方式进行处理，且无需就此向您承担任何责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">八</span>. 隐私政策</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">保护用户隐私信息是万店的一项基本政策，您提供的资料及天津保留的您的其他资料将受到中国隐私法律和《万店隐私政策》（</span></span><a href="http://pp.laiwang.com/policy.htm"><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">请点击查看</span></span></a><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">）的规范。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">九</span>. 知识产权</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1. 您了解及同意，除非万店另行声明，本协议项下服务包含的所有产品、技术、软件、程序、数据及其他信息（包括但不限于文字、图像、图片、照片、音频、视频、图表、色彩、版面设计、电子文档）的所有知识产权（包括但不限于版权、商标权、专利权、商业秘密等）及相关权利均归万店或其关联公司所有。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 您应保证，除非取得万店书面授权，对于上述权利您不得（并不得允许任何第三人）实施包括但不限于出租、出借、出售、散布、复制、修改、转载、汇编、发表、出版、还原工程、反向汇编、反向编译，或以其它方式发现原始码等的行为。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3. 万店服务涉及的Logo、“”、“</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;">hualin</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;">”、“万店通联”、“wandiantonglian”、“通联”、“tonglian”等文字、图形及其组成，以及天津其他标识、徵记、产品和服务名称均为万店及其关联公司在中国和其他国家的商标。未经万店书面授权，您不得以任何方式展示、使用或作其他处理，也不得向他人表明您有权展示、使用、或作其他处理。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">十</span>. 免责及有限责任</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.&nbsp;服务将按“现状”和按“可得到”的状态提供。万店在此明确声明对服务不作任何明示或暗示的保证，包括但不限于对服务的可适用性、没有错误或疏漏、持续性、准确性、可靠性、适用于某一特定用途之类的保证、声明或承诺。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2.&nbsp;不论在何种情况下，万店均不对由于Internet连接故障，电脑，通讯或其他系统的故障，电力故障，罢工，劳动争议，暴乱，起义，骚乱，生产力或生产资料不足，火灾，洪水，风暴，爆炸，不可抗力，战争，政府行为，国际、国内法院的命令或第三方的不作为而造成的不能服务或延迟服务承担责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3.&nbsp;您充分了解并同意，鉴于互联网体制及环境的特殊性，您通过天津公众账号分享的信息及个人资料有可能会被他人复制、转载、擅改或做其他非法用途；您在此已充分意识此类风险的存在，并确认此等风险应完全由您自行承担，万店对此不承担任何责任。</span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">十一</span>. 违约责任</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:黑体;color:#000000;font-size:10.5000pt;"><span style="font-family:黑体;">您理解并认可，由于您通过天津公众平台服务、天津公众账号制作、复制、发布、上载、传播或分享之信息、使用本服务其他功能、违反本协议、或您侵害他人任何权利因而衍生或导致任何第三人向万店及其关联公司提出任何索赔或请求，或万店及其关联公司因此而发生任何损失，您同意将足额进行赔偿（包括但不限于合理律师费）。</span></span>
                  </p>
                  <p style="text-align:left;">
                    <strong><span style="font-family:黑体;color:#000000;font-size:14.0000pt;"><span style="font-family:黑体;">十二</span>. 协议转让及争议解决</span></strong>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">1.&nbsp;万店有权根据业务调整情况将本协议项下的全部权利义务一并转移给其关联公司而无须事先征得您的同意，届时万店将通过网站公告等方式向您发出转让通知。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">2. 本协议适用中华人民共和国大陆地区法律。因万店与您就本协议的签订、履行或解释发生争议，双方应努力友好协商解决；如协商不成，双方同意以天津市有管辖权的人民法院为第一审管辖法院。</span>
                  </p>
                  <p style="text-align:left;">
                    <span style="font-family:黑体;color:#000000;font-size:10.5000pt;">3、用于解决纠纷而产生的所有费用，包括但不限与诉讼相关费用、聘请律师的费用、差旅费用等等均由违约方承担。</span>
                  </p>
                  <p>
                    <span style="font-family:黑体;font-size:10.5000pt;">&nbsp;</span>
                  </p>


               </div>
               <div class="btn_nav">
                  <input type="button" class="prev" style="float:left" value="&laquo;上一步" />
                  <input name="agree" type="checkbox" checked class="vm ml10" id="clause" value="1"  />
                  <span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a href="#"  class="agreement" title="<?php echo "阅读并同意";?>">阅读并同意服务协议</a></span>
               
                  <input type="button" class="next right" id='sub' value="充值" />
               </div>
      </div>

    </div>
  </div>
</form>

</div>
<script type="text/javascript" src="/data/resource/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/data/resource/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/data/resource/js/jquery.mousewheel.js"></script>
<script type="text/javascript">
$(function(){
  $("#wizard").scrollable({
    onSeek: function(event,i){
      $("#status li").removeClass("active").eq(i).addClass("active");
    },
    onBeforeSeek:function(event,i){
      var length=$("input[type='checkbox']:checked").length;  
   
        if(length==2){
        
          alert("阅读并同意服务协议");
          return false;
        }
      if(i==1){
        
        var user = $("#pdr_amount").val();
        if(user==""){
          alert("请输入充值金额！");
          return false;
        }
        if(user<1000){
           alert("请输入大于等于1000！");
          return false;
        }
      }
      
      if(i==2){
       
        var length=$("input[type='checkbox']:checked").length;     
        if(length==2){
        
          alert("阅读并同意服务协议");
          return false;
        }
      }
      if(i==3){
       
        var length=$("input[type='checkbox']:checked").length;     
        if(length==2){
        
          alert("阅读并同意服务协议");
          return false;
        }
      }
      if(i==4){
       
        var length=$("input[type='checkbox']:checked").length;     
        if(length==2){
        
          alert("阅读并同意服务协议");
          return false;
        }
        if(length==3)
        {
          $("#sub").click(function(){
            var member_level = <?php echo $output['member_info']['member_level']; ?>;
            var agreement_id = <?php echo $output['member_info']['agreement_id']; ?>;
            if(member_level>0 && agreement_id==0){
              if(confirm('成为业务员可以获得奖励，请确认是否成为业务员')){
                $("#agreement_id").val('1');
                document.getElementById("recharge_form").submit();
              }else{
                document.getElementById("recharge_form").submit();
              }              
            }else{
              document.getElementById("recharge_form").submit();
            }    
            
          });
         
        }
      }
    }
  });

  
});
</script>;
</script>