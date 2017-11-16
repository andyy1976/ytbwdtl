<?php

include 'unionpay.class.php';

echo com\unionpay\acp\sdk\Unionpay::getB2cForm([
    'orderId' => date('YmdHis'), // 订单号，8-32位数字字母
    'txnAmt' => '10' // 单位分
]);

?>