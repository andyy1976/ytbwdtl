<?php
	
	
	// if ($this->member_info['member_name'] != '18975810567' ) {
	// 	echo '银联支付升级维护中，请暂时使用随行付支付！！！';
	// 	// echo "<script>alert('银联支付升级维护中，请暂时使用随行付支付！！！');</script>";
	// 	exit;
	// }
$time=strtotime(date('Y-m-d H:i:s'));
//限制每天凌晨00：00至00：20这个时间段无法充值购买云豆
$time_1=strtotime(date('Y-m-d'))-300;
$time_2=strtotime(date('Y-m-d'))+60*20;
if($time>$time_1 && $time<$time_2){
    echo "<script>alert('00：00至00：20系统每日赠送时间。兑换、充值云豆以及激活会员请于00：20之后进行操作。');history.go(-1);</script>";
    // echo '00：00至00：20系统每日赠送时间。兑换、充值云豆以及激活会员请于00：20之后进行操作。';
    exit;
}

    function postJsonCurl($json,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        // curl_setopt($ch, CURLOP_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        //运行curl
        $data = curl_exec($ch);
        //curl_close($ch);
        //返回结果
        if($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            curl_close($ch);
            return false;
        }
    }


    function doBind(){
		$name     = $_GET['name'];
		$certifId = $_GET['certifId'];
		$accNo    = $_GET['accNo'];
		$phone    = $_GET['phone'];
		// print_r($_GET);
		// echo "\n\n";


	    $data = [
	        // 发送流水号   
	        'sendSeqId' => date('YmdHis'), //   N(20)   M   M   唯一
	        // 交易类型码   
	        'transType' => 'OPENK', //   N(6)    M   M   默认值：OPENK
	        // 机构号 
	        'organizationId' => '138734566661', //  N(11)   M   M   代理商手机号
	        // 交易卡号    
	        'cardNo' => $accNo, //  N(19)   M   
	        // 商户手机号   
	        'mobile' => '13873456666', //  N(20)   M       我方开通的上送交易商户手机号
	        // 银行预留手机号 
	        'payerPhone' => $phone, //  N(11)   M       交易卡在银行预留的手机号
	        // 持卡人姓名   
	        'name' => $name, //    N(11)   M       
	        // 持卡人身份证号 
	        'idNum' => $certifId, //   N(11)   M      
	        // 开通的支付类型 
	        'payType' => 'T1', // N(2)    M       T1
	        // 报文鉴别码   
	        // 'mac' => '', // N(8)    M   M   MAC
	        // // 应答码 
	        // 'respCode' => '', //    N(2)        M   00：成功；其他为失败
	        // // 应答码描述   
	        // 'respDesc' => '', //    ANS(..128)      M   返回详细的操作结果信息
	        // 对接开发语言  
	        'subject' => 'php', // N(10)           不填写默认识Java，如果是PHP请填写php
	    ];

	    ksort($data);
	    $macstr = implode('', $data);
	    // echo $macstr;
	    // echo "\n\n";

	    // $data = "621700001008091629211010419790219205513873456666huanghao18510407890T115001120301PHP00100010001phpOPENK";
	    $salt     = "C5E908D7C1A2F705D5928701118079E8"; //签到后的Mackkey
	    $b        = $macstr . $salt;  //报文值和Mackkey拼接
	    $e        = md5($b);  //Md5加密
	    $macstr   = base64_encode($e); //Base64加密
	    // echo $macstr;
	    // echo "\n\n";

	    $data['mac'] = $macstr;

	    $datastr = "data=".json_encode($data);
	    $datastr = preg_replace('!"name"\:".*?"!', '"name":"'.$name.'"', $datastr);
	    // echo $datastr;
	    // echo "\n\n";

	    $res = postJsonCurl($datastr, 'http://cfi.daxtech.com.cn:8020/payform/quickPay');
	    if (!$res || !strpos($res, 'respCode')) {
		    // var_dump($res);
		    // var_dump(strpos($res, 'respCode'));
	    	exit('银行卡绑定请求失败');
	    }

	    $ret = json_decode($res,true);

	    // 00：成功；03：该银行卡已绑定快捷支付；
	    if ($ret['respCode'] != '00' && $ret['respCode'] != '03') {
	    	// var_dump($ret);
	    	exit('银行卡绑定失败');
	    }    	
    }

    function doRequestPay($sendSeqId, $amt){
		$name      = $_GET['name'];
		$certifId  = $_GET['certifId'];
		$accNo     = $_GET['accNo'];
		$phone     = $_GET['phone'];
	    $data = [
	        // 发送时间    
	        'sendTime' => date('YmdHis'), //    N(14)   M   M   格式：yyyyMMddHHmmss
	        // 发送流水号   
	        'sendSeqId' => $sendSeqId, //   N(20)   M   M   唯一
	        // 交易类型码   
	        'transType' => 'KUAIP', //   N(6)    M   M   默认值：KUAIP
	        // 机构号 
	        'organizationId' => '138734566661', //  N(11)   M   M   代理商手机号
	        // 交易金额    
	        'transAmt' => $amt, //    N(12)   M   M   分为单位
	        // 商品描述    
	        'body' => 'porduct', //    N(127)  M       商品描述
	        // 交易卡号    
	        'cardNo' => $accNo, //  N(19)   M       
	        // 银行预留手机号 
	        'payerPhone' => $phone, //  N(11)   M       交易卡在银行预留的手机号
	        // 持卡人姓名   
	        'name' => $name, //    N(6)    M       持卡人姓名
	        // 持卡人身份证号 
	        'idNum' => $certifId, //   N(18)   M       持卡人身份证号
	        // 商户手机号   
	        'mobile' => '13873456666', //  N(20)   M       我方开通的上送交易商户手机号
	        // 开通的支付类型 
	        'payType' => 'T1', // N(2)    M       T1

	        'notifyUrl' => 'https://ytbwdtl.com/mobile/api/payment/ylwtz/notify.php',
	        
	        // // 报文鉴别码   
	        // 'mac' => '', // N(8)    M   M   MAC
	        // // 应答码 
	        // 'respCode' => '', //    N(2)        M   00：成功；其他为失败
	        // // 应答码描述   
	        // 'respDesc' => '', //    ANS(..128)      M   返回详细的操作结果信息
	        // 对接开发语言  
	        'subject' => 'php', // N(10)           不填写默认识Java，如果是PHP请填写php
	    ];

	    ksort($data);
	    $macstr = implode('', $data);
	    // echo $macstr;
	    // echo "\n\n";

	    $salt     = "C5E908D7C1A2F705D5928701118079E8"; //签到后的Mackkey
	    $b        = $macstr . $salt;  //报文值和Mackkey拼接
	    $e        = md5($b);  //Md5加密
	    $macstr   = base64_encode($e); //Base64加密
	    // echo $macstr;
	    // echo "\n\n";

	    $data['mac'] = $macstr;

	    $datastr = "data=".json_encode($data);
	    $datastr = preg_replace('!"name"\:".*?"!', '"name":"'.$name.'"', $datastr);
	    // echo $datastr;
	    // echo "\n\n";

	    $res = postJsonCurl($datastr, 'http://cfi.daxtech.com.cn:8020/payform/quickPay');
	    if (!$res || !strpos($res, 'respCode')) {
		    // var_dump($res);
		    // var_dump(strpos($res, 'respCode'));
	    	exit('支付请求失败');
	    }

	    $ret = json_decode($res,true);
	    if ($ret['respCode'] != '00') {
	    	// var_dump($ret);
	    	exit($ret['respDesc']);
	    }   

	    return true;
    }


    doBind();

    $amt = $amout * 100;

    // var_dump($amout);
    // var_dump(intval($amout));
    // var_dump($amt);

    // if ($this->member_info['member_name'] == '18975810567') {
    // 	$amt = '10000'; // 金额单位分
    // }

    // print_r($this->member_info);
    // print_r($amt);
    // exit;

    $sendSeqId = $pdr_sn; // 发送流水号
    $bool = doRequestPay($sendSeqId, $amt);

    if ( $bool ) {
		$str  = 'OK|'.$pdr_sn.'|'.$sendSeqId.'|'.$amout;
		$sign = md5($str ."C5E908D7C1A2F705D5928701118079E8");
		$str  = $str .'|'.$sign;
    	exit($str);
    }

    exit;
