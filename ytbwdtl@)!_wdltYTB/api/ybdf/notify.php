<?php
/*
 * 仅供参考
 */
#导入日志
include_once("./log_.php");
#读取内容
$xml = file_get_contents("php://input");
$log_ = new Log_();
$log_name = "./notify_url.log"; //log文件路径
$log_->log_result($log_name, "【xml】:\n" . $xml . "\n");
#xml传数组
$data = json_decode(json_encode((array) simplexml_load_string($xml)), true);
#验证签名
$hmac = Hmacsafe($data);
if ($hmac == "SUCCESS")
    echo "验签成功" . $hmac;
else
    echo "验签失败" . $hmac;

/*
 * 回写易宝
 * cmd、mer_Id、batch_No、order_Id 、ret_Code
 */
$cmd = $data['cmd'];
$version = $data['version'];
$mer_Id = $data['mer_Id'];
$batch_No = $data['batch_No'];
$order_Id = $data['order_Id'];
$ret_Code = 'S';
$arr = array(
    'cmd' => $cmd,
    'mer_Id' => $mer_Id,
    'batch_No' => $batch_No,
    'order_Id' => $order_Id,
    'ret_Code' => $ret_Code
);
$str = '<?xml version="1.0" encoding="GBK"?>
                    <data>
                    <cmd>%s</cmd>
                    <version>%s</version>
                    <mer_Id>%s</mer_Id>
                    <batch_No>%s</batch_No>
                    <order_Id>%s</order_Id>
                    <ret_Code>%s</ret_Code>
                    <hmac>%s</hmac>
                    </data>';
$resultStr = sprintf($str, $cmd, $version, $mer_Id, $batch_No, $order_Id, $ret_Code, $hmac);
$resultStr = mb_convert_encoding($resultStr, 'gbk', 'utf-8');
echo mb_convert_encoding($resultStr, 'utf-8', 'gbk');
$hmac = Hmac($arr);

function Hmac($arr) {
    $cmd = trim($arr['cmd']);
    $mer_Id = trim($arr['mer_Id']);
    $batch_No = trim($arr['batch_No']);
    $order_Id = trim($arr['order_Id']);
    $ret_Code = trim($arr['ret_Code']);
    // 商户私钥
    $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
    //拼接加密字符串
    $str = $cmd . $mer_Id . $batch_No . $order_Id . $ret_Code . $merchantPrivateKey;
    $data = scurl($str);
    return $data;
}

function scurl($str) {
    $url = "http://127.0.0.1:8188/sign?req=" . $str;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
    //echo $data;
}

function scurl1($str, $hmac) {
    $url = "http://127.0.0.1:8188/verify?req=" . $str . "&sign=" . $hmac;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
    //echo $data;
}

function Hmacsafe($str) {
    $cmd = $str['cmd'];
    $mer_Id = $str['mer_Id'];
    $batch_No = $str['batch_No'];
    $order_Id = $str['order_Id'];
    $status = $str['status'];
    $message = '';
    if(leng($str['message'])>0)
        $message = $str['message'];
    $hmac = urlencode($str['hmac']);
    #商户密钥
    $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
    //拼接加密字符串
    $arr = $cmd . $mer_Id . $batch_No . $order_Id . $status . $message . $merchantPrivateKey;
    $hmactrue = scurl1($arr, $hmac);
    return $hmactrue;
}