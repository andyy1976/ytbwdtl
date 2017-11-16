<?php

function debugLog ( $filename, $contents ) {
    $filename = __DIR__. '/' . $filename;
    file_put_contents($filename, $contents);
}

if (!empty($_POST)) {
    ksort($_POST);
    $filename = $_POST['orderId'].'-'.$_POST['respCode'].'-'. time() .'.txt';
    $contents = "\nGET\n" .print_r($_GET, true). "\nPOST\n" .print_r($_POST, true). "\n";
    debugLog( $filename, $contents );
} else {
    // $filename = 'Get-'. time() .'.txt';
    // $contents = "\nGET\n" .print_r($_GET, true);
    // debugLog( $filename, $contents );
    $_GET['act']    = 'payment';
    $_GET['op']     = 'unionpay_return';
    $_GET['type']   = 'pd_order';
    require_once(dirname(__FILE__).'/../../../index.php');
    exit;
}


include "unionpay.class.php";

// 验证签名
if (com\unionpay\acp\sdk\Unionpay::verify($_POST)) {

    // POST
    // Array
    // (
    //     [accessType] => 0
    //     [bizType] => 000201
    //     [certId] => 69597475696
    //     [currencyCode] => 156
    //     [encoding] => utf-8
    //     [merId] => 848116089110001
    //     [orderId] => 20170604172536
    //     [queryId] => 201706041725360330628
    //     [respCode] => 00
    //     [respMsg] => success
    //     [settleAmt] => 10
    //     [settleCurrencyCode] => 156
    //     [settleDate] => 0604
    //     [signMethod] => 01
    //     [signature] => M2nQBWLUNpvctzQuCMKd2ZA+vooHTzP9GAQwf2bUCHErCnTJClO8+UdASqPf2GGe3h8Xq0VTwx5X3HNMdralTQz5qdZEYY109qaiCEYJyWHUFBq7Vu/RfdVQvWK2Th6CEqrbCltHY8x93p0yUlHXz24hhiC4Vg28EYfNS9BMYAq+jrs8ZdJ3C2gRpmIM/2x3GtJX8T8oN/jeFvI/W4qI84AJS0fap2oKO9622jZRvo1zAAXGBaOK980sLKV0yFx95ZgVaHYbXVNlppWxLdvS6jLhJeZ9daRKJnsdn6RfTAjIJpkcARPIEiMbHRWnHXon3PlUcSH22rbEr50coEpGaQ==
    //     [traceNo] => 033062
    //     [traceTime] => 0604172536
    //     [txnAmt] => 10
    //     [txnSubType] => 01
    //     [txnTime] => 20170604172536
    //     [txnType] => 01
    //     [version] => 5.0.0
    // )

    $orderId  = $_POST ['orderId'];   // 订单号
    $respCode = $_POST ['respCode']; // 判断 respCode=00 或 A6 即可认为交易成功
    $txnAmt   = $_POST ['txnAmt']; // 交易金额，单位为分

    //////////////////////////////////////////////////////////////////////
    if ($respCode == '00' || $respCode == 'A6') {

        // 交易成功：
        // 判断该笔订单是否在商户网站中已经做过处理，如果有做过处理，不执行商户的业务程序
        // 如果没有做过处理，根据订单号在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序

        // 注：判断订单需支付的金额 与 通知时获取的交易金额是一致的

        // ...

            $_GET['act']    = 'payment';
            $_GET['op']     = 'unionpay_return';
            $_GET['type']   = 'real_order';
            require_once(dirname(__FILE__).'/../../../index.php');

    }

}

?>


<?php if(!empty($_POST)) { ?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <table width="800px" border="1" align="center">
        <tr>
            <th colspan="2" align="center">银联在线交易测试-交易结果</th>
        </tr>

        <?php foreach ( $_POST as $key => $val ) { ?>
        <tr>
            <td width='30%'><?php echo isset($mpi_arr[$key]) ?$mpi_arr[$key] : $key ;?></td>
            <td><?php echo $val ;?></td>
        </tr>
        <?php }?>

        <tr>
            <td width='30%'>验证签名</td>
            <td><?php           
            if (isset ( $_POST ['signature'] )) {
                echo com\unionpay\acp\sdk\Unionpay::verify($_POST) ? '验签成功' : '验签失败';
            } else {
                echo '签名为空';
            }
            ?></td>
        </tr>
    </table>
</body>
</html>
<?php }?>