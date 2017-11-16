<?php
/**
 * 支付回调
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class paymentControl extends mobileHomeControl{

    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = $_GET['payment_code'];
    }

  

    /**
     * 支付宝移动支付
     */
    public function notify_alipay_nativeOp() {
        $this->payment_code = 'alipay_native';
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }
        $payment_config = $this->_get_payment_config();
        $payment_api = new $this->payment_code();
        $payment_api->payment_config = $payment_config;
        $payment_api->alipay_config['partner'] = $payment_config['alipay_partner'];
        
        if ($payment_api->verify_notify()) {
            
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $result = $this->_update_order($out_trade_no, $trade_no);
                if(!$result['state']) {
                    logResult("订单状态更新失败".$out_trade_no);
                }
            }
            exit("success");
        } else {
            logResult("verifyNotify验证失败".$out_trade_no);
            exit("fail");
        }
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        if($this->payment_code == 'wxpay3') {
            $condition['payment_code'] = 'wxpay';
        } else {
            $condition['payment_code'] = $this->payment_code;
        }
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);

        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no) {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('_', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
                $order_type = 'v';
            } else {
                $order_type = 'r';
            }
        }

        // wxpay_jsapi
        $paymentCode = $this->payment_code;
        if ($paymentCode == 'wxpay_jsapi') {
            $paymentCode = 'wx_jsapi';
        } elseif ($paymentCode == 'wxpay3') {
            $paymentCode = 'wxpay';
        } elseif ($paymentCode == 'alipay_native') {
            $paymentCode = 'ali_native';
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $paymentCode, $order_list, $trade_no);

            $api_pay_amount = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }
            $log_buyer_id = $order_list[0]['buyer_id'];
            $log_buyer_name = $order_list[0]['buyer_name'];
            $log_desc = '实物订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;

        } elseif ($order_type == 'v') {
            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            $order_info = $result['data'];
            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_CANCEL))) {
                return array('state'=>true);
            }
            $result = $logic_payment->updateVrOrder($out_trade_no, $paymentCode, $result['data'], $trade_no);

            $api_pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
            $log_buyer_id = $order_info['buyer_id'];
            $log_buyer_name = $order_info['buyer_name'];
            $log_desc = '虚拟订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;
        }
        if ($result['state']) {
            //记录消费日志
            QueueClient::push('addConsume', array('member_id'=>$log_buyer_id,'member_name'=>$log_buyer_name,
            'consume_amount'=>ncPriceFormat($api_pay_amount),'consume_time'=>TIMESTAMP,'consume_remark'=>$log_desc));
        }

        return $result;
    }
    
        /**
     * 银联支付回调
     */
    public function unionpay_returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);
        //支付成功后跳转
        $order_type = $_GET['type'];
        if ($order_type == 'real_order') {
          $pay_ok_url = WAP_SITE_URL.'/tmpl/member/order_list.html?#selected';
        } elseif ($order_type == 'vr_order') {
            $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy_virtual&op=pay_ok&order_sn='.$out_trade_no.'&order_id='.$order_pay_info['order_id'].'&order_amount='.ncPriceFormat($api_pay_amount);
        } elseif ($order_type == 'pd_order') {
            $pay_ok_url =WAP_SITE_URL."/tmpl/member/pdrecharge_list.html";
        }
      
        if ($payment_info['payment_code'] == 'tenpay') {
            showMessage('',$pay_ok_url,'tenpay');
        } else {
            redirect($pay_ok_url);
        }

        // Tpl::showpage('payment_message');
    }
