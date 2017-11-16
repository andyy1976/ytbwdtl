<?php

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
header("Content-type: text/html; charset=utf-8");

/**
 * 易宝代付代发接口
 * 相应报文
 * 
 */
class Model_Base {

    function create_batchno() {
        $rand = str_shuffle("1234567890");
        $round = substr($rand, 0, 10);
        return "20168" . $round;
    }

}

class Model_Yibao extends Model_Base {

    /**
     * 单笔打款接口
     */
    public function https_request($url = '', $data = null) {
        $cmd = 'TransferSingle';//命令 固定值: TransferSingle
        $version = '1.1';//接口版本 固定值:1.1
        $group_Id = '10000450379';//总公司商户编号 总公司在易宝支付的客户编号
        $mer_Id = '10000450379';//实际发起付款的交易商户编号  发起付款的总(分)公司在易宝 支付的客户编号
        $product = '';//产品类型  为空走代付、代发出款 值为“RJT”走日结通出款
        //不区分产品,必须唯一 必须为 15 位的数字串
        $batch_No = $this->create_batchno(); //打款批次号
        $order_Id = $this->create_batchno(); //订单号
        $amount = $_REQUEST['amount']; //打款金额
        $account_Name = $_REQUEST['account_Name']; //账户名称
        $account_Number = $_REQUEST['account_Number']; //账户号
        #cmd、mer_Id、batch_No、order_Id、amount 、account_Number
        $Harr = array(
            'cmd' => $cmd,
            'mer_Id' => $mer_Id,
            'batch_No' => $batch_No,
            'order_Id' => $order_Id,
            'amount' => $amount,
            'account_Number' => $account_Number
        );
        $hmac = $this->Hmac($Harr); //签名信息
        $bank_Code = $_REQUEST['bank_Code']; //收款银行编号
        $bank_Name = '中国银行'; //收款银行 全称
        $branch_Bank_Name=$_REQUEST['branch_Bank_Name'];//非直联银行需添写支行信息
        $province=$_REQUEST['province'];
        $city=$_REQUEST['city'];
        $account_Type = 'pr';//对私
        //“SOURCE” 商户承担 “TARGET”用户承担
        $fee_Type = 'SOURCE'; //手续费收 取方式
        //只能填写 0 或者 1,最终是 否实时出款取决于商户是否 开通该银行的实时出款。
        $urgency = '1'; //加急
        $str = '<?xml version="1.0" encoding="GBK"?>
                    <data>
                    <cmd>%s</cmd>
                    <version>%s</version>
                    <group_Id>%s</group_Id>
                    <mer_Id>%s</mer_Id>
                    <batch_No>%s</batch_No>
                    <bank_Code>%s</bank_Code>
                    <order_Id>%s</order_Id>
                    <bank_Name>%s</bank_Name>
                    <branch_Bank_Name>%s</branch_Bank_Name>
                    <amount>%s</amount>
                    <account_Name>%s</account_Name>
                    <account_Number>%s</account_Number>
                    <account_Type>%s</account_Type>
                    <province>%s</province>
                    <city>%s</city>
                    <fee_Type>%s</fee_Type>
                    <urgency>%s</urgency>
                    <hmac>%s</hmac>
                    </data>';
        $resultStr = sprintf($str, $cmd, $version, $group_Id, $mer_Id, $batch_No, $bank_Code, $order_Id, $bank_Name, $branch_Bank_Name,$amount, $account_Name, $account_Number, $account_Type,$province,$city, $fee_Type, $urgency, $hmac);
        $resultStr = mb_convert_encoding($resultStr, 'gbk', 'utf-8');
        echo "<br>" . "<textarea name='name' rows='10' cols='120' wrap='hard'>" . mb_convert_encoding($resultStr, 'utf-8', 'gbk') . "</textarea>";
        echo "<br>";
        $url = 'https://cha.yeepay.com/app-merchant-proxy/groupTransferController.action';
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
     * 按顺序将 cmd、mer_Id、 batch_No 、 order_Id 、 amount、account_Number
     * 参数值 +商户密钥组成字符串,并采用商户证书进行签名(签名方式参考样例代码)
     * @param type $arr
     */
    #cmd、mer_Id、batch_No、order_Id、amount 、account_Number
    protected function Hmac($arr) {
        $cmd = trim($arr['cmd']);
        $mer_Id = trim($arr['mer_Id']);
        $batch_No = trim($arr['batch_No']);
        $order_Id = trim($arr['order_Id']);
        $amount = trim($arr['amount']);
        $account_Number = trim($arr['account_Number']);
        // 商户私钥
        $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
        //拼接加密字符串
        $str = $cmd . $mer_Id . $batch_No . $order_Id . $amount . $account_Number . $merchantPrivateKey;
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
    #验签
    public function Hmacsafe($str) {

        $data = json_decode(json_encode((array) simplexml_load_string($str)), true);
        $cmd = $data['cmd'];
        $ret_Code = $data['ret_Code'];
        $mer_Id=$data['mer_Id'];
        $batch_No=$data['batch_No'];
        $total_Amt=$data['total_Amt'];
        $total_Num=$data['total_Num'];
        $r1_Code = $data['r1_Code'];
        $hmac = urlencode($data['hmac']);
        #商户密钥
        $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
        //拼接加密字符串
        $arr = $cmd . $ret_Code . $mer_Id.$batch_No.$total_Amt.$total_Num.$r1_Code .$merchantPrivateKey;
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
