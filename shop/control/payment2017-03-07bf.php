<?php
/**
 * 支付入口
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
FUNCTION doHttpGet ( $url, $options=null ) {
    
    static $defaults = array(
        CURLOPT_HEADER         => false
      , CURLOPT_CONNECTTIMEOUT => 10
      , CURLOPT_TIMEOUT        => 20
      , CURLOPT_MAXREDIRS      => 10
      , CURLOPT_FOLLOWLOCATION => true
      , CURLOPT_AUTOREFERER    => true
      , CURLOPT_RETURNTRANSFER => true
      , CURLOPT_SSL_VERIFYPEER => false
      , CURLOPT_SSL_VERIFYHOST => false
      , CURLOPT_ENCODING       => 'gzip,deflate'
    );

    $options = is_array($options) ? $options + $defaults : $defaults;
    $options[CURLOPT_URL] = $url;
    
    # 初始化 && 设置选项
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

FUNCTION doHttpPost ( $url, $data, $options=null ) {
    
    static $defaults = array(
        CURLOPT_HEADER         => false
      , CURLOPT_CONNECTTIMEOUT => 10
      , CURLOPT_TIMEOUT        => 20
      , CURLOPT_MAXREDIRS      => 10
      , CURLOPT_FOLLOWLOCATION => true
      , CURLOPT_AUTOREFERER    => true
      , CURLOPT_RETURNTRANSFER => true
      , CURLOPT_SSL_VERIFYPEER => false
      , CURLOPT_SSL_VERIFYHOST => false
      , CURLOPT_ENCODING       => 'gzip,deflate'
    );

    $options = is_array($options) ? $options + $defaults : $defaults;
    $options[CURLOPT_URL] = $url;
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_POSTFIELDS] = $data;
    
    # 初始化 / 设置选项 / 执行 / 关闭
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}
class paymentControl extends BaseHomeControl{

    public function __construct() {
        Language::read('common,home_layout');
    }

    /**
     * 实物商品订单
     */
    public function real_orderOp(){
        $pdr_sn = $_POST['pay_sn'];
        $payment_code = $_POST['payment_code'];
        $url = 'index.php?act=member_order';

        if(!preg_match('/^\d{18}$/',$pdr_sn)){
            showMessage('参数错误','','html','error');
        }

        //取订单列表
        $logic_payment = Logic('payment');
        $order_pay_info = $logic_payment->getRealOrderInfo($pdr_sn, $_SESSION['member_id']);

        if(!$order_pay_info['state']) {
            showMessage($order_pay_info['msg'], $url, 'html', 'error');
        }
         //获取该商品类别
        $order_goods=Model('order_goods');
        $pd['order_id']=$order_pay_info['data']['order_list'][0]['order_id'];

        $goodsinfo=$order_goods->where($pd)->find();

        $type='real_order';
        $gc_id=$goodsinfo['gc_id'];
        //商品价格
        foreach ($order_pay_info['data']['order_list'] as $key => $value) {
           $amout+=$value['order_amount'];
        }

        //站内余额支付
        $order_list = $this->_pd_pay($order_pay_info['data']['order_list'],$_POST);

        //计算本次需要在线支付（分别是含站内支付、纯第三方支付接口支付）的订单总金额
        $pay_amount = 0;
        $api_pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($order_list)) {
            foreach ($order_list as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }
                $pay_amount += $order_info['order_amount'];
            }
        }
        if (empty($api_pay_amount)) {
            redirect(SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$order_pay_info['data']['pay_sn'].'&pay_amount='.ncPriceFormat($pay_amount));
        }

        $result = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$result) {
            showMessage('更新订单信息发生错误，请重新支付', $url, 'html', 'error');
        }

        // $result = $logic_payment->getPaymentInfo($payment_code);
        if (in_array($payment_code,array('offline','predeposit')) || empty($payment_code)) {
          
            return callback(false,'系统不支持选定的支付方式');
        }
  
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
       
        if(empty($payment_info)) {
            return callback(false,'系统不支持选定的支付方式');
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
        
        if(!file_exists($inc_file)){
            return callback(false,'系统不支持选定的支付方式');
        }
        
        require_once($inc_file);
        
        // $payment_info['payment_config'] = unserialize($payment_info['payment_config']);
        // if(!$result['state']) {
        //     showMessage($result['msg'], $url, 'html', 'error');
        // }
        // $payment_info = $result['data'];

        // $order_pay_info['data']['api_pay_amount'] = ncPriceFormat($api_pay_amount);

        // //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        // if ($order_pay_info['data']['if_buyer_repay']) {
        //     $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$order_pay_info['data']['pay_id']));
        //     if (!$update) {
        //         showMessage('订单支付失败', $url, 'html', 'error');
        //     }
        //     $order_pay_info['data']['api_pay_state'] = 0;
        // }

        //转到第三方API支付
        $this->_api_pay($order_pay_info['data'], $payment_info);            
    }

    /**
     * 虚拟商品购买
     */
    public function vr_orderOp(){
        $order_sn = $_POST['order_sn'];
        $payment_code = $_POST['payment_code'];
        $url = 'index.php?act=member_vr_order';
    
        if(!preg_match('/^\d{18}$/',$order_sn)){
            showMessage('参数错误','','html','error');
        }

        //计算所需支付金额等支付单信息
        $result = Logic('payment')->getVrOrderInfo($order_sn, $_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }

        //站内余额支付
        $order_info = $this->_pd_vr_pay($result['data'],$_POST);
        if ($order_info['order_state'] == ORDER_STATE_PAY) {
            //发送兑换码到手机
            $param = array('order_id'=>$order_info['order_id'],'buyer_id'=>$order_info['buyer_id'],'buyer_phone'=>$order_info['buyer_phone'],'goods_name'=>$order_info['goods_name']);
            QueueClient::push('sendVrCode', $param);
        }
        
        //计算本次需要在线支付金额
        $api_pay_amount = 0;
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $api_pay_amount = floatval(ncPriceFormat($order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount']));
        }

        //如果所需支付金额为0，转到支付成功页
        if (empty($api_pay_amount)) {
            redirect('index.php?act=buy_virtual&op=pay_ok&order_sn='.$order_info['order_sn'].'&order_id='.$order_info['order_id'].'&order_amount='.ncPriceFormat($order_info['order_amount']));
        }

        $result = Model('vr_order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>$order_info['order_id']));
        if(!$result) {
            showMessage('更新订单信息发生错误，请重新支付', $url, 'html', 'error');
        }

        $result = Logic('payment')->getPaymentInfo($payment_code);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        $payment_info = $result['data'];

        $order_info['api_pay_amount'] = ncPriceFormat($api_pay_amount);

        //转到第三方API支付
        $this->_api_pay($order_info, $payment_info);
    }

    /**
     * 预存款充值
     */
    public function pd_orderOp(){
        $pdr_sn = $_POST['pdr_sn'];
        $payment_code = $_POST['payment_code'];
        $url = urlMember('predeposit');
       
        if(!preg_match('/^\d{18}$/',$pdr_sn)){
            showMessage('参数错误',$url,'html','error');
        }
        $type='pd_order';
        $logic_payment = Logic('payment');
        // $result = $logic_payment->getPaymentInfo($payment_code);
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);

        if(empty($payment_info)) {
            return callback(false,'系统不支持选定的支付方式');
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
        
        if(!file_exists($inc_file)){
            return callback(false,'系统不支持选定的支付方式');
        }
        $result = $logic_payment->getPdOrderInfo($pdr_sn,$_SESSION['member_id']);
        $amout=$result['data']['pdr_amount'];
        require_once($inc_file);

        
        
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {
            showMessage('该充值单不需要支付', $url, 'html', 'error');
        }
       
        //转到第三方API支付
        $this->_api_pay($result['data'], $payment_info);
    }

    /**
     * 站内余额支付(充值卡、预存款支付) 实物订单
     *
     */
    private function _pd_pay($order_list, $post) {

        if (empty($post['password'])) {
            return $order_list;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
       
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }
        if ($buyer_info['member_predeposit'] == 0) {//充值金额    改动过
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {//可用余额   改动过
            $post['pd_pay'] = null;
        }
        if($buyer_info['distributor_predeposit'] == 0){//分销奖金   改动过
            $post['dis_pay'] = null;
        }
        if (floatval($order_list[0]['rcb_amount']) > 0 || floatval($order_list[0]['pd_amount']) > 0) {
            return $order_list;
        }
        
        try {
            $model_member->beginTransaction();
            $logic_buy_1 = Logic('buy_1');
             if (!empty($post['rcb_pay'])) {
                $order_list = $logic_buy_1->rcbPay($order_list, $post, $buyer_info);
            }

            //使用预存款支付   改动过
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }
            //使用奖金支付加入的。。。。。。
            if (!empty($post['dis_pay'])) {
                $order_list = $logic_buy_1->disPay($order_list, $post, $buyer_info);
            }

            //特殊订单站内支付处理
            $logic_buy_1->extendInPay($order_list);

            $model_member->commit();
            
        } catch (Exception $e) {
            $model_member->rollback();
            showMessage($e->getMessage(), '', 'html', 'error');
        }

        return $order_list;
    }

    /**
     * 站内余额支付(充值卡、预存款支付) 虚拟订单
     *
     */
    private function _pd_vr_pay($order_info, $post) {
        if (empty($post['password'])) {
            return $order_info;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_info;
        }

        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_info['rcb_amount']) > 0 || floatval($order_info['pd_amount']) > 0) {
            return $order_info;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy = Logic('buy_virtual');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_info = $logic_buy->rcbPay($order_info, $post, $buyer_info);
            }

            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_info = $logic_buy->pdPay($order_info, $post, $buyer_info);
            }

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            showMessage($e->getMessage(), '', 'html', 'error');
        }

        return $order_info;
    }

    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_info, $payment_info) {
 
        // $payment_api = new $payment_info['payment_code']($payment_info,$order_info);

        // if($payment_info['payment_code'] == 'chinabank') {
        //     $payment_api->submit();
        // } elseif ($payment_info['payment_code'] == 'wxpay') {
        //     if (!extension_loaded('curl')) {
        //         showMessage('系统curl扩展未加载，请检查系统配置', '', 'html', 'error');
        //     }
        //     Tpl::setDir('buy');
        //     Tpl::setLayout('buy_layout');
        //     if (array_key_exists('order_list', $order_info)) {
        //         Tpl::output('order_list',$order_info['order_list']);
        //         Tpl::output('args','buyer_id='.$_SESSION['member_id'].'&pay_id='.$order_info['pay_id']);
        //     } else {
        //         Tpl::output('order_list',array($order_info));
        //         Tpl::output('args','buyer_id='.$_SESSION['member_id'].'&order_id='.$order_info['order_id']);
        //     }
        //     Tpl::output('api_pay_amount',$order_info['api_pay_amount']);
			
        //     Tpl::output('pay_url',base64_encode(encrypt($payment_api->get_payurl(),MD5_KEY)));
        //     Tpl::output('nav_list', rkcache('nav',true));
        //     Tpl::showpage('payment.wxpay');
        // } else {

            @header("Location: ".$payment_api->get_payurl());
        // }
        exit();
    }

    /**
     * 通知处理(支付宝异步通知和网银在线自动对账)
     *
     */
    public function notifyOp(){
      
        $arrl=explode('|',$_POST['ext1']);
        $amout=$_POST['orderAmount']*0.01;
        $gc_id=$arrl[1];
       $member_id=$_POST['ext2'];
        $order_type = $arrl[0];
        if ($order_type == 'real_order') {
            $act = 'member_order';
        } elseif($order_type == 'vr_order') {
            $act = 'member_vr_order';
        } elseif($order_type == 'pd_order') {
            $act = 'predeposit';
        } else {
            exit();
        }
      
        $out_trade_no = $_POST['orderNo'];

        $trade_no = $_GET['trade_no'];
        $url = SHOP_SITE_URL.'/index.php?act='.$act;

        //对外部交易编号进行非空判断
        if(!preg_match('/^\d{18}$/',$out_trade_no)) {
            showMessage('参数错误',$url,'','html','error');
        }

        $logic_payment = Logic('payment');

        if ($order_type == 'real_order') {

            $result = $logic_payment->getRealOrderInfo($out_trade_no);
           
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }
            if ($result['data']['api_pay_state']) {
                $payment_state = 'success';
            }
            $order_list = $result['data']['order_list'];

            //支付成功页面展示在线支付了多少金额
            $result['data']['api_pay_amount'] = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    $result['data']['api_pay_amount'] += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }

        }elseif ($order_type == 'vr_order') {

            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }

            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW))) {
                $payment_state = 'success';
            }

            //支付成功页面展示在线支付了多少金额
            $result['data']['api_pay_amount'] = $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];

        } elseif ($order_type == 'pd_order') {

            $result = $logic_payment->getPdOrderInfo($out_trade_no);
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }
            if ($result['data']['pdr_payment_state'] == 1) {
                $payment_state = 'success';
            }
            $result['data']['api_pay_amount'] = $result['data']['pdr_amount'];
        }

        $order_pay_info = $result['data'];
        $api_pay_amount = $result['data']['api_pay_amount'];
        $code='tlzf';
        
        if ($payment_state != 'success') {

            //取得支付方式
            $result = Model('payment')->where(array('payment_code'=>$code))->find();
            $payment_info = $result['data'];
            //更改订单支付状态
           

            if ($order_type == 'real_order') {
                
                if($gc_id=='10351'){
                   
                    // $member_id=$_SESSION['member_id'];
                    $member=Model('member');
                    $percent=Model('chief');
                    $arras=array('member_level'=>'1');
                    $seve=$member->where(array('member_id'=>$member_id))->find();
                    if($seve['member_level']==0){
                        $member->where(array('member_id'=>$member_id))->update($arras);                                        
                        $order_money=$order_info['order_amount'];
                        $chiefs=$percent->getfby_id(11,'chief');                    
                        $arr=get_parent_info($member_id);                         
                        $pd_log=Model("pd_log");
                        if(is_array($arr)){
                            $mount=500* $chiefs;                         
                            $moneys=$arr["distributor_predeposit"]+500* $chiefs; 
                          
                            $buyer_puid=$arr['member_id'];
                            
                            $buyer_pname=$arr['member_name']; 
                            $avai=array('distributor_predeposit'=>$moneys); 

                            $member->where(array('member_id'=>$buyer_puid) )->update($avai); 
                           
                            $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'激活会员下级购买提成');
                            
                            $pd_log->insert($data);
                            
                        }                   
                        $arrs=get_parent_info($buyer_puid); 
                        if(is_array($arrs)){             
                            $buyer_pid=$arrs['member_id'];
                            $buyer_pname=$arrs['member_name'];
                            $chiefs=$percent->getfby_id(12,'chief');
                            $mount=500* $chiefs;
                            $moneys=$arrs["distributor_predeposit"]+500*$chiefs;                    
                           
                            $avai=array('distributor_predeposit'=>$moneys);
                            $member->where(array('member_id'=>$buyer_pid))->update($avai);                                    
                            // $member->updateMember($avai,$buyer_pid);
                            $data=array('lg_member_id'=>$buyer_pid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'激活会员下级购买提成');
                            //资金变动记录
                            $pd_log->insert($data);                
                        }
                    }          
                }
                
                $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);
            } else if ($order_type == 'vr_order') {
                $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);
            } else if ($order_type == 'pd_order') {
               
                $pd_recharge=Model('pd_recharge');
                $pdr_info=$pd_recharge->where(array('pdr_sn'=>$out_trade_no))->find();
                //写入日志文件
                header("Content-type: text/html; charset=utf-8");
                $file  = '/data/wwwlogs/zf'.date('y-m-d',time()).'.log';
                $content='\r\n接口返回次数：'.$pdr_info['pdr_auto'].'|订单号：'.$out_trade_no.'|金额：'.$amout.'|ID：'.$member_id.'|时间:'.date('y-m-d h:i:s',time());
                $f  = file_put_contents($file, $content,FILE_APPEND);
                if(empty($pdr_info['pdr_auto'])){
                    $pdr['pdr_auto']=array('exp','pdr_auto+1');
                    $update_pd=$pd_recharge->where(array('pdr_sn'=>$out_trade_no))->update($pdr);
                    $pdr_info=$pd_recharge->where(array('pdr_sn'=>$out_trade_no))->find();
                    if($pdr_info['pdr_auto']=='1'){
                        give_se($pdr_info['pdr_member_id'],$amout);
                        $payment_info['payment_name']='通联支付';
                        $order_pay_info['pdr_member_id']=$pdr_info['pdr_member_id'];
                        $order_pay_info['pdr_member_name']=$pdr_info['pdr_member_name'];
                        $order_pay_info['pdr_amount']=$amout;
                        $order_pay_info['pdr_sn']=$out_trade_no;
                        $trade_no=$out_trade_no;
                        $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);
                    }
                }
            }
            if (!$result['state']) {
                showMessage('支付状态更新失败',$url,'html','error');
            } else {
                //记录消费日志
                if ($order_type == 'real_order') {
                    $log_buyer_id = $order_list[0]['buyer_id'];
                    $log_buyer_name = $order_list[0]['buyer_name'];
                    $log_desc = '实物订单使用'.orderPaymentName($payment_info['payment_code']).'成功支付，支付单号：'.$out_trade_no;
                } else if ($order_type == 'vr_order') {
                    $log_buyer_id = $order_pay_info['buyer_id'];
                    $log_buyer_name = $order_pay_info['buyer_name'];
                    $log_desc = '虚拟订单使用'.orderPaymentName($payment_info['payment_code']).'成功支付，支付单号：'.$out_trade_no;                    
                } else if ($order_type == 'pd_order') {
                    $log_buyer_id = $order_pay_info['buyer_id'];
                    $log_buyer_name = $order_pay_info['buyer_name'];
                    $log_desc = '预存款充值成功，使用'.orderPaymentName($payment_info['payment_code']).'成功支付，充值单号：'.$out_trade_no;                   
                }
                QueueClient::push('addConsume', array('member_id'=>$log_buyer_id,'member_name'=>$log_buyer_name,
                'consume_amount'=>ncPriceFormat($api_pay_amount),'consume_time'=>TIMESTAMP,'consume_remark'=>$log_desc));
            }
        }
       

        exit($result['state'] ? $success : $fail);
    }

    /**
     * 支付接口返回
     *
     */
    public function returnOp(){
         
        $arrl=explode('|',$_POST['ext1']);
        $amout=$_POST['orderAmount']*0.01;
        $gc_id=$arrl[1];
        // exit;
        $order_type = $arrl[0];
        if ($order_type == 'real_order') {
            $act = 'member_order';
        } elseif($order_type == 'vr_order') {
            $act = 'member_vr_order';
        } elseif($order_type == 'pd_order') {
            $act = 'predeposit';
        } else {
            exit();
        }
      
        $out_trade_no = $_POST['orderNo'];

        $trade_no = $_GET['trade_no'];
        $url = SHOP_SITE_URL.'/index.php?act='.$act;

        //对外部交易编号进行非空判断
        if(!preg_match('/^\d{18}$/',$out_trade_no)) {
            showMessage('参数错误',$url,'','html','error');
        }

        $logic_payment = Logic('payment');

        if ($order_type == 'real_order') {

            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }
            if ($result['data']['api_pay_state']) {
                $payment_state = 'success';
            }
            $order_list = $result['data']['order_list'];

            //支付成功页面展示在线支付了多少金额
            $result['data']['api_pay_amount'] = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    $result['data']['api_pay_amount'] += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }
            
        }elseif ($order_type == 'vr_order') {

            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }

            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW))) {
                $payment_state = 'success';
            }

            //支付成功页面展示在线支付了多少金额
            $result['data']['api_pay_amount'] = $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];

        } elseif ($order_type == 'pd_order') {

            $result = $logic_payment->getPdOrderInfo($out_trade_no);
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }
            if ($result['data']['pdr_payment_state'] == 1) {
                $payment_state = 'success';
            }
            $result['data']['api_pay_amount'] = $result['data']['pdr_amount'];
        }

        $order_pay_info = $result['data'];
        $api_pay_amount = $result['data']['api_pay_amount'];
        $code='tlzf';
        
        if ($payment_state != 'success') {            
            //取得支付方式
            $result = Model('payment')->where(array('payment_code'=>$code))->find();
            $payment_info = $result['data'];
            //更改订单支付状态
           

            if ($order_type == 'real_order') {
                
                if($gc_id=='10351'){
                   
                    $member_id=$_SESSION['member_id'];
                    $member=Model('member');
                    $percent=Model('chief');
                    $arras=array('member_level'=>'1');
                    $seve=$member->where(array('member_id'=>$member_id))->find();
                    if($seve['member_level']==0){
                        $member->where(array('member_id'=>$member_id))->update($arras);
                        
                        $order_money=$order_info['order_amount'];
                        $chiefs=$percent->getfby_id(11,'chief');                    
                        $arr=get_parent_info($_SESSION['member_id']);  
                       
                        $pd_log=Model("pd_log");
                        if(is_array($arr)){
                            $mount=500* $chiefs;
                           
                            $moneys=$arr["distributor_predeposit"]+500* $chiefs; 
                          
                            $buyer_puid=$arr['member_id'];
                            
                            $buyer_pname=$arr['member_name']; 
                            $avai=array('distributor_predeposit'=>$moneys); 

                            $member->where(array('member_id'=>$buyer_puid) )->update($avai); 
                           
                            $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'激活会员下级购买提成');
                            
                            $pd_log->insert($data);
                            
                        }
                    
                        $arrs=get_parent_info($buyer_puid); 
                        if(is_array($arrs)){             
                            $buyer_pid=$arrs['member_id'];
                            $buyer_pname=$arrs['member_name'];
                            $chiefs=$percent->getfby_id(12,'chief');
                            $mount=500* $chiefs;
                            $moneys=$arrs["distributor_predeposit"]+500*$chiefs;                    
                           
                            $avai=array('distributor_predeposit'=>$moneys);
                            $member->where(array('member_id'=>$buyer_pid))->update($avai);                                    
                            // $member->updateMember($avai,$buyer_pid);
                            $data=array('lg_member_id'=>$buyer_pid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'激活会员下级购买提成');
                            //资金变动记录
                            $pd_log->insert($data);                
                        }
                    }             
                }
                
                $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);
            } else if ($order_type == 'vr_order') {
                $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);
            } else if ($order_type == 'pd_order') {
               
                $pd_recharge=Model('pd_recharge');
                $pdr_info=$pd_recharge->where(array('pdr_sn'=>$out_trade_no))->find();
                if(empty($pdr_info['pdr_auto'])){
                    $pdr['pdr_auto']=array('exp','pdr_auto+1');
                    $update_pd=$pd_recharge->where(array('pdr_sn'=>$out_trade_no))->update($pdr);
                    $pdr_info=$pd_recharge->where(array('pdr_sn'=>$out_trade_no))->find();
                    if($pdr_info['pdr_auto']==1){
                        give_se($pdr_info['pdr_member_id'],$amout);
                        $payment_info['payment_name']='通联支付';
                        $order_pay_info['pdr_member_id']=$pdr_info['pdr_member_id'];
                        $order_pay_info['pdr_member_name']=$pdr_info['pdr_member_name'];
                        $order_pay_info['pdr_amount']=$amout;
                        $order_pay_info['pdr_sn']=$out_trade_no;
                        $trade_no=$out_trade_no;
                        $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);
                    }
                }
            }
            if (!$result['state']) {
                showMessage('支付状态更新失败',$url,'html','error');
            } else {
                //记录消费日志
                if ($order_type == 'real_order') {
                    $log_buyer_id = $order_list[0]['buyer_id'];
                    $log_buyer_name = $order_list[0]['buyer_name'];
                    $log_desc = '实物订单使用'.orderPaymentName($payment_info['payment_code']).'成功支付，支付单号：'.$out_trade_no;
                } else if ($order_type == 'vr_order') {
                    $log_buyer_id = $order_pay_info['buyer_id'];
                    $log_buyer_name = $order_pay_info['buyer_name'];
                    $log_desc = '虚拟订单使用'.orderPaymentName($payment_info['payment_code']).'成功支付，支付单号：'.$out_trade_no;                    
                } else if ($order_type == 'pd_order') {
                    $log_buyer_id = $order_pay_info['buyer_id'];
                    $log_buyer_name = $order_pay_info['buyer_name'];
                    $log_desc = '预存款充值成功，使用'.orderPaymentName($payment_info['payment_code']).'成功支付，充值单号：'.$out_trade_no;                   
                }
                QueueClient::push('addConsume', array('member_id'=>$log_buyer_id,'member_name'=>$log_buyer_name,
                'consume_amount'=>ncPriceFormat($api_pay_amount),'consume_time'=>TIMESTAMP,'consume_remark'=>$log_desc));
            }
        }
        
        //支付成功后跳转
        if ($order_type == 'real_order') {
            $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$out_trade_no.'&pay_amount='.ncPriceFormat($api_pay_amount);
        } elseif ($order_type == 'vr_order') {
            $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy_virtual&op=pay_ok&order_sn='.$out_trade_no.'&order_id='.$order_pay_info['order_id'].'&order_amount='.ncPriceFormat($api_pay_amount);
        } elseif ($order_type == 'pd_order') {
            $pay_ok_url = urlMember('predeposit');
        }
        if ($payment_info['payment_code'] == 'tenpay') {
            showMessage('',$pay_ok_url,'tenpay');
        } else {
            redirect($pay_ok_url);
        }
    }
    /**
     * 二维码显示(微信扫码支付)
     */
    public function qrcodeOp() {
        $data = base64_decode($_GET['data']);
        $data = decrypt($data,MD5_KEY,30);
        require_once BASE_RESOURCE_PATH.'/phpqrcode/phpqrcode.php';
        QRcode::png($data);
    }

    /**
     * 接收微信请求，接收productid和用户的openid等参数，执行（【统一下单API】返回prepay_id交易会话标识
     */
    public function wxpay_returnOp() {
        $result = Logic('payment')->getPaymentInfo('wxpay');
        if (!$result['state']) {
            Log::record('wxpay not found','RUN');			
        }
        new wxpay($result['data'],array());
        require_once BASE_PATH.'/api/payment/wxpay/native_notify.php';
    }

    /**
     * 支付成功，更新订单状态
     */
    public function wxpay_notifyOp() {
        $result = Logic('payment')->getPaymentInfo('wxpay');
        if (!$result['state']) {
            Log::record('wxpay not found','RUN');
        }
        new wxpay($result['data'],array());
        require_once BASE_PATH.'/api/payment/wxpay/notify.php';
    }

    public function query_stateOp() {
        if ($_GET['pay_id'] && intval($_GET['pay_id']) > 0) {
            $info = Model('order')->getOrderPayInfo(array('pay_id'=>intval($_GET['pay_id']),'buyer_id'=>intval($_GET['buyer_id'])));
            exit(json_encode(array('state'=>($info['api_pay_state'] == '1'),'pay_sn'=>$info['pay_sn'],'type'=>'r')));
        } elseif (intval($_GET['order_id']) > 0) {
            $info = Model('vr_order')->getOrderInfo(array('order_id'=>intval($_GET['order_id']),'buyer_id'=>intval($_GET['buyer_id'])));
            exit(json_encode(array('state'=>($info['order_state'] == '20'),'pay_sn'=>$info['order_sn'],'type'=>'v')));
        }
    }


}
