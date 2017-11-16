<?php
/**
 * Created by PhpStorm.
 * User: dtsola
 * Date: 2017/7/31
 * Time: 17:51
 */

namespace alleria\util;
include_once "AES4PKCS5.php";
///
use \alleria\util\AES4PKCS5;

class CryptoUtil
{
    public static $MD5withRSA = "MD5withRSA";
    public static $SHA1withRSA = "SHA1withRSA";
    public static $SHA256withRSA = "SHA256withRSA";
    public static $RSA = "RSA";
    public static $AES = "AES";
    public static $LENGTH_2048 = 2048;
    public static $RSA_PADDING = "RSA/ECB/PKCS1Padding";
    public static $AES_PADDING = "AES/ECB/PKCS5Padding";


    //$publicKeyFilePath：公钥路径
    public static function getPublicKey($publicKeyFilePath){
        

        return file_get_contents($publicKeyFilePath);
    }

    //$privateKeyFilePath：私钥路径
    public static function getPrivateKey($privateKeyFilePath){
        return file_get_contents($privateKeyFilePath);
    }

    public static function aes_encrypt($key, $data){

        $r = \alleria\util\AES4PKCS5::encrypt($data, $key);
      
        return $r;
    }

    public static function aes_decrypt($key, $data){
       $r = \alleria\util\AES4PKCS5::decrypt($data, $key);
        return $r;
    }

    public static function rsa_encrypt($publicKeyFilePath, $data){
        $r = null;
        $publicKey = openssl_pkey_get_public($publicKeyFilePath);
        if($publicKey) {
            openssl_public_encrypt($data, $r, $publicKey);
            if ($r) {
                $r = base64_encode($r);
            }
            openssl_free_key($publicKey);
        }

        return $r;
    }

    public static function rsa_decrypt($privateKeyFilePath, $data){
        $r = null;
        $privateKey  = openssl_pkey_get_private($privateKeyFilePath);
        if($privateKey){
            openssl_private_decrypt(base64_decode($data), $r, $privateKey);
            openssl_free_key($privateKey);
        }
        return $r;
    }

    public static function sign($privateKeyFilePath, $data){
        $r = null;
        $privateKey  = openssl_pkey_get_private($privateKeyFilePath);
        if($privateKey){
            openssl_sign($data, $r, $privateKey, OPENSSL_ALGO_MD5);
            if($r){
                $r = base64_encode($r);
            }
        }
        return $r;
    }

    public static function verifyData($publicKeyFilePath, $data, $sign){
        
        $r = null;
        
        $publicKey = openssl_pkey_get_public($publicKeyFilePath);
        
        if($publicKey) {
            $r = openssl_verify($data, $sign, $publicKey, OPENSSL_ALGO_MD5);

            openssl_free_key($publicKey);
        }
        return $r;
    }

    public static function initAESKey(){
        $r = '1234'. rand(1000, 9999). '8979'. rand(1000, 9999);
        return $r;
    }



}