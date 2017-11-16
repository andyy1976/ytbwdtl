<?php defined('In33hao') or exit('Access Invalid!');?>
<style>
    .ncm-default-form {}
.ncm-default-form h3 { font-weight: 600; line-height: 22px; color: #555; clear: both; background-color: #F5F5F5; padding: 5px 0 5px 12px; border-bottom: solid 1px #E7E7E7;}
.ncm-default-form dl { font-size: 0; word-spacing:-1em; line-height: 20px; clear: both; padding: 0; margin: 0; border-bottom: dotted 1px #E6E6E6; overflow: hidden;}
.ncm-default-form dl:hover { background-color: #FCFCFC;}
.ncm-default-form dl:hover .hint { color: #666;}
.ncm-default-form dl.bottom { border-bottom-width: 0px;}
.ncm-default-form dl dt,
.ncm-default-form dl dd { font-size: 12px; line-height: 32px; vertical-align: top; letter-spacing: normal; word-spacing: normal; text-align: right; display: inline-block; width: 14%; padding: 10px 1% 10px 0; margin: 0; *display: inline/*IE6,7*/; *zoom: 1;}
.ncm-default-form dl dt i.required { font: 12px/16px Tahoma; color: #F30; vertical-align: middle; margin-right: 4px;}
.ncm-default-form dl dd { text-align: left; width: 85%; padding: 10px 0 10px 0; }
.ncm-default-form dl dd span { display: inline-block; *line-height: 20px; *display: inline; *height: 20px; *margin-top: 6px; *zoom:1;}
.ncm-default-form dl dd p { clear: both;}
.ncm-default-form dl dd .hint { color: #AAA;}
.ncm-default-form div.bottom { text-align: center;}

.ncm-default-form .ncsc-upload-thumb { background-color: #FFF; display: block; border: dashed 1px #E6E6E6; position: relative; z-index: 1;}
.ncm-default-form .ncsc-upload-thumb:hover { border-style: solid; border-color: #27A9E3;}
.ncm-default-form .ncsc-upload-thumb p { line-height: 0; background-color: #FFF; text-align: center; vertical-align: middle; display: table-cell; *display: block/*IE6,7*/; width: 100px; height: 100px; overflow: hidden;}
.ncm-default-form .ncsc-upload-thumb i { font-size: 48px; color: #CCC;}
.ncm-default-form .ncsc-upload-thumb a { font: 10px/14px Tahoma; background-color: #FFF; text-align: center; vertical-align: middle; display: none; width: 14px; height: 14px; border: 1px solid; border-radius: 8px 8px 8px 8px; position: absolute; z-index: 2; top: -8px; right: -8px;}
.ncm-default-form .ncsc-upload-thumb:hover a { color: #27A9E3; display: block; border-color: #27A9E3;}
.ncm-default-form .ncsc-upload-thumb:hover a:hover { text-decoration: none;}
.ncm-default-form .upload-appeal-pic { border: dotted 1px #D8D8D8; padding: 5px; width: 250px; margin-left: 32px;}
.ncm-default-form .upload-appeal-pic p { padding: 5px;}
.ncm-default-form .handle { height: 30px; margin: 10px 0;}

.bottom .submit-border { margin: 10px auto;}
.bottom .submit { font: 14px/36px "microsoft yahei"; text-align: center; min-width: 100px; *min-width: auto; height: 36px;}
.bottom a.submit { width: 100px; margin: 0 auto;}
.bottom .submit[disabled="disabled"] { color: #999; text-shadow: none; background-color: #F5F5F5; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; cursor: default;}
.bottom .ncbtn { font-size: 14px; vertical-align: top; padding: 8px 19px; margin: 10px auto;}

</style>
<div class="wrap">
<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="index.php?act=store_bill&op=store_cash">商家提现</a></li>
    <li class="normal"><a href="index.php?act=store_bill&op=store_rcb">货款明细</a></li>
    <li class="normal"><a href="index.php?act=store_bill&op=store_cash_info">提现明细</a></li>
  </ul>
</div>
  <div class="ncm-default-form">
    <form method="post" id="cash_form" action="/index.php?act=store_bill&op=store_add_cash">
      <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><i class="required">*</i>提现类型：</dt>
        <dd>
            <select name='predeposit_type' id='predeposit_type'>
                <option value='1' selected='selected'>钱包2</option>           
            </select>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>提现金额：</dt>
        <dd><input name="pdc_amount" type="text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9]/g," class="text w50" id="pdc_amount" maxlength="10" ><em class="add-on">
<i class="icon-renminbi"></i></em> （当前可用金额：<strong class="orange" id="moneys"><?php echo $output['seller_info']['wallet_release']; ?></strong>&nbsp;&nbsp;元）<span></span>
          <p class="hint mt5"></p>
        </dd>
      </dl>

      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="submit"  class="submit" value="确认提现" /></label></dd>
      </dl>
    </form>
  </div>
</div>
