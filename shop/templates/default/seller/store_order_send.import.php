<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="alert mt15 mb5"><strong>万店通联操作提示：</strong>
  <ul>
    <li>1.如果修改xlsx文件，xls文件，csv文件请务必使用微软excel软件，且必须保证第一行表头名称含有如下项目: 
订单编号、快递公司编号、物流单号。<br>
2.如果xlsx文件，xls文件，csv文件超过2M请通过excel软件编辑拆成多个文件进行导入。<br>
3.请联系技术部门索要标准上传文档结构以及物流公司编号文件。</li>
  </ul>
</div>
<form method="post" action="index.php?act=store_order&op=doimport" enctype="multipart/form-data" id="goods_form">
  <div class="ncsc-form-goods">
    <dl>
      <dt><i class="required">*</i>请选择文件</dt>
      <dd>
        <div class="handle">
        <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="15"  name="csv" id="csv">
          </span></a></div>
          </div>
      </dd>
    </dl>
    <dl>
      <dt>文件格式</dt>
      <dd>
        <p>xlsx文件，xls文件，csv文件</p>
      </dd>
    </dl>
    <dl>
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" value="导入" />
      </dd>
    </dl>
  </div>
</form>