/**
     * 银联支付异步通知
     */
    public function unionpay_notifyOp() {
        //写入日志文件
        header("Content-type: text/html; charset=utf-8");
        $file  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/notifyzf'.date('y-m-d',time()).'.log';
        $content='\r\n订单号：'.$_GET['out_trade_no'].'|金额：'.$_GET['amount'].'|时间:'.date('y-m-d h:i:s',time());
        $f  = file_put_contents($file, $content,FILE_APPEND);

        $sign = $_GET['sign'];
        $time = $_GET['time'];
        $key  = '@)!&wdtlytb20171';     
        $amout= $_GET['amount'];
        $out_trade_no = $_GET['out_trade_no'];
        $trade_no     = $result['out_trade_no'];
        $order_type   = $_GET['type'];
        $str = $_GET['act'].$_GET['op'].$_GET['type'].$_GET['out_trade_no'].$_GET['amount'].$_GET['time'].$key;
       
        $sign_again   = md5($str);
        
        $date_time = time();
        //写入日志文件
        header("Content-type: text/html; charset=utf-8");
        $file  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/notify_2_zf'.date('y-m-d',time()).'.log';
        $content='\r\n订单号：'.$_GET['out_trade_no'].'|验签sign_again：'.$sign_again.'|验签sign：'.$sign.'|时间$date_time:'.$date_time.'|时间$time:'.$time;
        $f  = file_put_contents($file, $content,FILE_APPEND);
        if($sign_again!=$sign){
            echo "验签失败";
            exit;
        }
        if($date_time-$time>180){
            echo "验证超时";
            exit;
        }
        
        if ($order_type == 'real_order') {
            $act = 'member_order';
        } elseif($order_type == 'vr_order') {
            $act = 'member_vr_order';
        } elseif($order_type == 'pd_order') {
            $act = 'predeposit';
        } else {
            exit();
        }
        
        // $trade_no = $_GET['trade_no'];
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
        $code='unionpay';
        
        if ($payment_state != 'success') {
            
            //取得支付方式
            $result = Model('payment')->where(array('payment_code'=>$code))->find();
            $payment_info = $result['data'];
            //更改订单支付状态
            if ($order_type == 'real_order') {
                $order = Model('orders'); 
                 //地面商家订单更改状态
                        if($orders['is_dm']!=0){
                        $order->table('orders')->where(array('order_id'=>$orders['order_id']))->update(array('order_state'=>30));
                         }   
                        elseif($orders['is_dm']==1){
                            $order->table('orders')->where(array('order_id'=>$orders['order_id']))->update(array('order_state'=>50));
                        }
                $ordergoods=Model('order_goods');                       
                $orders = $order->where(array('pay_sn'=>$out_trade_no))->find();
                $order_goods = $ordergoods->where(array('order_id'=>$orders['order_id']))->select();
                $member_id=$orders['buyer_id'];
                foreach ($order_goods as $key => $value) {
                     
                    if($value['goods_id']=="30587"){
                        $pd_log=Model("pd_log");
                        $member=Model('member');
                        $percent=Model('chief');                       
                        $arra['member_level']='2';
                        $arra['member_time']=time();
                        $arra['portid']=$member_id;
                        $seve=$member->where(array('member_id'=>$member_id))->find();
                        if($seve['member_level'] < 2 ){ 
                                        //赠送3000积分
                                        $arra['free']='0';                    
                                        $arra['subsidiary_id']=$seve['portid'];
                                        $arra['frozen_agentotal']='100000';
                                        $update_member=$member->where(array('member_id'=>$member_id))->update($arra);
                                        //各级代理分成
                                        find_agent($member_id);        
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
                                            // $member->updateMember($avai,$buyer_pid);
                                            $data=array('lg_member_id'=>$buyer_pid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'会员激活端口产生分销');
                                            //资金变动记录
                                            $update_datas=$pd_log->insert($data);                
                                        }
                        }      
                    }elseif($value['gc_id']=="10351"){
                        $member=Model('member');
                        $percent=Model('chief');
                        $arras['member_points']=array('exp','member_points+500');
                        $arras['member_level']='1';
                        $arras['free']='0';
                        $arras['member_time']=time();
                        // $member_id=$value['buyer_id'];
                        $seve=$member->where(array('member_id'=>$member_id))->find();
                        $point_array=Model()->table('pd_log')->where(array('lg_member_id'=>$member_id,'lg_type'=>'complimentary'))->find();
                      
                        if($seve['member_level']==0 && empty($point_array)){
                            $order=Model('orders');
                            $order_info=$order->where(array('pay_sn'=>$out_trade_no))->find();
                          
                            if(empty($order_info['pdr_auto'])){
                                
                                $pdr['pdr_auto']=array('exp','pdr_auto+1');
                                $update_pd=$order->where(array('pay_sn'=>$out_trade_no))->update($pdr);
                                $orderinfo=$order->where(array('pay_sn'=>$out_trade_no))->find();

                                if(intval($orderinfo['pdr_auto'])==1){
                                   
                                    $member->where(array('member_id'=>$member_id))->update($arras);  
                                    chief_card($seve['member_name']);                                       
                                    $order_money=$order_info['order_amount'];
                                    $chiefs=$percent->getfby_id(11,'chief');                    
                                    $arr=get_parent_info($member_id);                         
                                    $pd_log=Model("pd_log");
                                    //赠送500云豆
                                    $data_point=array('lg_member_id'=>$member_id,'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500云豆');
                                    
                                    $update_point=Model()->table('pd_log')->insert($data_point);
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
                        }  
                    }
                }                
                $payment_info['payment_code']='unionpay';
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
                    //写入日志文件
                    header("Content-type: text/html; charset=utf-8");
                    $file  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/notify_3_zf'.date('y-m-d',time()).'.log';
                    $content='\r\n订单号：'.$_GET['out_trade_no'].'|金额：'.$_GET['amount'].'|时间:'.date('y-m-d h:i:s',time());
                    $f  = file_put_contents($file, $content,FILE_APPEND);

                    if($pdr_info['pdr_auto']=='1'){
                        // give_se($pdr_info['pdr_member_id'],$amout);
                        $payment_info['payment_name']='银联支付';
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
                echo 'SUCCESS';
            }
        }
        // exit($result['state'] ? $success : $fail);
    }
        /**
     * 随行付支付回调
     */
    public function vbill_returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);
        //支付成功后跳转
        $order_type = $_GET['type'];
        if ($order_type == 'real_order') {
            $pay_ok_url = WAP_SITE_URL.'/tmpl/member/order_list.html?#selected';
        } elseif ($order_type == 'vr_order') {
            $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy_virtual&op=pay_ok&order_sn='.$out_trade_no.'&order_id='.$order_pay_info['order_id'].'&order_amount='.ncPriceFormat($api_pay_amount);
        } elseif ($order_type == 'pd_order') {
            $pay_ok_url =WAP_SITE_URL."/tmpl/member/pdrecharge_list.html";
        }
        redirect($pay_ok_url);
       

        // Tpl::showpage('payment_message');
    }
