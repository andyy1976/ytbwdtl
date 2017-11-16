<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/8/1
 * Time: 12:44
 */

namespace alleria\test;
// echo $_SERVER['DOCUMENT_ROOT'] . '/mobile/api/payment/lepay/libs/httpful.phar';
// echo __DIR__; echo '<br>';
// echo  __DIR__.'/libs/httpful.phar';
// echo '<br>';
// echo '/home/wwwroot/lnmp01/domain/wdtl.com/web/mobile/api/payment/lepay/libs/';
// exit;

include_once $_SERVER['DOCUMENT_ROOT'] . '/mobile/api/payment/lepay/libs/httpful.phar';

include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/util/CryptoUtil.php';

include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/pay/Pay.php';

include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/pay/PayRequest.php';

include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/pay/ConfirmRequest.php';

include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/pay/ChannelExtra.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/AppRequest.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/Sdk.php';


// use PHPUnit\Framework\TestCase;


class SdkTest 
{
    private $sdk;
    private $baseUrl = "http://api.syuniv.com:8000";
    private $appId = '10064';

    public function __construct()
    {
        if(!$this->sdk){
            $appId = $this->appId;
            $baseUrl = $this->baseUrl;
            $sdk = new \alleria\Sdk();
            //私钥路径
            $sdk->setPrivateKey(\alleria\util\CryptoUtil::getPrivateKey($_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/pem/si.pem'));
            //sdk 地址
            $sdk->setBaseUrl($baseUrl);
            //appId
            $sdk->setAppId($appId);
            //公钥路径
            $sdk->setPublicKey(\alleria\util\CryptoUtil::getPublicKey($_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/pem/gong.pem'));
            $this->sdk = $sdk;
        }
    }
    
    public function testCreatePay($query){

        $request = new \alleria\pay\PayRequest();
        $request->setMerchantId(10064831702);
        $request->setOrderNo($query['order_sn']);
        $request->setChannel("express_t1");
        $request->setSubject("测试");
        $request->setDesc("测试描述");
        $query['amount']=$query['amount']*100;
        $request->setAmount($query['amount']);

        $request->setClientIp("118.249.122.251");
        $extra = new \alleria\pay\ChannelExtra();
        $extra->setName($query['cardname']);
        $extra->setPhone($query['phone']);
        $extra->setIdCard($query['cardid']);
        $extra->setBankCard($query['acc_no']);
        $extra->setExpirationDate("");
        $extra->setCvn2("");
        $request->setExtra($extra);
        // print_r($request);
        $r = $this->sdk->pay()->create($request);
        return $r;
        // var_dump($r);

    }

    public function testConfirmPay($query){

        $request = new \alleria\pay\ConfirmRequest();
        $request->setOrderNo($query['orderNo']);
        $request->setMerchantOrderNo($query['merchantOrderNo']);
        $request->setSmsCode($query['auth_code']);
        $request->setPassword($query['paypwd']);

        $r = $this->sdk->pay()->confirm($request);
        return $r;

    }

    public function testQueryOrder($query){

        $request = new \alleria\pay\ConfirmRequest();
        $request->setOrderNo($query['orderNo']);
        $request->setMerchantOrderNo($query['merchantOrderNo']);

        $r = $this->sdk->pay()->query($request);
        return $r;

    }
    public function signPay($query){
       
        $r = $this->sdk->pay()->check($query);
        return $r;
    }


}