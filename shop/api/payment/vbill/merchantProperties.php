<?php
/**
 * Created by PhpStorm.
 * User: MaChao
 * Date: 16/4/28
 * Time: 下午5:12
 */

//商户编号
$mercNo = '600000000000827';

/**
 * 密钥文件的路径
 */

$privateKeyFilePath = __DIR__ . '/key/ytb_private.pem';

/**
 * 公钥文件的路径
 */

$publicKeyFilePath = __DIR__ . '/key/ytb_public.pem';

/**
 * 支付请求地址（测试环境）
 */

// $payURL = 'https://cgw.vbill.cn/onlinepay/pay';
// $payQueryURL = 'https://cgw.vbill.cn/onlinepay/queryOrder';
// $refundURL = 'https://cgw.vbill.cn/onlinepay/refund';
// $refundQueryURL = 'https://cgw.vbill.cn/onlinepay/refundQuery';

/**
 * 支付请求地址（生产环境）
 */
$payURL         = 'https://api.suixingpay.com/onlinepay/pay';
$payQueryURL    = 'https://api.suixingpay.com/onlinepay/queryOrder';
$refundURL      = 'https://api.suixingpay.com/onlinepay/refund';
$refundQueryURL = 'https://api.suixingpay.com/onlinepay/refundQuery';

?>