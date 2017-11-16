<?php
/**
 * 收货地址
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_bankcardControl extends mobileMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addOp ()
    {
        $data = [];
        $data['acc_no']    = $_POST['accNo'];
        $data['phone']     = $_POST['phone'];
        $data['name']      = $_POST['name'];
        $data['certif_id'] = $_POST['certifId'];
        $data['bank_name'] = $this->getBankInfo($data['acc_no']);
        $data['member_id'] = $this->member_info['member_id'];
        
        // print_r($data);
        // exit;

        $lastId = Model('bankcard')->add($data);
        echo $lastId;
    }

    public function getCardListOp()
    {
        $condition['member_id'] = $this->member_info['member_id'];
        $list = Model('bankcard')->getCardList($condition);
        if ($list) {
            echo json_encode($list,true);
        } else {
            echo '';
        }
    }

    public function getBankInfo($card)
    {
        $bankList = include __DIR__ . '/return.banklist.php';

        $card_8 = substr($card, 0, 8);  
        if (isset($bankList[$card_8])) {  
            return $bankList[$card_8];  
        }

        $card_6 = substr($card, 0, 6);  
        if (isset($bankList[$card_6])) {  
            return $bankList[$card_6];  
        }

        $card_5 = substr($card, 0, 5);  
        if (isset($bankList[$card_5])) {  
            return $bankList[$card_5];   
        }

        $card_4 = substr($card, 0, 4);  
        if (isset($bankList[$card_4])) {  
            return $bankList[$card_4];  
        }

        return '银行卡';

    }
}
