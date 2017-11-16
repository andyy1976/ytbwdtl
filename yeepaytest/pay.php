<?php

namespace Yeepay;

/**

订单支付接口

请求方式：GET、POST 均可；中文编码：.UTF-8；参数类型：String。
所有明文参数值都必须经过加密，最终发送给接口的请求参数为 customernumber、data；
其中，data 的生成，请参考附录：7.1 加密代码示例。

customernumber = 商户编号 MAX(11) √ 主账号商户编号 1
requestid      = 商户订单号 MAX(50) √ 必须在该商编下唯一，由商户输入 2
amount         = 商户订单金额 MAX(18) √ 单位：元，.必须大于等于 0.01 3
assure         = 是否需要担保 MAX(1) × 1 – 担保交易 0 – 非担保交易 4
productname    = 商品名称 MAX(50) × 商户名称，当支付产品 payproducttype＝ONEKEY 时， 必填 5
productcat     = 商品类别 MAX(50) × 商品类别 6
productdesc    = 商品描述 MAX(50) × 商品描述。当支付产品 payproducttype 为微信支付 （WECHATM,WECHATU）时， 该参数必填。


*/

// include 'dumper.php';
include 'CryptAES.php';


function getHmac(array $arr, $key) {
    
    $data = implode('', $arr);

    $b = 64; // byte length for md5
    if (strlen($key) > $b) {
        $key = pack("H*",md5($key));
    }
    
    $key  = str_pad($key, $b, chr(0x00));
    $ipad = str_pad('', $b, chr(0x36));
    $opad = str_pad('', $b, chr(0x5c));
    $k_ipad = $key ^ $ipad ;
    $k_opad = $key ^ $opad;

    return md5($k_opad . pack("H*",md5($k_ipad . $data)));
}
/**
  * @取得aes加密
  * @$dataArray 明文字符串
  * @$key 密钥
  * @return string
  *
 */
function getAes($data, $aesKey) {

    //print_r(mcrypt_list_algorithms());
    //print_r(mcrypt_list_modes());

    $aes = new CryptAES();
    $aes->set_key($aesKey);
    $aes->require_pkcs5();
    $encrypted = strtoupper($aes->encrypt($data));
    
    return $encrypted;

}

/**
  * @取得aes解密
  * @$dataArray 密文字符串
  * @$key 密钥
  * @return string
  *
 */
function getDeAes($data, $aesKey) {

    $aes = new CryptAES();
    $aes->set_key($aesKey);
    $aes->require_pkcs5();
    $text = $aes->decrypt($data);
    
    return $text;
}

/**
  * @发起http请求
  * @$url 请求的url
  * @$method POST 或者 GET
  * @$postfields 请求的参数
  * @return mixed
  */
