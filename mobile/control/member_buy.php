<?php
/**
 * 购买
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_buyControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op() {
        $cart_id = explode(',', $_POST['cart_id']);
        $logic_buy = logic('buy');
        //得到会员等级
        
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if(!$member_info['is_buy']){
            output_error('您账户无法购物！！');exit;
        }
        if ($member_info){
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
            $member_discount = $member_gradeinfo['orderdiscount'];
            $member_level = $member_gradeinfo['level'];
        } else {
            $member_discount = $member_level = 0;
        }

        //得到购买数据
        //20170824潘丙福修改--调整预售商品的相关价格、云豆
        $result = $logic_buy->buyStep1($cart_id, $_POST['ifcart'], $this->member_info['member_id'], $this->member_info['store_id'],null,$member_discount,$member_level);
        //潘丙福添加是否为跨境商品、预售商品
        $panIsCrossBorder = $result['isCrossBorder'];
        $panIsBookGoods   = $result['isBookGoods'];

        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            $result = $result['data'];
        }
        
        if (intval($_POST['address_id']) > 0) {
            $result['address_info'] = Model('address')->getDefaultAddressInfo(array('address_id'=>intval($_POST['address_id']),'member_id'=>$this->member_info['member_id']));
        }
        if ($result['address_info']) {
            $data_area = $logic_buy->changeAddr($result['freight_list'], $result['address_info']['city_id'], $result['address_info']['area_id'], $this->member_info['member_id']);
            if(!empty($data_area) && $data_area['state'] == 'success' ) {
                if (is_array($data_area['content'])) {
                    foreach ($data_area['content'] as $store_id => $value) {
                        $data_area['content'][$store_id] = ncPriceFormat($value);
                    }
                }
            } else {
                output_error('地区请求失败');
            }
        }

        //整理数据
        $store_cart_list = array();
        //20170824潘丙福修改开始--预售商品金额核算
        $store_total_list = $result['store_goods_total_1'];
        //20170824潘丙福修改结束
        //20170824潘丙福修改开始--预售商品云豆核算
        $store_total_points_list = $result['store_goods_total_points'];//加入的。。。。。。。。。
        //20170824潘丙福修改结束
        foreach ($result['store_cart_list'] as $key => $value) {
            $store_cart_list[$key]['goods_list'] = $value;
            if($value[0]['isdmgoods']==1){  //判断线下商家产品,
            $goods_flag = 1;
            $model_addr = Model('address');
             $cs = $model_addr->where(array('member_id'=>$member_info['member_id']))->count();
                   if($cs==0){
             $data = array('member_id'=>$member_info['member_id'],'true_name'=>$member_info['member_name'],'area_id'=>'3042','city_id'=>'289','area_info'=>'线下产品无需地址','address'=>'线下产品无需地址','tel_phone'=>'','mob_phone'=>$member_info['member_mobile'],'is_default'=>0,'dlyp_id'=>0,'isdm_a'=>1 );
                 $model_addr->insert($data);
             }
            }else{
               $model_addr = Model('address');
               $cs = $model_addr->where(array('member_id'=>$member_info['member_id'],'isdm_a'=>1))->find();
               if($cs){
        $model_addr->where(array('member_id'=>$member_info['member_id'],'isdm_a'=>1))->delete();

                 }   
            }
            $store_cart_list[$key]['store_goods_total'] = $result['store_goods_total'][$key];
            $store_cart_list[$key]['store_goods_total_points'] = $result['store_goods_total_points'][$key];//加入的。。。。。。。。。
            $store_cart_list[$key]['store_mansong_rule_list'] = $result['store_mansong_rule_list'][$key];

            if (is_array($result['store_voucher_list'][$key]) && count($result['store_voucher_list'][$key]) > 0) {
                reset($result['store_voucher_list'][$key]);
                $store_cart_list[$key]['store_voucher_info'] = current($result['store_voucher_list'][$key]);
                $store_cart_list[$key]['store_voucher_info']['voucher_price'] = ncPriceFormat($store_cart_list[$key]['store_voucher_info']['voucher_price']);
                $store_total_list[$key] -= $store_cart_list[$key]['store_voucher_info']['voucher_price'];
            } else {
                $store_cart_list[$key]['store_voucher_info'] = array();
            }

            $store_cart_list[$key]['store_voucher_list'] = $result['store_voucher_list'][$key];
            if(!empty($result['cancel_calc_sid_list'][$key])) {
                $store_cart_list[$key]['freight'] = '0';
                $store_cart_list[$key]['freight_message'] = $result['cancel_calc_sid_list'][$key]['desc'];
            } else {
                $store_cart_list[$key]['freight'] = '1';
            }
            $store_cart_list[$key]['store_name'] = $value[0]['store_name'];
        }

        $buy_list = array();
        $buy_list['isdmflag'] = $goods_flag; 
        $buy_list['store_cart_list'] = $store_cart_list;
        $buy_list['freight_hash'] = $result['freight_list'];
        $buy_list['address_info'] = $result['address_info'];
        $buy_list['ifshow_offpay'] = $result['ifshow_offpay'];
        $buy_list['vat_hash'] = $result['vat_hash'];
        $buy_list['inv_info'] = $result['inv_info'];
        $buy_list['available_predeposit'] = $result['available_predeposit'];
        $buy_list['available_rc_balance'] = $result['member_predeposit'];//修改。。。。。。。。。。。
        if (is_array($result['rpt_list']) && !empty($result['rpt_list'])) {
            foreach ($result['rpt_list'] as $k => $v) {
                unset($result['rpt_list'][$k]['rpacket_id']);
                unset($result['rpt_list'][$k]['rpacket_end_date']);
                unset($result['rpt_list'][$k]['rpacket_owner_id']);
                unset($result['rpt_list'][$k]['rpacket_code']);
            }
        }
        $buy_list['rpt_list'] = $result['rpt_list'] ? $result['rpt_list'] : array();
        $buy_list['zk_list'] = $result['zk_list'];

        if ($data_area['content']) {
            $store_total_list = Logic('buy_1')->reCalcGoodsTotal($store_total_list,$data_area['content'],'freight');
            //返回可用平台红包
            $result['rpt_list'] = Logic('buy_1')->getStoreAvailableRptList($this->member_info['member_id'],array_sum($store_total_list),'rpacket_limit desc');
            reset($result['rpt_list']);
            if (is_array($result['rpt_list']) && count($result['rpt_list']) > 0) {
                $result['rpt_info'] = current($result['rpt_list']);
                unset($result['rpt_info']['rpacket_id']);
                unset($result['rpt_info']['rpacket_end_date']);
                unset($result['rpt_info']['rpacket_owner_id']);
                unset($result['rpt_info']['rpacket_code']);
            }
        }
        $buy_list['order_amount'] = ncPriceFormat(array_sum($store_total_list)-$result['rpt_info']['rpacket_price']);
        $buy_list['rpt_info'] = $result['rpt_info'] ? $result['rpt_info'] : array();
        $buy_list['address_api'] = $data_area ? $data_area : '';

        foreach ($store_total_list as $store_id => $value) {
            $store_total_list[$store_id] = ncPriceFormat($value);
        }
       
        $buy_list['store_final_total_list'] = $store_total_list;
        $buy_list['store_final_total_points_list'] = $store_total_points_list;//加入的。。。。。。。
        //潘丙福添加是否为跨境商品订单、预售订单
        $buy_list['isCrossBorder'] = $panIsCrossBorder;
        $buy_list['isBookGoods']   = $panIsBookGoods;
        output_data($buy_list);
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buy_step2Op() {
        $param = array();
        $param['flag'] = $_POST['flag'];
        $param['ifcart'] = $_POST['ifcart'];
        $param['cart_id'] = explode(',', $_POST['cart_id']);
        $param['address_id'] = $_POST['address_id'];
        $param['vat_hash'] = $_POST['vat_hash'];
        $param['offpay_hash'] = $_POST['offpay_hash'];
        $param['offpay_hash_batch'] = $_POST['offpay_hash_batch'];
        $param['pay_name'] = $_POST['pay_name'];
        $param['invoice_id'] = $_POST['invoice_id'];
        $param['rpt'] = $_POST['rpt'];
        $param['buyer_cardid'] = $_POST['buyer_cardid'];
        //20170828潘丙福添加开始--增加字段
        if ($_POST['book_pay_type'] == 'part') {
            $param['book_pay_type'] = $_POST['book_pay_type'];
        }
        //20170828潘丙福添加结束
        //处理代金券
        $voucher = array();
        $post_voucher = explode(',', $_POST['voucher']);
        if(!empty($post_voucher)) {
            foreach ($post_voucher as $value) {
                list($voucher_t_id, $store_id, $voucher_price) = explode('|', $value);
                $voucher[$store_id] = $value;
            }
        }
        $param['voucher'] = $voucher;

        $_POST['pay_message'] = trim($_POST['pay_message'],',');
        $_POST['pay_message'] = explode(',',$_POST['pay_message']);
        $param['pay_message'] = array();
        if (is_array($_POST['pay_message']) && $_POST['pay_message']) {
            foreach ($_POST['pay_message'] as $v) {
                if (strpos($v, '|') !== false) {
                    $v = explode('|', $v);
                    $param['pay_message'][$v[0]] = $v[1];
                }
            }
        }
        $param['pd_pay'] = $_POST['pd_pay'];
        $param['rcb_pay'] = $_POST['rcb_pay'];
        $param['password'] = $_POST['password'];
        $param['fcode'] = $_POST['fcode'];
        $param['order_from'] = 2;
        $logic_buy = logic('buy');

        //得到会员等级
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($member_info){
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
            $member_discount = $member_gradeinfo['orderdiscount'];
            $member_level = $member_gradeinfo['level'];
        } else {
            $member_discount = $member_level = 0;
        }
        $result = $logic_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email'],$member_discount,$member_level);
        if(!$result['state']) {
            output_error($result['msg']);
        }
        $order_info = current($result['data']['order_list']);
        if(!empty($param['flag'])){   //更新为地面订单
         $model_order = Model('order');
         $model_order->table('orders')->where(array('order_id'=>$order_info['order_id']))->update(array('is_dm'=>'2'));
      }
        output_data(array('pay_sn' => $result['data']['pay_sn'],'payment_code'=>$order_info['payment_code'],'mid'=>$this->member_info['member_id']));
    }

    /**
     * 验证密码
     */
    public function check_passwordOp() {
        if(empty($_POST['password'])) {
            output_error('参数错误');
        }

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if($member_info['member_paypwd'] == md5($_POST['password'])) {
            output_data('1');
        } else {
            output_error('密码错误');
        }
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

    /**
     * 实物订单支付(新接口)
     */
    public function payOp() {
        //20170922潘丙福修改--获取pay_sn的方式
        // $pay_sn = $_POST['pay_sn'];
        $pay_sn = $_REQUEST['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            output_error('该订单不存在');
        }

        //查询支付单信息
        $model_order= Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$this->member_info['member_id']),true);
        if(empty($pay_info)){
            output_error('该订单不存在');
        }
    
        //取子订单列表
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','*','','',array(),true);
        if (empty($order_list)) {
            output_error('未找到需要支付的订单');
        }
        //20170822潘丙福添加开始--预售订单金额以及云豆处理
        $pay_points=0;
        $model_order_goods= Model('order_goods');
        foreach($order_list as $key=>$value){
            //获取本次所有订单云豆，因为不同的店铺会分成不同的订单
            $pay_points += $model_order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');
            //获取每个订单号下的所有商品的总云豆
            $order_list[$key]['order_points']= $model_order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');
        }
        $this->_getOrderExtendList($order_list,$pay_points);
        //20170822潘丙福添加结束

        //20170830潘丙福添加
        $panOrderidArray = array();
        foreach ($order_list as $key => $value) {
            $panOrderidArray[] = $value['order_id'];
        }
        //20170830潘丙福添加开始--判断当前订单的商品是否正常（不含有下架、审核商品）
        $panOrderidStr = implode(',', $panOrderidArray);
        $panOrderidStr = trim($panOrderidStr, ',');
        $panOrderidCondition = array();
        $panOrderidCondition['order_id'] = array('in', $panOrderidStr);
        $panOrderidGoodsArray = $model_order->getOrderGoodsIdName($panOrderidCondition, 'order_id,goods_id,goods_name');
        $panOrderidGoodsStr = null;
        foreach ($panOrderidGoodsArray as $panOrderidKey => $panOrderidValue) {
            $panOrderidGoodsStr .= $panOrderidValue['goods_id'].',';
        }
        $panOrderidGoodsStr = trim($panOrderidGoodsStr, ',');
        $panGoodsArrayCondition = array();
        $panGoodsArrayCondition['goods_id'] = array('in', $panOrderidGoodsStr);
        $panGoodsArray = Model()->table('goods')->field('goods_id,goods_name,goods_state,goods_verify')->where($panGoodsArrayCondition)->select();
        $panShowMessage = null;
        foreach ($panGoodsArray as $panGoodsArrayKey => $panGoodsArrayValue) {
            if ($panGoodsArrayValue['goods_state'] != 1 || $panGoodsArrayValue['goods_verify'] != 1) {
                $panShowMessage .= '商品"'.$panGoodsArrayValue['goods_name'].'"<br/>为下架商品，请取消包含此产品的订单再支付。<br />';
            }
        }
        if ($panShowMessage) {
            output_error($panShowMessage);
        }
        //20170830潘丙福添加结束

        //定义输出数组
        $pay = array();
        //支付提示主信息
        //订单总支付金额(不包含货到付款)
        $pay['pay_amount'] = 0;
        //充值卡支付金额(之前支付中止，余额被锁定)
        $pay['payed_rcb_amount'] = 0;
        //预存款支付金额(之前支付中止，余额被锁定)
        $pay['payed_pd_amount'] = 0;
        //还需在线支付金额(之前支付中止，余额被锁定)
        $pay['pay_diff_amount'] = 0;
        //账户可用金额
        $pay['member_available_pd'] = 0;
        $pay['member_available_rcb'] = 0;

        $logic_order = Logic('order');

        //计算相关支付金额
        foreach ($order_list as $key => $order_info) {
            if (!in_array($order_info['payment_code'],array('offline','chain'))) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay['payed_rcb_amount'] += $order_info['rcb_amount'];
                    $pay['payed_pd_amount'] += $order_info['pd_amount'];
                    $pay['pay_diff_amount'] += $order_info['order_amount'] - $order_info['rcb_amount'] - $order_info['pd_amount'];
                }
            }
        }
        if ($order_info['chain_id'] && $order_info['payment_code'] == 'chain') {
            $order_list[0]['order_remind'] = '下单成功，请在'.CHAIN_ORDER_PAYPUT_DAY.'日内前往门店提货，逾期订单将自动取消。';
            $flag_chain = 1;
        }

        //如果线上线下支付金额都为0，转到支付成功页
        // if (empty($pay['pay_diff_amount'])) {
        //     output_error('订单重复支付');
        // }

        $payment_list = Model('mb_payment')->getMbPaymentOpenList();
        if(!empty($payment_list)) {
            foreach ($payment_list as $k => $value) {
                if ($value['payment_code'] == 'wxpay') {
                    unset($payment_list[$k]);
                    continue;
                }
                unset($payment_list[$k]['payment_id']);
                unset($payment_list[$k]['payment_config']);
                unset($payment_list[$k]['payment_state']);
                unset($payment_list[$k]['payment_state_text']);
            }
        }
        //显示预存款、支付密码、充值卡
        $pay['member_points_poit'] = $this->member_info['member_points'];
        $pay['member_available_pd'] = $this->member_info['available_predeposit'];
        $pay['member_available_rcb'] = $this->member_info['member_predeposit'];//修改。。。。。。。。。。。
        $pay['member_paypwd'] = $this->member_info['member_paypwd'] ? true : false;
        //20170923潘丙福修改开始--预售商品支付尾款会生成新的pay_sn
        if ($pay_sn_new) {
            $pay['pay_sn'] = $pay_sn_new;
        } else {
            $pay['pay_sn'] = $pay_sn;
        }
        //20170923潘丙福修改结束
        $pay['payed_amount'] = ncPriceFormat($pay['payed_rcb_amount']+$pay['payed_pd_amount']);
        unset($pay['payed_pd_amount']);unset($pay['payed_rcb_amount']);
        $pay['pay_amount'] = ncPriceFormat($pay['pay_diff_amount']);
        unset($pay['pay_diff_amount']);
        $pay['member_available_pd'] = ncPriceFormat($pay['member_available_pd']);
        $pay['member_available_rcb'] = ncPriceFormat($pay['member_available_rcb']);
        $pay['payment_list'] = $payment_list ? array_values($payment_list) : array();
        output_data(array('pay_info'=>$pay));
    }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwdOp(){
        if (empty($_POST['password'])) {
            output_error('支付密码格式不正确');
        }
        $buyer_info = Model('member')->getMemberInfoByID($this->member_info['member_id'],'member_paypwd');
        if ($buyer_info['member_paypwd'] != '') {
            if ($buyer_info['member_paypwd'] === md5($_POST['password'])) {
                output_data('1');
            }
        }
        output_error('支付密码验证失败');
    }

    /**
     * F码验证
     */
    public function check_fcodeOp() {
        $goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0) {
            output_error('商品ID格式不正确');
        }
        if ($_POST['fcode'] == '') {
            output_error('F码格式不正确');
        }
        $result = logic('buy')->checkFcode($goods_id, $_POST['fcode']);
        if ($result['state']) {
            output_data('1');
        } else {
            output_error('F码验证抢购');
        }
    }

    /**
     * 特殊订单支付最后一步界面展示（目前只有预定）
     * @param unknown $order_list
     */
    private function _getOrderExtendList(& $order_list, &$pay_points) {
        //预定订单
        if ($order_list[0]['order_type'] == 2) {
            $order_info = $order_list[0];
            $result = Logic('order_book')->getOrderBookInfo($order_info);
            if (!$result['data']['if_buyer_pay']) {
                output_error('未找到需要支付的订单');
            }
            $order_list[0] = $result['data'];
            $order_list[0]['order_amount'] = $order_list[0]['pay_amount'];
            //20170822潘丙福添加开始
            $pay_points                    = $order_list[0]['pay_amount_ppoints'];
            $order_list[0]['order_points'] = $order_list[0]['pay_amount_ppoints'];
            //20170822潘丙福添加结束
            $order_list[0]['order_state']  = ORDER_STATE_NEW;
            if ($order_list[0]['if_buyer_repay']) {
                $order_list[0]['order_remind'] = '请您在 '.date('Y-m-d H:i',$order_list[0]['book_list'][1]['book_end_time']+1).' 之前完成支付，否则订单会被自动取消。';
            }
        }
    }
}
