<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 16:57
 */

namespace alleria\pay;


class PayOrder
{
    public $orderNo;
    public $merchantOrderNo;
    public $createDate;
    public $finishDate;
    public $status;
    public $amount;
    public $fee;
    public $redirectUrl;
    public $metaData;

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

    //日期类型
    public function getCreateDate() {
        return $this->createDate;
    }

    public function setCreateDate($createDate) {
        $this->createDate= $createDate;
    }

    public function getFinishDate() {
        return $this->finishDate;
    }

    public function setFinishDate($finishDate) {
        $this->finishDate = $finishDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getFee() {
        return $this->fee;
    }

    public function setFee($fee) {
        $this->fee = $fee;
    }

    public function getRedirectUrl() {
        return $this->redirectUrl;
    }

    public function setRedirectUrl($redirectUrl) {
        $this->redirectUrl = $redirectUrl;
    }

    //键值对
    public function getMetaData() {
        return $this->metaData;
    }

    public function setMetaData($metaData) {
        $this->metaData = $metaData;
    }

}