<?php



// DebugLog...
// $contents = "\nGET\n" .print_r($_GET, true)
//           . "\nPOST\n" .print_r($_POST, true)
//           . "\nphp://input\n" . file_get_contents("php://input");
// $filename = __DIR__. '/' . time() . '.return.txt';
// file_put_contents($filename, $contents);
// print_r($contents);

		$_GET['act']	= 'payment';
		$_GET['op']		= 'txpay_return';
		$_GET['type']   = 'pd_order';
		require_once(dirname(__FILE__).'/../../../index.php');