<?php
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
// namespace alleria\test;
// include_once "src/alleria/test/SdkTest.php";
// $sdk=new SdkTest();

// // echo $sdk->testCreatePay();

// $sign=	file_get_contents("php://input");
// // $sign= '{"encryptKey":"Jsd96lPAKVXnCOLEwcgqMhO35/KOCau2u3jGZM5Px4qa56uBy4/CWakMpli7FdT7Wwu1sdE4cqWcbClKAB3gCxga1odNF/+gSfS+OtuSAk7IHf4wQxOo5L2I05neUQ5qrd9cqAkRe5EarL902SEwdX1oLj77YkCWTf5qO6cKWR6GDtK/j2WAe9MBYse7d+aNTWlKjBlfa28HG6VC7xY8RlfPVeaxD4H4ka0s9yP14PRAimuvACMx834IRD1qt7AjYJwp9yEmB17Z8HKOL5H0WxHBm57SIhY2bHFyNqRd5FbPQt+cix+K45MLxTK0DD1SeUru2Eb6Jy17Usg+ARANWw==","encryptData":"Rn/ogKg5OWhFTCi5wkwgvmOjLssjsXPsJKkWWV+oGmfGbxc/ctIYe8fD78RDBfNwTq+g9gxgj/yGbDx9Tj0wKiOGCDoxRA0gRe64vHsMizIPD0EXCJ7L5sq8vACFOMXwyB9xoeMBS0IbLLMsAkSTAbh9/tF/ZfaNg8tlYdeBq366NsU/io3gH04qWNwAL+uYIYI+po0sOb90/UCwx65mEutUJE6t19B5VOL5lAhYyvbOnKLWWVdP2FRVfMtl+lN13oaVugwH+76k6ipIZopmxbdWbcB+qOUamRi5cYsTjWc8wPe5K04+fQB3d8a/W03DUl6XoPSZOivBEaUxMMacaDLG0hdJB582pCUz4FtsgMk6AH/JuyY6anmQW7i8VDV6","signature":"VVZ0REa0qxZKw0L2cOvcNuKk1fe19CnwVp9258exAH3dx7I929iDG99mSrBfWs89xxBzbckGBsMG5eOAuPkZ/BA4cPHATwJFKsRavOe+KauOH+8ELbmDCAIpyZ1j+Tc8XUf/i4S1IpU2+3FYb204EamOIlwHbZVHEweItHscS2P5bYYcPPCpfIYRWh10LguFcfx5CA6Hd7kcFqKXhc0/ULpyNC3CYNO/DUBldSuR92ErLGA7n3O9nDVpk8wXZY+fUmUxTNiS/DDMU8Ez9zcGAi/J+Gw0ZJcOI7uz6I0172lPONFOBXBK5ozScebG4bNO95kfyFFWbMPWWIL7MPNjYg==","message":"success"}';
// $array=$sdk->signPay($sign);
// $filename = 'notify-'.time().'.txt';
// $contents ="\nGET\n\nPOST\n". print_r($array, true);
// // $contents = "\nGET\n\nPOST\n" .print_r($_POST, true). "\n"
// // . "\nAAAA\n" .$sign. "\n";
// debugLog( $filename, $contents );	

// if($array['data']['success'] && $array['data']['status']=='2'){
	$array['data']['merchantOrderNo']='21312312312312';
    $array['data']['amount']='123123';
	$_GET['act']    = 'payment';
    $_GET['op']     = 'lepay_return';
    $_GET['type']   = 'pd_order';
    $_GET['out_trade_no'] = $array['data']['merchantOrderNo'];
    $_GET['amount'] = $array['data']['amount']/100;
    $_GET['time'] = time();
    $key  = '@)!&wdtlytb20171';
    $str = $_GET['act'].$_GET['op'].$_GET['type'].$_GET['out_trade_no'].$_GET['amount'].$_GET['time'].$key;
   
    $_GET['sign'] = md5($str);
    echo $str.'<br>';
    echo $_GET['sign'].'<br>';
    $url = 'https://'.$_SERVER['HTTP_HOST'].'/mobile/index.php?' . http_build_query([
        'act'          => 'payment',
        'op'           => 'lepay_return',
        'type'         => 'pd_order',
        'out_trade_no' => $array['data']['merchantOrderNo'],
        'amount'       => $array['data']['amount']/100, // 单位元
        'time'         => time(),
        'sign'         => md5($str)
    ]);
    $res=doCurlGet($url);
    echo $res;
    // print_r($_GET);
    
    // require_once(dirname(__FILE__).'/../../../index.php');

// }
// print_r($array);


?>
	
	
	
