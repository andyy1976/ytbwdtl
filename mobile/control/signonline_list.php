<?php
/**
 * 会员内推升级申请列表
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class signonline_listControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 订单列表
     */
    public function order_listOp() {

        $model_sign_online = Model('sign_online');
        $condition = array();
        $condition['submit_member_id'] = $this->member_info['member_id'];

        if ($_POST['state_type'] == 'state_fail') {
            $condition['update_status'] = '1';
        }
        if ($_POST['state_type'] == 'state_uncheck') {
            $condition['update_status'] = '0';
        }
        if ($_POST['state_type'] == 'state_success') {
            $condition['update_status'] = '2';
        }

        $signonline_list_array = $model_sign_online->getList($condition, $this->page, 'add_time desc', '*');
        $update_level_array = array(2=>'端口代理',3=>'区/县代',4=>'市代',5=>'省代'); 
        foreach ($signonline_list_array as $key => $value) {
            switch ($value['update_status']) {
                case '0':
                    $signonline_list_array[$key]['update_status'] = '等待审核';
                    break;
                case '1':
                    $signonline_list_array[$key]['update_status'] = '审核失败';
                    break;                
                case '2':
                    $signonline_list_array[$key]['update_status'] = '审核成功';
                    break;
            }
            $signonline_list_array[$key]['update_level'] = $update_level_array[$value['update_level']];

        }
        $page_count = $model_sign_online->gettotalpage();

        output_data(array('signonline_list_array' => $signonline_list_array), mobile_page($page_count));
    }

    /**
     * 取消订单
     */
    // public function order_deleteOp() {
    //     $model_order = Model('order');
    //     $logic_order = Logic('order');
    //     $order_id = intval($_POST['order_id']);
    
    //     $condition = array();
    //     $condition['order_id'] = $order_id;
    //     $condition['buyer_id'] = $this->member_info['member_id'];
    //     $condition['order_type'] = array('in',array(1,3));
    //     $order_info = $model_order->getOrderInfo($condition);
    //     $if_allow = $model_order->getOrderOperateState('delete',$order_info);
    //     if (!$if_allow) {
    //         output_error('无权操作');
    //     }

    //     $result = $logic_order->changeOrderStateRecycle($order_info,'buyer','delete');
    //     if(!$result['state']) {
    //         output_error($result['msg']);
    //     } else {
    //         output_data('1');
    //     }
    // }

    // public function order_infoOp() {
    //     $logic_order = logic('order');
    //     $result = $logic_order->getMemberOrderInfo($_GET['order_id'],$this->member_info['member_id']);
    //     if (!$result['state']) {
    //         output_error($result['msg']);
    //     }
    //     $data = array();
    //     $data['order_id'] = $result['data']['order_info']['order_id'];
    //     $data['order_sn'] = $result['data']['order_info']['order_sn'];
    //     $data['store_id'] = $result['data']['order_info']['store_id'];
    //     $data['store_name'] = $result['data']['order_info']['store_name'];
    //     $data['add_time'] = date('Y-m-d H:i:s',$result['data']['order_info']['add_time']);
    //     $data['payment_time'] = $result['data']['order_info']['payment_time'] ? date('Y-m-d H:i:s',$result['data']['order_info']['payment_time']) : '';
    //     $data['shipping_time'] = $result['data']['order_info']['extend_order_common']['shipping_time'] ? date('Y-m-d H:i:s',$result['data']['order_info']['extend_order_common']['shipping_time']) : '';
    //     $data['finnshed_time'] = $result['data']['order_info']['finnshed_time'] ? date('Y-m-d H:i:s',$result['data']['order_info']['finnshed_time']): '';
    //     $data['order_amount'] = ncPriceFormat($result['data']['order_info']['order_amount']);
    //     $data['shipping_fee'] = ncPriceFormat($result['data']['order_info']['shipping_fee']);
    //     $data['real_pay_amount'] = ncPriceFormat($result['data']['order_info']['order_amount']);
    //     $data['state_desc'] = $result['data']['order_info']['state_desc'];
    //     $data['payment_name'] = $result['data']['order_info']['payment_name'];
    //     $data['order_message'] = $result['data']['order_info']['extend_order_common']['order_message'];
    //     $data['reciver_phone'] = $result['data']['order_info']['buyer_phone'];
    //     $data['reciver_name'] = $result['data']['order_info']['extend_order_common']['reciver_name'];
    //     $data['reciver_addr'] = $result['data']['order_info']['extend_order_common']['reciver_info']['address'];
    //     $data['store_member_id'] = $result['data']['order_info']['extend_store']['member_id'];
    //     $data['store_phone'] = $result['data']['order_info']['extend_store']['store_phone'];
    //     $data['order_tips'] = $result['data']['order_info']['order_state'] == ORDER_STATE_NEW ? '请于'.ORDER_AUTO_CANCEL_TIME.'小时内完成付款，逾期未付订单自动关闭' : '';
    //     $_tmp = $result['data']['order_info']['extend_order_common']['invoice_info'];
    //     $_invonce = '';
    //     if (is_array($_tmp) && count($_tmp) > 0) {
    //         foreach ($_tmp as $_k => $_v) {
    //             $_invonce .= $_k.'：'.$_v.' ';
    //         }
    //     }
    //     $_tmp = $result['data']['order_info']['extend_order_common']['promotion_info'];
    //     $data['promotion'] = array();
    //     if(!empty($_tmp)){
    //         $pinfo = unserialize($_tmp);
    //         if (is_array($pinfo) && $pinfo){
    //             foreach ($pinfo as $pk => $pv){
    //                 if (!is_array($pv) || !is_string($pv[1]) || is_array($pv[1])) {
    //                     $pinfo = array();
    //                     break;
    //                 }
    //                 $pinfo[$pk][1] = strip_tags($pv[1]);
    //             }
    //             $data['promotion'] = $pinfo;
    //         }
    //     }
        
    //     $data['invoice'] = rtrim($_invonce);
    //     $data['if_deliver'] = $result['data']['order_info']['if_deliver'];
    //     $data['if_buyer_cancel'] = $result['data']['order_info']['if_buyer_cancel'];
    //     $data['if_refund_cancel'] = $result['data']['order_info']['if_refund_cancel'];
    //     $data['if_receive'] = $result['data']['order_info']['if_receive'];
    //     $data['if_evaluation'] = $result['data']['order_info']['if_evaluation'];
    //     $data['if_lock'] = $result['data']['order_info']['if_lock'];
    //     $data['goods_list'] = array();
    //     foreach ($result['data']['order_info']['goods_list'] as $_k => $_v) {
    //         $data['goods_list'][$_k]['rec_id'] = $_v['rec_id'];
    //         $data['goods_list'][$_k]['goods_id'] = $_v['goods_id'];
    //         $data['goods_list'][$_k]['goods_name'] = $_v['goods_name'];
    //         $data['goods_list'][$_k]['goods_price'] = ncPriceFormat($_v['goods_price']);
    //         $data['goods_list'][$_k]['goods_num'] = $_v['goods_num'];
    //         $data['goods_list'][$_k]['goods_spec'] = $_v['goods_spec'];
    //         $data['goods_list'][$_k]['image_url'] = $_v['image_240_url'];
    //         $data['goods_list'][$_k]['refund'] = $_v['refund'];
    //     }
    //     $data['zengpin_list'] = array();
    //     foreach ($result['data']['order_info']['zengpin_list'] as $_k => $_v) {
    //         $data['zengpin_list'][$_k]['goods_name'] = $_v['goods_name'];
    //         $data['zengpin_list'][$_k]['goods_num'] = $_v['goods_num'];
    //     }

    //     $ownShopIds = Model('store')->getOwnShopIds();
    //     $data['ownshop'] = in_array($data['store_id'], $ownShopIds);

    //     output_data(array('order_info'=>$data));
    // }

    /**
     * 修改申请
     */
    // public function update_signonline()
    // {
        
    // }
}