/**
     * 随行付支付异步通知
     */
    public function vbill_notifyOp() {
         //写入日志文件
        header("Content-type: text/html; charset=utf-8");
        $file  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/vbillzf'.date('y-m-d',time()).'.log';
        $content='\r\n订单号：'.$_GET['out_trade_no'].'|金额：'.$_GET['amount'].'|时间:'.date('y-m-d h:i:s',time());
        $f  = file_put_contents($file, $content,FILE_APPEND);  
        $sign = $_GET['sign'];
        $time = $_GET['time'];
        $amout= $_GET['amount'];
        $out_trade_no = $_GET['out_trade_no'];
        $trade_no     = $result['out_trade_no'];
        $order_type   = $_GET['type'];
        $key  = '@)!&wdtlytb20171';
        $str = $_GET['act'].$_GET['op'].$_GET['type'].$_GET['out_trade_no'].$_GET['amount'].$_GET['time'].$key;
       
        $sign_again   = md5($str);
        $date_time = time();
        //写入日志文件
        header("Content-type: text/html; charset=utf-8");
        $file  = '/home/wwwroot/lnmp01/domain/wdtl.com/web/logs/vbill_4_zf'.date('y-m-d',time()).'.log';
        $content='\r\n订单号：'.$_GET['out_trade_no'].'|验签sign_again：'.$sign_again.'|验签sign：'.$sign.'|时间$date_time:'.$date_time.'|时间$time:'.$time;
        $f  = file_put_contents($file, $content,FILE_APPEND);
        
        if($sign_again!=$sign){
            echo "验签失败";
            exit;
        }
        if($date_time-$time>180){
            echo "验证超时";
            exit;
        }

        if ($order_type == 'real_order') {
            $act = 'member_order';
        } elseif($order_type == 'vr_order') {
            $act = 'member_vr_order';
        } elseif($order_type == 'pd_order') {
            $act = 'predeposit';
        } else {
            exit();
        }
        
        // $trade_no = $_GET['trade_no'];
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
        $code='vbill';
        
        if ($payment_state != 'success') {
            
            //取得支付方式
            $result = Model('payment')->where(array('payment_code'=>$code))->find();
            $payment_info = $result['data'];
            //更改订单支付状态
            if ($order_type == 'real_order') {
                $order = Model('orders'); 
     
				 //地面商家订单更改状态
                        if($orders['is_dm']!=0){
                        $order->table('orders')->where(array('order_id'=>$orders['order_id']))->update(array('order_state'=>30));

                         }
				$ordergoods=Model('order_goods'); 
                $orders = $order->where(array('pay_sn'=>$out_trade_no))->find();
                $order_goods = $ordergoods->where(array('order_id'=>$orders['order_id']))->select();
                $member_id=$orders['buyer_id'];
                foreach ($order_goods as $key => $value) {
                     
                    if($value['goods_id']=="30587"){
                        $pd_log=Model("pd_log");
                        $member=Model('member');
                        $percent=Model('chief');                       
                        $arra['member_level']='2';
                        $arra['member_time']=time();
                        $arra['portid']=$member_id;
                        $seve=$member->where(array('member_id'=>$member_id))->find();
                        if($seve['member_level'] < 2 ){ 
                                        //赠送3000积分
                                        $arra['free']='0';                    
                                        $arra['subsidiary_id']=$seve['portid'];
                                        $arra['frozen_agentotal']='100000';
                                        $update_member=$member->where(array('member_id'=>$member_id))->update($arra);
                                        //各级代理分成
                                        find_agent($member_id);        
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
                                            // $member->updateMember($avai,$buyer_pid);
                                            $data=array('lg_member_id'=>$buyer_pid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'会员激活端口产生分销');
                                            //资金变动记录
                                            $update_datas=$pd_log->insert($data);                
                                        }
                        }      
                    }elseif($value['gc_id']=="10351"){
                        $member=Model('member');
                        $percent=Model('chief');
                        $arras['member_points']=array('exp','member_points+500');
                        $arras['member_level']='1';
                        $arras['free']='0';
                        $arras['member_time']=time();
                        // $member_id=$value['buyer_id'];
                        $seve=$member->where(array('member_id'=>$member_id))->find();
                        $point_array=Model()->table('pd_log')->where(array('lg_member_id'=>$member_id,'lg_type'=>'complimentary'))->find();
                      
                        if($seve['member_level']==0 && empty($point_array)){
                            $order=Model('orders');
                            $order_info=$order->where(array('pay_sn'=>$out_trade_no))->find();
                          
                            if(empty($order_info['pdr_auto'])){
                                
                                $pdr['pdr_auto']=array('exp','pdr_auto+1');
                                $update_pd=$order->where(array('pay_sn'=>$out_trade_no))->update($pdr);
                                $orderinfo=$order->where(array('pay_sn'=>$out_trade_no))->find();

                                if(intval($orderinfo['pdr_auto'])==1){
                                   
                                    $member->where(array('member_id'=>$member_id))->update($arras);  
                                    chief_card($seve['member_name']);                                       
                                    $order_money=$order_info['order_amount'];
                                    $chiefs=$percent->getfby_id(11,'chief');                    
                                    $arr=get_parent_info($member_id);                         
                                    $pd_log=Model("pd_log");
                                    //赠送500云豆
                                    $data_point=array('lg_member_id'=>$member_id,'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500云豆');
                                    
                                    $update_point=Model()->table('pd_log')->insert($data_point);
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
                        }  
                    }
                }                
                $payment_info['payment_code']='vbill';
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
                    if($pdr_info['pdr_auto']=='1'){
                        // give_se($pdr_info['pdr_member_id'],$amout);
                        $payment_info['payment_name']='随行付';
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
                echo 'SUCCESS';
            }
        }
        // exit($result['state'] ? $success : $fail);
    }
/**
     * 乐支付异步通知
     */
    public function lepay_notifyOp() {
        $sign = $_GET['sign'];
        $time = $_GET['time'];
        $key  = '@)!&wdtlytb20171';     
        $amout= $_GET['amount'];
        $out_trade_no = $_GET['out_trade_no'];
        $trade_no     = $result['out_trade_no'];
        $order_type   = $_GET['type'];
        $str = $_GET['act'].$_GET['op'].$_GET['type'].$_GET['out_trade_no'].$_GET['amount'].$_GET['time'].$key;
       
        $sign_again   = md5($str);
        
        $date_time = time();
        if($sign_again!=$sign){
            echo "验签失败";
            exit;
        }
        if($date_time-$time>180){
            echo "验证超时";
            exit;
        }

        if ($order_type == 'real_order') {
            $act = 'member_order';
        } elseif($order_type == 'vr_order') {
            $act = 'member_vr_order';
        } elseif($order_type == 'pd_order') {
            $act = 'predeposit';
        } else {
            exit();
        }
        
        // $trade_no = $_GET['trade_no'];
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
        $code='lepay';
        
        if ($payment_state != 'success') {
            
            //取得支付方式
            $result = Model('payment')->where(array('payment_code'=>$code))->find();
            $payment_info = $result['data'];
            //更改订单支付状态
            if ($order_type == 'real_order') {
                $order = Model('orders'); 
                 //地面商家订单更改状态
                        if($orders['is_dm']!=0){
                        $order->table('orders')->where(array('order_id'=>$orders['order_id']))->update(array('order_state'=>30));

                         } elseif($orders['is_dm']==1){
                            $order->table('orders')->where(array('order_id'=>$orders['order_id']))->update(array('order_state'=>40));
                        }
                $ordergoods=Model('order_goods');                       
                $orders = $order->where(array('pay_sn'=>$out_trade_no))->find();
                $order_goods = $ordergoods->where(array('order_id'=>$orders['order_id']))->select();
                $member_id=$orders['buyer_id'];
                foreach ($order_goods as $key => $value) {
                     
                    if($value['goods_id']=="30587"){
                        $pd_log=Model("pd_log");
                        $member=Model('member');
                        $percent=Model('chief');                       
                        $arra['member_level']='2';
                        $arra['member_time']=time();
                        $arra['portid']=$member_id;
                        $seve=$member->where(array('member_id'=>$member_id))->find();
                        if($seve['member_level'] < 2 ){ 
                                        //赠送3000积分
                                        $arra['free']='0';                    
                                        $arra['subsidiary_id']=$seve['portid'];
                                        $arra['frozen_agentotal']='100000';
                                        $update_member=$member->where(array('member_id'=>$member_id))->update($arra);
                                        //各级代理分成
                                        find_agent($member_id);        
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
                                            // $member->updateMember($avai,$buyer_pid);
                                            $data=array('lg_member_id'=>$buyer_pid,'lg_member_name'=>$buyer_pname,'lg_type'=>'distribution','lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'会员激活端口产生分销');
                                            //资金变动记录
                                            $update_datas=$pd_log->insert($data);                
                                        }
                        }      
                    }elseif($value['gc_id']=="10351"){
                        $member=Model('member');
                        $percent=Model('chief');
                        $arras['member_points']=array('exp','member_points+500');
                        $arras['member_level']='1';
                        $arras['free']='0';
                        $arras['member_time']=time();
                        // $member_id=$value['buyer_id'];
                        $seve=$member->where(array('member_id'=>$member_id))->find();
                        $point_array=Model()->table('pd_log')->where(array('lg_member_id'=>$member_id,'lg_type'=>'complimentary'))->find();
                      
                        if($seve['member_level']==0 && empty($point_array)){
                            $order=Model('orders');
                            $order_info=$order->where(array('pay_sn'=>$out_trade_no))->find();
                          
                            if(empty($order_info['pdr_auto'])){
                                
                                $pdr['pdr_auto']=array('exp','pdr_auto+1');
                                $update_pd=$order->where(array('pay_sn'=>$out_trade_no))->update($pdr);
                                $orderinfo=$order->where(array('pay_sn'=>$out_trade_no))->find();

                                if(intval($orderinfo['pdr_auto'])==1){
                                   
                                    $member->where(array('member_id'=>$member_id))->update($arras);  
                                    chief_card($seve['member_name']);                                       
                                    $order_money=$order_info['order_amount'];
                                    $chiefs=$percent->getfby_id(11,'chief');                    
                                    $arr=get_parent_info($member_id);                         
                                    $pd_log=Model("pd_log");
                                    //赠送500云豆
                                    $data_point=array('lg_member_id'=>$member_id,'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500云豆');
                                    
                                    $update_point=Model()->table('pd_log')->insert($data_point);
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
                        }  
                    }
                }                
                $payment_info['payment_code']='lepay';
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
                    if($pdr_info['pdr_auto']=='1'){
                        // give_se($pdr_info['pdr_member_id'],$amout);
                        $payment_info['payment_name']='乐支付';
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
                echo 'SUCCESS';
            }
        }
        // exit($result['state'] ? $success : $fail);
    }

}
