<?php defined('In33hao') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<!--[if lt IE 8]>
  <script src="<?php echo RESOURCE_SITE_URL;?>/js/json2.js"></script>
<![endif]-->
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_goods_add.step2.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style type="text/css">
.ncs-figure-input {
    display: inline-block;
    position: relative;
    vertical-align: top;
    width: 65px;
    z-index: 1;
}
.dccss{ border-bottom:#666666 1px solid; border-right:#666666 1px solid;}
.ncs-figure-input .input-text {
    border: 1px solid #eee;
    color: #333;
    font-family: Tahoma;
    font-size: 16px;
    font-weight: 600;
    height: 41px;
    line-height: 41px;
    padding: 0;
    text-align: center;
    width: 41px;
}
.ncs-figure-input a {
    -moz-text-size-adjust: none;
    background: rgba(0, 0, 0, 0) url("shop/templates/default/images/shop/2014_ncs_public_img.png") no-repeat scroll 0 0;
    border-color: #eee;
    border-style: solid;
    border-width: 1px 1px 1px 0;
    display: block;
    font-size: 0;
    height: 20px;
    left: 42px;
    position: absolute;
    width: 20px;
    z-index: 1;
}
.ncs-figure-input a:hover {
    color: #c40000;
    text-decoration: none;
}
.ncs-figure-input a.increase {
    background-position: -100px -100px;
    top: 0;
}
.ncs-figure-input a.decrease {
    background-position: -120px -100px;
    top: 21px;
}
.ncs-figure-input span {
    display: block;
    left: 0;
    position: absolute;
    top: 50px;
    white-space: nowrap;
    z-index: 1;
}
.ncs-figure-input span em {
    margin: 0 2px;
}
.ncs-figure-input span strong {
    color: #f60;
    margin: 0 2px;
}
#fixedNavBar { filter:progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#CCFFFFFF', endColorstr='#CCFFFFFF');background:rgba(255,255,255,0.8); width: 90px; margin-left: 510px; border-radius: 4px; position: fixed; z-index: 999; top: 172px; left: 50%;}
#fixedNavBar h3 { font-size: 12px; line-height: 24px; text-align: center; margin-top: 4px;}
#fixedNavBar ul { width: 80px; margin: 0 auto 5px auto;}
#fixedNavBar li { margin-top: 5px;}
#fixedNavBar li a { font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 20px; background-color: #F5F5F5; color: #999; text-align: center; display: block;  height: 20px; border-radius: 10px;}
#fixedNavBar li a:hover { color: #FFF; text-decoration: none; background-color: #27a9e3;}
</style>
 <script language="javascript">// Example: obj = findObj("image1");
function findObj(theObj, theDoc)
{
var p, i, foundObj;
if(!theDoc) theDoc = document;
if( (p = theObj.indexOf("?")) > 0 && parent.frames.length)
{ 
    theDoc = parent.frames[theObj.substring(p+1)].document;    
    theObj = theObj.substring(0,p); 
} 
if(!(foundObj = theDoc[theObj]) && theDoc.all) 
    foundObj = theDoc.all[theObj]; 

for (i=0; !foundObj && i < theDoc.forms.length; i++)     
    foundObj = theDoc.forms[i][theObj]; 

for(i=0; !foundObj && theDoc.layers && i < theDoc.layers.length; i++)     
    foundObj = findObj(theObj,theDoc.layers[i].document); 
    
if(!foundObj && document.getElementById) 
    foundObj = document.getElementById(theObj);    

return foundObj;
}
//添加一个参与人填写行
function AddSignRow(){ //读取最后一行的行号，存放在txtTRLastIndex文本框中
var txtTRLastIndex = findObj("txtTRLastIndex",document);
var rowID = parseInt(txtTRLastIndex.value);

var signFrame = findObj("SignFrame",document);
//添加行
var newTR = signFrame.insertRow(signFrame.rows.length);
newTR.id = "SignItem" + rowID;


//添加列:序号
var newNameTD=newTR.insertCell(0);
//添加列内容
newNameTD.innerHTML = "<input name='txtName[]' id='txtName" + rowID + "' type='text' />";
newNameTD.align="center";
newNameTD.valign="middle";


//添加列:单价
var newxnTD=newTR.insertCell(1);
//添加列内容
newxnTD.innerHTML = "<input name='txtDanj[]' id='txtDanj" + rowID + "' type='text' onblur='return danjia("+rowID+")'/>";
newxnTD.align="center";
newxnTD.valign="middle";

//添加列:数量
var newEmailTD=newTR.insertCell(2);
//添加列内容
newEmailTD.innerHTML = "<input name='txtSulian[]' id='txtSulian" + rowID + "' type='text'  onblur='return shuliang("+rowID+")'/>";
newEmailTD.align="center";
newEmailTD.valign="middle";

//添加列:电话
var newTelTD=newTR.insertCell(3);
//添加列内容
newTelTD.innerHTML = "<input name='txtTel[]' id='txtTel" + rowID + "' type='text' readonly='readonly' /></div>";
newTelTD.align="center";
newTelTD.valign="middle";



//添加列:删除按钮
var newDeleteTD=newTR.insertCell(4);
//添加列内容
newDeleteTD.innerHTML = "<div align='center' style='width:40px'><a href='javascript:;' onclick=\"DeleteSignRow('SignItem" + rowID + "')\">删除</a></div>";

//将行号推进下一行
txtTRLastIndex.value = (rowID + 1).toString() ;
}
//删除指定行
function DeleteSignRow(rowid){
var signFrame = findObj("SignFrame",document);
var signItem = findObj(rowid,document);

//获取将要删除的行的Index
var rowIndex = signItem.rowIndex;

//删除指定Index的行
signFrame.deleteRow(rowIndex);

//重新排列序号，如果没有序号，这一步省略
for(i=rowIndex;i<signFrame.rows.length;i++){
signFrame.rows[i].cells[0].innerHTML = i.toString();
}
}//清空列表
function ClearAllSign(){
if(confirm('确定要清空所有套餐内容吗？')){
var signFrame = findObj("SignFrame",document);
var rowscount = signFrame.rows.length;

//循环删除行,从最后一行往前删除
for(i=rowscount - 1;i > 0; i--){
   signFrame.deleteRow(i);
}

//重置最后行号为1
var txtTRLastIndex = findObj("txtTRLastIndex",document);
txtTRLastIndex.value = "1";

//预添加一行
AddSignRow();
}
}
</script>

<div id="fixedNavBar">
<h3>页面导航</h3>
  <ul>
    <li><a id="demo1Btn" href="#demo1" class="demoBtn">基本信息</a></li>
    <li><a id="demo2Btn" href="#demo2" class="demoBtn">详情描述</a></li>
    <?php if ($output['goods_class']['gc_virtual'] == 1) {?>
    <li><a id="demo3Btn" href="#demo3" class="demoBtn">特殊商品</a></li>
    <?php }?>
    <li><a id="demo4Btn" href="#demo4" class="demoBtn">物流运费</a></li>
    <li><a id="demo5Btn" href="#demo5" class="demoBtn">发票信息</a></li>
    <li><a id="demo6Btn" href="#demo6" class="demoBtn">其他信息</a></li>
  </ul>
</div>
<?php if ($output['edit_goods_sign']) {?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<?php } else {?>
<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STEP.1</h6>
    <h2>选择商品分类</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STEP.2</h6>
    <h2>填写商品详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-camera-retro "></i>
    <h6>STEP.3</h6>
    <h2>上传商品图片</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-ok-circle"></i>
    <h6>STEP.4</h6>
    <h2>商品发布成功</h2>
  </li>
</ul>
<?php }?>
<div class="item-publish">
  <form method="post" id="goods_form" action="<?php if ($output['edit_goods_sign']) { echo urlShop('store_goods_online', 'edit_save_goods');} else { echo urlShop('store_goods_add', 'save_goods');}?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="commonid" value="<?php echo $output['goods']['goods_commonid'];?>" />
    <input type="hidden" name="type_id" value="<?php echo $output['goods_class']['type_id'];?>" />
    <input type="hidden" name="ref_url" value="<?php echo $_GET['ref_url'] ? $_GET['ref_url'] : getReferer();?>" />
    <input type="hidden" name="goods_id" value="<?php echo $output['goods_id'];?>">
    <div class="ncsc-form-goods">
      <h3 id="demo1"><?php echo $lang['store_goods_index_goods_base_info']?></h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_class'].$lang['nc_colon'];?></dt>
        <dd id="gcategory"> <?php echo $output['goods_class']['gc_tag_name'];?> <a class="ncbtn" href="<?php if ($output['edit_goods_sign']) { echo urlShop('store_goods_online', 'edit_class', array('commonid' => $output['goods']['goods_commonid'], 'ref_url' => getReferer())); } else { echo urlShop('store_goods_add', 'add_step_one'); }?>"><?php echo $lang['nc_edit'];?></a>
          <input type="hidden" id="cate_id" name="cate_id" value="<?php echo $output['goods_class']['gc_id'];?>" class="text" />
          <input type="hidden" name="cate_name" value="<?php echo $output['goods_class']['gc_tag_name'];?>" class="text"/>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['store_goods_index_goods_name'].$lang['nc_colon'];?></dt>
        <dd>
          <input name="g_name" type="text" class="text w400" value="<?php echo $output['goods']['goods_name']; ?>" />
          <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_goods_name_help'];?></p>
        </dd>
      </dl>
 <dl>
          <dt>商品关键字</dt>
          <dd><input type="text" class="text w400" value="<?php echo $output['goodss']['goods_key']; ?>" name="g_key"/>
            <span></span>
          <p class="hint">结合商品内容列出商品特点，用户习惯搜索的1-3个简短词组，以英文逗号,隔开</p>
          </dd>
      </dl>         <?php if($output['store_info']['store_flag']!=1){ ?>
      <dl>
        <dt><i class="required">*</i>是否为跨境商品</dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <label>
                <input name="g_is_cross_border" value="1" <?php if (!empty($output['goods']) && $output['goods']['is_cross_border'] == 1) { ?>checked="checked" <?php } ?> type="radio" />
                <?php echo $lang['nc_yes'];?></label>
            </li>
            <li>
              <label>
                <input name="g_is_cross_border" value="0" <?php if (empty($output['goods']) || $output['goods']['is_cross_border'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>
                <?php echo $lang['nc_no'];?></label>
            </li>
          </ul>
          <p class="hint"></p>
        </dd>
      </dl>
    
      
      <dl>
        <dt>商品卖点<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <textarea name="g_jingle" class="textarea h60 w400"><?php echo $output['goods']['goods_jingle']; ?></textarea>
          <span></span>
          <p class="hint">商品卖点最长不能超过140个汉字</p>
        </dd>
      </dl>
        <?php }  ?>
      <dl>
        <dt nc_type="no_spec"><i class="required">*</i><?php echo $lang['store_goods_index_store_price'].$lang['nc_colon'];?></dt>
        <dd nc_type="no_spec">
          <input name="g_price" id='g_price' value="<?php echo ncPriceFormat($output['goods']['goods_price']); ?>" type="text"  class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_store_price_help'];?>，且不能高于市场价。<br>
            此价格为商品实际销售价格，如果商品存在规格，该价格显示最低价格。</p>
        </dd>
      </dl>
      <?php if($output['store_info']['store_flag']!=1){ ?>
      <dl>
        <dt><i class="required">*</i>云豆<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="g_points" value="<?php echo ncPriceFormat($output['goods']['goods_points']); ?>" type="text"  class="text w60"  /><em class="add-on">分</em> <span></span>
          <p class="hint">购买该商品需要的云豆，1~9999（市场实际情况）</p>
        </dd>
      </dl>
      <?php }else{ ?>
        <input name="g_points" value="<?php echo ncPriceFormat($output['goods']['goods_points']); ?>" type="hidden"  class="text w60"  />
      <?php } ?>
      <dl>
        <dt><i class="required">*</i>市场价<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="g_marketprice" value="<?php echo ncPriceFormat($output['goods']['goods_marketprice']); ?>" type="text" class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_store_price_help'];?>，此价格仅为市场参考售价，请根据该实际情况认真填写。</p>
        </dd>
      </dl>
     <?php if($output['store_info']['store_flag']==1){ ?>
      <dl>
        <dt><i class="required">*</i><?php echo '选择套餐：';?></dt>
        <dd>
           <input type="radio"  name="usertc" value="0" <?php if(empty($output['goods']['usertc'])){ echo "checked=checked"; } ?>  onclick="return checkit()" />A套餐
             <input type="radio"  name="usertc" value="1" <?php if($output['goods']['usertc']==1){ echo "checked=checked"; } ?> onclick="return checkit()"  />B套餐
               <input type="radio"  name="usertc" value="2" <?php if($output['goods']['usertc']==2){ echo "checked=checked"; } ?>  onclick="return checkit()" />C套餐
         <span></span>
         
        </dd>
      </dl>
      <div id = "acd" <?php if($output['goods']['usertc']==0){ echo 'style="display:block"'; }else{ echo 'style="display:none"';} ?>>
      <dl>
      <input type="hidden" name="store_flag" value="<?php echo $output['store_info']['store_flag']; ?>" />
        <dt><i class="required">*</i><?php echo '默认A套餐赠送云豆数量：';?></dt>
        <dd>
          <input name="points" id="points" value=" <?php echo $output['goods']['goods_price']*0.5; ?>" type="text"  class="text w60" readonly="readonly" /><em class="add-on">颗</em> <span></span>
          <p class="hint">默认为A套餐</p>
        </dd>
      </dl>
      </div>
      <div id= "bcd" <?php if($output['goods']['usertc']==1){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
       <dl>
        <dt><i class="required">*</i><?php echo '设置B套餐赠送倍数：';?></dt>
        <dd> <div class="ncs-figure-input">
           <input name="quantity" type="text" class="input-text" id="quantity"
           <?php  if($output['goods']['pointsb']=='0.00'||empty($output['goods']['pointsb'])){  echo "value=0.6"; }else{  echo "value=".$output['goods']['pointsb']; ?>   <?php }?> size="2" readonly="readonly">
        
          
<a href="javascript:void(0)" class="increase" nctype="increase">&nbsp;</a> <a href="javascript:void(0)" class="decrease" nctype="decrease">&nbsp;</a> </div> 
          <p class="hint">如果为B套餐，请选择设置上面的基数，基数越大赠送的云豆越多！</p>
  </dd>
      </dl>
       <dl>
        <dt><i class="required">*</i><?php echo '默认B套餐赠送云豆数量：';?></dt>
        <dd>
          <input name="pointsb" id="pointsb" value="<?php if($output['goods']['pointsb']>0.6){echo $output['goods']['goods_price']*$output['goods']['pointsb']; }else{  echo $output['goods']['goods_price']*0.6; }?>" type="text"  class="text w60"  readonly="readonly"/><em class="add-on">颗</em> <span></span>
        
           <div class="ncs-figure-input">


                 
</div>
        </dd>
      </dl>
      </div>
      <div id="ccpoint"  <?php if($output['goods']['usertc']==2){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
        <dl>
        <dt><i class="required">*</i><?php echo '默认c套餐消费云豆数量：';?></dt>
        <dd>
          <input name="pointcsb" id="pointcsb" value=" <?php if(empty($output['goods']['goods_points'])){  echo $output['goods']['goods_price']*0.2; } else{ echo $output['goods']['goods_points']; } ?>" type="text"  class="text w60"  readonly="readonly"/><em class="add-on">颗</em> <span></span>
        
           <div class="ncs-figure-input">


                 
</div>
     </dd>
     </dl>
      </div>
      <?php } ?>
      <dl>
        <dt>成本价<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="g_costprice" value="<?php echo ncPriceFormat($output['goods']['goods_costprice']); ?>" type="text" class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint">价格必须是0.00~9999999之间的数字，此价格为商户对所销售的商品实际成本价格进行备注记录，非必填选项，不会在前台销售页面中显示。</p>
        </dd>
      </dl>
      <dl>
        <dt>折扣<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="g_discount" value="<?php echo $output['goods']['goods_discount']; ?>" type="text" class="text w60" readonly style="background:#E7E7E7 none;" /><em class="add-on">%</em>
          <p class="hint">根据销售价与市场价比例自动生成，不需要编辑。</p>
        </dd>
      </dl>
      <?php if($output['goods_class']['gc_id']=='10417'){ ?>
        <dl>
        <dt>电影投资金额</dt>
        <dd>
          <input name="goods_max_money" value="<?php echo ncPriceFormat($output['goods']['goods_max_money']); ?>" type="text" class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint">电影需要多少资金投入最低0-1000000000元</p>
        </dd>
      </dl>
                <?php } ?>
      <?php if(is_array($output['spec_list']) && !empty($output['spec_list'])){?>
      <?php $i = '0';?>
      <?php foreach ($output['spec_list'] as $k=>$val){?>
      <dl nc_type="spec_group_dl_<?php echo $i;?>" nctype="spec_group_dl" class="spec-bg" <?php if($k == '1'){?>spec_img="t"<?php }?>>
        <dt>
          <input name="sp_name[<?php echo $k;?>]" type="text" class="text w60 tip2 tr" title="自定义规格类型名称，规格值名称最多不超过4个字" value="<?php if (isset($output['goods']['spec_name'][$k])) { echo $output['goods']['spec_name'][$k];} else {echo $val['sp_name'];}?>" maxlength="4" nctype="spec_name" data-param="{id:<?php echo $k;?>,name:'<?php echo $val['sp_name'];?>'}"/>
          <?php echo $lang['nc_colon']?></dt>
        <dd <?php if($k == '1'){?>nctype="sp_group_val"<?php }?>>
          <ul class="spec">
            <?php if(is_array($val['value'])){?>
            <?php foreach ($val['value'] as $v) {?>
            <li class="panceil"><span nctype="input_checkbox">
              <input type="checkbox" value="<?php echo $v['sp_value_name'];?>" nc_type="<?php echo $v['sp_value_id'];?>" <?php if($k == '1'){?>class="sp_val"<?php }?> name="sp_val[<?php echo $k;?>][<?php echo $v['sp_value_id']?>]">
              </span><span nctype="pv_name"><?php echo $v['sp_value_name'];?></span>
              <span class="pandelete"></span>
            </li>
            <?php }?>
            <?php }?>
            <li data-param="{gc_id:<?php echo $output['goods_class']['gc_id'];?>,sp_id:<?php echo $k;?>,url:'<?php echo urlShop('store_goods_add', 'ajax_add_spec');?>'}">
              <div nctype="specAdd1"><a href="javascript:void(0);" class="ncbtn" nctype="specAdd"><i class="icon-plus"></i>添加规格值</a></div>
              <div nctype="specAdd2" style="display:none;">
                <input class="text w60" type="text" placeholder="规格值名称" maxlength="40">
                <a href="javascript:void(0);" nctype="specAddSubmit" class="ncbtn ncbtn-aqua ml5 mr5">确认</a><a href="javascript:void(0);" nctype="specAddCancel" class="ncbtn ncbtn-bittersweet">取消</a></div>
            </li>
          </ul>
          <?php if($output['edit_goods_sign'] && $k == '1'){?>
          <p class="hint">添加或取消颜色规格时，提交后请编辑图片以确保商品图片能够准确显示。</p>
          <?php }?>
        </dd>
      </dl>
      <?php $i++;?>
      <?php }?>
      <?php }?>
      <dl nc_type="spec_dl" class="spec-bg" style="display:none; overflow: visible;">
        <dt><?php echo $lang['srore_goods_index_goods_stock_set'].$lang['nc_colon'];?></dt>
        <dd class="spec-dd">
          <div nctype="spec_div" class="spec-div">
          <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
            <thead>
              <?php if(is_array($output['spec_list']) && !empty($output['spec_list'])){?>
              <?php foreach ($output['spec_list'] as $k=>$val){?>
            <th nctype="spec_name_<?php echo $k;?>"><?php if (isset($output['goods']['spec_name'][$k])) { echo $output['goods']['spec_name'][$k];} else {echo $val['sp_name'];}?></th>
              <?php }?>
              <?php }?>
              <th class="w90"><span class="red">*</span>市场价
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="ncbtn-mini" data-type="marketprice">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w90"><span class="red">*</span><?php echo $lang['store_goods_index_price'];?>
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="ncbtn-mini" data-type="price">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w60"><span class="red">*</span><?php echo $lang['store_goods_index_stock'];?>
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置库存：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="ncbtn-mini" data-type="stock">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w70">预警值
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置预警值：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="ncbtn-mini" data-type="alarm">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w100"><?php echo $lang['store_goods_index_goods_no'];?></th>
              <th class="w100">商品条形码</th>
                </thead>
            <tbody nc_type="spec_table">
            </tbody>
          </table>
          </div>
          <p class="hint">点击<i class="icon-edit"></i>可批量修改所在列的值。<br>当规格值较多时，可在操作区域通过滑动滚动条查看超出隐藏区域。</p>
        </dd>
      </dl>
      
      <dl>
        <dt nc_type="no_spec"><i class="required">*</i><?php echo $lang['store_goods_index_goods_stock'].$lang['nc_colon'];?></dt>
        <dd nc_type="no_spec">
          <input name="g_storage" value="<?php echo $output['goods']['g_storage'];?>" type="text" class="text w60" />
          <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_goods_stock_help'];?></p>
        </dd>
      </dl>
    
      <dl>
        <dt>库存预警值<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="g_alarm" value="<?php echo $output['goods']['goods_storage_alarm'];?>" type="text" class="text w60" />
          <span></span>
          <p class="hint">设置最低库存预警值。当库存低于预警值时商家中心商品列表页库存列红字提醒。<br>
            请填写0~255的数字，0为不预警。</p>
        </dd>
      </dl>  
         <?php if($output['store_info']['store_flag']!=1){ ?>
      <dl>
        <dt nc_type="no_spec"><?php echo $lang['store_goods_index_goods_no'].$lang['nc_colon'];?></dt>
        <dd nc_type="no_spec">
          <p>
            <input name="g_serial" value="<?php echo $output['goods']['goods_serial'];?>" type="text" class="text" />
          </p>
          <p class="hint"><?php echo $lang['store_goods_index_goods_no_help'];?></p>
        </dd>
      </dl>
 
      <dl>
        <dt nc_type="no_spec">商品条形码：</dt>
        <dd nc_type="no_spec">
          <p>
            <input name="g_barcode" value="<?php echo $output['goods']['goods_barcode'];?>" type="text" class="text" />
          </p>
          <p class="hint">请填写商品条形码下方数字。</p>
        </dd>
      </dl>
    
      <?php }else{ ?>
      <?php if(!empty($output['gcm'])){ ?>
       <dl>
        <dt nc_type="no_spec"><i class="required">*</i>购买须知配置：</dt>
        <dd nc_type="no_spec">
          <p class="hint"> 
         
          <?php  if($output['gcm']==1){ ?>
     
          <table width="500" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="300" height="50" align="right"><table width="500" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="300" height="50" align="right">有效期：</td>
                  <td width="70%" height="50"><textarea name="validate" id="validate"><?php echo $output['validate']; ?></textarea></td>
                </tr>
                <tr>
                  <td width="300" height="50" align="right">使用时间：</td>
                  <td height="50"><textarea name="usertime" id="usertime"><?php echo $output['usertime']; ?></textarea></td>
                </tr>
                <tr>
                  <td width="300" height="50" align="right">预约提醒：</td>
                  <td height="50"><textarea name="attationpeople" id="attationpeople"><?php echo $output['attationpeople']; ?></textarea></td>
                </tr>
                <tr>
                  <td width="300" height="50" align="right">其他费用：</td>
                  <td height="50"><textarea name="otherfree" id="otherfree"><?php echo $output['otherfree']; ?></textarea></td>
                </tr>
                <tr>
                  <td width="300" height="50" align="right">其他优惠：</td>
                  <td height="50"><textarea name="othercoupon" id="othercoupon"><?php echo $output['othercoupon']; ?></textarea></td>
                </tr>
                <tr>
                  <td width="300" height="50" align="right">使用规则：</td>
                  <td height="50"><textarea name="otherglue" id="otherglue"><?php echo $output['otherglue']; ?></textarea></td>
                </tr>
               <!-- <tr>
                  <td width="300" height="50" align="right">套餐内容：</td>
                  <td height="50"><textarea name="suitcontent" id="suitcontent"><?php echo $output['suitcontent']; ?></textarea></td>
                </tr>-->
              </table></td>
            </tr>
          </table>
          <?php }else if($output['gcm']==2){?>
          <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" height="50" align="right">有效期：</td>
    <td width="70%" height="50"><textarea name="validate" id="validate"><?php echo $output['validate']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用时间：</td>
    <td height="50"><textarea name="usertime" id="usertime"><?php echo $output['usertime']; ?></textarea></td>
  </tr>
 
  <tr>
    <td width="300" height="50" align="right">预约提醒：</td>
    <td height="50"><textarea name="attationpeople" id="attationpeople"><?php echo $output['attationpeople']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">适用人数：</td>
    <td height="50"><textarea name="suitpepole" id="suitpepole"><?php echo $output['suitpepole']; ?></textarea></td>
  </tr>
  <tr>
  <tr>
    <td width="300" height="50" align="right">其他费用：</td>
    <td height="50"><textarea name="otherfree" id="otherfree"><?php echo $output['otherfree']; ?></textarea></td>
  </tr>
 <tr>
    <td width="300" height="50" align="right">其他优惠：</td>
    <td height="50"><textarea name="othercoupon" id="othercoupon"><?php echo $output['othercoupon']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用规则：</td>
    <td height="50"><textarea name="otherglue" id="otherglue"><?php echo $output['otherglue']; ?></textarea></td>
  </tr>
 
  <!--<tr>
    <td width="300" height="50" align="right">套餐内容：</td>
    <td height="50"><textarea name="suitcontent" id="suitcontent"><?php echo $output['suitcontent']; ?></textarea></td>
  </tr>-->
          </table>
          <?php }else if($output['gcm']==3){ ?>
          <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" height="50" align="right">有效期：</td>
    <td width="70%" height="50"><textarea name="validate" id="validate"><?php echo $output['validate']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用时间：</td>
    <td height="50"><textarea name="usertime" id="usertime"><?php echo $output['usertime']; ?></textarea></td>
  </tr>
  
  <tr>
    <td width="300" height="50" align="right">预约提醒：</td>
    <td height="50"><textarea name="attationpeople" id="attationpeople"><?php echo $output['attationpeople']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">入住时间：</td>
    <td height="50"><textarea name="ruzhutime" id="ruzhutime"><?php echo $output['ruzhutime']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">入住须知：</td>
    <td height="50"><textarea name="shopknow" id="shopknow"><?php echo $output['shopknow']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用规则：</td>
    <td height="50"><textarea name="otherglue" id="otherglue"><?php echo $output['otherglue']; ?></textarea></td>
  </tr>
  <!--<tr>
    <td width="300" height="50" align="right">套餐内容：</td>
    <td height="50"><textarea name="suitcontent" id="suitcontent"><?php echo $output['suitcontent']; ?></textarea></td>
  </tr>-->
          </table>
          <?php }else if($output['gcm']==4){ ?>
          <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" height="50" align="right">有效期：</td>
    <td width="70%" height="50"><textarea name="validate" id="validate"><?php echo $output['validate']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用时间：</td>
    <td height="50"><textarea name="usertime" id="usertime"><?php echo $output['usertime']; ?></textarea></td>
  </tr> 
  <tr>
    <td width="300" height="50" align="right">预约提醒：</td>
    <td height="50"><textarea name="attationpeople" id="attationpeople"><?php echo $output['attationpeople']; ?></textarea></td>
  </tr>
  <tr>
  <tr>
    <td width="300" height="50" align="right">适用人数：</td>
    <td height="50"><textarea name="suitpepole" id="suitpepole"><?php echo $output['suitpepole']; ?></textarea></td>
  </tr>
 
    <td width="300" height="50" align="right">其他费用：</td>
    <td height="50"><textarea name="otherfree" id="otherfree"><?php echo $output['otherfree']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">其他优惠：</td>
    <td height="50"><textarea name="othercoupon" id="othercoupon"><?php echo $output['othercoupon']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用规则：</td>
    <td height="50"><textarea name="otherglue" id="otherglue"><?php echo $output['otherglue']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">适用人群：</td>
    <td height="50"><textarea name="otherpeole" id="otherpeole"><?php echo $output['otherpeole']; ?></textarea></td>
  </tr>
  <!--<tr>
    <td width="300" height="50" align="right">套餐内容：</td>
    <td height="50"><textarea name="suitcontent" id="suitcontent"><?php echo $output['suitcontent']; ?></textarea></td>
  </tr>-->
          </table>
          <?php }else if($output['gcm']==5){ ?>
            <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" height="50" align="right">有效期：</td>
    <td width="70%" height="50"><textarea name="validate" id="validate"><?php echo $output['validate']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用时间：</td>
    <td height="50"><textarea name="usertime" id="usertime"><?php echo $output['usertime']; ?></textarea></td>
  </tr> 
  <tr>
    <td width="300" height="50" align="right">预约提醒：</td>
    <td height="50"><textarea name="attationpeople" id="attationpeople"><?php echo $output['attationpeople']; ?></textarea></td>
  </tr>
  <tr>
  <tr>
    <td width="300" height="50" align="right">适用人数：</td>
    <td height="50"><textarea name="suitpepole" id="suitpepole"><?php echo $output['suitpepole']; ?></textarea></td>
  </tr>
 
    <td width="300" height="50" align="right">其他费用：</td>
    <td height="50"><textarea name="otherfree" id="otherfree"><?php echo $output['otherfree']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">其他优惠：</td>
    <td height="50"><textarea name="othercoupon" id="othercoupon"><?php echo $output['othercoupon']; ?></textarea></td>
  </tr>
  <tr>
    <td width="300" height="50" align="right">使用规则：</td>
    <td height="50"><textarea name="otherglue" id="otherglue"><?php echo $output['otherglue']; ?></textarea></td>
  </tr>
  <!--<tr>
    <td width="300" height="50" align="right">套餐内容：</td>
    <td height="50"><textarea name="suitcontent" id="suitcontent"><?php echo $output['suitcontent']; ?></textarea></td>
  </tr>-->
          </table>
          <?php } ?>
</p>
        </dd>
      </dl>
      
      
      
      
       <dl>
        <dt nc_type="no_spec"><i class="required">*</i>购买套餐配置：</dt>
        <dd nc_type="no_spec">
          <p class="hint"> 
         
          <?php  if($output['gcm']==1){ ?>
         
         <div>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td width="27%" height="30" align="center" valign="middle" bgcolor="#96e0e2">套餐内容</td>
    <td width="73%" rowspan="2" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="1" cellspacing="1" id="SignFrame" style=" border:#ffffff 1px solid" >
      <tr id="trHeader">
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2" >菜品名</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">单价</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">数量</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">小计</td>
        <td width="50" height="30" align="center" valign="middle" bgcolor="#96E0E2">操作</td>
      </tr>
      <?php if($output['txtTRLastIndex']){
		  
		  for($i==0;$i<$output['txtTRLastIndex'];$i++){
		  ?>
      
      <tr id="SignItem<?php if($i==0){echo'0';}else{echo $i;}?>">
      <td align="center" valign="middle"><input name='txtName[]' id='txtName<?php if($i==0){echo'0';}else{echo $i;}?>' type='text' value="<?php if($i==0){echo $output['txtName'][0];}else{ echo $output['txtName'][$i];} ?>"  style="height:25px" /> </td>
      <td align="center" valign="middle"><input name='txtDanj[]' id='txtDanj<?php if($i==0){echo'0';}else{echo $i;}?>' type='text' onblur='return danjia("<?php if($i==0){echo'0';}else{echo $i;}?>")'  style="height:25px" value="<?php if($i==0){echo $output['txtDanj'][0];}else{ echo $output['txtDanj'][$i];} ?>" /></td>
      <td align="center" valign="middle"><input name='txtSulian[]' id='txtSulian<?php if($i==0){echo'0';}else{echo $i;}?>' type='text'  onblur='return shuliang("<?php if($i==0){echo'0';}else{echo $i;}?>")'  style="height:25px"  value="<?php if($i==0){echo $output['txtSulian'][0];}else{ echo $output['txtSulian'][$i];} ?>"/></td>
      <td align="center" valign="middle"><input name='txtTel[]' id='txtTel<?php if($i==0){echo'0';}else{echo $i;}?>' type='text' readonly='readonly'  style="height:25px" value="<?php if($i==0){echo $output['txtTel'][0];}else{ echo $output['txtTel'][$i];} ?>"/></td>
     <td align="center" valign="middle"> <div align='center' style='width:40px'><a href='javascript:;' onclick="DeleteSignRow('SignItem<?php if($i==0){echo'0';}else{echo $i;}?>')">删除</a></div></td>
    </tr>
    <?php } } ?>
    </table>
    <tr>
    <td bgcolor="#FFFFFF">如：烤鱼套餐、烤肉套餐、饮品3选1等；自行填写</td>
  </table>

   </div>
   <div>
        <input type="button" name="Submit" value="添加套餐内容" onclick="AddSignRow()" />
     <input type="button" name="Submit2" value="清空" onclick="ClearAllSign()" />
      
     <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="<?php if($output['txtTRLastIndex']){ echo $output['txtTRLastIndex']; }else{ echo "0";} ?>" />
     
   </div>

          <?php }else if($output['gcm']==2){?>
    
  
         <div>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td width="27%" height="30" align="center" valign="middle" bgcolor="#96e0e2">套餐内容</td>
    <td width="73%" rowspan="2" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="1" cellspacing="1" id="SignFrame" style=" border:#ffffff 1px solid" >
      <tr id="trHeader">
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2" >项目</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">单价</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">数量</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">小计</td>
        <td width="50" height="30" align="center" valign="middle" bgcolor="#96E0E2">操作</td>
      </tr>
    </table>
    <tr>
    <td bgcolor="#FFFFFF">如：美甲套餐、美发套餐</td>
  </table>

   </div>
   <div>
        <input type="button" name="Submit" value="添加套餐内容" onclick="AddSignRow()" />
     <input type="button" name="Submit2" value="清空" onclick="ClearAllSign()" />
     <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="1" />
   </div>

          <?php }else if($output['gcm']==3){ ?>
           
         <div>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td width="27%" height="30" align="center" valign="middle" bgcolor="#96e0e2">套餐内容</td>
    <td width="73%" rowspan="2" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="1" cellspacing="1" id="SignFrame" style=" border:#ffffff 1px solid" >
      <tr id="trHeader">
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2" >项目</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">单价</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">数量</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">小计</td>
        <td width="50" height="30" align="center" valign="middle" bgcolor="#96E0E2">操作</td>
      </tr>
    </table>
    <tr>
    <td bgcolor="#FFFFFF">内容（如：烤鱼套餐、烤肉套餐、饮品3选1等；自行填写）</td>
  </table>

   </div>
   <div>
        <input type="button" name="Submit" value="添加套餐内容" onclick="AddSignRow()" />
     <input type="button" name="Submit2" value="清空" onclick="ClearAllSign()" />
     <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="1" />
   </div>

          <?php }else if($output['gcm']==4){ ?>
           <div>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td width="27%" height="30" align="center" valign="middle" bgcolor="#96e0e2">套餐内容</td>
    <td width="73%" rowspan="2" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="1" cellspacing="1" id="SignFrame" style=" border:#ffffff 1px solid" >
      <tr id="trHeader">
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2" >项目</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">单价</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">数量</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">小计</td>
        <td width="50" height="30" align="center" valign="middle" bgcolor="#96E0E2">操作</td>
      </tr>
    </table>
    <tr>
    <td bgcolor="#FFFFFF">如：24小时净桑套餐</td>
  </table>

   </div>
   <div>
        <input type="button" name="Submit" value="添加套餐内容" onclick="AddSignRow()" />
     <input type="button" name="Submit2" value="清空" onclick="ClearAllSign()" />
     <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="1" />
   </div>

  
          <?php }else if($output['gcm']==5){ ?>
          <div>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td width="27%" height="30" align="center" valign="middle" bgcolor="#96e0e2">套餐内容</td>
    <td width="73%" rowspan="2" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="1" cellspacing="1" id="SignFrame" style=" border:#ffffff 1px solid" >
      <tr id="trHeader">
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2" >项目</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">单价</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">数量</td>
        <td width="100" height="30" align="center" valign="middle" bgcolor="#96E0E2">小计</td>
        <td width="50" height="30" align="center" valign="middle" bgcolor="#96E0E2">操作</td>
      </tr>
    </table>
    <tr>
    <td bgcolor="#FFFFFF">如：汽车美容套餐、宠物美容套餐</td>
  </table>

   </div>
   <div>
        <input type="button" name="Submit" value="添加套餐内容" onclick="AddSignRow()" />
     <input type="button" name="Submit2" value="清空" onclick="ClearAllSign()" />
     <input name='txtTRLastIndex' type='hidden' id='txtTRLastIndex' value="1" />
   </div>

            
          
          <?php } ?>
</p>
        </dd>
      </dl>
       <!--<dl>
        <dt nc_type="no_spec"><i class="required">*</i>商品套餐内容配置：</dt>
        <dd nc_type="no_spec">
       
          <p class="hint">
          </p>
        </dd>
      </dl>-->
      
      <?php } }?>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['store_goods_album_goods_pic'].$lang['nc_colon'];?></dt>
        <dd>
          <div class="ncsc-goods-default-pic">
            <div class="goodspic-uplaod">
              <div class="upload-thumb"> <img nctype="goods_image" src="<?php echo thumb($output['goods'], 240);?>"/> </div>
              <input type="hidden" name="image_path" id="image_path" nctype="goods_image" value="<?php echo $output['goods']['goods_image']?>" />
              <span></span>
              <p class="hint">上传商品默认主图必须为<font color="red">宽高比1:1的正方形</font>图片，如多规格值时将默认使用该图或分规格上传各规格主图；支持jpg、gif、png格式上传或从图片空间中选择，建议使用<font color="red">尺寸800x800像素以上、大小不超过400KB的正方形图片</font>，上传后的图片将会自动保存在图片空间的默认分类中。</p>
              <div class="handle">
                <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="goods_image" id="goods_image">
                  </span>
                  <p><i class="icon-upload-alt"></i>图片上传</p>
                  </a> 
                </div>
                <a class="ncbtn mt5" nctype="show_image" href="index.php?act=store_album&op=pic_list&item=goods"><i class="icon-picture"></i>从图片空间选择</a> <a href="javascript:void(0);" nctype="del_goods_demo" class="ncbtn mt5" style="display: none;"><i class="icon-circle-arrow-up"></i>关闭相册</a>
              </div>
            </div>
          </div>
          <div id="demo"></div>
        </dd>
      </dl>
      <h3 id="demo2"><?php echo $lang['store_goods_index_goods_detail_info']?></h3>
   <!-- 2017/9/6 xin增加商品详情视频展示-->
            <dl>
                <dt>视频地址</dt>
                <dd><input type="text" value="<?php echo $output['goodss']['goods_url']; ?>" name="g_url"/></dd>
            </dl>
      <dl style="overflow: visible;">
        <dt><?php echo $lang['store_goods_index_goods_brand'].$lang['nc_colon'];?></dt>
        <dd>
          <div class="ncsc-brand-select">
            <div class="selection">
              <input name="b_name" id="b_name" value="<?php echo $output['goods']['brand_name'];?>" type="text" class="text w180" readonly /><input type="hidden" name="b_id" id="b_id" value="<?php echo $output['goods']['brand_id'];?>" /><em class="add-on"><i class="icon-collapse"></i></em></div>
            <div class="ncsc-brand-select-container">
              <div class="brand-index" data-tid="<?php echo $output['goods_class']['type_id'];?>" data-url="<?php echo urlShop('store_goods_add', 'ajax_get_brand');?>">
                <div class="letter" nctype="letter">
                  <ul>
                    <li><a href="javascript:void(0);" data-letter="all">全部品牌</a></li>
                    <li><a href="javascript:void(0);" data-letter="A">A</a></li>
                    <li><a href="javascript:void(0);" data-letter="B">B</a></li>
                    <li><a href="javascript:void(0);" data-letter="C">C</a></li>
                    <li><a href="javascript:void(0);" data-letter="D">D</a></li>
                    <li><a href="javascript:void(0);" data-letter="E">E</a></li>
                    <li><a href="javascript:void(0);" data-letter="F">F</a></li>
                    <li><a href="javascript:void(0);" data-letter="G">G</a></li>
                    <li><a href="javascript:void(0);" data-letter="H">H</a></li>
                    <li><a href="javascript:void(0);" data-letter="I">I</a></li>
                    <li><a href="javascript:void(0);" data-letter="J">J</a></li>
                    <li><a href="javascript:void(0);" data-letter="K">K</a></li>
                    <li><a href="javascript:void(0);" data-letter="L">L</a></li>
                    <li><a href="javascript:void(0);" data-letter="M">M</a></li>
                    <li><a href="javascript:void(0);" data-letter="N">N</a></li>
                    <li><a href="javascript:void(0);" data-letter="O">O</a></li>
                    <li><a href="javascript:void(0);" data-letter="P">P</a></li>
                    <li><a href="javascript:void(0);" data-letter="Q">Q</a></li>
                    <li><a href="javascript:void(0);" data-letter="R">R</a></li>
                    <li><a href="javascript:void(0);" data-letter="S">S</a></li>
                    <li><a href="javascript:void(0);" data-letter="T">T</a></li>
                    <li><a href="javascript:void(0);" data-letter="U">U</a></li>
                    <li><a href="javascript:void(0);" data-letter="V">V</a></li>
                    <li><a href="javascript:void(0);" data-letter="W">W</a></li>
                    <li><a href="javascript:void(0);" data-letter="X">X</a></li>
                    <li><a href="javascript:void(0);" data-letter="Y">Y</a></li>
                    <li><a href="javascript:void(0);" data-letter="Z">Z</a></li>
                    <li><a href="javascript:void(0);" data-letter="0-9">其他</a></li>
                  </ul>
                </div>
                <div class="search" nctype="search">
                  <input name="search_brand_keyword" id="search_brand_keyword" type="text" class="text" placeholder="品牌名称关键字查找"/><a href="javascript:void(0);" class="ncbtn-mini" style="vertical-align: top;">Go</a></div>
              </div>
              <div class="brand-list" nctype="brandList">
                <ul nctype="brand_list">
                  <?php if(is_array($output['brand_list']) && !empty($output['brand_list'])){?>
                  <?php foreach($output['brand_list'] as $val) { ?>
                  <li data-id='<?php echo $val['brand_id'];?>'data-name='<?php echo $val['brand_name'];?>'><em><?php echo $val['brand_initial'];?></em><?php echo $val['brand_name'];?></li>
                  <?php } ?>
                  <?php }?>
                </ul>
              </div>
              <div class="no-result" nctype="noBrandList" style="display: none;">没有符合"<strong>搜索关键字</strong>"条件的品牌</div>
              <div class="tc"><a href="javascript:void(0);" class="ncbtn-mini" onclick="$(this).parents('.ncsc-brand-select-container:first').hide();">关闭品牌列表</a></div>
            </div>
            
          </div>
        </dd>
      </dl>
      <?php if(is_array($output['attr_list']) && !empty($output['attr_list'])){?>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_attr'].$lang['nc_colon']; ?></dt>
        <dd>
          <?php foreach ($output['attr_list'] as $k=>$val){?>
          <span class="property">
          <label class="mr5"><?php echo $val['attr_name']?></label>
          <input type="hidden" name="attr[<?php echo $k;?>][name]" value="<?php echo $val['attr_name']?>" />
          <?php if(is_array($val) && !empty($val)){?>
          <select name="" attr="attr[<?php echo $k;?>][__NC__]" nc_type="attr_select">
            <option value='不限' nc_type='0'>不限</option>
            <?php foreach ($val['value'] as $v){?>
            <option value="<?php echo $v['attr_value_name']?>" <?php if(isset($output['attr_checked']) && in_array($v['attr_value_id'], $output['attr_checked'])){?>selected="selected"<?php }?> nc_type="<?php echo $v['attr_value_id'];?>"><?php echo $v['attr_value_name'];?></option>
            <?php }?>
          </select>
          <?php }?>
          </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <?php if (!empty($output['custom_list'])) {?>
      <dl>
        <dt>自定义属性：</dt>
        <dd>
          <?php foreach ($output['custom_list'] as $val) {?>
          <span class="property">
            <label class="mr5"><?php echo $val['custom_name'];?></label>
            <input type="hidden" name="custom[<?php echo $val['custom_id'];?>][name]" value="<?php echo $val['custom_name'];?>" />
            <input class="text w60" type="text" name="custom[<?php echo $val['custom_id'];?>][value]" value="<?php if ($output['goods']['goods_custom'][$val['custom_id']]['value'] != '') {echo $output['goods']['goods_custom'][$val['custom_id']]['value'];}?>" />
          </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_desc'].$lang['nc_colon'];?></dt>
        <dd id="ncProductDetails">
          <div class="tabs">
            <ul class="ui-tabs-nav">
              <li class="ui-tabs-selected"><a href="#panel-1"><i class="icon-desktop"></i> 电脑端</a></li>
              <li class="selected"><a href="#panel-2"><i class="icon-mobile-phone"></i>手机端</a></li>
            </ul>
            <div id="panel-1" class="ui-tabs-panel">
              <?php showEditor('g_body',$output['goods']['goods_body'],'100%','480px','visibility:hidden;',"false",$output['editor_multimedia']);?>
              <div class="hr8">
                <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="add_album" id="add_album" multiple>
                  </span>
                  <p><i class="icon-upload-alt" data_type="0" nctype="add_album_i"></i>图片上传</p>
                  </a> </div>
                <a class="ncbtn mt5" nctype="show_desc" href="index.php?act=store_album&op=pic_list&item=des"><i class="icon-picture"></i><?php echo $lang['store_goods_album_insert_users_photo'];?></a> <a href="javascript:void(0);" nctype="del_desc" class="ncbtn mt5" style="display: none;"><i class=" icon-circle-arrow-up"></i>关闭相册</a> </div>
              <p id="des_demo"></p>
            </div>
            <div id="panel-2" class="ui-tabs-panel ui-tabs-hide">
              <div class="ncsc-mobile-editor">
                <div class="pannel">
                  <div class="size-tip"><span nctype="img_count_tip">图片总数不得超过<em>20</em>张</span><i>|</i><span nctype="txt_count_tip">文字不得超过<em>5000</em>字</span></div>
                  <div class="control-panel" nctype="mobile_pannel">
                    <?php if (!empty($output['goods']['mb_body'])) {?>
                    <?php foreach ($output['goods']['mb_body'] as $val) {?>
                    <?php if ($val['type'] == 'text') {?>
                    <div class="module m-text">
                      <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_edit" href="javascript:void(0);">编辑</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="text-div"><?php echo $val['value'];?></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php if ($val['type'] == 'image') {?>
                    <div class="module m-image">
                      <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_rpl" href="javascript:void(0);">替换</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="image-div"><img src="<?php echo $val['value'];?>"></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php }?>
                    <?php }?>
                  </div>
                  <div class="add-btn">
                    <ul class="btn-wrap">
                      <li><a href="javascript:void(0);" nctype="mb_add_img"><i class="icon-picture"></i>
                        <p>图片</p>
                        </a></li>
                      <li><a href="javascript:void(0);" nctype="mb_add_txt"><i class="icon-font"></i>
                        <p>文字</p>
                        </a></li>
                    </ul>
                  </div>
                </div>
                <div class="explain">
                  <dl>
                    <dt>1、基本要求：</dt>
                    <dd>（1）手机详情总体大小：图片+文字，图片不超过20张，文字不超过5000字；</dd>
                    <dd>建议：所有图片都是本宝贝相关的图片。</dd>
                  </dl><dl>
                    <dt>2、图片大小要求：</dt>
                    <dd>（1）建议使用宽度480 ~ 620像素、高度小于等于960像素的图片；</dd>
                    <dd>（2）格式为：JPG\JEPG\GIF\PNG；</dd>
                    <dd>举例：可以上传一张宽度为480，高度为960像素，格式为JPG的图片。</dd>
                  </dl><dl>
                    <dt>3、文字要求：</dt>
                    <dd>（1）每次插入文字不能超过500个字，标点、特殊字符按照一个字计算；</dd>
                    <dd>（2）请手动输入文字，不要复制粘贴网页上的文字，防止出现乱码；</dd>
                    <dd>（3）以下特殊字符“<”、“>”、“"”、“'”、“\”会被替换为空。</dd>
                    <dd>建议：不要添加太多的文字，这样看起来更清晰。</dd>
                  </dl>
                </div>
              </div>
              <div class="ncsc-mobile-edit-area" nctype="mobile_editor_area">
                <div nctype="mea_img" class="ncsc-mea-img" style="display: none;"></div>
                <div class="ncsc-mea-text" nctype="mea_txt" style="display: none;">
                  <p id="meat_content_count" class="text-tip"></p>
                  <textarea class="textarea valid" nctype="meat_content"></textarea>
                  <div class="button"><a class="ncbtn ncbtn-bluejeansjeansjeans" nctype="meat_submit" href="javascript:void(0);">确认</a><a class="ncbtn ml10" nctype="meat_cancel" href="javascript:void(0);">取消</a></div>
                  <a class="text-close" nctype="meat_cancel" href="javascript:void(0);">X</a>
                </div>
              </div>
              <input name="m_body" autocomplete="off" type="hidden" value='<?php echo $output['goods']['mobile_body'];?>'>
            </div>
          </div>
        </dd>
      </dl>
      <dl>
        <dt>关联版式：</dt>
        <dd> <span class="mr50">
          <label>顶部版式</label>
          <select name="plate_top">
            <option>请选择</option>
            <?php if (!empty($output['plate_list'][1])) {?>
            <?php foreach ($output['plate_list'][1] as $val) {?>
            <option value="<?php echo $val['plate_id']?>" <?php if ($output['goods']['plateid_top'] == $val['plate_id']) {?>selected="selected"<?php }?>><?php echo $val['plate_name'];?></option>
            <?php }?>
            <?php }?>
          </select>
          </span> <span class="mr50">
          <label>底部版式</label>
          <select name="plate_bottom">
            <option>请选择</option>
            <?php if (!empty($output['plate_list'][0])) {?>
            <?php foreach ($output['plate_list'][0] as $val) {?>
            <option value="<?php echo $val['plate_id']?>" <?php if ($output['goods']['plateid_bottom'] == $val['plate_id']) {?>selected="selected"<?php }?>><?php echo $val['plate_name'];?></option>
            <?php }?>
            <?php }?>
          </select>
          </span> </dd>
      </dl>
      <!-- 只有可发布虚拟商品才会显示 S -->
      <?php if ($output['goods_class']['gc_virtual'] == 1) {?>
      <h3 id="demo3">特殊商品</h3>
      <dl class="special-01">
        <dt>虚拟商品<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <?php if ($output['edit_goods_sign']) {?>
            <input type="hidden" name="is_gv" value="<?php echo $output['goods']['is_virtual'];?>">
          <?php }?>
          <ul class="ncsc-form-radio-list">
            <li>
              <input type="radio" name="is_gv" value="1" id="is_gv_1" <?php if ($output['goods']['is_virtual'] == 1) {?>checked<?php }?> <?php if ($output['edit_goods_sign']) {?>disabled<?php }?>>
              <label for="is_gv_1">是</label>
            </li>
            <li>
              <input type="radio" name="is_gv" value="0" id="is_gv_0" <?php if ($output['goods']['is_virtual'] == 0) {?>checked<?php }?> <?php if ($output['edit_goods_sign']) {?>disabled<?php }?>>
              <label for="is_gv_0">否</label>
            </li>
          </ul>
          <p class="hint vital">*虚拟商品不能参加限时折扣和组合销售两种促销活动。也不能赠送赠品和推荐搭配。</p>
        </dd>
      </dl>
      <dl class="special-01" nctype="virtual_valid" <?php if ($output['goods']['is_virtual'] == 0) {?>style="display:none;"<?php }?>>
        <dt><i class="required">*</i>虚拟商品有效期至<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="g_vindate" id="g_vindate" class="w80 text" value="<?php if($output['goods']['is_virtual'] == 1 && !empty($output['goods']['virtual_indate'])) { echo date('Y-m-d', $output['goods']['virtual_indate']);}?>"><em class="add-on"><i class="icon-calendar"></i></em>
          <span></span>
          <p class="hint">虚拟商品可兑换的有效期，过期后商品不能购买，电子兑换码不能使用。</p>
        </dd>
      </dl>
      <dl class="special-01" nctype="virtual_valid" <?php if ($output['goods']['is_virtual'] == 0) {?>style="display:none;"<?php }?>>
        <dt><i class="required">*</i>虚拟商品购买上限<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="g_vlimit" id="g_vlimit" class="w80 text" value="<?php if ($output['goods']['is_virtual'] == 1) {echo $output['goods']['virtual_limit'];}?>">
          <span></span>
          <p class="hint">请填写1~10之间的数字，虚拟商品最高购买数量不能超过10个。</p>
        </dd>
      </dl>
      <dl class="special-01" nctype="virtual_valid" <?php if ($output['goods']['is_virtual'] == 0) {?>style="display:none;"<?php }?>>
        <dt>支持过期退款<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <input type="radio" name="g_vinvalidrefund" id="g_vinvalidrefund_1" value="1" <?php if ($output['goods']['virtual_invalid_refund'] ==1) {?>checked<?php }?>>
              <label for="g_vinvalidrefund_1">是</label>
            </li>
            <li>
              <input type="radio" name="g_vinvalidrefund" id="g_vinvalidrefund_0" value="0" <?php if ($output['goods']['virtual_invalid_refund'] == 0) {?>checked<?php }?>>
              <label for="g_vinvalidrefund_0">否</label>
            </li>
          </ul>
          <p class="hint">兑换码过期后是否可以申请退款。</p>
        </dd>
      </dl>
      <?php }?>
      <!-- 只有可发布虚拟商品才会显示 E --> 
      <!-- 商品物流信息 S -->
        <?php if($output['store_info']['store_flag']!=1){ ?>
      <h3 id="demo4"><?php echo $lang['store_goods_index_goods_transport']?></h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_szd'].$lang['nc_colon']?></dt>
        <dd>
          <input type="hidden" value="<?php echo $output['goods']['areaid_2'] ? $output['goods']['areaid_2'] : $output['goods']['areaid_1'];?>" name="region" id="region">
          <input type="hidden" value="<?php echo $output['goods']['areaid_1'];?>" name="province_id" id="_area_1">
          <input type="hidden" value="<?php echo $output['goods']['areaid_2'];?>" name="city_id" id="_area_2">
          </p>
        </dd>
      </dl>
      <dl nctype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>
        <dt><?php echo $lang['store_goods_index_goods_transfee_charge'].$lang['nc_colon']; ?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <input id="freight_0" nctype="freight" name="freight" class="radio" type="radio" <?php if (intval($output['goods']['transport_id']) == 0) {?>checked="checked"<?php }?> value="0">
              <label for="freight_0">固定运费</label>
              <div nctype="div_freight" <?php if (intval($output['goods']['transport_id']) != 0) {?>style="display: none;"<?php }?>>
                <input id="g_freight" class="w50 text" nc_type='transport' type="text" value="<?php printf('%.2f', floatval($output['goods']['goods_freight']));?>" name="g_freight"><em class="add-on"><i class="icon-renminbi"></i></em> </div>
            </li>
            <li>
              <input id="freight_1" nctype="freight" name="freight" class="radio" type="radio" <?php if (intval($output['goods']['transport_id']) != 0) {?>checked="checked"<?php }?> value="1">
              <label for="freight_1"><?php echo $lang['store_goods_index_use_tpl'];?></label>
              <div nctype="div_freight" <?php if (intval($output['goods']['transport_id']) == 0) {?>style="display: none;"<?php }?>>
                <input id="transport_id" type="hidden" value="<?php echo $output['goods']['transport_id'];?>" name="transport_id">
                <input id="transport_title" type="hidden" value="<?php echo $output['goods']['transport_title'];?>" name="transport_title">
                <span id="postageName" class="transport-name" <?php if ($output['goods']['transport_title'] != '' && intval($output['goods']['transport_id'])) {?>style="display: inline-block;"<?php }?>><?php echo $output['goods']['transport_title'];?></span><a href="JavaScript:void(0);" onclick="window.open('index.php?act=store_transport&type=select')" class="ncbtn" id="postageButton"><i class="icon-truck"></i><?php echo $lang['store_goods_index_select_tpl'];?></a> </div>
            </li>
          </ul>
          <p class="hint">运费设置为 0 元，前台商品将显示为免运费。</p>
        </dd>
      </dl>
      <!-- 商品物流信息 E -->
      <h3 id="demo5" nctype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>发票信息</h3>
      <dl nctype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>
        <dt>是否开增值税发票：</dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <label>
                <input name="g_vat" value="1" <?php if (!empty($output['goods']) && $output['goods']['goods_vat'] == 1) { ?>checked="checked" <?php } ?> type="radio" />
                <?php echo $lang['nc_yes'];?></label>
            </li>
            <li>
              <label>
                <input name="g_vat" value="0" <?php if (empty($output['goods']) || $output['goods']['goods_vat'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>
                <?php echo $lang['nc_no'];?></label>
            </li>
          </ul>
          <p class="hint"></p>
        </dd>
      </dl>
      <?php } ?>
      <h3 id="demo6"><?php echo $lang['store_goods_index_goods_other_info']?></h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_store_goods_class'].$lang['nc_colon'];?></dt>
        <dd><span class="new_add"><a href="javascript:void(0)" id="add_sgcategory" class="ncbtn"><?php echo $lang['store_goods_index_new_class'];?></a> </span>
          <?php if (!empty($output['store_class_goods'])) { ?>
          <?php foreach ($output['store_class_goods'] as $v) { ?>
          <select name="sgcate_id[]" class="sgcategory">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>" <?php if ($v==$val['stc_id']) { ?>selected="selected"<?php } ?>><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($v==$child_val['stc_id']) { ?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }?>
            <?php }?>
            <?php } ?>
          </select>
          <?php } ?>
          <?php } else { ?>
          <select name="sgcate_id[]" class="sgcategory">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>"><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }?>
            <?php }?>
            <?php } ?>
            <?php } ?>
          </select>
          <?php } ?>
          <p class="hint"><?php echo $lang['store_goods_index_belong_multiple_store_class'];?></p>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_show'].$lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <label>
                <input name="g_state" value="1" type="radio" <?php if (empty($output['goods']) || $output['goods']['goods_state'] == 1 || $output['goods']['goods_state'] == 10) {?>checked="checked"<?php }?> />
                <?php echo $lang['store_goods_index_immediately_sales'];?> </label>
            </li>
            <li>
              <label>
                <input name="g_state" value="0" type="radio" nctype="auto" />
                <?php echo $lang['store_goods_step2_start_time'];?> </label>
              <input type="text" class="w80 text" name="starttime" disabled="disabled" style="background:#E7E7E7 none;" id="starttime" value="<?php echo date('Y-m-d');?>" />
              <select disabled="disabled" style="background:#E7E7E7 none;" name="starttime_H" id="starttime_H">
                <?php foreach ($output['hour_array'] as $val){?>
                <option value="<?php echo $val;?>" <?php $sign_H = 0;if($val>=date('H') && $sign_H != 1){?>selected="selected"<?php $sign_H = 1;}?>><?php echo $val;?></option>
                <?php }?>
              </select>
              <?php echo $lang['store_goods_step2_hour'];?>
              <select disabled="disabled" style="background:#E7E7E7 none;" name="starttime_i" id="starttime_i">
                <?php foreach ($output['minute_array'] as $val){?>
                <option value="<?php echo $val;?>" <?php $sign_i = 0;if($val>=date('i') && $sign_i != 1){?>selected="selected"<?php $sign_i = 1;}?>><?php echo $val;?></option>
                <?php }?>
              </select>
              <?php echo $lang['store_goods_step2_minute'];?> </li>
            <li>
              <label>
                <input name="g_state" value="0" type="radio" <?php if (!empty($output['goods']) && $output['goods']['goods_state'] == 0) {?>checked="checked"<?php }?> />
                <?php echo $lang['store_goods_index_in_warehouse'];?> </label>
            </li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_recommend'].$lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <label>
                <input name="g_commend" value="1" <?php if (empty($output['goods']) || $output['goods']['goods_commend'] == 1) { ?>checked="checked" <?php } ?> type="radio" />
                <?php echo $lang['nc_yes'];?></label>
            </li>
            <li>
              <label>
                <input name="g_commend" value="0" <?php if (!empty($output['goods']) && $output['goods']['goods_commend'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>
                <?php echo $lang['nc_no'];?></label>
            </li>
          </ul>
          <p class="hint"><?php echo $lang['store_goods_index_recommend_tip'];?></p>
        </dd>
      </dl>
      <?php if (is_array($output['supplier_list'])) {?>
      <dl>
        <dt>供货商：</dt>
        <dd>
          <select name="sup_id">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach ($output['supplier_list'] as $val) {?>
            <option value="<?php echo $val['sup_id'];?>" <?php if ($output['goods']['sup_id'] == $val['sup_id']) {?>selected<?php }?>><?php echo $val['sup_name']?></option>
            <?php }?>
          </select>
          <?php if($output['store_info']['store_flag']==0){ ?>
          &nbsp;&nbsp;<input name="ext_sup" value="<?php echo $output['goods']['ext_sup'];?>" type="text" style="margin-top:2px;" class="text" />
          <?php }?>
          <p class="hint">可以选择商品的供货商。</p>
        </dd>
      </dl>
      <?php }?>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" nctype="formSubmit" class="submit" value="<?php if ($output['edit_goods_sign']) {echo '提交';} else {?><?php echo $lang['store_goods_add_next'];?>，上传商品图片<?php }?>" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 60);?>";
var SHOP_RESOURCE_SITE_URL = "<?php echo SHOP_RESOURCE_SITE_URL;?>";

$(function(){

    // 防止重复提交 by 33h ao.co m
    var __formSubmit = false;
    $('input[nctype="formSubmit"]').click(function(){
        if (__formSubmit) {
            return false;
        }
        __formSubmit = true;
    });
	
    $.validator.addMethod('checkPrice', function(value,element){
    	_g_price = parseFloat($('input[name="g_price"]').val());
        _g_marketprice = parseFloat($('input[name="g_marketprice"]').val());
        if (_g_marketprice <= 0) {
            return true;
        }
        if (_g_price > _g_marketprice) {
            return false;
        }else {
            return true;
        }
    }, '');
    $('#goods_form').validate({
        errorPlacement: function(error, element){
            $(element).nextAll('span').append(error);
			__formSubmit = false;
        },
        <?php if ($output['edit_goods_sign']) {?>
        submitHandler:function(form){
            ajaxpost('goods_form', '', '', 'onerror');
        },
        <?php }?>
        rules : {
            g_name : {
                required    : true,
                minlength   : 3,
                maxlength   : 50
            },
            g_jingle : {
                maxlength   : 140
            },
            g_price : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
			
            // g_points : {
            //     required    : true,
            //     number      : true,

            //     max         : 9999999,                
            // },
            g_marketprice : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
            g_costprice : {
                number      : true,
                min         : 0.00,
                max         : 9999999
            },
			
            g_storage  : {
                required    : true,
                digits      : true,
                min         : 0,
                max         : 999999999
            },
            image_path : {
                required    : true
            },
			quantity:{
				 required : function(){ if($("#quantity").val()!=""){return true;}else{return false}}
				},
            g_vindate : {
                required    : function() {if ($("#is_gv_1").prop("checked")) {return true;} else {return false;}}
            },
			g_vlimit : {
				required	: function() {if ($("#is_gv_1").prop("checked")) {return true;} else {return false;}},
				range		: [1,10]
			},
			g_deliverdate : {
				required	: function () {if ($('#is_presell_1').prop("checked")) {return true;} else {return false;}}
			}
        },
        messages : {
            g_name  : {
                required    : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_name_null'];?>',
                minlength   : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_name_help'];?>',
                maxlength   : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_name_help'];?>'
            },
            g_jingle : {
                maxlength   : '<i class="icon-exclamation-sign"></i>商品卖点不能超过140个字符'
            },
            g_price : {
                required    : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_null'];?>',
                number      : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_error'];?>',
                min         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_interval'];?>',
                max         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_interval'];?>',
                checkPrice  : '<i class="icon-exclamation-sign"></i>商品价格不能高于市场价格'
            },
            // g_points : {
            //     required    : '<i class="icon-exclamation-sign"></i>商品云豆必须填写',
            //     number      : '<i class="icon-exclamation-sign"></i>云豆必须是数字',
              
            //     max         : '<i class="icon-exclamation-sign"></i>最大云豆为9999',               
            // },
            g_marketprice : {
                required    : '<i class="icon-exclamation-sign"></i>请填写市场价',
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的价格',
                min         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',

                max         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',
                checkPrice  : '<i class="icon-exclamation-sign"></i>市场价格不能低于商品价格'
            },
            g_costprice : {
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的价格',
                min         : '<i class="icon-exclamation-sign"></i>请填写0.00~9999999之间的数字',
                max         : '<i class="icon-exclamation-sign"></i>请填写0.00~9999999之间的数字'
            },
            g_storage : {
                required    : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_null'];?>',
                digits      : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_error'];?>',
                min         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_checking'];?>',
                max         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_checking'];?>'
            },
            image_path : {
                required    : '<i class="icon-exclamation-sign"></i>请设置商品主图'
            },
            g_vindate : {
                required    : '<i class="icon-exclamation-sign"></i>请选择有效期'
            },
			g_vlimit : {
				required	: '<i class="icon-exclamation-sign"></i>请填写1~10之间的数字',
				range		: '<i class="icon-exclamation-sign"></i>请填写1~10之间的数字'
			},
			g_deliverdate : {
				required	: '<i class="icon-exclamation-sign"></i>请选择有效期'
			}
        }
    });
    <?php if (isset($output['goods'])) {?>
	setTimeout("setArea(<?php echo $output['goods']['areaid_1'];?>, <?php echo $output['goods']['areaid_2'];?>)", 1000);
	<?php }?>
});
// 按规格存储规格值数据
var spec_group_checked = [<?php for ($i=0; $i<$output['sign_i']; $i++){if($i+1 == $output['sign_i']){echo "''";}else{echo "'',";}}?>];
var str = '';
var V = new Array();

<?php for ($i=0; $i<$output['sign_i']; $i++){?>
var spec_group_checked_<?php echo $i;?> = new Array();
<?php }?>

$(function(){
	$('dl[nctype="spec_group_dl"]').on('click', 'span[nctype="input_checkbox"] > input[type="checkbox"]',function(){
		into_array();
		goods_stock_set();
	});

	// 提交后不没有填写的价格或库存的库存配置设为默认价格和0
	// 库存配置隐藏式 里面的input加上disable属性
	$('input[type="submit"]').click(function(){
		$('input[data_type="price"]').each(function(){
			if($(this).val() == ''){
				$(this).val($('input[name="g_price"]').val());
			}
		});
		$('input[data_type="stock"]').each(function(){
			if($(this).val() == ''){
				$(this).val('0');
			}
		});
		$('input[data_type="alarm"]').each(function(){
			if($(this).val() == ''){
				$(this).val('0');
			}
		});
		if($('dl[nc_type="spec_dl"]').css('display') == 'none'){
			$('dl[nc_type="spec_dl"]').find('input').attr('disabled','disabled');
		}
	});
	
});

// 将选中的规格放入数组
function into_array(){
<?php for ($i=0; $i<$output['sign_i']; $i++){?>
		
		spec_group_checked_<?php echo $i;?> = new Array();
		$('dl[nc_type="spec_group_dl_<?php echo $i;?>"]').find('input[type="checkbox"]:checked').each(function(){
			i = $(this).attr('nc_type');
			v = $(this).val();
			c = null;
			if ($(this).parents('dl:first').attr('spec_img') == 't') {
				c = 1;
			}
			spec_group_checked_<?php echo $i;?>[spec_group_checked_<?php echo $i;?>.length] = [v,i,c];
		});

		spec_group_checked[<?php echo $i;?>] = spec_group_checked_<?php echo $i;?>;

<?php }?>
}

// 生成库存配置
function goods_stock_set(){
    //  店铺价格 商品库存改为只读
    $('input[name="g_price"]').attr('readonly','readonly').css('background','#E7E7E7 none');
    $('input[name="g_storage"]').attr('readonly','readonly').css('background','#E7E7E7 none');

    $('dl[nc_type="spec_dl"]').show();
    str = '<tr>';
    <?php recursionSpec(0,$output['sign_i']);?>
    if(str == '<tr>'){
        //  店铺价格 商品库存取消只读
        $('input[name="g_price"]').removeAttr('readonly').css('background','');
        $('input[name="g_storage"]').removeAttr('readonly').css('background','');
        $('dl[nc_type="spec_dl"]').hide();
    }else{
        $('tbody[nc_type="spec_table"]').empty().html(str)
            .find('input[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{$(this).val(V[s]);}catch(ex){$(this).val('');};
                if ($(this).attr('data_type') == 'marketprice' && $(this).val() == '') {
                    $(this).val($('input[name="g_marketprice"]').val());
                }
                if ($(this).attr('data_type') == 'price' && $(this).val() == ''){
                    $(this).val($('input[name="g_price"]').val());
                }
                if ($(this).attr('data_type') == 'stock' && $(this).val() == ''){
                    $(this).val('0');
                }
                if ($(this).attr('data_type') == 'alarm' && $(this).val() == ''){
                    $(this).val('0');
                }
            }).end()
            .find('input[data_type="stock"]').change(function(){
                computeStock();    // 库存计算
            }).end()
            .find('input[data_type="price"]').change(function(){
                computePrice();     // 价格计算
            }).end()
            .find('input[nc_type]').change(function(){
                s = $(this).attr('nc_type');
                V[s] = $(this).val();
            });
    }
    $('div[nctype="spec_div"]').perfectScrollbar('update');
}

<?php 
/**
 * 
 * 
 *  生成需要的js循环。递归调用	PHP
 * 
 *  形式参考 （ 2个规格）
 *  $('input[type="checkbox"]').click(function(){
 *      str = '';
 *      for (var i=0; i<spec_group_checked[0].length; i++ ){
 *      td_1 = spec_group_checked[0][i];
 *          for (var j=0; j<spec_group_checked[1].length; j++){
 *              td_2 = spec_group_checked[1][j];
 *              str += '<tr><td>'+td_1[0]+'</td><td>'+td_2[0]+'</td><td><input type="text" /></td><td><input type="text" /></td><td><input type="text" /></td>';
 *          }
 *      }
 *      $('table[class="spec_table"] > tbody').empty().html(str);
 *  });
 */
function recursionSpec($len,$sign) {
    if($len < $sign){
        echo "for (var i_".$len."=0; i_".$len."<spec_group_checked[".$len."].length; i_".$len."++){td_".(intval($len)+1)." = spec_group_checked[".$len."][i_".$len."];\n";
        $len++;
        recursionSpec($len,$sign);
    }else{
        echo "var tmp_spec_td = new Array();\n";
        for($i=0; $i< $len; $i++){
            echo "tmp_spec_td[".($i)."] = td_".($i+1)."[1];\n";
        }
        echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
        echo "var spec_bunch = 'i_';\n";
        for($i=0; $i< $len; $i++){
            echo "spec_bunch += tmp_spec_td[".($i)."];\n";
        }
        echo "str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][goods_id]\" nc_type=\"'+spec_bunch+'|id\" value=\"\" />';";
        for($i=0; $i< $len; $i++){
            echo "if (td_".($i+1)."[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_".($i+1)."[1]+'\" />';}";
            echo "str +='<td><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_".($i+1)."[1]+']\" value=\"'+td_".($i+1)."[0]+'\" />'+td_".($i+1)."[0]+'</td>';\n";
        }
        echo "str +='<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][marketprice]\" data_type=\"marketprice\" nc_type=\"'+spec_bunch+'|marketprice\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
                    '<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][price]\" data_type=\"price\" nc_type=\"'+spec_bunch+'|price\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
                    '<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][stock]\" data_type=\"stock\" nc_type=\"'+spec_bunch+'|stock\" value=\"\" /></td>' +
                    '<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][alarm]\" data_type=\"alarm\" nc_type=\"'+spec_bunch+'|alarm\" value=\"\" /></td>' +
                    '<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][sku]\" nc_type=\"'+spec_bunch+'|sku\" value=\"\" /></td>' +
                    '<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][barcode]\" nc_type=\"'+spec_bunch+'|barcode\" value=\"\" /></td>' +
                    '</tr>';\n";
        for($i=0; $i< $len; $i++){
            echo "}\n";
        }
    }
}

?>


<?php if (!empty($output['goods']) && $_GET['class_id'] <= 0 && !empty($output['sp_value']) && !empty($output['spec_checked']) && !empty($output['spec_list'])){?>
//  编辑商品时处理JS
$(function(){
	var E_SP = new Array();
	var E_SPV = new Array();
	<?php
	$string = '';
	foreach ($output['spec_checked'] as $v) {
		$string .= "E_SP[".$v['id']."] = '".$v['name']."';";
	}
	echo $string;
	echo "\n";
	$string = '';
	foreach ($output['sp_value'] as $k=>$v) {
		$string .= "E_SPV['{$k}'] = '{$v}';";
	}
	echo $string;
	?>
	V = E_SPV;
	$('dl[nc_type="spec_dl"]').show();
	$('dl[nctype="spec_group_dl"]').find('input[type="checkbox"]').each(function(){
		//  店铺价格 商品库存改为只读
		// $('input[name="g_price"]').attr('readonly','readonly').css('background','#E7E7E7 none');
		$('input[name="g_storage"]').attr('readonly','readonly').css('background','#E7E7E7 none');
		s = $(this).attr('nc_type');
		if (!(typeof(E_SP[s]) == 'undefined')){
			$(this).attr('checked',true);
			v = $(this).parents('li').find('span[nctype="pv_name"]');
			if(E_SP[s] != ''){
				$(this).val(E_SP[s]);
				v.html('<input type="text" maxlength="20" value="'+E_SP[s]+'" />');
			}else{
				v.html('<input type="text" maxlength="20" value="'+v.html()+'" />');
			}
			change_img_name($(this));			// 修改相关的颜色名称
		}
	});

    into_array();	// 将选中的规格放入数组
    str = '<tr>';
    <?php recursionSpec(0,$output['sign_i']);?>
    if(str == '<tr>'){
        $('dl[nc_type="spec_dl"]').hide();
        $('input[name="g_price"]').removeAttr('readonly').css('background','');
        $('input[name="g_storage"]').removeAttr('readonly').css('background','');
    }else{
        $('tbody[nc_type="spec_table"]').empty().html(str)
            .find('input[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{$(this).val(E_SPV[s]);}catch(ex){$(this).val('');};
            }).end()
            .find('input[data_type="stock"]').change(function(){
                computeStock();    // 库存计算
            }).end()
            .find('input[data_type="price"]').change(function(){
                computePrice();     // 价格计算
            }).end()
            .find('input[type="text"]').change(function(){
                s = $(this).attr('nc_type');
                V[s] = $(this).val();
            });
    }
    $('div[nctype="spec_div"]').perfectScrollbar('update');
});
<?php }?>
</script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/scrolld.js"></script>
<script type="text/javascript">$("[id*='Btn']").stop(true).on('click', function (e) {e.preventDefault();$(this).scrolld();});

$('#g_price').change(function(){
	var g_price =Math.round($('#g_price').val()*0.5);
	var c_price = Math.round($('#g_price').val()*0.2);
	if($('#quantity').val()==0.6){
	var bg_price =Math.round($('#g_price').val()*0.6);
	}else{
	var bg_price = Math.round($('#g_price').val()*$('#quantity').val());	
		}
	$('#points').val(g_price);
	$('#pointcsb').val(c_price);
	$('#pointsb').val(bg_price);
	})
	/* 商品购买数量增减js */
	// 增加
	$('a[nctype="increase"]').click(function(){
		
		num = parseFloat($('#quantity').val());
		max = 10000;
		if(num < max){
	   $('#quantity').val(numAdd(num,0.1));
	  
		}
		 $('#pointsb').val(Math.round($('#g_price').val()*$('#quantity').val()));
	});
	//减少
	$('a[nctype="decrease"]').click(function(){
		num = parseFloat($('#quantity').val());
		if(num > 0.6){
			$('#quantity').val(numSub(num, 0.1));
		}else{
			$('#quantity').val(0.6);
			}
		 $('#pointsb').val(Math.round($('#g_price').val()*$('#quantity').val()));
	});


function numAdd(num1, num2) {
 var baseNum, baseNum1, baseNum2;
 try {
  baseNum1 = num1.toString().split(".")[1].length;
 } catch (e) {
  baseNum1 = 0;
 }
 try {
  baseNum2 = num2.toString().split(".")[1].length;
 } catch (e) {
  baseNum2 = 0;
 }
 baseNum = Math.pow(10, Math.max(baseNum1, baseNum2));
 return (num1 * baseNum + num2 * baseNum) / baseNum;
};

function numSub(num1, num2) {
 var baseNum, baseNum1, baseNum2;
 var precision;// 精度
 try {
  baseNum1 = num1.toString().split(".")[1].length;
 } catch (e) {
  baseNum1 = 0;
 }
 try {
  baseNum2 = num2.toString().split(".")[1].length;
 } catch (e) {
  baseNum2 = 0;
 }
 baseNum = Math.pow(10, Math.max(baseNum1, baseNum2));
 precision = (baseNum1 >= baseNum2) ? baseNum1 : baseNum2;
 return ((num1 * baseNum - num2 * baseNum) / baseNum).toFixed(precision);
};
</script>
<script type="text/javascript">
function danjia(obj){
	var  sls= document.getElementById('txtSulian'+obj).value;
	var  ts= document.getElementById('txtDanj'+obj).value;
	
	if(!isNumber(ts)){
		alert('请输入数字或小数点');
		document.getElementById('txtDanj'+obj).value='';
		document.getElementById('txtTel'+obj).value='';
		return 
		}else{
		 if(sls!=''){
		  if(sls!=''&&ts!=''){
	  document.getElementById('txtTel'+obj).value=eval(sls*ts).toFixed(2);
		   }}		
				}
	}
	
function shuliang(obj){
	var  sls= document.getElementById('txtSulian'+obj).value;
	var  ts= document.getElementById('txtDanj'+obj).value;
	
	 if(!isInteger(sls)&&sls>0){
		alert('请输入整数');
		document.getElementById('txtSulian'+obj).value='';
		document.getElementById('txtTel'+obj).value='';
		return 
			}else{
		if(ts!=''){
		  if(sls!=''&&ts!=''){
	  document.getElementById('txtTel'+obj).value=eval(sls*ts).toFixed(2);
		  }
		   }		
				}
	}
function isNumber( s )
{
    var regu = "^([0-9])[0-9]*(\\.\\w*)?$";
    var re = new RegExp(regu);
    if (re.test(s)) 
    {
        return true;
    } 
    else 
    {
        return false;
    }
}
function isInteger(obj) {
 return obj%1 === 0
}
function checkit(){
	$dqz=$("input[name='usertc']:checked").val();
	if($dqz==0){
		$('#acd').show();
		$('#ccpoint').hide();
		$('#bcd').hide();
		}
	if($dqz==1){
		$('#bcd').show();
		$('#ccpoint').hide();
		$('#acd').hide();
		}
	if($dqz==2){
		$('#bcd').hide();
		$('#ccpoint').show();
		$('#acd').hide();
			}
}
</script>
