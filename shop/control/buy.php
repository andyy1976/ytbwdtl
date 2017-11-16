<?php
/**
 * 购买流程
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class buyControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']){
            redirect(urlLogin('login', 'index', array('ref_url' => request_uri())));
        }
        //验证该会员是否禁止购买
        if(!$_SESSION['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }

    /**
     * 实物商品 购物车、直接购买第一步:选择收获地址和配送方式
     */
    public function buy_step1Op() {
        //虚拟商品购买分流
        $this->_buy_branch($_POST);
        $model = Model('member');
        $member = $model->getMemberInfo(array('member_id'=>$_SESSION['member_id']));
        $buy_items = $this->_parseItems($_POST['cart_id']);
       if($_POST['cart']=='judge'){
        foreach ($buy_items as $key => $value) { //李志军加入，判断购物车商品
           $model_cart = Model('cart');
           $cartgoods = $model_cart->where(array('cart_id'=>$key))->find();   //逐一获取产品ID
           if(!empty($cartgoods['goods_id'])){
            $goods_id = $cartgoods['goods_id'];
           }
        }
       }else{
        $goods_id = key($buy_items);
    }
        $quantity = current($buy_items);
        $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);

        if($goods_info['gc_id'] == 10351 && $member['member_level'] == 1 && $goods_info['goods_id']!='30587'){
                showMessage("该页面无法访问！");
            }
        if($goods_info['isdmgoods']==1){   //地面商家产品默认插入地址(如果没有)
            Tpl::output('isdmgoods',$goods_info['isdmgoods']);
            $model_addr = Model('address');
            $cs = $model_addr->where(array('member_id'=>$member['member_id']))->count();
       if($cs==0){
             $data = array('member_id'=>$member['member_id'],'true_name'=>$member['member_name'],'area_id'=>'3042','city_id'=>'289','area_info'=>'线下产品无需地址','address'=>'线下产品无需地址','tel_phone'=>'','mob_phone'=>$member['member_mobile'],'is_default'=>0,'dlyp_id'=>0,'isdm_a'=>1 );
           $model_addr->insert($data);
          }

        }
        //得到购买数据
        $logic_buy = Logic('buy');
        $result = $logic_buy->buyStep1($_POST['cart_id'], $_POST['ifcart'], $_SESSION['member_id'], $_SESSION['store_id'], $_POST['jjg'],$this->member_info['orderdiscount'],$this->member_info['level'],$_POST['ifchain']);

        // 20170321潘丙福添加
        // 跨境商品判断
        Tpl::output('isCrossBorder', $result['isCrossBorder']);

        if (!$result['state']) {
            showMessage($result['msg'], '', 'html', 'error');
        } else {
            $result = $result['data'];
        }
        // 加价购
        Tpl::output('jjgValidSkus', $result['jjgValidSkus']);
        Tpl::output('jjgStoreCosts', $result['jjgStoreCosts']);

        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        Tpl::output('store_cart_list', $result['store_cart_list']);
        Tpl::output('store_goods_total', $result['store_goods_total']);
        Tpl::output('store_goods_total_points', $result['store_goods_total_points']);//加入的。。。。。。。

        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        Tpl::output('store_premiums_list', $result['store_premiums_list']);
        Tpl::output('store_mansong_rule_list', $result['store_mansong_rule_list']);

        //返回店铺可用的代金券
        Tpl::output('store_voucher_list', $result['store_voucher_list']);

        //返回平台可用红包
        Tpl::output('rpt_list_json', json_encode($result['rpt_list']));

        //输出符合满X元包邮条件的店铺ID及包邮设置信息
        Tpl::output('cancel_calc_sid_list', $result['cancel_calc_sid_list']);

        //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        Tpl::output('freight_hash', $result['freight_list']);

        //输出用户默认收货地址
        if (!$_POST['ifchain']) {  //商城购物则删除以前线上商家地址
            if($goods_info['isdmgoods']!=1){
            $model_addr = Model('address');
            $cs = $model_addr->where(array('member_id'=>$member['member_id'],'isdm_a'=>1))->count();
            if($cs>0){ //如果购买的商品不是线下产品有则清默认的线下产品发货地址
                $model_addr->where(array('member_id'=>$member['member_id'],'isdm_a'=>1))->delete();}
        }
             Tpl::output('address_info', $result['address_info']);            
        }
        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        Tpl::output('pay_goods_list', $result['pay_goods_list']);
        Tpl::output('ifshow_offpay', $result['ifshow_offpay']);
        Tpl::output('deny_edit_payment', $result['deny_edit_payment']);

        //输出是否有门店自提支付
        Tpl::output('ifshow_chainpay', $result['ifshow_chainpay']);
        Tpl::output('chain_store_id', $result['chain_store_id']);

        //不提供增值税发票时抛出true(模板使用)
        Tpl::output('vat_deny', $result['vat_deny']);

        //增值税发票哈希值(php验证使用)
        Tpl::output('vat_hash', $result['vat_hash']);

        //输出默认使用的发票信息
        Tpl::output('inv_info', $result['inv_info']);

        //删除购物车无效商品
        $logic_buy->delCart($_POST['ifcart'], $_SESSION['member_id'], $_POST['invalid_cart']);

        //标识购买流程执行步骤
        Tpl::output('buy_step','step2');

        Tpl::output('ifcart', $_POST['ifcart']);

        Tpl::output('ifchain', $_POST['ifchain']);

        //输出会员折扣
        Tpl::output('zk_list',$result['zk_list']);

        //店铺信息
        $store_list = Model('store')->getStoreMemberIDList(array_keys($result['store_cart_list']),'store_id,member_id,store_domain,is_own_shop');
        Tpl::output('store_list',$store_list);

        $current_goods_info = current($result['store_cart_list']);
        Tpl::output('current_goods_info',$current_goods_info[0]);

        Tpl::showpage('buy_step1');
    }

    /**
     * 生成订单
     *
     */
    public function buy_step2Op() {
        $logic_buy = logic('buy');
        $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'],$this->member_info['orderdiscount'],$this->member_info['level']);
        if(!$result['state']) {
            showMessage($result['msg'], 'index.php?act=cart', 'html', 'error');
        }
        // 
        //转向到商城支付页面
        redirect('index.php?act=buy&op=pay&pay_sn='.$result['data']['pay_sn']);
    }

    public function pan_payOp()
    {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=store_joinin','html','error');
        }
        //获取订单信息
        $condition = array();
        $condition['member_id'] = array('eq', $_SESSION['member_id']);
        $condition['openshop_pay_sn'] = array('eq', $pay_sn);
        $pay_info = Model()->table('opshop_order')->where($condition)->find();
        if(empty($pay_info)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=store_joinin','html','error');
        }
        Tpl::output('pay_info',$pay_info);
        //获取支付方式
        $model_payment = Model('payment');
        $condition = array();
        $payment_list = $model_payment->getPaymentOpenList($condition);
        if (!empty($payment_list)) {
            unset($payment_list['predeposit']);
            unset($payment_list['offline']);
            unset($payment_list['alipay']);
            unset($payment_list['tenpay']);
            unset($payment_list['chinabank']);
            unset($payment_list['wxpay']);
            unset($payment_list['tlzf']);
        }
        if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式','index.php?act=store_joinin','html','error');
        }

        Tpl::output('payment_list',$payment_list);
        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('buy_step22');
    }

    /**
     * 下单时支付页面
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }

        //查询支付单信息
        $model= Model('member');
        $model_order= Model('order');
        $model_order_goods= Model('order_goods');
        $mem = $model->where(array('member_id'=>$_SESSION['member_id']))->find();
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']),true);
        if(empty($pay_info)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }
        Tpl::output('pay_info',$pay_info);

        //取子订单列表
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','*','','',array(),true);
        if (empty($order_list)) {
            showMessage('未找到需要支付的订单','index.php?act=member_order','html','error');
        }
        //获取订单云豆
        $pay_points=0;
        foreach($order_list as $key=>$value){
            //获取本次所有订单云豆，因为不同的店铺会分成不同的订单
            $pay_points += $model_order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');
            //获取每个订单号下的所有商品的总云豆
            $order_list[$key]['order_points']= $model_order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');
        }
        //取特殊类订单信息
        $this->_getOrderExtendList($order_list,$pay_points);
        $panOrderidArray = array();
        foreach ($order_list as $key => $value) {
            $ka = $value['order_id'];
            $panOrderidArray[] = $value['order_id'];
        }
		$member_fo = $model_order->getOrderGoodsInfo(array('order_id'=>$ka));
		
        if($member_fo['gc_id'] == 10351 && $mem['member_level'] >0 && $member_fo['goods_id']!='30587'){
            showDialog('无法购买!!');
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
            showMessage($panShowMessage,'index.php?act=member_order','html','error');
        }
        //20170830潘丙福添加结束 

        tpl::output('gc_id',$member_fo);
        //处理预订单重复支付问题
        if ($order_list[0]['if_buyer_repay'] && $order_list[0]['pay_sn1'] == '') {
            $pay_sn_new = Logic('buy_1')->makePaySn($_SESSION['member_id']);
            $order_pay = array();
            $order_pay['pay_sn'] = $pay_sn_new;
            $order_pay['buyer_id'] = $_SESSION['member_id'];
            $order_pay_id = $model_order->addOrderPay($order_pay);
            if (!$order_pay_id) {
                showMessage('支付失败','index.php?act=member_order','html','error');
            }
            $update = $model_order->editOrder(array('pay_sn'=>$pay_sn_new,'pay_sn1'=>$pay_sn),array('order_id'=>$order_list[0]['order_id'],'order_type'=>2));
            if (!$update) {
                showMessage('支付失败','index.php?act=member_order','html','error');
            } else {
                redirect('index.php?act=buy&op=pay&pay_sn='.$pay_sn_new);exit;
            }
        }

        //定义输出数组
        $pay = array();
        //支付提示主信息
        $pay['order_remind'] = '';
        //重新计算支付金额
        $pay['pay_amount_online'] = 0;
        $pay['pay_amount_offline'] = 0;
        //订单总支付金额(不包含货到付款)
        $pay['pay_amount'] = 0;
        //充值卡支付金额(之前支付中止，余额被锁定)
        $pay['payd_rcb_amount'] = 0;
        //奖金支付金额(之前支付中止，余额被锁定)
        $pay['payd_dis_amount'] = 0;
        //预存款支付金额(之前支付中止，余额被锁定)
        $pay['payd_pd_amount'] = 0;
        //还需在线支付金额(之前支付中止，余额被锁定)
        $pay['payd_diff_amount'] = 0;
        //账户可用金额
        $pay['member_pd'] = 0;
        $pay['member_rcb'] = 0;//充值余额
        $pay['member_dis'] = 0;//奖金余额
        $pay['pay_points'] = $pay_points;

        $logic_order = Logic('order');

        //计算相关支付金额
        foreach ($order_list as $key => $order_info) {
            if (!in_array($order_info['payment_code'],array('offline','chain'))) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay['pay_amount_online'] += $order_info['order_amount'];
                    $pay['payd_rcb_amount'] += $order_info['rcb_amount'];
                    $pay['payd_pd_amount'] += $order_info['pd_amount'];
                    $pay['payd_dis_amount'] += $order_info['dis_amount'];
                    $pay['payd_diff_amount'] += $order_info['order_amount'] - $order_info['rcb_amount'] - $order_info['pd_amount'] - $order_info['dis_amount'];
                }
                $pay['pay_amount'] += $order_info['order_amount'];
            } else {
                $pay['pay_amount_offline'] += $order_info['order_amount'];
            }
            //显示支付方式
            if ($order_info['payment_code'] == 'offline') {
                $order_list[$key]['payment_type'] = '货到付款';
            } elseif ($order_info['payment_code'] == 'chain') {
                $order_list[$key]['payment_type'] = '门店支付';
            } else {
                $order_list[$key]['payment_type'] = '在线支付';
            }
        }
        if ($order_info['chain_id'] && $order_info['payment_code'] == 'chain') {
            $order_list[0]['order_remind'] = '下单成功，请在'.CHAIN_ORDER_PAYPUT_DAY.'日内前往门店提货，逾期订单将自动取消。';
            $flag_chain = 1;
        }

        Tpl::output('order_list',$order_list);

        //如果线上线下支付金额都为0，转到支付成功页

        
        // if (empty($pay['pay_amount_online']) && empty($pay['pay_amount_offline'])) {
        //     redirect('index.php?act=buy&op=pay_ok&pay_sn='.$pay_sn.'&is_chain='.$flag_chain.'&pay_amount='.ncPriceFormat($order_info['order_amount']));
        // }

        //是否显示站内余额操作(如果以前没有使用站内余额支付过且非货到付款)
        $pay['if_show_pdrcb_select'] = ($pay['pay_amount_offline'] == 0 && $pay['payd_rcb_amount'] == 0 && $pay['payd_pd_amount'] == 0 && $pay['payd_dis_amount'] == 0);

        //输出订单描述
        if (empty($pay['pay_amount_online'])) {
            $pay['order_remind'] = '下单成功，我们会尽快为您发货，请保持电话畅通。';
        } elseif (empty($pay['pay_amount_offline'])) {
            $pay['order_remind'] = '请您在'.(ORDER_AUTO_CANCEL_TIME*60).'分钟内完成支付，逾期订单将自动取消。 ';
        } else {
            $pay['order_remind'] = '部分商品需要在线支付，请您在'.(ORDER_AUTO_CANCEL_TIME*60).'分钟内完成支付，逾期订单将自动取消。';
        }
        if (!empty($order_list[0]['order_remind'])) {
            $pay['order_remind'] = $order_list[0]['order_remind'];
        }

        if ($pay['pay_amount_online'] > 0) {
            //显示支付接口列表
            $model_payment = Model('payment');
            $condition = array();
            $payment_list = $model_payment->getPaymentOpenList($condition);
			
            if (!empty($payment_list)) {
                unset($payment_list['predeposit']);
                unset($payment_list['offline']);
                unset($payment_list['alipay']);
                unset($payment_list['tenpay']);
                unset($payment_list['chinabank']);
               // unset($payment_list['wxpay']);
            }
            if (empty($payment_list)) {
                showMessage('暂未找到合适的支付方式','index.php?act=member_order','html','error');
            }

            Tpl::output('payment_list',$payment_list);
        }
        if ($pay['if_show_pdrcb_select']) {
            //显示预存款、支付密码、充值卡
            $available_predeposit = $available_rc_balance = 0;
            $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
  if($_SESSION['member_level']=='5'){
                if (floatval($buyer_info['province_predeposit']) > 0) {
                    $pay['member_pd'] = $buyer_info['province_predeposit'];
                }
            }else{
            if (floatval($buyer_info['available_predeposit']) > 0) {
                $pay['member_pd'] = $buyer_info['available_predeposit'];
            }
}
            if (floatval($buyer_info['member_predeposit']) > 0) {   //改动过
                $pay['member_rcb'] = $buyer_info['member_predeposit'];
            }
            //加入的。。。。。。。。。。。。
            if (floatval($buyer_info['distributor_predeposit']) > 0) {
                $pay['member_dis'] = $buyer_info['distributor_predeposit'];
            }
            if (floatval($buyer_info['member_points']) > 0) {
                $pay['member_poi'] = $buyer_info['member_points'];
            }
            $pay['member_paypwd'] = $buyer_info['member_paypwd'] ? true : false;
        }

        Tpl::output('pay',$pay);

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('buy_step2');
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
                showMessage('未找到需要支付的订单','index.php?act=member_order','html','error');
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

    /**
     * 预存款充值下单时支付页面
     */
    public function pd_payOp() {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('para_error'),urlMember('predeposit'),'html','error');
        }

        //查询支付单信息
        $model_order= Model('predeposit');
        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$_SESSION['member_id']));
        if(empty($pd_info)){
            showMessage(Language::get('para_error'),'','html','error');
        }
        if (intval($pd_info['pdr_payment_state'])) {
            showMessage('您的订单已经支付，请勿重复支付',urlMember('predeposit'),'html','error');
        }
        Tpl::output('pdr_info',$pd_info);
        //限额
        $money=$pd_info['pdr_amount'];
        $type=$pd_info['pdr_type'];
        $pd_recharge = Model('pd_recharge');
        $points_log=Model('points_log');
      
        $recharge_count=$points_log->where(array('pl_memberid'=>$_SESSION['member_id'],'pl_addtime'=>array('gt',strtotime(date("Y-m-d"))),'pl_stage'=>'rechart'))->sum('pl_points');
        if($recharge_count>1000000 && $_SESSION['member_id']!='146986'){
            showMessage('每日限100万！！！');
        }
       if($type=='1' && ($recharge_count+$money*20)>1000000 && $_SESSION['member_id']!='146986'){
            showMessage('每日限100万！！！');
        }
        if($type=='2' && ($recharge_count+$money*12.5)>1000000 && $_SESSION['member_id']!='146986'){
            showMessage('每日限100万！！！');
        }
        //购买5%云豆，判断当天是否满1千  7/14 START
        
        if($type!=null){

            $where['pdr_member_id']=$_SESSION['member_id'];
            $where['pdr_type']='2';
            $where['pdr_payment_state']='1';
            $where['pdr_add_time']=array('gt',strtotime(date('Y-d-m')));
            $recharge_amount=$pd_recharge->where($where)->sum('pdr_amount');
            $recharge_amount=empty($recharge_amount)?0:$recharge_amount;
            $amount=($recharge_count-$recharge_amount*12.5)/20+$money;
           
            if($amount>1000 && $type=='1'){
                showMessage('每日限购1千金额！！！');
            }
            if($money<50 && $type=='1'){
                showMessage('每日购买至少50金额！！！');
            }
            if($money<50 && $type=='2'){
                showMessage('每日购买至少50金额！！！');
            }                  
            
        } 
        //显示支付接口列表
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = array('not in',array('wxpay','offline','predeposit','chinabank','tenpay','alipay'));
        $condition['payment_state'] = 1;
        $payment_list = $model_payment->getPaymentList($condition);
        if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式',urlMember('predeposit'),'html','error');
        }
        Tpl::output('payment_list',$payment_list);

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('predeposit_pay');
    }

    /**
     * 支付成功页面
     */
    public function pay_okOp() {

        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }

        //查询支付单信息
        $mo = model();
        $model_order= Model('order');
        $member=Model('member');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']));
        $ord_info = $model_order->getOrderInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']));//查询用户信息     加入的
        $order_goods_list = $model_order->getOrderGoodsInfo(array('order_id'=>$ord_info['order_id']));//商品訂單表       加入的
        if(empty($pay_info)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }
        if($ord_info['order_state']  == 20){
        if($order_goods_list['gc_id']==10351){
            //给1 2级分成
                $member_id=$_SESSION['member_id'];
                 $seve=$member->where(array('member_id'=>$member_id))->find();
               
                $pd_log = model('pd_log');
                $percent=Model('chief');
                $orders = $model_order->getOrderInfo(array('pay_sn'=>$pay_sn));
                        $order_goods = $model_order->getOrderGoodsInfoArray(array('order_id'=>$orders['order_id']));
                        //升级3000端口
                        if($order_goods[0]['goods_id']=="30587"){
                            $pd_log=Model("pd_log");
                            $member=Model('member');
                            $percent=Model('chief');
                            $member_id=$_SESSION['member_id'];
                            // $arra['member_points']=array('exp','member_points+3000');
                            $arra['member_level']='2';
                            $arra['member_time']=time();
                            $arra['portid']=$member_id;
                           
                            if($seve['member_level'] < 2 ){ 
                                //赠送3000积分                                                           
                                $arra['subsidiary_id']=$seve['portid'];
                                $arra['frozen_agentotal']='100000';
                                $arra['free']='0';
                                $update_member=$member->where(array('member_id'=>$member_id))->update($arra);
                                //各级代理分成
                                find_agent($member_id);
                                // $data_point=array('lg_member_id'=>$member_id,'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'3000','lg_add_time'=>time(),'lg_desc'=>'会员激活端口赠送3000积分');
                                // $update_point=$pd_log->insert($data_point);
                                $order_money=$order_info['order_amount'];
                                $chiefs=$percent->getfby_id(20,'chief');                    
                                $arr=get_parent_info($member_id);  
                                if(is_array($arr)){
                                                   
                                    $mount=3000* $chiefs;
                                    $moneys=$arr["distributor_predeposit"]+3000* $chiefs; 
                                    $buyer_puid=$arr['member_id'];
                                    $buyer_pname=$arr['member_name']; 
                                    $avai=array('distributor_predeposit'=>$moneys); 
                                    $update_avai=$member->where(array('member_id'=>$buyer_puid) )->update($avai); 
                                    $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'会员激活端口产生分销');
                                    $update_data=$pd_log->insert($data);
                                }
                                $arrs=get_parent_info($buyer_puid); 
                                if(is_array($arrs)){     
                                    $buyer_pid=$arrs['member_id'];
                                    $buyer_pname=$arrs['member_name'];
                                    $chiefs=$percent->getfby_id(21,'chief');
                                    $mount=3000* $chiefs;
                                    $moneys=$arrs["distributor_predeposit"]+3000*$chiefs;                    
                                    $avai=array('distributor_predeposit'=>$moneys);
                                    $update_avais=$member->where(array('member_id'=>$buyer_pid))->update($avai);                                    
                                    $data=array('lg_member_id'=>$buyer_pid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'会员激活端口产生分销');
                                    //资金变动记录
                                    $update_datas=$pd_log->insert($data);                
                                }
                            }      
                        }
                $arras['member_points']=array('exp','member_points+500');
                $arras['member_time']=time();
                $arras['member_level']='1';
                $arras['free']='0';
                $mems = $member->getMemberInfo(array('member_id'=>$_SESSION['member_id']));
                if($mems['member_level'] ==0){
                    $memberarr=$member->where(array('member_id'=>$_SESSION['member_id']))->update($arras);
                    chief_card($mems['member_name']);                     
                //赠送500云豆
                    $data_point=array('lg_member_id'=>$member_id,'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500云豆');
                    $update_point=$pd_log->insert($data_point);
                    $order_money=$_GET['pay_amount'];
                    $chiefs=$percent->where(array('id'=>11))->find();

                    // $las =  $mo->table('orders')->where(array('order_id'=>$ord_info['order_id']))->update(array('order_state'=>30));
                    $arr=get_parent_info($_SESSION['member_id']);  
                    
                        if(is_array($arr)){
                            $mount= 500*$chiefs['chief'];
                                   
                            $buyer_puid=$arr['member_id'];                    
                            $buyer_pname=$arr['member_name']; 
                            

                            $member->where(array('member_id'=>$buyer_puid))->setInc('distributor_predeposit',$mount);
                            $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_type'=>'distribution','lg_desc'=>'激活会员下级购买提成');
                            $pd_log->insert($data);
                           
                        }
                        
                        $arr=get_parent_info($buyer_puid); 
                        if(is_array($arr)){             
                            $buyer_puid=$arr['member_id'];
                            $buyer_pname=$arr['member_name'];
                            $chiefs=$percent->where(array('id'=>12))->find();
                            $mount=500*$chiefs['chief'];
                                                             
                            $member->where(array('member_id'=>$buyer_puid))->setInc('distributor_predeposit',$mount);
                            $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_av_amount'=>$mount,'lg_type'=>'distribution','lg_add_time'=>time(),'lg_desc'=>'激活会员下级购买提成');
                            //资金变动记录
                            $pd_log->insert($data);    
                            
                        }
                }        
            }  
        }
        if(!empty($order_goods_list['goods_id'])){  //查询产品信息(是否是地面商家产品),如果是则更改为已经发货状态
        $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($order_goods_list['goods_id']);
        if($goods_info['isdmgoods']==1){
         $data = array('is_dm'=>'2');
         $model_order->table('orders')->where(array('order_id'=>$ord_info['order_id']))->update($data);}}
        //发消息给店铺商家
        $sell=Model('seller');
        
        $sell_info=$sell->where(array('store_id'=>$ord_info['store_id']))->find();
       
        $members_info=$member->where(array('member_id'=>$sell_info['member_id']))->find();
        // if($_SESSION['member_id']==10072){
           
        //     echo $members_info['member_mobile'];
        //     exit;
        // }
        send($members_info['member_mobile'],$ord_info['order_sn'].'|'.$ord_info['store_id']);
 
        Tpl::output('pay_info',$pay_info);

        Tpl::output('buy_step','step4');
        Tpl::showpage('buy_step3');
    }

    /**
     * 加载买家收货地址
     *
     */
    public function load_addrOp() {
        $model_addr = Model('address');
        //如果传入ID，先删除再查询
        if (!empty($_GET['id']) && intval($_GET['id']) > 0) {
            $model_addr->delAddress(array('address_id'=>intval($_GET['id']),'member_id'=>$_SESSION['member_id']));
        }
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        if (!C('delivery_isuse')) {
            $condition['dlyp_id'] = 0;
            $order = 'dlyp_id asc,address_id desc';
        }
        $list = $model_addr->getAddressList($condition,$order);
        Tpl::output('address_list',$list);
        Tpl::showpage('buy_address.load','null_layout');
    }

    /**
     * 载入门店自提点
     */
    public function load_chainOp() {
        $list = Model('chain')->getChainList(array('area_id'=>intval($_GET['area_id']),'store_id'=>intval($_GET['store_id'])),
                'chain_id,chain_name,area_info,chain_address');
        echo $_GET['callback'].'('.json_encode($list).')';
    }

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function change_addrOp() {
        $logic_buy = Logic('buy');
        if (empty($_POST['city_id'])) {
            $_POST['city_id'] = $_POST['area_id'];
        }

        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $_SESSION['member_id']);

        if(!empty($data)) {
            exit(json_encode($data));
        } else {
            exit('error');
        }
    }

    //根据门店自提站ID计算商品库存
    public function change_chainOp() {
        $logic_buy = Logic('buy');
        $data = $logic_buy->changeChain($_POST['chain_id'],$_POST['product']);
        if(!empty($data)) {
            exit(json_encode($data));
        } else {
            exit('error');
        }
    }

     /**
      * 添加新的收货地址
      *
      */
     public function add_addrOp(){
        $model_addr = Model('address');
        if (chksubmit()){
            $count = $model_addr->getAddressCount(array('member_id'=>$_SESSION['member_id']));
            if ($count >= 20) {
                exit(json_encode(array('state'=>false,'msg'=>'最多允许添加20个有效地址')));
            }
            //验证表单信息
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["true_name"],"require"=>"true","message"=>Language::get('cart_step1_input_receiver')),
                array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('cart_step1_choose_area'))
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                $error = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($error) : $error;
                exit(json_encode(array('state'=>false,'msg'=>$error)));
            }
            $data = array();
            $data['member_id'] = $_SESSION['member_id'];
            $data['true_name'] = $_POST['true_name'];
            $data['area_id'] = intval($_POST['area_id']);
            $data['city_id'] = intval($_POST['city_id']);
            $data['area_info'] = $_POST['region'];
            $data['address'] = $_POST['address'];
            $data['tel_phone'] = $_POST['tel_phone'];
            $data['mob_phone'] = $_POST['mob_phone'];
            $insert_id = $model_addr->addAddress($data);
            if ($insert_id){
                exit(json_encode(array('state'=>true,'addr_id'=>$insert_id)));
            }else {
                exit(json_encode(array('state'=>false,'msg'=>'新地址添加失败')));
            }
        } else {
            Tpl::showpage('buy_address.add','null_layout');
        }
     }

     /**
      * 添加新的门店自提点
      *
      */
     public function add_chainOp(){
         Tpl::showpage('buy_address.add_chain','null_layout');
     }

    /**
     * 加载买家发票列表，最多显示10条
     *
     */
    public function load_invOp() {
        $logic_buy = Logic('buy');

        $condition = array();
        if ($logic_buy->buyDecrypt($_GET['vat_hash'], $_SESSION['member_id']) == 'allow_vat') {
        } else {
            Tpl::output('vat_deny',true);
            $condition['inv_state'] = 1;
        }
        $condition['member_id'] = $_SESSION['member_id'];

        $model_inv = Model('invoice');
        //如果传入ID，先删除再查询
        if (intval($_GET['del_id']) > 0) {
            $model_inv->delInv(array('inv_id'=>intval($_GET['del_id']),'member_id'=>$_SESSION['member_id']));
        }
        $list = $model_inv->getInvList($condition,10);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
               if ($value['inv_state'] == 1) {
                   $list[$key]['content'] = '普通发票'.' '.$value['inv_title'].' '.$value['inv_content'];
               } else {
                   $list[$key]['content'] = '增值税发票'.' '.$value['inv_company'].' '.$value['inv_code'].' '.$value['inv_reg_addr'];
               }
            }
        }
        Tpl::output('inv_list',$list);
        Tpl::showpage('buy_invoice.load','null_layout');
    }

     /**
      * 新增发票信息
      *
      */
     public function add_invOp(){
        $model_inv = Model('invoice');
        if (chksubmit()){
            //如果是增值税发票验证表单信息
            if ($_POST['invoice_type'] == 2) {
                if (empty($_POST['inv_company']) || empty($_POST['inv_code']) || empty($_POST['inv_reg_addr'])) {
                    exit(json_encode(array('state'=>false,'msg'=>Language::get('nc_common_save_fail','UTF-8'))));
                }
            }
            $data = array();
            if ($_POST['invoice_type'] == 1) {
                $data['inv_state'] = 1;
                $data['inv_title'] = $_POST['inv_title_select'] == 'person' ? '个人' : $_POST['inv_title'];
                $data['inv_content'] = $_POST['inv_content'];
            } else {
                $data['inv_state'] = 2;
                $data['inv_company'] = $_POST['inv_company'];
                $data['inv_code'] = $_POST['inv_code'];
                $data['inv_reg_addr'] = $_POST['inv_reg_addr'];
                $data['inv_reg_phone'] = $_POST['inv_reg_phone'];
                $data['inv_reg_bname'] = $_POST['inv_reg_bname'];
                $data['inv_reg_baccount'] = $_POST['inv_reg_baccount'];
                $data['inv_rec_name'] = $_POST['inv_rec_name'];
                $data['inv_rec_mobphone'] = $_POST['inv_rec_mobphone'];
                $data['inv_rec_province'] = $_POST['vregion'];
                $data['inv_goto_addr'] = $_POST['inv_goto_addr'];
            }
            $data['member_id'] = $_SESSION['member_id'];
            //转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
            $insert_id = $model_inv->addInv($data);
            if ($insert_id) {
                exit(json_encode(array('state'=>'success','id'=>$insert_id)));
            } else {
                exit(json_encode(array('state'=>'fail','msg'=>Language::get('nc_common_save_fail','UTF-8'))));
            }
        } else {
            Tpl::showpage('buy_address.add','null_layout');
        }
     }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwdOp(){
        if (empty($_GET['password'])) exit('0');
        $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_paypwd');
        echo ($buyer_info['member_paypwd'] != '' && $buyer_info['member_paypwd'] === md5($_GET['password'])) ? '1' : '0';
    }

    /**
     * F码验证
     */
    public function check_fcodeOp() {
        $result = logic('buy')->checkFcode($_GET['goods_id'], $_GET['fcode']);
        echo $result['state'] ? '1' : '0';
        exit;
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }
        return $buy_items;
    }

    /**
     * 购买分流
     */
    private function _buy_branch($post) {
        if (!$post['ifcart']) {
            //取得购买商品信息
            $buy_items = $this->_parseItems($post['cart_id']);
            $goods_id = key($buy_items);
            $quantity = current($buy_items);

            $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);
            if ($goods_info['is_virtual']) {
                redirect('index.php?act=buy_virtual&op=buy_step1&goods_id='.$goods_id.'&quantity='.$quantity);
            }
        }
    }
        /*
    查询地址  新加入 熊磊
     */
    public function addr_infoOp(){
        $model = model('address');
        $address_id = $_GET['address_id'];

        $address_info = $model->getAddressInfo(array('address_id'=>$address_id));
        exit(json_encode(array('state'=>true,'result'=>$address_info)));
    }

}
