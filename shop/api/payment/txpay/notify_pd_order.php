<?php

// echo "请求成功";

// DebugLog...
// $contents = "\nGET\n" .print_r($_GET, true)
//           . "\nPOST\n" .print_r($_POST, true)
//           . "\nphp://input\n" . file_get_contents("php://input");
// $filename = __DIR__. '/' . time() . '.notify.txt';
// $params = base64_decode($_POST['params']);
// $contents .= print_r($params,true);
// file_put_contents($filename, $contents);


// Array
// (
//     [orderNo] => 580558805460344385
//     [memo] => 商品名称
//     [status] => 1
//     [attach] => 
//     [money] => 20000
//     [agentOrgno] => 16270906
//     [channel] => 1
//     [spbillno] => 20170915000000034
// )


if ( isset($_POST['sign']) && isset($_POST['params']) ) {

    $json = base64_decode($_POST['params']);
    $arr  = json_decode($json, true);
    $key  = 'B2364467007E804EE80A75A065C04BA7';
    $sign = md5($_POST['params'] . $key);

    if ($sign == $_POST['sign']) {

        if ($arr['status'] == '1') { // 支付成功

            //金额
            $_GET['act']  = 'payment';
            $_GET['op']   = 'txpay_notify';
            $_GET['type'] = 'pd_order';
            $_GET['out_trade_no'] = $arr['orderNo'];
            $_GET['amount'] = $arr['money'] / 100;
            require_once(dirname(__FILE__).'/../../../index.php');

        }

    }

}

