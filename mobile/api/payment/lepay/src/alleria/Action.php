<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 17:17
 */

namespace alleria;

include_once "AppRequest.php";
include_once "util/CryptoUtil.php";

abstract class Action
{
    protected $baseUrl;
    protected $appId;
    protected $publicKey;
    protected $privateKey;

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function getAppId() {
        return $this->appId;
    }

    public function setAppId($appId) {
        $this->appId = $appId;
    }

    public function getPublicKey() {
        return $this->publicKey;
    }

    public function setPublicKey($publicKey) {
        $this->publicKey = $publicKey;
    }

    public function getPrivateKey() {
        return $this->privateKey;
    }

    public function setPrivateKey($privateKey) {
        $this->privateKey = $privateKey;
    }

    protected function encrypt($request) {

        $appRequest = new \alleria\AppRequest();
        $data = json_encode($request, JSON_UNESCAPED_UNICODE);

        $key = \alleria\util\CryptoUtil::initAESKey();

        $encryptData = \alleria\util\CryptoUtil::aes_encrypt($key, $data);

        $encryptKey = \alleria\util\CryptoUtil::rsa_encrypt($this->publicKey, $key);
        $sign = \alleria\util\CryptoUtil::sign($this->privateKey, $data);

        $appRequest->setAppId($this->appId);
        $appRequest->setEncryptData($encryptData);
        $appRequest->setEncryptKey($encryptKey);
        $appRequest->setSignature($sign);

        return $appRequest;
    }
    protected function decrypt($response) {
       
        header("Content-type: text/html; charset=utf8");
        if(!$response || !$response->body)
            return null;
        
       
        $AESKey = \alleria\util\CryptoUtil::rsa_decrypt($this->privateKey, $response->body->encryptKey);
        $data = \alleria\util\CryptoUtil::aes_decrypt($AESKey, $response->body->encryptData);

        $res = \alleria\util\CryptoUtil::verifyData($this->publicKey, $data, base64_decode($response->body->signature));
       
        if(!$res){
            throw new \Exception('签名校验错误');
        }
        
        // ('/"gcid":\s*"([a-zA-Z0-9]*)"/', $str, $matches);
        // echo $this->$data['orderNo'];
        // $ok = preg_match('/orderNo.*?([1-9]+)/', $data, $matches);
       
        $ok = preg_match('/"orderNo":\s*([0-9]*)/', $data, $matches);
        $t = json_decode($data, true);
        
        if ($ok) {
            
            $t['orderNo']=$matches[1];
            
        }
        return $t;
        
        
    }
    protected function decrypt_s($response) {
        $datas=json_decode($response);
        header("Content-type: text/html; charset=utf8");    
        if(!$response)
            return null;
        $AESKey = \alleria\util\CryptoUtil::rsa_decrypt($this->privateKey, $datas->encryptKey);
        $data = \alleria\util\CryptoUtil::aes_decrypt($AESKey, $datas->encryptData);
        
        $res = \alleria\util\CryptoUtil::verifyData($this->publicKey, $data, base64_decode($datas->signature));
       
        if(!$res){
            throw new \Exception('签名校验错误');
        }
        $ok = preg_match('/"orderNo":\s*([0-9]*)/', $data, $matches);
        $t = json_decode($data, true);
        
        if ($ok) {
            
            $t['orderNo']=$matches[1];
            
        }
        return $t;
        
        
    }


}