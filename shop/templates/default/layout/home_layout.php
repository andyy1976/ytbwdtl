<?php defined('In33hao') or exit('Access Invalid!');?>
<?php 
	if (trim($_GET['act']) == 'seller_help') {
		include template('layout/common_layout1');
	} else {
		include template('layout/common_layout');
	}
?>
<?php include template('layout/cur_local');?>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
</body>
</html>
