<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 17:07
 */

namespace alleria\pay;


class ChannelExtra
{
    public $name;
    public $phone;
    public $idCard;
    public $bankCard;
    public $expirationDate;
    public $cvn2;

    public function ChannelExtra() {}

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getIdCard() {
        return $this->idCard;
    }

    public function setIdCard($idCard) {
        $this->idCard = $idCard;
    }

    public function getBankCard() {
        return $this->bankCard;
    }

    public function setBankCard($bankCard) {
        $this->bankCard = $bankCard;
    }

    public function getExpirationDate() {
        return $this->expirationDate;
    }

    public function setExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;
    }

    public function getCvn2() {
        return $this->cvn2;
    }

    public function setCvn2($cvn2) {
        $this->cvn2 = $cvn2;
    }


}