<?php


function httpPost ($url, $data=null, $opts=null) // -> array
{
    $options = [
          CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_0
        , CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        , CURLOPT_HEADER         => false
        , CURLOPT_CONNECTTIMEOUT => 10   
        , CURLOPT_TIMEOUT        => 10   
        , CURLOPT_MAXREDIRS      => 10   
        , CURLOPT_FOLLOWLOCATION => true 
        , CURLOPT_AUTOREFERER    => true 
        , CURLOPT_RETURNTRANSFER => true 
        , CURLOPT_SSL_VERIFYPEER => false
        , CURLOPT_SSL_VERIFYHOST => false
        , CURLOPT_ENCODING       => "gzip,deflate"
    ];

    $opts = !$opts || !is_array($opts) ? $options
        : array_merge($options, $opts);
    // echo $url ."\n"; print_r($data); var_dump($opts);

    $data = http_build_query($data);
    // echo $url . '?' . $data; exit;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt_array($ch, $opts);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type:application/x-www-form-urlencoded',
        'Content-Length:' . strlen($data)
    ]);
    $data = curl_exec($ch);
    $arr = curl_getinfo($ch);
    $arr["code"] = $arr["http_code"];
    $arr["data"] = $data;
    curl_close($ch);
    return $arr;
}


// 机构号：16270906
// 密钥：B2364467007E804EE80A75A065C04BA7
// IP端口：114.80.200.90:40009

// 工商银行    ICBC      1001
// 农业银行    ABC       1002
// 中国银行    BOC       1003
// 建设银行    CCB       1004
// 光大银行    CEB       1008
// 平安银行    PINGAN    1011
// 邮储银行    POST      1006
// 中信银行    ECITIC    1007
// 招商银行    CMBCHINA  1012
// 民生银行    CMBC      1010
// 广发银行    CGB       1017

// 浦发银行    SPDB    1014 -- 不能用
// 兴业银行    CIB     1013 -- 不能用
// 华夏银行    HXB     1009 -- 不能用
// 北京银行    BCCB    1016 -- 不能用
// 上海银行    SHB     1025 -- 不能用
// 交通银行    BOCO    1005 -- 不能用
// 北京农村商业银行    BJRCB   1103 -- 不能用

$bankSegment = $bankId;
$agentOrgno = '16270906';
$key = 'B2364467007E804EE80A75A065C04BA7';
$url = 'http://114.80.200.90:40009/gateway/order/pay_apply_api.action';

$arr = [
    'agentOrgno'  => $agentOrgno,  //  机构号 N       我司分配机构号
    'orderNo'     => $pdr_sn,  // 支付订单号   N   32  机构平台唯一
    'money'       => strval($amout * 100),  //   支付金额    N   11   单位：分
    'curType'     => '1',  // 订单接类型   N   2   1-人民币
    'returnUrl'   => 'https://ytbwdtl.com/shop/api/payment/txpay/return_'.$type.'.php',  //   页面回调    N   255 网银支付结果页面通知
    'notifyUrl'   => 'https://ytbwdtl.com/shop/api/payment/txpay/notify_'.$type.'.php',  //   后台回调    N   255 网银支付结果后台通知
    'memo'        => '商品名称',  //    商品名称    N   255 
    'attach'      => '',  //  备注  Y   255 商户自定义(只容许字母数字下划线)
    'cardType'    => '1',  //    银行类型    N   2   银行卡类型。 1：借记卡 2：贷记卡
    'bankSegment' => $bankSegment,  // 银行代号    N   4   详见文档银行代号部分
    'userType'    => '1',  //    用户类型    N   2   发起支付交易的用户的类型。 1：个人 2：企业
    'channel'     => '1',  // 渠道类型    N   2   商户的用户使用的终端类型。 1 – PC端 2 – 手机端
];

$str = json_encode($arr, JSON_UNESCAPED_UNICODE);

// $contents = print_r($arr, true);
// $filename = __DIR__. '/' . $pdr_sn .'request.txt';
// file_put_contents($filename, $contents);

// print_r($arr);
// echo "<hr>\n";
// echo "支付请求数据：\n";
// echo $str;
// echo "\n\n";


$str = base64_encode($str);
$sign = md5($str . $key);

// echo $str;
// echo "\n\n";

// echo "<hr>\n";
// echo "支付请求数据签名：\n";
// echo $sign;
// echo "\n\n";

// echo "<hr>\n";
// echo "支付请求地址：\n";
// echo $url;
// echo "\n\n";

// $url = $url . '?params=' . $str .'&sign=' . $sign;
// echo "<hr>\n";
// echo "支付请求URL：\n";
// echo $url;
// echo "\n\n";

$data = [];
$data['params'] = $str;
$data['sign'] = $sign;

$res = httpPost($url,$data);
$arr = json_decode($res['data'],true);

if ($arr['res'] == '0000') {
    ob_clean();
    header('Location: '. $arr['url']);
    exit;
}

if ($_SESSION['member_name']=='18975810567') {
    echo "<hr>\n";
    echo "支付请求响应数据：\n";
    print_r($res);
    echo "\n\n";
    echo $arr['url'];
}