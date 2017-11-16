<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 16:50
 */

namespace alleria;


class AppRequest
{
    public $appId;

    public $signature;

    public $encryptKey;

    public $encryptData;

    public function AppRequest() {}

    public function getAppId() {
        return $this->appId;
    }

    public function setAppId($appId) {
        $this->appId = $appId;
    }

    public function getSignature() {
        return $this->signature;
    }

    public function setSignature($signature) {
        $this->signature = $signature;
    }

    public function getEncryptKey() {
        return $this->encryptKey;
    }

    public function setEncryptKey($encryptKey) {
        $this->encryptKey = $encryptKey;
    }

    public function getEncryptData() {
        return $this->encryptData;
    }

    public function setEncryptData($encryptData) {
        $this->encryptData = $encryptData;
    }

    public function json() {
        return json_encode($this);
    }
}