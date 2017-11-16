<?php
/**
 * 抽奖确认收货地址
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class sweeporder_addressControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 选择收获地址和显示中奖信息
     */
    public function show_addressOp() {
        $logic_buy = logic('buy');
        $result = array();
        //输出用户默认收货地址
        $result['address_info'] = Model('address')->getDefaultAddressInfo(array('member_id'=>$this->member_info['member_id']));

        if (intval($_POST['address_id']) > 0) {
            $result['address_info'] = Model('address')->getDefaultAddressInfo(array('address_id'=>intval($_POST['address_id']),'member_id'=>$this->member_info['member_id']));
        }
        //显示中奖信息
        if (intval($_REQUEST['sweeporder_id'])  <= 0 ) {
            output_error('中奖信息不正确');
        }
        
        $result['sweepOrderInfo'] = Model()->table('sweepstakes_orders')->find($_REQUEST['sweeporder_id']);

        if (!$result['sweepOrderInfo']) {
            output_error('查无此中奖信息');
        }

        $awardTmp = Model()->table('sweepstakes_award')->find($result['sweepOrderInfo']['award_id']);
        
        $buy_list = array();
        //收货地址信息
        $buy_list['address_info']   = $result['address_info'];
        $buy_list['sweepOrderInfo'] = $result['sweepOrderInfo'];
        $buy_list['isPhoneRequire'] = $awardTmp['is_phone_require'];
        output_data($buy_list);
    }

    /**
     * 存储地址以及留言
     */
    public function store_infoOp()
    {
        $param = array();
        if(intval($_POST['sweeporder_id']) <= 0){
            output_error('非法入侵');
        }
        $param['point_orderid'] = trim($_POST['sweeporder_id']);
        $param['address_id']    = trim($_POST['address_id']);
        //如果有留言信息提交留言信息
        if (trim($_POST['message']) || trim($_POST['phone_require'])) {
            $update = array();
            $update['id']            = $_POST['sweeporder_id'];
            $update['message']       = trim($_POST['message']) ? trim($_POST['message']) : 0;
            $update['phone_require'] = trim($_POST['phone_require']) ? trim($_POST['phone_require']) : 0;
            Model()->table('sweepstakes_orders')->update($update);
        }
        //验证是否存在收货地址
        $address_options = intval($_POST['address_id']);
        if ($address_options <= 0){
            output_error('请选择收货人地址');
        }
        $address_info = Model('address')->getOneAddress($address_options);
        if (empty($address_info)){
            output_error('收货人地址信息错误');
        }
        //保存买家收货地址,添加订单收货地址
        if ($address_info){
            $address_array      = array();
            $address_array['point_orderid']     = $param['point_orderid'];
            $address_array['point_truename']    = $address_info['true_name'];
            $address_array['point_areaid']      = $address_info['area_id'];
            $address_array['point_areainfo']    = $address_info['area_info'];
            $address_array['point_address']     = $address_info['address'];
            $address_array['point_telphone']    = $address_info['tel_phone'];
            $address_array['point_mobphone']    = $address_info['mob_phone'];
            Model('pointorder')->addPointOrderAddress1($address_array);
        }
        //获取当前抽奖id
        $tmpArray = Model()->table('sweepstakes_orders')->find($param['point_orderid']);
        output_data(array('success' => 1, 'sweepstakesId' => $tmpArray['sweepstakes_id']));
    }

    /**
     * 更换收货地址
     */
    public function change_addressOp() {
        $logic_buy = Logic('buy');
        if (empty($_POST['city_id'])) {
            $_POST['city_id'] = $_POST['area_id'];
        }
        
        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);
        if(!empty($data) && $data['state'] == 'success' ) {
            output_data($data);
        } else {
            output_error('地址修改失败');
        }
    }
}
