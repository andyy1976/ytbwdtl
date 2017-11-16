<?php

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
header("Content-type: text/html; charset=UTF-8");
include 'excelreader/excel_reader2.php';

/**
 * 易宝代付代发接口
 * 相应报文
 *
 */
class Model_Yibao_Base {

    function create_batchno() {
        $rand = str_shuffle("1234567890");
        $round = substr($rand, 0, 10);
        return "20168" . $round;
    }

}

class Model_Yibao extends Model_Yibao_Base {

    /**
     * 批量打款接口
     */
    public function https_request($url = '', $data = null) 
    {    
        move_uploaded_file($_FILES['file']['tmp_name'],'excelreader/test.xls');

        //创建对象
        $data = new Spreadsheet_Excel_Reader();

        //设置文本输出编码
        $data->setOutputEncoding('utf-8');

        //读取Excel文件
        $data->read('excelreader/test.xls');

        //$data->sheets[0]['numRows']为Excel行数
        $total_Num = ''; //总笔数
        $total_Amt = ''; //总金额
        $is_Repay = '0'; //是否需要判 断重复打款 0-不需要判断 1-需要判断
        $list = '';
        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
            if ($i > 1) {
                $total_Num+=1;
                $order_Id         = $this->create_batchno();
                $amount           = $data->sheets[0]['cells'][$i][1];
                $account_Name     = $data->sheets[0]['cells'][$i][2];
                $account_Number   = $data->sheets[0]['cells'][$i][3];
                $bank_Name        = $data->sheets[0]['cells'][$i][4];
                $fee_Type         = $data->sheets[0]['cells'][$i][5];
                $urgency          = $data->sheets[0]['cells'][$i][6];
                $branch_Bank_Name = $data->sheets[0]['cells'][$i][7];
                $province         = $data->sheets[0]['cells'][$i][8];
                $city             = $data->sheets[0]['cells'][$i][9];
                //拼接list内的部分
                $str = '
                <item>
                   <order_Id>%s</order_Id>
                   <amount>%s</amount>
                   <account_Name>%s</account_Name>
                   <account_Number>%s</account_Number>
                   <bank_Name>%s</bank_Name>
                   <fee_Type>%s</fee_Type>
                   <urgency>%s</urgency>
                </item>';
                $resultStr = sprintf($str, $order_Id, $amount, $account_Name, 
                    $account_Number, $bank_Name, $fee_Type, 
                    $urgency,$branch_Bank_Name,$province,$city
                );

                $resultStr = mb_convert_encoding($resultStr, 'gbk', 'utf-8');
                $list = $list . $resultStr;
            }
            //$data->sheets[0]['numCols']为Excel列数
            for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                //显示每个单元格内容
                //echo $data->sheets[0]['cells'][$i][$j] . ' ';
                if ($j == 1)
                    $total_Amt+=$data->sheets[0]['cells'][$i][$j];
            }
        }
        //echo $list;
        $cmd      = 'TransferBatch'; //命令 固定值: TransferSingle
        $version  = '1.1'; //接口版本 固定值:1.1
        $group_Id = '10000450379'; //总公司商户编号 总公司在易宝支付的客户编号
        $mer_Id   = '10000450379'; //实际发起付款的交易商户编号  发起付款的总(分)公司在易宝 支付的客户编号
        
        //不区分产品,必须唯一 必须为 15 位的数字串
        $batch_No = $this->create_batchno(); //打款批次号
        $Harr = array(
            'cmd'       => $cmd,
            'mer_Id'    => $mer_Id,
            'batch_No'  => $batch_No,
            'total_Num' => $total_Num,
            'total_Amt' => $total_Amt,
            'is_Repay'  => $is_Repay
        );
        $hmac = $this->Hmac($Harr); //签名信息
        $str = '<?xml version="1.0" encoding="GBK"?>
                   <data>
                        <cmd>%s</cmd>
                        <version>%s</version>
                        <group_Id>%s</group_Id>
                        <mer_Id>%s</mer_Id>
                        <batch_No>%s</batch_No>
                        <total_Num>%s</total_Num>
                        <total_Amt>%s</total_Amt>
                        <is_Repay>%s</is_Repay>
                        <hmac>%s</hmac>
                        <list>%s</list>
                    </data>';
        $resultStr = sprintf($str, $cmd, $version, $group_Id, $mer_Id, 
            $batch_No, $total_Num, $total_Amt, $is_Repay, $hmac, $list);

        echo "<br>" . "<textarea name='name' rows='10' cols='120' wrap='hard'>" . mb_convert_encoding($resultStr,'utf-8','gbk') . "</textarea>";
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
     * 批量打款接口
     * 
     */
    public function request($data, $batch_No) 
    {    
        $total_Num = ''; //总笔数
        $total_Amt = ''; //总金额
        $is_Repay = '0'; //是否需要判 断重复打款 0-不需要判断 1-需要判断
        $list = '';

        $week = date('w');
        $hour = date('H');

        // var_dump($week);
        // var_dump($hour);
        // exit;

        // 周一到周五的9点-18点间用加急出款
        if ($week > 0 && $week < 6 && $hour > 8 && $hour < 18 ) {
            $urgencyV = '1';
        } else {
            $urgencyV = '0';
        }
        

        foreach ($data as $key => $item) {
            $total_Num += 1;
            $total_Amt += $item['amount'];

            $order_Id         = $item['order_Id']; //$this->create_batchno();
            $amount           = $item['amount'];
            $account_Name     = $item['account_Name'];
            $account_Number   = $item['account_Number'];
            $bank_Name        = $item['bank_Name'];
            $fee_Type         = $item['fee_Type'];
            $urgency          = $urgencyV; //$item['urgency'];
            $branch_Bank_Name = $item['branch_Bank_Name'];
            $province         = $item['province'];
            $city             = $item['city'];

            // 拼接list内的部分
            $str = ''
            . "\n  <item>"
            . "\n    <order_Id>%s</order_Id>"
            . "\n    <amount>%s</amount>"
            . "\n    <account_Name>%s</account_Name>"
            . "\n    <account_Number>%s</account_Number>"
            . "\n    <bank_Name>%s</bank_Name>"
            . "\n    <fee_Type>%s</fee_Type>"
            . "\n    <urgency>%s</urgency>"
            . "\n  </item>"
            ;

            $resultStr = sprintf($str, $order_Id, $amount, $account_Name, 
                $account_Number, $bank_Name, $fee_Type, 
                $urgency,$branch_Bank_Name,$province,$city
            );

            $resultStr = mb_convert_encoding($resultStr, 'gbk', 'utf-8');
            $list = $list . $resultStr;
        }

        //echo $list;
        $cmd      = 'TransferBatch'; //命令 固定值: TransferSingle
        $version  = '1.1'; //接口版本 固定值:1.1
        // $group_Id = '10000450379'; //总公司商户编号 总公司在易宝支付的客户编号
        // $mer_Id   = '10000450379'; //实际发起付款的交易商户编号  发起付款的总(分)公司在易宝 支付的客户编号

        // 云托邦正式商编
        $group_Id = '10015102858'; //总公司商户编号 总公司在易宝支付的客户编号
        $mer_Id   = '10015102858'; //实际发起付款的交易商户编号  发起付款的总(分)公司在易宝 支付的客户编号
        
        // 不区分产品,必须唯一 必须为 15 位的数字串
        // $batch_No = $this->create_batchno(); //打款批次号

        $total_Amt = number_format($total_Amt,2,'.','');

        // var_dump($total_Amt);

        $Harr = array(
            'cmd'       => $cmd,
            'mer_Id'    => $mer_Id,
            'batch_No'  => $batch_No,
            'total_Num' => $total_Num,
            'total_Amt' => $total_Amt,
            'is_Repay'  => $is_Repay
        );
        $hmac = $this->Hmac($Harr); //签名信息
        $str = '<?xml version="1.0" encoding="GBK"?>'
            . "\n<data>"
            . "\n<cmd>%s</cmd>"
            . "\n<version>%s</version>"
            . "\n<group_Id>%s</group_Id>"
            . "\n<mer_Id>%s</mer_Id>"
            . "\n<batch_No>%s</batch_No>"
            . "\n<total_Num>%s</total_Num>"
            . "\n<total_Amt>%s</total_Amt>"
            . "\n<is_Repay>%s</is_Repay>"
            . "\n<hmac>%s</hmac>"
            . "\n<list>%s\n</list>"
            . "\n</data>"
        ;
        $resultStr = sprintf($str, $cmd, $version, $group_Id, $mer_Id, 
            $batch_No, $total_Num, $total_Amt, $is_Repay, $hmac, $list);

        // echo "<br>" . "<textarea name='name' rows='10' cols='120' wrap='hard'>" 
        //     . mb_convert_encoding($resultStr,'utf-8','gbk') . "</textarea>";
        // echo "<br>";

        // echo "Send:\n\n\n" . mb_convert_encoding($resultStr,'utf-8','gbk') ."\n\n\n";

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

        // echo "Response:\n\n\n" . mb_convert_encoding($output,'utf-8','gbk') ."\n\n\n";

        return $output;
    }


    /**
     * 按顺序将 cmd、mer_Id、 batch_No 、 total_Num 、 total_Amt、is_Repay
     */
    protected function Hmac($arr)
    {
        $cmd       = trim($arr['cmd']);
        $mer_Id    = trim($arr['mer_Id']);
        $batch_No  = trim($arr['batch_No']);
        $total_Num = trim($arr['total_Num']);
        $total_Amt = trim($arr['total_Amt']);
        $is_Repay  = trim($arr['is_Repay']);

        // 商户密钥
        // $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
        $merchantPrivateKey = '6bXzykZG4ZMCy98Mza869gBvI3BoFyy9P9G8Cgin6K4E0r060PBG4k3429u4';

        //拼接加密字符串
        $str = $cmd . $mer_Id . $batch_No . $total_Num . $total_Amt . $is_Repay . $merchantPrivateKey;
        $data = $this->scurl($str);
        return $data;
    }

    #生成HMAC签名
    protected function scurl($str)
    {
        $url = "http://127.0.0.1:8088/sign?req=" . $str;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        // echo "HMAC签名:\n\n";
        // echo $data;
        // echo "\n\n";
        return $data;
    }
    
    protected function scurl1($str, $hmac)
    {
        $url = "http://127.0.0.1:8088/verify?req=" . $str . "&sign=" . $hmac;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        // echo "验证HMAC签名:\n\n";
        // echo $data;
        // echo "\n\n";
        // exit;
        return $data;
    }

    public function Hmacsafe($str)
    {
        $data      = json_decode(json_encode((array) simplexml_load_string($str)), true);
        $cmd       = $data['cmd'];
        $ret_Code  = $data['ret_Code'];
        $mer_Id    = $data['mer_Id'];
        $batch_No  = $data['batch_No'];
        $total_Amt = $data['total_Amt'];
        $total_Num = $data['total_Num'];
        $r1_Code   = $data['r1_Code'];
        $hmac      = urlencode($data['hmac']);

        #商户密钥
        // $merchantPrivateKey = 'su1KU96573FKlt580404tU6XJDcA004oD2u75cgA33Q2W7I1542xR38XaI3t';
        $merchantPrivateKey = '6bXzykZG4ZMCy98Mza869gBvI3BoFyy9P9G8Cgin6K4E0r060PBG4k3429u4';

        //拼接加密字符串
        $arr = $cmd .$ret_Code .$mer_Id .$batch_No .$total_Amt .$total_Num .$r1_Code .$merchantPrivateKey;
        $hmactrue = $this->scurl1($arr, $hmac);
        return $hmactrue;
    }

}


// $a = new Model_Yibao();
// $data = $a->https_request($url = '', $data = null);
// $hmac = $a->Hmacsafe($data);
// if ($hmac == "SUCCESS")
//     echo  '验签成功' . $hmac;
// else
//     echo  '验签失败' . $hmac;
// echo "<br>";
// echo "<textarea name='name' rows='15' cols='120' wrap='hard'>" . mb_convert_encoding($data,'utf-8','gbk') . "</textarea>";

