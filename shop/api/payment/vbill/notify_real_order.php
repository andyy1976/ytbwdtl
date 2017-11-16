<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      order.php
 * @package ${NAMESPACE}
 * @author    ma_chao <ma_chao@suixingpay.com>
 */

include 'sxfCommon.php';

header("Content-type: text/html; charset=utf-8");
    FUNCTION doCurlGet ( $url, $options=null ) {
        
        static $defaults = array(
            CURLOPT_HEADER         => false
          , CURLOPT_CONNECTTIMEOUT => 10
          , CURLOPT_TIMEOUT        => 20
          , CURLOPT_MAXREDIRS      => 10
          , CURLOPT_FOLLOWLOCATION => true
          , CURLOPT_AUTOREFERER    => true
          , CURLOPT_RETURNTRANSFER => true
          , CURLOPT_SSL_VERIFYPEER => false
          , CURLOPT_SSL_VERIFYHOST => false
          , CURLOPT_ENCODING       => 'gzip,deflate'
        );

        $options = is_array($options) ? $options + $defaults : $defaults;
        $options[CURLOPT_URL] = $url;
        
        # 初始化 && 设置选项
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
//读取公钥
$pu_key = file_get_contents($publicKeyFilePath);

//读取私钥
$pi_key = file_get_contents($privateKeyFilePath);


// DebugLog...
// $contents = "\nGET\n" .print_r($_GET, true)
//           . "\nPOST\n" .print_r($_POST, true)
//           . "\nphp://input\n" . file_get_contents("php://input");
// $filename = __DIR__. '/' . time() . '.notify.txt';
// file_put_contents($filename, $contents);


if (!isset($_REQUEST['_t'])) {
    exit('error');
}

//获取数据
$_t         = $_REQUEST['_t'];

// print_r('http回调结果--><br/>'.$_t.'<br/>');

//解析json
$de_json = json_decode($_t,TRUE);

$mercNo  = $de_json['mercNo'];
$orderNo = $de_json['orderNo'];
$tranCd  = $de_json['tranCd'];
$resCode = $de_json['resCode'];
$resMsg  = $de_json['resMsg'];

if ($resCode=='000000'){

    $resData = $de_json['resData'];
    $sign    = $de_json['sign'];

    //验签 准备数据
    $result= array(
        'mercNo'  => $mercNo,
        'orderNo' => $orderNo,
        'tranCd'  => $tranCd,
        'resCode' => $resCode,
        'resMsg'  => $resMsg,
        'resData' => $resData
    );

    $result = json_encode($result,JSON_UNESCAPED_UNICODE);
    $result = stripslashes($result);

    // print_r('验签内容-->'.'<br/>'.$result.'<br/>');

    $sign_result = openssl_verify($result, base64_decode($sign), $pu_key);
    // print_r('验签结果--><br/>'.$sign_result.'<br/>');
    
    if ( $sign_result == '1') {
        // print_r('验签成功'.'<br/>');

        //解密
        $encodeType = $de_json['encodeType'];

        //加密加签方式RSA#RSA
        $de_data = base64_decode(decode($resData,$pi_key));
        // print_r('解密resData结果-->'.$de_data.'<br/>');

        // file_put_contents(__DIR__ . '/' . time() . '.Notify.deData.txt', $de_data);

        $resData = json_decode($de_data,true);

        // 支付成功
        if ($resData['tranSts'] == 'S') {

            //金额
            // $_GET['act']    = 'payment';
            // $_GET['op']     = 'vbill_notify';
            // $_GET['type']   = 'real_order';
            // $_GET['out_trade_no'] = $resData['orderNo'];
            // $_GET['amount'] = $resData['tranAmt'];
            // require_once(dirname(__FILE__).'/../../../index.php');
            $_GET['act']    = 'payment';
            $_GET['op']     = 'vbill_notify';
            $_GET['type']   = 'real_order';
            $_GET['out_trade_no'] = $resData['orderNo'];
            $_GET['amount'] = $resData['tranAmt'];
            $_GET['time'] = time();
            $key  = '@)!&wdtlytb20171';
            $str = $_GET['act'].$_GET['op'].$_GET['type'].$_GET['out_trade_no'].$_GET['amount'].$_GET['time'].$key;
          
            $_GET['sign'] = md5($str);
            $url = 'https://'.$_SERVER['HTTP_HOST'].'/shop/index.php?' . http_build_query([
                'act'          => 'payment',
                'op'           => 'vbill_notify',
                'type'         => 'real_order',
                'out_trade_no' => $resData['orderNo'],
                'amount'       => $resData['tranAmt'], // 单位元
                'time'         => $_GET['time'],
                'sign'         => $_GET['sign']
            ]);
            // echo $url;
            doCurlGet($url);
            // 在这里写支付成功后的逻辑代码
            // .....


        }
    }

}


