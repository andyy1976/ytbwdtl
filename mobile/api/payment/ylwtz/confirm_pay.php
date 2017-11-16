<?php
// echo "<script>alert('银联支付正在升级中！！！');window.location.href='https://ytbwdtl.com';</script>";


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


	$orderNo   = $_POST['orderNo'];

	$sendSeqId = $_POST['sendSeqId'];
	$smsCode   = $_POST['smsCode'];
	$sign      = $_POST['sign'];
	$amt       = $_POST['amt'];

	$str =  'OK|'.$orderNo.'|'.$sendSeqId.'|'.$amt;
	$signstr = md5($str ."C5E908D7C1A2F705D5928701118079E8");

	if ($sign != $signstr) {
		exit('签名错误');
	}

    //写入日志文件
    header("Content-type: text/html; charset=utf-8");
    $file  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/notify_1_zf'.date('y-m-d',time()).'.log';
    $content='\r\n订单号：'.$orderNo.'|金额：'.$amt.'|时间:'.date('y-m-d h:i:s',time());
    $f  = file_put_contents($file, $content,FILE_APPEND);

    $data = [
        // 发送流水号   
        'sendSeqId' => $sendSeqId, //   N(20)   M   M   唯一
        // 交易类型码   
        'transType' => 'KUAIC', //   N(6)    M   M   默认值：KUAIC
        // 机构号 
        'organizationId' => '138734566661', //  N(11)   M   M   代理商手机号
        // 商户手机号   
        'mobile' => '13873456666', //  N(20)   M       
        // 短信验证码   
        'smsVerifyCode' => $smsCode, //   N(6)    M       
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

    $salt     = "C5E908D7C1A2F705D5928701118079E8"; //签到后的Mackkey
    $b        = $macstr . $salt;  //报文值和Mackkey拼接
    $e        = md5($b);  //Md5加密
    $macstr   = base64_encode($e); //Base64加密
    // echo $macstr;
    // echo "\n\n";

    $data['mac'] = $macstr;

    $datastr = "data=".json_encode($data);
    // echo $datastr;
    // echo "\n\n";

    $res = postJsonCurl($datastr, 'http://cfi.daxtech.com.cn:8020/payform/quickPay');
    if (!$res || !strpos($res, 'respCode')) {
	    // var_dump($res);
	    // echo "\n\n";
	    // var_dump(strpos($res, 'respCode'));
    	exit('支付失败');
    }

    $ret = json_decode($res,true);
    if ($ret['respCode'] != '00') {
    	// var_dump($ret);
    	// echo "\n\n";
    	exit($ret['respDesc']);
    }
	    // var_dump($res);
	    // echo "\n\n";
    	// var_dump($ret);
    	// echo "\n\n";

    // http://wandiantonglian.com/mobile/index.php?act=payment&op=unionpay_notify&type=pd_order&out_trade_no=190556343227520440&amount=5555.00
  //   $url = 'http://'.$_SERVER['HTTP_HOST'].'/mobile/index.php?' . http_build_query([
		// 'act'          => 'payment',
		// 'op'           => 'unionpay_notify',
		// 'type'         => 'pd_order',
		// 'out_trade_no' => $orderNo,
		// 'amount'       => $amt, // 单位元
  //   ]);
    $_REST['act']    = 'payment';
    $_REST['op']     = 'unionpay_notify';
    $_REST['type']   = 'pd_order';
    $_REST['out_trade_no'] = $orderNo;
    $_REST['amount'] = $amt;
    $time = time();
    $key  = '@)!&wdtlytb20171';
    $str = $_REST['act'].$_REST['op'].$_REST['type'].$_REST['out_trade_no'].$_REST['amount'].$time.$key;
    // $_REST['str'] = $str;
    $sign = md5($str);
    //写入日志文件
    header("Content-type: text/html; charset=utf-8");
    $file_2  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/notify_7_zf'.date('y-m-d',time()).'.log';
    $content_2='\r\n订单号：'.$_POST['orderNo'].'|金额：'.$amt.'|时间:'.date('y-m-d h:i:s',time()).'|time：'.$time.'|sign：'.$sign;
    $f_2  = file_put_contents($file_2, $content_2,FILE_APPEND);
    $url = 'https://'.$_SERVER['HTTP_HOST'].'/mobile/index.php?' . http_build_query([
        'act'          => 'payment',
        'op'           => 'unionpay_notify',
        'type'         => 'pd_order',
        'out_trade_no' => $orderNo,
        'amount'       => $amt, // 单位元
        'time'         => $time,
        'sign'         => $sign
    ]);
    //写入日志文件
    header("Content-type: text/html; charset=utf-8");
    $file_1  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/notify_6_zf'.date('y-m-d',time()).'.log';
    $content_1='\r\n订单号：'.$_POST['orderNo'].'|金额：'.$amt.'|时间:'.date('y-m-d h:i:s',time()).'|time：'.$time.'|sign：'.$sign.'|url：'.$url;
    $f_1  = file_put_contents($file_1, $content_1,FILE_APPEND);
    doCurlGet($url);
    echo 'OK';

	// $_GET['act']  = 'payment';
	// $_GET['op']   = 'unionpay_notify';
	// $_GET['type'] = 'pd_order';
	// $_GET['out_trade_no'] = $orderNo;
	// $_GET['amount'] = $amt; // 单位元
	// require_once(dirname(__FILE__).'/../../../index.php');

    // var_dump($ret);
