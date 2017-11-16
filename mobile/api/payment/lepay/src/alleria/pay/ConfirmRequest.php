<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 16:54
 */

namespace alleria\pay;


class ConfirmRequest
{
    public $orderNo;
    public $merchantOrderNo;
    public $smsCode;
    public $password;

    public function getOrderNo() {
        return $this->orderNo;
    }

    public function setOrderNo($orderNo) {
        $this->orderNo = $orderNo;
    }

    public function getMerchantOrderNo() {
        return $this->merchantOrderNo;
    }

    public function setMerchantOrderNo($merchantOrderNo) {
        $this->merchantOrderNo = $merchantOrderNo;
    }

    public function getSmsCode() {
        return $this->smsCode;
    }

    public function setSmsCode($smsCode) {
        $this->smsCode = $smsCode;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}