<?php
/**
 * 支付
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
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
class member_paymentControl extends mobileMemberControl {

    private $payment_code;
    private $payment_config;

    public function __construct() {
        parent::__construct();

        // if($_GET['op'] != 'payment_list' && !$_POST['payment_code']) {
        //     $payment_code = 'alipay';

        //     if(in_array($_GET['op'], array('wx_app_pay', 'wx_app_pay3', 'wx_app_vr_pay', 'wx_app_vr_pay3'), true)) {
        //         $payment_code = 'wxpay';
        //     }
        //     else if (in_array($_GET['op'],array('alipay_native_pay','alipay_native_vr_pay'),true)) {
        //         $payment_code = 'alipay_native';
        //     }
        //     else if (isset($_GET['payment_code'])) {
        //         $payment_code = $_GET['payment_code'];
        //     }

        //     $model_mb_payment = Model('mb_payment');
        //     $condition = array();
        //     $condition['payment_code'] = $payment_code;
        //     $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        //     if(!$mb_payment_info) {
        //         output_error('支付方式未开启');
        //     }

        //     $this->payment_code = $payment_code;
        //     $this->payment_config = $mb_payment_info['payment_config'];

        // }
    }
/**
     * 预存款充值
     */
    public function pd_orderOp(){
   
        $pdr_sn = $_GET['pay_sn'];
       $map_id= $_GET['map_id'];  //地面商家需要
        $payment_code = $_GET['payment_code'];
        $url = urlMember('predeposit');
       
        if(!preg_match('/^\d{18}$/',$pdr_sn)){
            showMessage('参数错误',$url,'html','error');
        }

        $type='pd_order';
        $logic_payment = Logic('payment');
        // $result = $logic_payment->getPaymentInfo($payment_code);
	
        $model_payment = Model('payment');
        $member=Model('member');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
	  $points_log=Model('points_log');	

        if(empty($payment_info)) {
            return callback(false,'系统不支持选定的支付方式');
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
      
        if(!file_exists($inc_file)){
            
            return callback(false,'系统不支持选定的支付方式');
        }
        $result = $logic_payment->getPdOrderInfo($pdr_sn,$_SESSION['member_id']);
        $amout=$result['data']['pdr_amount'];        
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {
            showMessage('该充值单不需要支付', $url, 'html', 'error');
        }
        $member_info=$member->where(array('member_id'=>$this->member_info['member_id']))->find();
        
        $points_info=$points_log->where(array('pl_memberid'=>$this->member_info['member_id'],'pl_stage'=>'rechart'))->field('sum(pl_points) as points')->find();
        // var_dump($points_info);
        // exit;
         @header("Content-type: text/html; charset=".CHARSET);
        if(($points_info['points']>20000 || $points_info['points']+$amout>20000) && $member_info['free']=='1'){
            echo "<script>alert('您充值已经超过两万，请自行去激活会员！！');window.location.href = '".WAP_SITE_URL."/tmpl/product_list.html?gc_id=10351';</script>";          
            exit;
        }
        if(empty($member_info['uid'])){
            $member_info['uid']=$this->UserID($this->member_info['member_id']);          
            $member->where(array('member_id'=>$this->member_info['member_id']))->update(array('uid'=>$member_info['uid']));
        }
        require($inc_file);
        //转到第三方API支付
        // $this->_api_pay($result['data']);
    }
    /**
     * 实物订单支付 新方法
     */
    public function pay_newOp() {
        @header("Content-type: text/html; charset=".CHARSET);
        $pdr_sn = $_GET['pay_sn'];
        $payment_code = $_GET['payment_code'];
      
		if($payment_code=='wxpay'){    //李志军添加 2017-04-27
			  $logic_payment = Logic('payment');
        $order_pay_info = $logic_payment->getRealOrderInfo($pdr_sn, $this->member_info['member_id']);

        if(!$order_pay_info['state']) {
            showMessage($order_pay_info['msg'], $url, 'html', 'error');
        }else{
			$arrlizhijun=$order_pay_info['data']['order_list'];
			$lgc_id=array();
			 $order_goods=Model('order_goods');
			foreach($arrlizhijun as $k=>$v){
				 $lpd[]=$v['order_id'];
              }
			}
			$lpdc=implode(',',$lpd);
			$lgoodsinfo=$order_goods->where('order_id in('.$lpdc.')')->select();
		    if($lgoodsinfo){
				foreach($lgoodsinfo as $b=>$d){
					$lgc_id[]=$d['gc_id'];
					$lgoods_pay_points+=$d['goods_pay_points'];
					}
				
				}

		
			$lagc_id=implode(',',array_unique($lgc_id));
			}
		
        $url = 'index.php?act=member_order';
        $member=Model('member');
        if(!preg_match('/^\d{18}$/',$pdr_sn)){
            showMessage('参数错误','','html','error');
        }

        //取订单列表
        $logic_payment = Logic('payment');
        $order_pay_info = $logic_payment->getRealOrderInfo($pdr_sn, $this->member_info['member_id']);

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
        $amout=$order_pay_info['data']['order_list'][0]['order_amount'];
        //判断云豆余额
        if($this->member_info['member_level']=='5'){
            if($_GET['pd_pay']==1 && $this->member_info['province_predeposit']<$amout){
               echo "<script>alert('您的余额不够，暂时无法购买！！');window.history.go(-1);</script>";
               exit;
            }
        }else{
            if($_GET['pd_pay']==1 && $this->member_info['available_predeposit']<$amout){
               echo "<script>alert('您的余额不够，暂时无法购买！！');window.history.go(-1);</script>";
               exit;
            }
        }
       if($_GET['poi_pay']==1 && $this->member_info['member_points']<10000){
               echo "<script>alert('您的余额不够，暂时无法购买！！');window.history.go(-1);</script>";
               exit;
        }
        //站内余额支付
        $order_list = $this->_pd_pay($order_pay_info['data']['order_list'],$_GET);
        
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
            if($goodsinfo['goods_id']=='7172'){
                upgrade_member($this->member_info['member_id']);
            }
            $pay_ok_url = WAP_SITE_URL.'/tmpl/member/order_list.html?#selected';
            redirect($pay_ok_url);
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
        $member_info=$member->where(array('member_id'=>$this->member_info['member_id']))->find();
        if(empty($member_info['uid'])){
            $member_info['uid']=$this->UserID($this->member_info['member_id']);          
            $member->where(array('member_id'=>$this->member_info['member_id']))->update(array('uid'=>$member_info['uid']));
        }
        require_once($inc_file);
    }

    /**
     * 虚拟订单支付 新方法
     */
    public function vr_pay_newOp() {
        @header("Content-type: text/html; charset=".CHARSET);
        $order_sn = $_GET['pay_sn'];
        if(!preg_match('/^\d{18}$/',$order_sn)){
            exit('订单号错误');
        }
        if (in_array($_GET['payment_code'],array('alipay','wxpay_jsapi'))) {
            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $_GET['payment_code'];
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                exit('支付方式未开启');
            }

            $this->payment_code = $_GET['payment_code'];
            $this->payment_config = $mb_payment_info['payment_config'];
        } else {
            exit('支付方式提交错误');
        }

        $pay_info = $this->_get_vr_order_info($order_sn,$_GET);
        if(isset($pay_info['error'])) {
            exit($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    
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
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }
    
        if ($buyer_info['member_predeposit'] == 0) {
            $post['rcb_pay'] = null;
        }
        if($buyer_info['member_level']=='5'){
            //可用余额   改动过
            $post['pd_pay'] =$buyer_info['province_predeposit']==0?null:$post['pd_pay'];
            
        }else{
           //可用余额   改动过
            $post['pd_pay'] =$buyer_info['available_predeposit']==0?null:$post['pd_pay'];
            
        }
        if (floatval($order_list[0]['rcb_amount']) > 0 || floatval($order_list[0]['pd_amount']) > 0) {
            return $order_list;
        }
        if($post['poi_pay']){
            $post['poi_pay'] =$buyer_info['member_points']==0?null:$post['poi_pay'];
            
        } 
        try {
            $model_member->beginTransaction();
            $logic_buy_1 = Logic('buy_1');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_list = $logic_buy_1->rcbPay($order_list, $post, $buyer_info);
            }
    
            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }
            //使用云豆支付加入的。。。。。。
            if (!empty($post['poi_pay'])) {
                $order_list = $logic_buy_1->pointPay($order_list, $post, $buyer_info);
            }
            //特殊订单站内支付处理
            $logic_buy_1->extendInPay($order_list);
    
            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            exit($e->getMessage());
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
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_info;
        }
        if ($buyer_info['member_predeposit'] == 0) {
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
            exit($e->getMessage());
        }
    
        return $order_info;
    }

    /**
     * 实物订单支付
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 虚拟订单支付
     */
    public function vr_payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_pay_info) {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $param = $this->payment_config;

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $param['orderSn'] = $order_pay_info['pay_sn'];
            $param['orderFee'] = (int) (100 * $order_pay_info['api_pay_amount']);
            $param['orderInfo'] = C('site_name') . '商品订单' . $order_pay_info['pay_sn'];
            $param['orderAttach'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
            $api = new wxpay_jsapi();
            $api->setConfigs($param);
            try {
                echo $api->paymentHtml($this);
            } catch (Exception $ex) {
                if (C('debug')) {
                    header('Content-type: text/plain; charset=utf-8');
                    echo $ex, PHP_EOL;
                } else {
                    Tpl::output('msg', $ex->getMessage());
                    Tpl::showpage('payment_result');
                }
            }
            exit;
        }

        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['api_pay_amount'];
        $param['order_type'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
        $payment_api = new $this->payment_code();
        $return = $payment_api->submit($param);
        echo $return;
        exit;
    }

    /**
     * 获取订单支付信息
     */
    private function _get_real_order_info($pay_sn,$rcb_pd_pay = array()) {
        $logic_payment = Logic('payment');

        //取订单信息
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            return array('error' => $result['msg']);
        }

        //站内余额支付
        if ($rcb_pd_pay) {
            $result['data']['order_list'] = $this->_pd_pay($result['data']['order_list'],$rcb_pd_pay);
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        //$pay_amount_points=0;//加入的。。。。。。。。。。
        $pay_order_id_list = array();
        if (!empty($result['data']['order_list'])) {
            foreach ($result['data']['order_list'] as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    //$pay_amount_points += $order_info['order_points'];//加入的。。。。。。。。。。
                    $pay_order_id_list[] = $order_info['order_id'];
                }
            }
        }

        if ($pay_amount == 0) {
            redirect(WAP_SITE_URL.'/tmpl/member/order_list.html');
        }

        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);
        //$result['data']['api_pay_amount_points'] = ncPriceFormat($pay_amount_points);

        $update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$update) {
            return array('error' => '更新订单信息发生错误，请重新支付');
        }

        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($result['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$result['data']['pay_id']));
            if (!$update) {
                return array('error' => '订单支付失败');
            }
            $result['data']['api_pay_state'] = 0;
        }

        return $result;
    }

    /**
     * 获取虚拟订单支付信息
     */
    private function _get_vr_order_info($pay_sn,$rcb_pd_pay = array()) {
        $logic_payment = Logic('payment');

        //取得订单信息
        $result = $logic_payment->getVrOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        }

        //站内余额支付
        if ($rcb_pd_pay) {
            $result['data'] = $this->_pd_vr_pay($result['data'],$rcb_pd_pay);
        }
        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        if ($result['data']['order_state'] == ORDER_STATE_NEW) {
            $pay_amount += $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];
        }

        if ($pay_amount == 0) {
            redirect(WAP_SITE_URL.'/tmpl/member/vr_order_list.html');
        }

        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);
        
        $update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>$result['data']['order_id']));
        if(!$update) {
            return array('error' => '更新订单信息发生错误，请重新支付');
        }       

        //计算本次需要在线支付的订单总金额
        $pay_amount = $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];
        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);

        return $result;
    }

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        $model_mb_payment = Model('mb_payment');

        $payment_list = $model_mb_payment->getMbPaymentOpenList();

        $payment_array = array();
        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }

        output_data(array('payment_list' => $payment_array));
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'];

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info($pay_param) {
        $access_token = $this->_get_wx_access_token();
        if(empty($access_token)) {
            return array('error' => '支付失败code:1001');
        }

        $package = $this->_get_wx_package($pay_param);

        $noncestr = md5($package + TIMESTAMP);
        $timestamp = TIMESTAMP;
        $traceid = $this->member_info['member_id'];

        // 获取预支付app_signature
        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['noncestr'] = $noncestr;
        $param['package'] = $package;
        $param['timestamp'] = $timestamp;
        $param['traceid'] = $traceid;
        $app_signature = $this->_get_wx_signature($param);

        // 获取预支付编号
        $param['sign_method'] = 'sha1';
        $param['app_signature'] = $app_signature;
        $post_data = json_encode($param);
        $prepay_result = http_postdata('https://api.weixin.qq.com/pay/genprepay?access_token=' . $access_token, $post_data);
        $prepay_result = json_decode($prepay_result, true);
        if($prepay_result['errcode']) {
            return array('error' => '支付失败code:1002');
        }
        $prepayid = $prepay_result['prepayid'];

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        $data['package'] = 'Sign=WXPay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = $prepayid;
        $data['timestamp'] = $timestamp;
        $sign = $this->_get_wx_signature($data);
        $data['sign'] = $sign;
        return $data;
    }

    /**
     * 获取微信access_token
     */
    private function _get_wx_access_token() {
        // 尝试读取缓存的access_token
        $access_token = rkcache('wx_access_token');
        if($access_token) {
            $access_token = unserialize($access_token);
            // 如果access_token未过期直接返回缓存的access_token
            if($access_token['time'] > TIMESTAMP) {
                return $access_token['token'];
            }
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $url = sprintf($url, $this->payment_config['wxpay_appid'], $this->payment_config['wxpay_appsecret']);
        $re = http_get($url);
        $result = json_decode($re, true);
        if($result['errcode']) {
            return '';
        }

        // 缓存获取的access_token
        $access_token = array();
        $access_token['token'] = $result['access_token'];
        $access_token['time'] = TIMESTAMP + $result['expires_in'];
        wkcache('wx_access_token', serialize($access_token));

        return $result['access_token'];
    }

    /**
     * 获取package
     */
    private function _get_wx_package($param) {
        $array = array();
        $array['bank_type'] = 'WX';
        $array['body'] = $param['subject'];
        $array['fee_type'] = 1;
        $array['input_charset'] = 'UTF-8';
        $array['notify_url'] = MOBILE_SITE_URL . '/api/payment/wxpay/notify_url.php';
        $array['out_trade_no'] = $param['pay_sn'];
        $array['partner'] = $this->payment_config['wxpay_partnerid'];
        $array['total_fee'] = $param['amount'];
        $array['spbill_create_ip'] = get_server_ip();

        ksort($array);

        $string = '';
        $string_encode = '';
        foreach ($array as $key => $val) {
            $string .= $key . '=' . $val . '&';
            $string_encode .= $key . '=' . urlencode($val). '&';
        }

        $stringSignTemp = $string . 'key=' . $this->payment_config['wxpay_partnerkey'];
        $signValue = md5($stringSignTemp);
        $signValue = strtoupper($signValue);

        $wx_package = $string_encode . 'sign=' . $signValue;
        return $wx_package;
    }

    /**
     * 获取微信支付签名
     */
    private function _get_wx_signature($param) {
        $param['appkey'] = $this->payment_config['wxpay_appkey'];

        $string = '';

        ksort($param);
        foreach ($param as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        $string = rtrim($string, '&');

        $sign = sha1($string);

        return $sign;
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info3($pay_param) {
        $noncestr = md5(rand());

        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['mch_id'] = $this->payment_config['wxpay_partnerid'];
        $param['nonce_str'] = $noncestr;
        $param['body'] = $pay_param['subject'];
        $param['out_trade_no'] = $pay_param['pay_sn'];
        $param['total_fee'] = $pay_param['amount'];
        $param['spbill_create_ip'] = get_server_ip();
        $param['notify_url'] = MOBILE_SITE_URL . '/api/payment/wxpay3/notify_url.php';
        $param['trade_type'] = 'APP';

        $sign = $this->_get_wx_pay_sign3($param);
        $param['sign'] = $sign;

        $post_data = '<xml>';
        foreach ($param as $key => $value) {
            $post_data .= '<' . $key .'>' . $value . '</' . $key . '>';
        }
        $post_data .= '</xml>';

        $prepay_result = http_postdata('https://api.mch.weixin.qq.com/pay/unifiedorder', $post_data);
        $prepay_result = simplexml_load_string($prepay_result);
        if($prepay_result->return_code != 'SUCCESS') {
            return array('error' => '支付失败code:1002');
        }

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        //微信修改接口参数，否则IOS报解析失败
        //$data['package'] = 'prepay_id=' . $prepay_result->prepay_id;
        $data['package'] = 'Sign=WXPay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = (string)$prepay_result->prepay_id;
        $data['timestamp'] = TIMESTAMP;
        $sign = $this->_get_wx_pay_sign3($data);
        $data['sign'] = $sign;
        return $data;
    }

    private function _get_wx_pay_sign3($param) {
        ksort($param);
        foreach ($param as $key => $val) {
            $string .= $key . '=' . $val . '&';
        }
        $string .= 'key=' . $this->payment_config['wxpay_partnerkey'];
        return strtoupper(md5($string));
    }

    /**
     * 取得支付宝移动支付 订单信息 实物订单
     */
    public function alipay_native_payOp() {
        $pay_sn = $_POST['pay_sn'];
        if (!preg_match('/^\d+$/',$pay_sn)){
            output_error('支付单号错误');
        }
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }
        
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $pay_info['data']['order_type'] = 'r';
        $payment_api = new $this->payment_code();
        $payment_api->init($this->payment_config,$pay_info['data']);
        $prestr = 'partner="'.$payment_api->param['partner']
        .'"&seller_id="'.$payment_api->param['seller_id']
        .'"&out_trade_no="'.$payment_api->param['out_trade_no']
        .'"&subject="'.$payment_api->param['subject'].'"&body="r"&total_fee="'
        .$payment_api->param['total_fee'].'"&notify_url="'
        .$payment_api->param['notify_url'].'"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="1"';
        $mysign = $payment_api->mySign($prestr);
        output_data(array('signStr'=>$prestr.'&sign_type="RSA"&sign="'.urlencode($mysign).'"'));
    }

    /**
     * 取得支付宝移动支付 订单信息 虚拟订单
     */
    public function alipay_native_vr_payOp() {
        $pay_sn = $_POST['pay_sn'];
        if (!preg_match('/^\d+$/',$pay_sn)){
            output_error('支付单号错误');
        }
        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $pay_info['data']['order_type'] = 'v';
        $payment_api = new $this->payment_code();
        $payment_api->init($this->payment_config,$pay_info['data']);
        $prestr = 'partner="'.$payment_api->param['partner']
        .'"&seller_id="'.$payment_api->param['seller_id']
        .'"&out_trade_no="'.$payment_api->param['out_trade_no']
        .'"&subject="'.$payment_api->param['subject'].'"&body="v"&total_fee="'
        .$payment_api->param['total_fee'].'"&notify_url="'
        .$payment_api->param['notify_url'].'"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="1"';
        $mysign = $payment_api->mySign($prestr);
        output_data(array('signStr'=>$prestr.'&sign_type="RSA"&sign="'.urlencode($mysign).'"'));

    }
    //获取通联注册会员id
    function UserID($id){

        $url='https://cashier.allinpay.com/usercenter/merchant/UserInfo/reg.do';
        $signType='0';
        $merchantId='008120189110009';
        $partnerUserId=$id;
        $userName='2222';
        $pidType='01'; 
        $key='1234567890'; 
        // 生成签名字符串。
        $bufSignSrc=""; 
        if($signType != "")
            $bufSignSrc='&'.$bufSignSrc."signType=".$signType."&";      
        if($merchantId != "")
            $bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";      
        if($partnerUserId != "")
            $bufSignSrc=$bufSignSrc."partnerUserId=".$partnerUserId."&";
            
          $bufSignSrc=$bufSignSrc."key=".$key.'&';
        //签名，设为signMsg字段值。
        $signMsg = strtoupper(md5($bufSignSrc));

       
 $http_query=$url.'?signType='.$signType.'&merchantId='.$merchantId.'&partnerUserId='.$partnerUserId.'&signMsg='.$signMsg;
        // $response =json_decode(doHttpPost($url,$http_query) );
        $response =json_decode(doHttpGet($http_query) );
        return $response->userId;     }

}
?>