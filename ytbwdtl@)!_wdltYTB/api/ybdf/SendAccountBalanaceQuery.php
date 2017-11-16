<?php

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
header("Content-type: text/html; charset=utf-8");

/**
 * 易宝代付代发接口
 * 相应报文
 * 
 */
class Model_Yibao  {

    /**
     * 可用打款余额查询
     */
    public function https_request($url = '', $data = null) {
        $cmd = 'AccountBalanaceQuery';//命令 固定值: TransferSingle
        $version = '1.0';//接口版本 固定值:1.0
        $mer_Id = '10000450379';//实际发起付款的交易商户编号  发起付款的总(分)公司在易宝 支付的客户编号
        $date =$_REQUEST['date'];       
        $Harr = array(
            'cmd' => $cmd,
            'mer_Id' => $mer_Id,            
            'date' => $date 
        );
        $hmac = $this->Hmac($Harr); //签名信息       
        $str = '<?xml version="1.0" encoding="GBK"?>
                    <data>
                    <cmd>%s</cmd>
                    <version>%s</version>                   
                    <mer_Id>%s</mer_Id>
                    <date>%s</date>
                    <hmac>%s</hmac>
                    </data>';
        $resultStr = sprintf($str, $cmd, $version, $mer_Id, $date, $hmac);
        $resultStr = mb_convert_encoding($resultStr, 'gbk', 'utf-8');
        echo "<br>" . "<textarea name='name' rows='10' cols='120' wrap='hard'>" . mb_convert_encoding($resultStr, 'utf-8', 'gbk') . "</textarea>";
        echo "<br>";
        $url = 'https://cha.yeepay.com/app-merchant-proxy/transferController.action';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, false);
        if (!empty($resultStr)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $resultStr);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     * 按顺序将 cmd、mer_Id、date
     * 参数值 +商户密钥组成字符串,并采用商户证书进行签名(签名方式参考样例代码)
     */
    protected function Hmac($arr) {
        $cmd = trim($arr['cmd']);
        $mer_Id = trim($arr['mer_Id']);
        $date = trim($arr['date']);     
        // 商户私钥
        $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
        //拼接加密字符串
        $str = $cmd . $mer_Id . $date . $merchantPrivateKey;
        $data = $this->scurl($str);
        return $data;
    }

    #生成HMAC签名

    protected function scurl($str) {
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
    protected function scurl1($str, $hmac) {
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
    public function Hmacsafe($str) {

        $data = json_decode(json_encode((array) simplexml_load_string($str)), true);
        $cmd = $data['cmd'];
        $ret_Code = $data['ret_Code'];
        $mer_Id=$data['mer_Id'];
        $balance_Amount=$data['balance_Amount'];
        $valid_Amount=$data['valid_Amount'];
        $hmac = urlencode($data['hmac']);
        #商户密钥
        $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
        //拼接加密字符串
        $arr = $cmd . $ret_Code . $mer_Id.$balance_Amount.$valid_Amount .$merchantPrivateKey;
        $hmactrue = $this->scurl1($arr, $hmac);
        return $hmactrue;
    }


}

$a = new Model_Yibao();
$data = $a->https_request($url = '', $data = null);
$hmac = $a->Hmacsafe($data);
if ($hmac == "SUCCESS")
    echo  '验签成功' . $hmac;
else
    echo  '验签失败' . $hmac;
echo "<br>";
echo "<textarea name='name' rows='15' cols='120' wrap='hard'>" . mb_convert_encoding($data,'utf-8','gbk') . "</textarea>";
