<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 17:31
 */

namespace alleria\pay;
include_once $_SERVER['DOCUMENT_ROOT'] . '/mobile/api/payment/lepay/libs/httpful.phar';
include_once $_SERVER['DOCUMENT_ROOT'] .'/mobile/api/payment/lepay/src/alleria/Action.php';
include_once "ConfirmRequest.php";
include_once "PayRequest.php";

use \alleria\Action;
use \Httpful\Request;

class Pay extends \alleria\Action
{

    public $CREATE;
    public $CONFIRM;
    public $QUERY;

    public function Pay() {}
    public function initURL(){
        $this->CREATE = $this->getBaseUrl() . "/payment";
        $this->CONFIRM = $this->getBaseUrl() . "/payment/confirm";
        $this->QUERY = $this->getBaseUrl() . "/payment/query";
    }

    public function create(\alleria\pay\PayRequest $request) {

        $appRequest = $this->encrypt($request);//\alleria\AppRequest
         
        $response = \Httpful\Request::post($this->CREATE, json_encode($appRequest, JSON_UNESCAPED_UNICODE))->send();
        
        return $this->decrypt($response);//PayOrder.class
    }

    public function confirm(\alleria\pay\ConfirmRequest $request) {
        $appRequest = $this->encrypt($request);//\alleria\AppRequest
        $response = \Httpful\Request::post($this->CONFIRM, json_encode($appRequest, JSON_UNESCAPED_UNICODE))->send();
        return $this->decrypt($response);//PayOrder.class
    }

    public function query(\alleria\pay\ConfirmRequest $request) {
      
        $appRequest = $this->encrypt($request);//\alleria\AppRequest
        $response = \Httpful\Request::post($this->QUERY, json_encode($appRequest, JSON_UNESCAPED_UNICODE))->send();
        return $this->decrypt($response);//PayOrder.class
    }
    //解密
    public function check($request) {    
       
        return $this->decrypt_s($request);//PayOrder.class
    }

}