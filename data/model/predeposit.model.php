<?php
/**
 * 预存款
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class predepositModel extends Model {
    /**
     * 生成充值编号
     * @return string
     */
    public function makeSn() {
       return mt_rand(10,99)
              . sprintf('%010d',time() - 946656000)
              . sprintf('%03d', (float) microtime() * 1000)
              . sprintf('%03d', (int) $_SESSION['member_id'] % 1000);
    }

    public function addRechargeCard($sn, array $session)
    {
        $memberId = (int) $session['member_id'];
        $memberName = $session['member_name'];

        if ($memberId < 1 || !$memberName) {
            throw new Exception("当前登录状态为未登录，不能使用充值卡");
        }

        $rechargecard_model = Model('rechargecard');

        $card = $rechargecard_model->getRechargeCardBySN($sn);

        if (empty($card) || $card['state'] != 0 || $card['member_id'] != 0) {
            throw new Exception("充值卡不存在或已被使用");
        }

        $card['member_id'] = $memberId;
        $card['member_name'] = $memberName;

        try {
            $this->beginTransaction();

            $rechargecard_model->setRechargeCardUsedById($card['id'], $memberId, $memberName);

            $card['amount'] = $card['denomination'];
            $this->changeRcb('recharge', $card);

            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * 取得充值列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getPdRechargeList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->table('pd_recharge')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 添加充值记录
     * @param array $data
     */
    public function addPdRecharge($data) {
        return $this->table('pd_recharge')->insert($data);
    }

    /**
     * 编辑
     * @param unknown $data
     * @param unknown $condition
     */
    public function editPdRecharge($data,$condition = array()) {
        return $this->table('pd_recharge')->where($condition)->update($data);
    }

    /**
     * 取得单条充值信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getPdRechargeInfo($condition = array(), $fields = '*',$lock = false) {
        return $this->table('pd_recharge')->where($condition)->field($fields)->lock($lock)->find();
    }

    /**
     * 取充值信息总数
     * @param unknown $condition
     */
    public function getPdRechargeCount($condition = array()) {
        return $this->table('pd_recharge')->where($condition)->count();
    }

    /**
     * 取提现单信息总数
     * @param unknown $condition
     */
    public function getPdCashCount($condition = array()) {
        return $this->table('pd_cash')->where($condition)->count();
    }

    /**
     * 取日志总数
     * @param unknown $condition
     */
    public function getPdLogCount($condition = array()) {
        return $this->table('pd_log')->where($condition)->count();
    }

    /**
     * 取得预存款变更日志列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getPdLogList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->table('pd_log')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
    //改动过
    public function changeRcb($type, $data = array())
    {
        $amount = (float) $data['amount'];
        if ($amount < .01) {
            throw new Exception('参数错误');
        }

        $available = $freeze = 0;
        $desc = null;

        switch ($type) {
        case 'order_pay':
            $available = -$amount;
            $desc = '下单，使用充值余额，订单号: ' . $data['order_sn'];
            break;

        case 'order_freeze':
            $available = -$amount;
            $freeze = $amount;
            $desc = '下单，冻结充值余额，订单号: ' . $data['order_sn'];
            break;

        case 'order_cancel':
            $available = $amount;
            $freeze = -$amount;
            $desc = '取消订单，解冻充值余额，订单号: ' . $data['order_sn'];
            break;

        case 'order_comb_pay':
            $freeze = -$amount;
            $desc = '下单，扣除被冻结的充值余额，订单号: ' . $data['order_sn'];
            break;

        case 'recharge':
            $available = $amount;
            $desc = '平台充值卡充值，充值卡号: ' . $data['sn'];
            break;

        case 'refund':
            $available = $amount;
            $desc = '确认退款001，订单号: ' . $data['order_sn'];
            break;

        case 'vr_refund':
            $available = $amount;
            $desc = '虚拟兑码退款成功，订单号: ' . $data['order_sn'];
            break;

        case 'order_book_cancel':
            $available = $amount;
            $desc = '取消预定订单，退还充值卡余额，订单号: ' . $data['order_sn'];
            break;

        default:
            throw new Exception('参数错误');
        }

        $update = array();
        if ($available) {
            $update['member_predeposit'] = array('exp', "member_predeposit + ({$available})");

            //生成充值余额安全码
            $obj_member_info=Model('member')->where(array('member_id'=>$data['member_id']))->find();
            $obj_points=$obj_member_info['member_predeposit']-$amount;
            $points_array=['id'=>$data['member_id'],'amt'=>$obj_points];
            $points_code = Ze\Secure::encode($points_array);
            $update['points_code']=$points_code;

        }
        if ($freeze) {
            $update['freeze_rc_balance'] = array('exp', "freeze_rc_balance + ({$freeze})");
        }

        if (!$update) {
            throw new Exception('参数错误');
        }

        // 更新会员
        $updateSuccess = Model('member')->editMember(array(
            'member_id' => $data['member_id'],
        ), $update);

        if (!$updateSuccess) {
            throw new Exception('操作失败');
        }

        // 添加日志
        $log = array(
            'member_id' => $data['member_id'],
            'member_name' => $data['member_name'],
            'type' => $type,
            'add_time' => TIMESTAMP,
            'available_amount' => $available,
            'freeze_amount' => $freeze,
            'description' => $desc,
        );

        $insertSuccess = $this->table('rcb_log')->insert($log);
        if (!$insertSuccess) {
            throw new Exception('操作失败');
        }

        $msg = array(
            'code' => 'recharge_card_balance_change',
            'member_id' => $data['member_id'],
            'param' => array(
                'time' => date('Y-m-d H:i:s', TIMESTAMP),
                'url' => urlMember('predeposit', 'rcb_log_list'),
                'available_amount' => ncPriceFormat($available),
                'freeze_amount' => ncPriceFormat($freeze),
                'description' => $desc,
            ),
        );

        QueueClient::push('addConsume', array('member_id'=>$data['member_id'],'member_name'=>$data['member_name'],
                'consume_amount'=>$amount,'consume_time'=>time(),'consume_remark'=>$desc));
        // 发送买家消息
        QueueClient::push('sendMemberMsg', $msg);

        return $insertSuccess;
    }
    public function changeDis($type, $data = array())
    {
        $amount = (float) $data['amount'];
        if ($amount < .01) {
            throw new Exception('参数错误');
        }

        $available = $freeze = 0;
        $desc = null;

        switch ($type) {
        case 'order_pay':
            $available = -$amount;
            $desc = '下单，使用分销余额，订单号: ' . $data['order_sn'];
            break;

        case 'order_freeze':
            $available = -$amount;
            $freeze = $amount;
            $desc = '下单，冻结分销余额，订单号: ' . $data['order_sn'];
            break;

        case 'order_cancel':
            $available = $amount;
            $freeze = -$amount;
            $desc = '取消订单，解冻分销余额，订单号: ' . $data['order_sn'];
            break;

        case 'order_comb_pay':
            $freeze = -$amount;
            $desc = '下单，扣除被冻结的分销余额，订单号: ' . $data['order_sn'];
            break;

        case 'recharge':
            $available = $amount;
            $desc = '平台充值卡充值，充值卡号: ' . $data['sn'];
            break;

        case 'refund':
            $available = $amount;
            $desc = '确认退款002，订单号: ' . $data['order_sn'];
            break;

        case 'vr_refund':
            $available = $amount;
            $desc = '虚拟兑码退款成功，订单号: ' . $data['order_sn'];
            break;

        case 'order_book_cancel':
            $available = $amount;
            $desc = '取消预定订单，退还分销余额，订单号: ' . $data['order_sn'];
            break;

        default:
            throw new Exception('参数错误');
        }

        $update = array();
        if ($available) {
            $update['distributor_predeposit'] = array('exp', "distributor_predeposit + ({$available})");
        }
        if ($freeze) {
            $update['freeze_rc_balance'] = array('exp', "freeze_rc_balance + ({$freeze})");
        }

        if (!$update) {
            throw new Exception('参数错误');
        }

        // 更新会员
        $updateSuccess = Model('member')->editMember(array(
            'member_id' => $data['member_id'],
        ), $update);

        if (!$updateSuccess) {
            throw new Exception('操作失败');
        }

        // 添加日志
        $log = array(
            'member_id' => $data['member_id'],
            'member_name' => $data['member_name'],
            'type' => $type,
            'add_time' => TIMESTAMP,
            'available_amount' => $available,
            'freeze_amount' => $freeze,
            'description' => $desc,
        );

        $insertSuccess = $this->table('fenxiao_log')->insert($log);
        if (!$insertSuccess) {
            throw new Exception('操作失败');
        }

        $msg = array(
            'code' => 'distributor_change',
            'member_id' => $data['member_id'],
            'param' => array(
                'time' => date('Y-m-d H:i:s', TIMESTAMP),
                'url' => urlMember('predeposit', 'rcb_log_fenxiao'),
                'available_amount' => ncPriceFormat($available),
                'freeze_amount' => ncPriceFormat($freeze),
                'description' => $desc,
            ),
        );

        QueueClient::push('addConsume', array('member_id'=>$data['member_id'],'member_name'=>$data['member_name'],
                'consume_amount'=>$amount,'consume_time'=>time(),'consume_remark'=>$desc));
        // 发送买家消息
        QueueClient::push('sendMemberMsg', $msg);

        return $insertSuccess;
    }
    public function pointsDis($type, $data = array())
    {
        $amount = (float) $data['amount'];
        // if ($amount < .01) {
        //     throw new Exception('参数错误');
        // }

        $points = $freeze = 0;
        $desc = null;

        switch ($type) {
        case 'order_pay':
            $points = -10000;
            $desc = '下单，使用云豆余额，订单号: ' . $data['order_sn'];
            break;
        default:
            throw new Exception('参数错误');
        }

        $update = array();
        if ($points) {
            $update['member_points'] = array('exp', "member_points -10000");
            //生成云豆余额安全码
            $obj_member_info=Model('member')->where(array('member_id'=>$data['pl_memberid']))->find();
            $obj_points=$obj_member_info['member_points']-10000;
            $points_array=['id'=>$data['member_id'],'amt'=>$obj_points];
            $points_code = Ze\Secure::encode($points_array);
            $update['points_code']=$points_code;
        }
        // if ($freeze) {
        //     $update['freeze_rc_balance'] = array('exp', "freeze_rc_balance + ({$freeze})");
        // }

        if (!$update) {
            throw new Exception('参数错误');
        }

        // 更新会员
        $updateSuccess = Model('member')->editMember(array(
            'member_id' => $data['member_id'],
        ), $update);

        if (!$updateSuccess) {
            throw new Exception('操作失败');
        }

        // 添加日志
        $log = array(
            'member_id' => $data['member_id'],
            'member_name' => $data['member_name'],
            'type' => $type,
            'add_time' => TIMESTAMP,
            'available_amount' => $points,
            'freeze_amount' => $freeze,
            'description' => $desc,
        );

        $insertSuccess = $this->table('fenxiao_log')->insert($log);
        if (!$insertSuccess) {
            throw new Exception('操作失败');
        }

        $msg = array(
            'code' => 'distributor_change',
            'member_id' => $data['member_id'],
            'param' => array(
                'time' => date('Y-m-d H:i:s', TIMESTAMP),
                'url' => urlMember('predeposit', 'rcb_log_fenxiao'),
                'available_amount' => ncPriceFormat($points),
                'freeze_amount' => ncPriceFormat($freeze),
                'description' => $desc,
            ),
        );

        QueueClient::push('addConsume', array('member_id'=>$data['member_id'],'member_name'=>$data['member_name'],
                'consume_amount'=>$amount,'consume_time'=>time(),'consume_remark'=>$desc));
        // 发送买家消息
        QueueClient::push('sendMemberMsg', $msg);

        return $insertSuccess;
    }
    /**
     * 变更预存款
     * @param unknown $change_type
     * @param unknown $data
     * @throws Exception
     * @return unknown
     */
    public function changePd($change_type,$data = array()) {
        $data_log = array();
        $data_pd = array();
        $data_msg = array();
        
        $data_log['lg_invite_member_id'] = $data['invite_member_id'];
        $data_log['lg_member_id'] = $data['member_id'];
        $data_log['lg_member_name'] = $data['member_name'];
        $data_log['lg_add_time'] = TIMESTAMP;
        $data_log['lg_type'] = $change_type;

        $data_msg['time'] = date('Y-m-d H:i:s');
        $data_msg['pd_url'] = urlMember('predeposit', 'pd_log_list');
        $chief=Model("chief");
        $widthdraw=$chief->getfby_id(8,'chief');
        $draws=$chief->getfby_id(18,'chief');
        $net = $chief->getfby_id(9,'chief');
        $poin=Model('points_log');
        $til['pl_memberid']=$data['member_id'];
        $til['pl_addtime']=array("gt",strtotime(date("Y-m-d")));
        $til['pl_stage']=array('in','rechart,buy_points');
        $poarrr=$poin->where($til)->sum('pl_points');  
         
        //获取8%的金额
        $pd_recharge=Model('pd_recharge');
        $where_pdr['pdr_member_id']=$data['member_id'];
        $where_pdr['pdr_payment_time']=array("gt",strtotime(date("Y-m-d")));
        $where_pdr['pdr_type']='2';
        $where_pdr['pdr_payment_state']='1';
        $pd_amount=$pd_recharge->where($where_pdr)->sum('pdr_amount');
        $amont=$poarrr+$data['amount']-$pd_amount*12.5;
    
        if($data['pdr_type']!='1' && $data['pdr_type']!='2'){
            if($poarrr-$pd_amount*12.5>='20000' || !empty($data['split_id'])){
                $amout=$data['amount']-$data['amount']*$draws;
            }
            elseif($amont>'20000')
            {
                $amout=$data['amount']-(20000-$poarrr+$pd_amount*12.5)*$widthdraw-($data['amount']-20000+$poarrr+$pd_amount*12.5)*$draws;
            } 
            else{
                $amout=$data['amount']-$data['amount']*$widthdraw;
            }
          if(trim($data['payment_name'])=='unionpay' && $change_type=='recharge'){
                    $data_pd['union_amount']=array('exp','union_amount+'.$amout);
            }elseif(trim($data['payment_name'])=='ybzf' && $change_type=='recharge'){
                    $data_pd['yb_amount']=array('exp','yb_amount+'.$amout);
            } 
        }else{
            if($data['pdr_type']=='1'){
                $points=$data['amount']*20;
            }elseif($data['pdr_type']=='2'){
                $points=$data['amount']*12.5;
            }
            
        }
        if($change_type=='recharge'){
            if($data['pdr_type']=='1' || $data['pdr_type']=='2'){
                give_se($data['member_id'],$points);
            }else{
                 give_se($data['member_id'],$data['amount']);
            }
        }
        $rsUser=$this->table('member')->where(array('member_id'=>$data['member_id']))->find();
        if(empty($rsUser['member_points']) || empty($rsUser['return_time'])){
            $date=strtotime(date("Y-m-d"));    
        }else{
            $date=$rsUser['return_time'];
        }
        
 
        $di = $data['amount']-$data['amount']*$net;
        switch ($change_type){
            case 'order_pay':
                $data_log['lg_av_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '下单，支付预存款，订单号: '.$data['order_sn'];
                $data['amount']=$data['amount']+0.08*$data['amount'];
                if($data['member_level']=='5'){
                    $data_pd['province_predeposit'] = array('exp','province_predeposit-'.$data['amount']);
                    $available=$rsUser['province_predeposit']-$data['amount'];
                }else{
                    $data_pd['available_predeposit'] = array('exp','available_predeposit-'.$data['amount']);
                    $available=$rsUser['available_predeposit']-$data['amount'];
                   
                }
                //生成返现余额安全码               
                $available_array=['id'=>$data['member_id'],'amt'=>$available];
                $available_code = Ze\Secure::encode($available_array);
                $data_pd['available_code']=$available_code;
                
                $data_msg['av_amount'] = -$data['amount'];
                $data_msg['freeze_amount'] = 0;
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'order_freeze':
                $data_log['lg_av_amount'] = -$data['amount'];
                $data_log['lg_freeze_amount'] = $data['amount'];
                $data_log['lg_desc'] = '下单，冻结预存款，订单号: '.$data['order_sn'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit+'.$data['amount']);
                $data_pd['available_predeposit'] = array('exp','available_predeposit-'.$data['amount']);

                $data_msg['av_amount'] = -$data['amount'];
                $data_msg['freeze_amount'] = $data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'order_cancel':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '取消订单，解冻预存款，订单号: '.$data['order_sn'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit-'.$data['amount']);
                $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['amount']);

                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'order_comb_pay':
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '下单，支付被冻结的预存款，订单号: '.$data['order_sn'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit-'.$data['amount']);

                $data_msg['av_amount'] = 0;
                $data_msg['freeze_amount'] = $data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'order_invite':
                $data_log['lg_av_amount'] = +$data['amount'];
                $data_log['lg_desc'] = '分销，获得推广佣金，订单号: '.$data['order_sn'];
                $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['amount']);

                $data_msg['av_amount'] = +$data['amount'];
                $data_msg['freeze_amount'] = 0;
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'recharge':
                           
                $data_log['lg_admin_name'] = $data['admin_name'];
                if($data['pdr_type']!='1' && $data['pdr_type']!='2'){
                    
                    //生成充值余额安全码
                    $predeposit=$rsUser['member_predeposit']+$amout;
                    $predeposit_array=['id'=>$rsUser['member_id'],'amt'=>$predeposit];
                    $predeposit_code = Ze\Secure::encode($predeposit_array);
                    $data_pd['predeposit_code']=$predeposit_code;
                    //生成云豆余额安全码
                    $points=$rsUser['member_points']+$data['amount'];
                    $points_array=['id'=>$data['member_id'],'amt'=>$points];
                    $points_code = Ze\Secure::encode($points_array);
                    $data_pd['points_code']=$points_code;
                  
                    $data_log['lg_av_amount'] = $data['amount'];
                    $data_log['lg_desc'] = '充值，充值单号: '.$data['pdr_sn'];
                    $data_pd['member_predeposit'] = array('exp','member_predeposit+'.$amout);
                    $data_pd['member_points'] = array('exp','member_points+'.$data['amount']);
                    $dat['pl_points']=$data['amount'];
                    $dat['pl_desc']="充值赠云豆";
                    $dat['pl_counter']=$data['amount']-$amout;
                }else{
                    // 生成云豆余额安全码
                    $points1=$rsUser['member_points']+$points;
                    $points_array=['id'=>$data['member_id'],'amt'=>$points1];
                    $points_code = Ze\Secure::encode($points_array);
                    $data_pd['points_code']=$points_code;


                    $data_log['lg_av_amount'] = $points;
                    $data_pd['member_points'] = array('exp','member_points+'.$points);
                    $data_log['lg_desc'] = '兑换云豆，兑换单号: '.$data['pdr_sn'];
                    if($data['pdr_type']==1){
                        $dat['pl_desc']="兑换云豆5%服务费";
                    }elseif($data['pdr_type']==2){
                        $dat['pl_desc']="兑换云豆8%服务费";
                    }
                    $dat['pl_points']=$points;
                }
                $data_pd['return_time']= $date;
                $dat['pl_stage']='rechart';
                $dat['pl_memberid']=$data['member_id'];
                $dat['pl_membername']=$data['member_name'];
                
                
                $dat['pl_addtime']=time();
                
                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = 0;
                $data_msg['desc'] = $data_log['lg_desc'];
                break;

            case 'refund':

                // $order=Model('order');
                $order_goods=Model('order_goods');
                //20170717潘丙福添加开始-判断退款订单里的字段-goods_id,order_goods_id是否为0
                $refundReturnArray = Model()->table('refund_return')->field('goods_id,order_goods_id')->find($data['refund_id']);

                if ($refundReturnArray['goods_id'] == 0 && $refundReturnArray['order_goods_id'] == 0) {

                    $returnPoints = round($data['pan_points'], 2);
                    

                } else {

                    $order_goods_info=$order_goods->where(array('rec_id'=>$refundReturnArray['order_goods_id']))->find();


                    $returnPoints = round($data['amount'] * $order_goods_info['goods_points'] / $order_goods_info['goods_price'], 2);


                }
                //20170717潘丙福添加结束
        
                $data_log['lg_av_amount']     = $data['amount'];
                $data_log['lg_desc']          = '确认退款，订单号: '.$data['order_sn'];
                
                //20171012潘丙福添加开始--判断订单付款方式从而确定退款返回到哪个钱包
                if ($data['payment_code'] == 'predeposit') {
                    $data_log['lg_type'] = 'refund_predepos';
                    $data_log['lg_av_amount'] = round($data['amount']*1.08, 2);
                    //20171019潘丙福添加开始--充值$data['amount']
                    $data['amount'] = round($data['amount']*1.08, 2);
                    //20171019潘丙福添加结束
                    //生成云豆可用余额安全码
                    $available_predeposit=$rsUser['available_predeposit']+round($data['amount']*1.08, 2);
                    $available_predeposit_array=['id'=>$rsUser['member_id'],'amt'=>$available_predeposit];
                    $available_predeposit_code = Ze\Secure::encode($available_predeposit_array);
                    $data_pd['available_code']=$available_predeposit_code;

                    $data_pd['available_predeposit'] = array('exp','available_predeposit+'.round($data['amount']*1.08, 2));
                } else {
                    //生成充值余额安全码
                    $predeposit=$rsUser['member_predeposit']+$data['amount'];
                    $predeposit_array=['id'=>$rsUser['member_id'],'amt'=>$predeposit];
                    $predeposit_code = Ze\Secure::encode($predeposit_array);
                    $data_pd['predeposit_code']=$predeposit_code;
                    $data_pd['member_predeposit'] = array('exp','member_predeposit+'.$data['amount']);
                }
                //20171012潘丙福添加结束
                //20170916潘丙福添加开始--当订单状态为已付款未发货时不增加云豆
                if ($data['pan_order_status'] > 20) {
                     //生成云豆余额安全码
                    $points=$rsUser['member_points']+$returnPoints;
                    $points_array=['id'=>$data['member_id'],'amt'=>$points];
                    $points_code = Ze\Secure::encode($points_array);
                    $data_pd['points_code']=$points_code;

                    $data_pd['member_points']     = array('exp','member_points+'.$returnPoints);
                }
                if ($data['pan_order_status'] > 20) {
                    $dat['pl_stage']              ='refund';
                    $dat['pl_memberid']           = $data['member_id'];
                    $dat['pl_membername']         = $data['member_name'];
                    $dat['pl_points']             = $returnPoints;
                    $dat['pl_counter']            = '';
                    $dat['pl_addtime']            = time();
                    $dat['pl_desc']               = "确认退款增加云豆，订单号：".$data['order_sn'];
                } else {
                    $dat['pl_stage']              ='refund';
                    $dat['pl_memberid']           = $data['member_id'];
                    $dat['pl_membername']         = $data['member_name'];
                    $dat['pl_points']             = 0;
                    $dat['pl_counter']            = '';
                    $dat['pl_addtime']            = time();
                    $dat['pl_desc']               = "确认退款因未发货会员云豆无需修改,订单号：".$data['order_sn'];                    
                }
                $data_msg['av_amount']        = $data['amount'];
                $data_msg['freeze_amount']    = 0;
                $data_msg['desc']             = $data_log['lg_desc'];

                break;
            case 'vr_refund':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_desc'] = '虚拟兑码退款成功，订单号: '.$data['order_sn'];
                $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['amount']);

                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = 0;
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
           case 'cash_apply':
                $data_log['lg_av_amount'] = -$data['amount'];
                $data_log['lg_freeze_amount'] = $data['amount'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit+'.$data['amount']);  
                if($data['predeposit_type']==1){   
                    $data_log['lg_av_amount'] = -$data['amount'];
                    $data_log['lg_freeze_amount'] = $di; 
                    $data_msg['av_amount'] = -$data['amount'];
                    $data_msg['freeze_amount'] = $data['amount'];
                    $data_msg['desc'] = $data_log['lg_desc'];               
                    $data_log['lg_desc'] = '申请提现，冻结云豆余额，提现单号: '.$data['order_sn'];
                    //生成返现余额安全码
                    $available=$rsUser['available_predeposit']-$data['amount'];
                    $available_array=['id'=>$data['member_id'],'amt'=>$available];
                    $available_code = Ze\Secure::encode($available_array);
                    $data_pd['available_code']=$available_code;
                    $data_pd['available_predeposit'] = array('exp','available_predeposit-'.$data['amount']);                    
                }elseif($data['predeposit_type']==2){   
                    $data_log['lg_av_amount'] = -$data['amount'];
                    $data_log['lg_freeze_amount'] = $data['amount'];
                    $data_msg['av_amount'] = -$data['amount'];
                    $data_msg['freeze_amount'] = $data['amount'];
                    $data_msg['desc'] = $data_log['lg_desc'];               
                    $data_log['lg_desc'] = '申请提现，冻结充值余额，提现单号: '.$data['order_sn'];
                    //生成充值余额安全码
                    $predeposit=$rsUser['member_predeposit']-$data['amount'];
                    $predeposit_array=['id'=>$rsUser['member_id'],'amt'=>$predeposit];
                    $predeposit_code = Ze\Secure::encode($predeposit_array);
                    $data_pd['predeposit_code']=$predeposit_code;

                    $data_pd['member_predeposit'] = array('exp','member_predeposit-'.$data['amount']);                  
                }elseif($data['predeposit_type']==3){ 
                    $data_log['lg_av_amount'] = -$data['amount'];
                    $data_log['lg_freeze_amount'] = $data['amount'];
                    $data_msg['av_amount'] = -$data['amount'];
                    $data_msg['freeze_amount'] = $data['amount'];
                    $data_msg['desc'] = $data_log['lg_desc'];                  
                    $data_log['lg_desc'] = '申请提现，冻结奖金余额，提现单号: '.$data['order_sn'];
                    $data_pd['distributor_predeposit'] = array('exp','distributor_predeposit-'.($data['amount']+($data['amount']*0.01)));                   
                }elseif($data['predeposit_type']==5){                   
                    $data_log['lg_av_amount'] = -$data['amount'];
                    $data_log['lg_freeze_amount'] = $data['amount'];
                    $data_log['lg_desc'] = '申请提现，冻结省代余额，提现单号: '.$data['order_sn'];
                    //生成返现余额安全码
                    $available=$rsUser['province_predeposit']-$data['amount'];
                    $available_array=['id'=>$data['member_id'],'amt'=>$available];
                    $available_code = Ze\Secure::encode($available_array);
                    $data_pd['available_code']=$available_code;

                    $data_pd['province_predeposit'] = array('exp','province_predeposit-'.$data['amount']);                   
                }elseif($data['predeposit_type']==6){                   
                    $data_log['lg_av_amount'] = -$data['amount'];
                    $data_log['lg_freeze_amount'] = $data['amount'];
                    $data_log['lg_desc'] = '申请提现，冻结代理余额，提现单号: '.$data['order_sn'];
                    //生成代理余额安全码
                    $agent=$rsUser['agent_predeposit']-$data['amount'];
                    $agent_array=['id'=>$data['member_id'],'amt'=>$agent];
                    $agent_code = Ze\Secure::encode($agent_array);
                    $data_pd['agent_code']=$available_code;
                    
                    $data_pd['agent_predeposit'] = array('exp','agent_predeposit-'.$data['amount']);                   
                }
                break;
            case 'cash_pay':
                $data_log['lg_freeze_amount'] = -$data['amount'];
                if($data['predeposit_type'] ==1){
                    $data_log['lg_desc'] = '云豆余额提现成功，提现单号: '.$data['order_sn'];
                }elseif($data['predeposit_type'] ==2){
                    $data_log['lg_desc'] = '充值余额提现成功，提现单号: '.$data['order_sn'];
                }elseif($data['predeposit_type'] ==3){
                    $data_log['lg_desc'] = '奖金余额提现成功，提现单号: '.$data['order_sn'];
                }elseif($data['predeposit_type'] ==5){
                    $data_log['lg_desc'] = '省代余额提现成功，提现单号: '.$data['order_sn'];
                }elseif($data['predeposit_type'] ==6){
                    $data_log['lg_desc'] = '代理余额提现成功，提现单号: '.$data['order_sn'];
                }
                
                $data_log['lg_admin_name'] = $data['admin_name'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit-'.$data['amount']);

                $data_msg['av_amount'] = 0;
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'cash_del':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_admin_name'] = $data['admin_name'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit-'.$data['amount']);  
                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                if($data['predeposit_type']==1){                    
                    $data_log['lg_desc'] = '取消提现申请，解冻预存款，提现单号: '.$data['order_sn'];                 
                    $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['amount']);                    
                }elseif($data['predeposit_type']==2){                   
                    $data_log['lg_desc'] = '取消提现申请，解冻充值余额，提现单号: '.$data['order_sn'];
                    $data_pd['member_predeposit'] = array('exp','member_predeposit+'.$data['amount']);                 
                }elseif($data['predeposit_type']==3){                   
                    $data_log['lg_desc'] = '取消提现申请，解冻分销余额，提现单号: '.$data['order_sn'];
                    $data_pd['distributor_predeposit'] = array('exp','distributor_predeposit+'.$data['amount']);                   
                }
                break;
            case 'order_book_cancel':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_desc'] = '取消预定订单，退还预存款，订单号: '.$data['order_sn'];
                $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['amount']);

                $data_msg['av_amount'] = $data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            //好商城新增
            case 'sys_add_money':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_desc'] = '管理员调节充值余额【增加】，充值单号: '.$data['pdr_sn'];
                $data_log['lg_admin_name'] = $data['admin_name'];
                //生成充值余额安全码
                $predeposit=$rsUser['member_predeposit']+$data['amount'];
                $predeposit_array=['id'=>$rsUser['member_id'],'amt'=>$predeposit];
                $predeposit_code = Ze\Secure::encode($predeposit_array);
                $data_pd['predeposit_code']=$predeposit_code;
                   
                $data_pd['member_predeposit'] = array('exp','member_predeposit+'.$data['amount']);

                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = 0;
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'sys_del_money':
                $data_log['lg_av_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '管理员调节充值余额【减少】，充值单号: '.$data['pdr_sn'];
                
                //生成充值余额安全码
                $predeposit=$rsUser['member_predeposit']-$data['amount'];
                $predeposit_array=['id'=>$rsUser['member_id'],'amt'=>$predeposit];
                $predeposit_code = Ze\Secure::encode($predeposit_array);
                $data_pd['predeposit_code']=$predeposit_code;

                $data_pd['member_predeposit'] = array('exp','member_predeposit-'.$data['amount']);

                $data_msg['av_amount'] = -$data['amount'];
                $data_msg['freeze_amount'] = 0;
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'sys_freeze_money':
                $data_log['lg_av_amount'] = -$data['amount'];
                $data_log['lg_freeze_amount'] = +$data['amount'];
                $data_log['lg_desc'] = '管理员调节充值余额【冻结】，充值单号: '.$data['pdr_sn'];
                $data_pd['member_predeposit'] = array('exp','member_predeposit-'.$data['amount']);
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit+'.$data['amount']);

                $data_msg['av_amount'] = -$data['amount'];
                $data_msg['freeze_amount'] = +$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'sys_unfreeze_money':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '管理员调节充值余额【解冻】，充值单号: '.$data['pdr_sn'];
                $data_log['lg_admin_name'] = $data['admin_name'];
                $data_pd['member_predeposit'] = array('exp','member_predeposit+'.$data['amount']);
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit-'.$data['amount']);

                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'seller_money':
                $msg=$data['msg'];
                $data_log['lg_freeze_amount'] = +$data['amount'];
                $data_log['lg_desc'] = '卖出商品收入,扣除拥金'.$msg.',订单号: '.$data['pdr_sn'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit+'.$data['amount']);
                $data_msg['av_amount'] = 0;
                $data_msg['freeze_amount'] = +$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'seller_refund':
                $msg=$data['msg'];
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '商家退款支出,扣除预存款'.$msg.',订单号: '.$data['order_sn'];
                $data_pd['freeze_predeposit'] = array('exp','freeze_predeposit-'.$data['amount']);
                $data_msg['av_amount'] = 0;
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            default:
                throw new Exception('参数错误');
                break;
        }
        $update = Model('member')->editMember(array('member_id'=>$data['member_id']),$data_pd);

        if (!$update) {
            throw new Exception('操作失败');
        }
        $instrdat=$this->table('points_log')->insert($dat);
        
        $insert = $this->table('pd_log')->insert($data_log);
        
        if (!$insert) {
            throw new Exception('操作失败');
        }

        // 支付成功发送买家消息
        $param = array();
        $param['code'] = 'predeposit_change';
        $param['member_id'] = $data['member_id'];
        $data_msg['av_amount'] = ncPriceFormat($data_msg['av_amount']);
        $data_msg['freeze_amount'] = ncPriceFormat($data_msg['freeze_amount']);
        $param['param'] = $data_msg;
        QueueClient::push('addConsume', array('member_id'=>$data['member_id'],'member_name'=>$data['member_name'],
        'consume_amount'=>$data['amount'],'consume_time'=>time(),'consume_remark'=>$data_log['lg_desc']));
        QueueClient::push('sendMemberMsg', $param);
        return $insert;
    }

    /**
     * 删除充值记录
     * @param unknown $condition
     */
    public function delPdRecharge($condition) {
        return $this->table('pd_recharge')->where($condition)->delete();
    }

    /**
     * 取得提现列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getPdCashList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->table('pd_cash')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
   /**
   *取得提现列表总金额
   */
   public function getPdCashMoneySum($condition, $field){
	     return $this->table('pd_cash')->where($condition)->sum($field);
        }
    /**
     * 添加提现记录
     * @param array $data
     */
    public function addPdCash($data) {
        return $this->table('pd_cash')->insert($data);
    }

    /**
     * 编辑提现记录
     * @param unknown $data
     * @param unknown $condition
     */
    public function editPdCash($data,$condition = array()) {
        return $this->table('pd_cash')->where($condition)->update($data);
    }

    /**
     * 取得单条提现信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getPdCashInfo($condition = array(), $fields = '*') {
        return $this->table('pd_cash')->where($condition)->field($fields)->find();
    }

    /**
     * 删除提现记录
     * @param unknown $condition
     */
    public function delPdCash($condition) {
        return $this->table('pd_cash')->where($condition)->delete();
    }
	    /**
     * 取数量
     * @param unknown $condition
     */
    public function getPdcaseCount($condition = array()) {
        return $this->table('pd_cash')->where($condition)->count();
    }
	
	
    /**
     * 取得提现列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getPdCashTotal($condition = array()) {   //特殊写法. 李志军
        return $this->table('pd_cash')->limit(0)->where($condition)->select();
    }

}

