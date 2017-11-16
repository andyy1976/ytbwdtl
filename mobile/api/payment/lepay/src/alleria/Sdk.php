<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 17:55
 */

namespace alleria;
include_once "pay/Pay.php";
include_once "util/CryptoUtil.php";


class Sdk
{
    private $baseUrl;
    private $appId;
    private $publicKey;
    private $privateKey;


    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getAppId() {
        return $this->appId;
    }

    public function setAppId($appId) {
        $this->appId = $appId;
        return $this;
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

    public function pay() {
        //$this->baseUrl, $this->appId, $this->publicKey, $this->privateKey
        $r = new \alleria\pay\Pay();
        $r->setPrivateKey($this->privateKey);
        $r->setBaseUrl($this->baseUrl);
        $r->setAppId($this->appId);
        $r->setPublicKey($this->publicKey);
        $r->initURL();
        return $r;
    }
}