function post($url, $postfields = array(), $uploadFile = array()) {
    $http_info = array();
    $header = array('Content-Type: multipart/form-data');
    $ci = curl_init();
    curl_setopt($ci, CURLOPT_URL, $url);
    curl_setopt($ci, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ci, CURLOPT_BINARYTRANSFER,true); 
    curl_custom_postfields($ci, $postfields, $uploadFile);
    curl_setopt($ci, CURLOPT_USERAGENT, "Yeepay ZGT PHPSDK v1.1x");
    curl_setopt($ci, CURLOPT_TIMEOUT, 30);
    //curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ci, CURLOPT_HEADER, false);    
    curl_setopt($ci, CURLOPT_POST, true);
    $response = curl_exec($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $http_info = array_merge($http_info, curl_getinfo($ci));
    //print_r($http_info);
    //echo "<br/>";
    curl_close ($ci);
    return $response;
}

/**
* 重写POST 参数body
* 
* @param resource $ch cURL resource
* @param array $assoc name => value
* @param array $files name => path
* @return bool
*/
function curl_custom_postfields($ch, array $assoc = array(), array $files = array()) {
    
    // invalid characters for "name" and "filename"
    static $disallow = array("\0", "\"", "\r", "\n");
    
    // build normal parameters
    foreach ($assoc as $k => $v) {
        $k = str_replace($disallow, "_", $k);
        $body[] = implode("\r\n", array(
            "Content-Disposition: form-data; name=\"{$k}\"",
            "",
            filter_var($v), 
        ));
    }
    
    // build file parameters
    foreach ($files as $k => $v) {      
        switch (true) {
            case false === $v = realpath(filter_var($v)):
            case !is_file($v):
            case !is_readable($v):
                continue; // or return false, throw new InvalidArgumentException
        }
        $data = file_get_contents($v);        
        $v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
        $k = str_replace($disallow, "_", $k);
        $v = str_replace($disallow, "_", $v);
        $body[] = implode("\r\n", array(
            "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
            "Content-Type: application/octet-stream",
            "",
            $data, 
        ));
    }
    
    // generate safe boundary 
    do {
        $boundary = "---------------------" . md5(mt_rand() . microtime());
    } while (preg_grep("/{$boundary}/", $body));
    
    // add boundary for each parameters
    array_walk($body, function (&$part) use ($boundary) {
        $part = "--{$boundary}\r\n{$part}";
    });
    
    // add final boundary
    $body[] = "--{$boundary}--";
    $body[] = "";
    
    // set options
    return @curl_setopt_array($ch, array(
        CURLOPT_POST       => true,
        CURLOPT_POSTFIELDS => implode("\r\n", $body),
        CURLOPT_HTTPHEADER => array(
            "Expect: 100-continue",
            "Content-Type: multipart/form-data; boundary={$boundary}", // change Content-Type
        ),
    ));
}

/**
  * @使用特定function对数组中所有元素做处理
  * @&$array 要处理的字符串
  * @$function 要执行的函数
  * @$apply_to_keys_also 是否也应用到key上
  *
  */
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
}

/**
  *
  * @将数组转换为JSON字符串（兼容中文）
  * @$array 要转换的数组
  * @return string 转换得到的json字符串
  *
  */
function cnJsonEncode($array) {
    $array = cnUrlEncode($array);
    $json = json_encode($array);
    return urldecode($json);
}

/**
  *
  * @将数组统一进行urlencode（兼容中文）
  * @$array 要转换的数组
  * @return array 转换后的数组
  *
  */
function cnUrlEncode($array) {
    arrayRecursive($array, "urlencode", true);
    return $array;
}


function genRequestHmac($arr, $key) {
    $need = array(
        'requestid',
        'amount',
        'assure',
        'productname',
        'productcat',
        'productdesc',
        'divideinfo',
        'callbackurl',
        'webcallbackurl',
        'bankid',
        'period',
        'memo'
    );
    $data = array();
    foreach ($need as $name) {
        $data[$name] = $arr[$name];
    }
    return getHmac($data, $key);
}

function getRequestData($arr, $customernumber, $hmac) {
    $need = array(
        0  => 'requestid',
        1  => 'amount',
        2  => 'assure',
        3  => 'productname',
        4  => 'productcat',
        5  => 'productdesc',
        6  => 'divideinfo',
        7  => 'callbackurl',
        8  => 'webcallbackurl',
        9  => 'bankid',
        10 => 'period',
        11 => 'memo',
        12 => 'payproducttype',
        13 => 'userno',
        14 => 'ip',
        15 => 'cardname',
        16 => 'idcard',
        17 => 'bankcardnum',
        18 => 'mobilephone',
        19 => 'orderexpdate',
        20 => 'appid',
        21 => 'openid',
        22 => 'directcode'
    );
    $data = array();
    $data['customernumber'] = $customernumber;
    foreach ($need as $name) {
        $data[$name] = $arr[$name];
    }
    $data['hmac'] = $hmac;
    return $data;
}



$requestURL = 'http://o2o.yeepay.com/zgt-api/api/pay';

$notifyURL = 'http://abc.com/payment/yeepay/notify.php';
$returnURL = 'http://abc.com/payment/yeepay/return.php';

//商户编号
$sysConfig["customernumber"] = "10000447996";

//商户密钥
$sysConfig["keyValue"] = "jj3Q1h0H86FZ7CD46Z5Nr35p67L199WdkgETx85920n128vi2125T9KY2hzv";

