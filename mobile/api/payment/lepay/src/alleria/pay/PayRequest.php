<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 17:04
 */

namespace alleria\pay;

include_once "ChannelExtra.php";

class PayRequest
{
    public $merchantId;
    // 商户订单号
    public $orderNo;
    // 支付类型代号
    public $channel;
    public $subject;
    public $desc;
    public $amount;
    public $clientIp;
    public $returnUrl;
    // 商户自定义数据
    public $metaData;
    // 支付通道额外数据
    //\alleria\pay\ChannelExtra
    public $extra;

    public function getMerchantId() {
        return $this->merchantId;
    }

    public function setMerchantId($merchantId) {
        $this->merchantId = $merchantId;
    }

    public function getOrderNo() {
        return $this->orderNo;
    }

    public function setOrderNo($orderNo) {
        $this->orderNo = $orderNo;
    }

    public function getChannel() {
        return $this->channel;
    }

    public function setChannel($channel) {
        $this->channel = $channel;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getDesc() {
        return $this->desc;
    }

    public function setDesc($desc) {
        $this->desc = $desc;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getClientIp() {
        return $this->clientIp;
    }

    public function setClientIp($clientIp) {
        $this->clientIp = $clientIp;
    }

    public function getReturnUrl() {
        return $this->returnUrl;
    }

    public function setReturnUrl($returnUrl) {
        $this->returnUrl = $returnUrl;
    }

    public function getMetaData() {
        return $this->metaData;
    }

    public function setMetaData($metaData) {
        $this->metaData = $metaData;
    }

    public function getExtra() {
        return $this->extra;
    }

    public function setExtra(\alleria\pay\ChannelExtra $extra) {
        $this->extra = $extra;
    }

}