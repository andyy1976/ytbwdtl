<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">

  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>

  <style type="text/css">
  .goodtxt ul{ width:100%; float:left; margin:0; padding:0; padding-bottom:15px;}
  .goodtxt ul li{ width:20%; float:left; margin:1rem 0 0 4%; list-style:none;}
  .goodtxt ul li img{ width:100%; float:left; border-radius:50%; border:2px solid #eee; box-sizing:border-box;}
  .goodtxt ul img.tx_cur { border:2px solid #aa374a;}
  .goodtxt span{ width:100%; float:left;}
  .goodtxt span a{
    width:80%; 
    float:left; 
    margin:1.2rem 0 0 10%;
    color:#fff; 
    font-size:1rem; 
    text-decoration:none; 
    border-radius:5px; 
    line-height:2.2rem; 
    background:#aa374a;
    text-align:center;
  }
  </style>

  <div class="goodtxt" style="width: 700px">
    <ul id="memberAvatarList">
      <?php for ($i=1; $i < 9; $i++) { ?>
        <?php if($output['member_avatar'] == 'tx_0'.$i.'.jpg') { ?>
        <li><a href="javascript:"><img v="<?php echo $i ?>" src="/data/upload/shop/avatar/tx_0<?php echo $i ?>.jpg" class="tx_cur"/></a></li>
        <?php } else { ?>
        <li><a href="javascript:"><img v="<?php echo $i ?>" src="/data/upload/shop/avatar/tx_0<?php echo $i ?>.jpg" /></a></li>
        <?php } ?>
      <?php } ?>
    </ul>
    <span><a id="avatarSubmitBtn" href="javascript:">确定头像</a></span>
  </div>

</div>

<script type="text/javascript">
;(function(doc){
  var avatarList = doc.getElementById('memberAvatarList')
  var submitBtn = doc.getElementById('avatarSubmitBtn')
  var number = 0
  avatarList.onclick = function(e){
    e = e || win.event
    var el = e.target||e.srcElement
    if (el.tagName.toLowerCase()==='img') {
      $('#memberAvatarList img').removeClass('tx_cur')
      el.className = 'tx_cur'
      number = el.getAttribute('v')
      return false
    }
  }
  submitBtn.onclick = function(e){
    var url = '<?php echo MEMBER_SITE_URL;?>/index.php?act=member_information&op=avatarset'
    url+= '&img=tx_0'+number+'.jpg';
    // console.log(url);
    window.location.href = url
  }
})(document)
</script>