//AES密钥
$sysConfig["keyAesValue"] = substr($sysConfig["keyValue"], 0, 16);

//本地编码
$sysConfig["localeCode"] = "UTF-8";

//远程编码
$sysConfig["remoteCode"] = "UTF-8";



// post
$params = array(
    'requestid'       => date('YmdHis') //  商户订单号
    ,'amount'         => '0.01' // 商户订单金额
    ,'assure'         => '0' // 是否担保: 1 – 担保交易 0 – 非担保交易
    ,'productname'    => 'productname' // 商品名称
    ,'productcat'     => 'productcat' // 商品种类
    ,'productdesc'    => 'productdesc' // 商品描述 - 当支付产品 payproducttype 为微信支付 （WECHATM,WECHATU）时，该参数必填
    ,'divideinfo'     => '' // 分账信息
    ,'callbackurl'    => $notifyURL // 后台通知地址
    ,'webcallbackurl' => $returnURL // 页面通知地址
    ,'bankid'         => '' // 银行编号 - 该参数仅当支付产品类别为网银，即参数 payproducttype=SALES 时才有效。
    ,'period'         => '' // 担保有效期 - 单位 ：天；当 assure=1 时必填，最大值：30
    ,'memo'           => '' // 商户备注
    ,'orderexpdate'   => '' // 订单有效期 - 微信：5<= date <= 120 其他：5<=date<=1440
    ,'payproducttype' => 'ALIPAYAPP' // 支付产品类型
    ,'userno'         => '' // 商户用户标识
    ,'cardname'       => '' // 持卡人姓名
    ,'idcard'         => '' // 身份证号
    ,'bankcardnum'    => '' // 银行卡号
    ,'mobilephone'    => '' // 预留手机号
    ,'appid'          => '' // 微信公众号 appid
    ,'openid'         => '' // 公众号用户 openid
    ,'directcode'     => '' // 直连代码
    ,'ip'             => '127.0.0.1' // 用户 IP
);


// 通用必要参数
$must = array(
    'requestid', 'amount', 'callbackurl', 'payproducttype'
);
// 支付产品类型为 WECHATU, WECHATAPP, ALIPAYAPP 时，需要用户IP地址
$type = strtoupper($params['payproducttype']);
$useips = array(
    'WECHATU'=>1, 'WECHATAPP'=>1, 'ALIPAYAPP'=>1
);
if ( isset($useips[$type]) ) {
    $must[] = 'ip';
}
foreach ($must as $value) {
    if (!array_key_exists($value, $params) || !$value) {
        throw new \Exception("params[$value] is must fill, and it can't be empty.");
    }
}



$needRequestHmac = array(
    'requestid',
    'amount',
    'assure',
    'productname',
    'productcat',
    'productdesc',
    'divideinfo',
    'callbackurl',
    'webcallbackurl',
    'bankid',
    'period',
    'memo'
);
$hmacData = array();
$hmacData["customernumber"] = $sysConfig['customernumber'];
foreach ( $needRequestHmac as $value ) {
    $v = "";
    if ( array_key_exists($value, $params) && $params[$value] ) {
        $v = $params[$value];
    }
    $hmacData[$value] = $v;
}

// echo 'hmacData';
// dump($hmacData);



$hmac = getHmac($hmacData, $sysConfig['keyValue']);
// echo 'hmac';
// dump($hmac);



$needRequest = array(
    0  => 'requestid',
    1  => 'amount',
    2  => 'assure',
    3  => 'productname',
    4  => 'productcat',
    5  => 'productdesc',
    6  => 'divideinfo',
    7  => 'callbackurl',
    8  => 'webcallbackurl',
    9  => 'bankid',
    10 => 'period',
    11 => 'memo',
    12 => 'payproducttype',
    13 => 'userno',
    14 => 'ip',
    15 => 'cardname',
    16 => 'idcard',
    17 => 'bankcardnum',
    18 => 'mobilephone',
    19 => 'orderexpdate',
    20 => 'appid',
    21 => 'openid',
    22 => 'directcode'
);

$requestData = array();
$requestData['customernumber'] = $sysConfig['customernumber'];
foreach ($needRequest as $value) {
    $v = "";
    if ( array_key_exists($value, $params) && $params[$value] ) {
        $v = $params[$value];
    }
    $requestData[$value] = $v;
}
$requestData['hmac'] = $hmac;

// echo 'requestData';
// dump($requestData);
// echo '<hr>';

$dataJsonString = cnJsonEncode($requestData);
// echo 'dataJsonString';
// dump($dataJsonString);

$cryptString = getAes($dataJsonString, $sysConfig['keyAesValue']);
// echo 'cryptString';
// dump($cryptString);

$post = array(
    'customernumber' => $sysConfig['customernumber'], 
    'data' => $cryptString
);
// echo 'post';
// dump($post);

$response = post($requestURL, $post);
// echo 'response';
// dump($response);

$responseJsonArray = json_decode($response, true);
$responseData = getDeAes($responseJsonArray['data'], $sysConfig['keyAesValue']);
$result = json_decode($responseData, true);
// echo 'result';
// dump($result);


if ( !empty($result["payurl"]) ) {
    // dump($result["payurl"]);
    $url = $result["payurl"];

    var_dump(headers_sent());


    // if (!headers_sent()) {
    //     header("Location:" . $url, true, $status);
    //     exit();
    // }

    // exit("<meta http-equiv=\"refresh\" content=\"0;url=" . $url ."\">");
}


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>订单支付结果</title>
</head>
    <body>
        <br /> <br />
        <table width="70%" border="0" align="center" cellpadding="5" cellspacing="0" 
                            style="word-break:break-all; border:solid 1px #107929">
            <tr>
                <th align="center" height="30" colspan="5" bgcolor="#6BBE18">
                    订单支付结果
                </th>
            </tr>

            <tr>
                <td width="15%" align="left">&nbsp;商户编号</td>
                <td width="5%"  align="center"> : </td> 
                <td width="60%" align="left"> <?php  echo $result["customernumber"]?> </td>
                <td width="5%"  align="center"> - </td> 
                <td width="15%" align="left">customernumber</td> 
            </tr>

            <tr>
                <td width="15%" align="left">&nbsp;返回码</td>
                <td width="5%"  align="center"> : </td> 
                <td width="60%" align="left"> <?php  echo $result["code"]?> </td>
                <td width="5%"  align="center"> - </td> 
                <td width="15%" align="left">code</td> 
            </tr>

            <tr>
                <td width="15%" align="left">&nbsp;商户订单号</td>
                <td width="5%"  align="center"> : </td> 
                <td width="60%" align="left"> <?php  echo $result["requestid"]?> </td>
                <td width="5%"  align="center"> - </td> 
                <td width="15%" align="left">requestid</td> 
            </tr>

            <tr>
                <td width="15%" align="left">&nbsp;易宝流水号</td>
                <td width="5%"  align="center"> : </td> 
                <td width="60%" align="left"> <?php  echo $result["externalid"]?> </td>
                <td width="5%"  align="center"> - </td> 
                <td width="15%" align="left">externalid</td> 
            </tr>

            <tr>
                <td width="15%" align="left">&nbsp;订单金额</td>
                <td width="5%"  align="center"> : </td> 
                <td width="60%" align="left"> <?php  echo $result["amount"]?> </td>
                <td width="5%"  align="center"> - </td> 
                <td width="15%" align="left">amount</td> 
            </tr>

            

            <tr>
                <td width="15%" align="left"  >&nbsp;支付链接</td>
                <td width="5%"  align="center"  > : </td> 
                <td width="60%" align="left"  >                 
                    <a href=<?php  echo $result["payurl"]?> style="text-decoration:none" target="_blank"> 
                        <?php  echo $result["payurl"]?>
                    </a> 
                
                </td>
                <td width="5%"  align="center" > - </td> 
                <td width="15%" align="left" >payurl</td> 
            </tr>

        </table>

    </body>
</html>