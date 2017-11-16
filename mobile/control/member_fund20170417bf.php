<?php
/**
 * 我的余额
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买正版
 */



defined('In33hao') or exit('Access Invalid!');

class member_fundControl extends mobileMemberControl {
    public function __construct(){
        parent::__construct();
    }
    /**
     * 预存款日志列表
     */
    public function predepositlogOp(){
        $model_predeposit = Model('predeposit');
        $where = array();
        $where['lg_member_id'] = $this->member_info['member_id'];
        $where['lg_av_amount'] = array('neq',0);
        

        if($_GET['lg_type']=='distribution'){
            $where['lg_type']=$_GET['lg_type'];
        }else{
            
            $where['lg_desc'] = array(array('like','%代理提成%'),array('like','%每日赠送%'),array('like','%充值提成%'),array('like','%消费提成%'),array('like','%消费分润%'),'or');

        }

        $list = $model_predeposit->getPdLogList($where, $this->page, '*', 'lg_id desc');
        $page_count = $model_predeposit->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['lg_add_time_text'] = @date('Y-m-d H:i:s',$v['lg_add_time']);
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
    }
    /**
     * 充值卡余额变更日志
     */
    public function rcblogOp()
    {
        $model_rcb_log = Model('rcb_log');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $where['available_amount'] = array('neq',0);
        $log_list = $model_rcb_log->getRechargeCardBalanceLogList($where, $this->page, '', 'id desc');
        $page_count = $model_rcb_log->gettotalpage();
        if ($log_list) {
            foreach($log_list as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['add_time']);
                $log_list[$k] = $v;
            }
        }
        output_data(array('log_list' => $log_list), mobile_page($page_count));
    }
    /**
     * 分销余额变更日志
     */
    public function fenxiaologOp()
    {
        $model_fenxiao_log = Model('fenxiao_log');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $where['available_amount'] = array('neq',0);
        $log_list = $model_fenxiao_log->getRechargeCardBalanceLogList($where, $this->page, '', 'id desc');
        $page_count = $model_fenxiao_log->gettotalpage();
        if ($log_list) {
            foreach($log_list as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['add_time']);
                $log_list[$k] = $v;
            }
        }
        output_data(array('log_list' => $log_list), mobile_page($page_count));
    }
    /**
     * 一级直推
     */
    public function yijilogOp()
    {
        $model_fenxiao_log = Model('member');
        $where = array();
        $where['member_pid'] = $this->member_info['member_id'];
        $log_list = $model_fenxiao_log->getMembersList($where);       
        if ($log_list) {
            foreach($log_list as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['member_time']);
                $v['member_id'] = $v['member_id'];
                $v['member_level'] = $this->member_level_name($v['member_level']);
                $log_list[$k] = $v;
            }
        }
        output_data(array('log_list' => $log_list));
    }
    /**
     * 端口代理
     */
    public function partlogOp()
    {
        $model_fenxiao_log = Model('member');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $log_list=array();
        if($this->member_info['member_level']>2){
            $log_list = $model_fenxiao_log->getMembersListpart($where);       
            if ($log_list) {
                foreach($log_list as $k=>$v){
                    $v['add_time_text'] = @date('Y-m-d H:i:s',$v['member_time']);
                    $v['member_id'] = $v['member_id'];
                    //$v['member_level'] = $this->member_level_name($v['member_level']);
                    $log_list[$k] = $v;
                }
            }
        }
        output_data(array('log_list' => $log_list));
    }
    /**
     * 区县代理
     */
    public function quxianlogOp()
    {
        $model_fenxiao_log = Model('member');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $log_list=array();
        if($this->member_info['member_level']>3){
            $log_list = $model_fenxiao_log->getMembersListquxian($where);       
            if ($log_list) {
                foreach($log_list as $k=>$v){
                    $v['add_time_text'] = @date('Y-m-d H:i:s',$v['member_time']);
                    $v['member_id'] = $v['member_id'];
                    //$v['member_level'] = $this->member_level_name($v['member_level']);
                    $log_list[$k] = $v;
                }
            }
        }
        output_data(array('log_list' => $log_list));
    }
    /**
     * 市级代理
     */
    public function citylogOp()
    {
        $model_fenxiao_log = Model('member');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $log_list=array();
        if($this->member_info['member_level']>4){
            $log_list = $model_fenxiao_log->getMembersListcity($where);       
            if ($log_list) {
                foreach($log_list as $k=>$v){
                    $v['add_time_text'] = @date('Y-m-d H:i:s',$v['member_time']);
                    $v['member_id'] = $v['member_id'];
                    //$v['member_level'] = $this->member_level_name($v['member_level']);
                    $log_list[$k] = $v;
                }
            }
        }
        output_data(array('log_list' => $log_list));
    }
    /**
     * 二级直推
     */
    public function erjilogOp()
    {
        $model_fenxiao_log = Model('member');
        $where = array();
        $where['member_pid'] = $this->member_info['member_id'];
        $log_list = $model_fenxiao_log->getMembersListtwo($where);       
        if ($log_list) {
            foreach($log_list as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['member_time']);
                $v['member_id'] = $v['member_id'];
                $v['member_level'] = $this->member_level_name($v['member_level']);
                $log_list[$k] = $v;
            }
        }
        output_data(array('log_list' => $log_list));
    }
    /**
     * 充值明细
     */
    public function pdrechargelistOp(){
        $where = array();
        $where['pdr_member_id'] = $this->member_info['member_id'];
        $model_pd = Model('predeposit');
        $list = $model_pd->getPdRechargeList($where, $this->page,'*','pdr_id desc');
        $page_count = $model_pd->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['pdr_add_time_text'] = @date('Y-m-d H:i:s',$v['pdr_add_time']);
                $v['pdr_payment_state_text'] = $v['pdr_payment_state']==1?'已支付':'未支付';
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
    }
    /**
     * 充值提现记录
     */
    public function pdcashlistOp(){
        $where = array();
        $where['pdc_member_id'] =  $this->member_info['member_id'];
        if($_GET['type']=='1'){
            $where['predeposit_type']=array('in','1,5,6');
        }else{
            $where['predeposit_type']=$_GET['type'];
        }
        
        $model_pd = Model('predeposit');
        $list = $model_pd->getPdCashList($where, $this->page, '*', 'pdc_id desc');
        $page_count = $model_pd->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['pdc_add_time_text'] = @date('Y-m-d H:i:s',$v['pdc_add_time']);
                $v['pdc_payment_time_text'] = @date('Y-m-d H:i:s',$v['pdc_payment_time']);
                $v['pdc_payment_state_text'] = $v['pdc_payment_state']==1?'已支付':'未支付';
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
    }
    /**
     * 充值卡充值
     */
    public function rechargecard_addOp()
    {
        $model_pdr = Model('pd_recharge');
        $member=Model('member');
        $info=$member->where(array('member_id'=>$this->member_info['member_id']))->find();
        if($info['member_bankcard']=='' || $info['member_bankname']==''){
            echo '1';exit;
        }
        if($info['member_level']==0){
            echo '2';exit;
        }
        $model_pdr = Model('predeposit');
        $data = array();
        $data['pdr_sn'] = $this->makeSns($this->member_info['member_id']);
        $data['pdr_member_id'] = $this->member_info['member_id'];
        $data['pdr_member_name'] = $this->member_info['member_name'];
        $data['pdr_amount'] = $_POST['money'];
        $data['pdr_add_time'] = TIMESTAMP;
        $insert = $model_pdr->addPdRecharge($data);
        if ($insert) {
           
            output_data($data['pdr_sn']);
            //转向到商城支付页面
            // redirect(SHOP_SITE_URL . '/index.php?act=buy&op=pd_pay&pay_sn='.$data['pdr_sn']);
        }
        /*
        $param = $_POST;
        $rc_sn = trim($param["rc_sn"]);
        if (!$rc_sn) {
            output_error('请输入平台充值卡号');
        }
        if (!Model('apiseccode')->checkApiSeccode($param["codekey"],$param['captcha'])) {
            output_error('验证码错误');
        }
        try {
            Model('predeposit')->addRechargeCard($rc_sn, array('member_id'=>$this->member_info['member_id'],'member_name'=>$this->member_info['member_name']));
            output_data('1');
        } catch (Exception $e) {
            output_error($e->getMessage());
        }
        */
    }
    /**
     * 预存款提现记录详细
     */
    public function pdcashinfoOp(){
        $param = $_GET;
        $pdc_id = intval($param["pdc_id"]);
        if ($pdc_id <= 0){
            output_error('参数错误');
        }
        $where = array();
        $where['pdc_member_id'] =  $this->member_info['member_id'];
        $where['pdc_id'] = $pdc_id;
        $info = Model('predeposit')->getPdCashInfo($where);
        if (!$info){
            output_error('参数错误');
        }
        $info['pdc_add_time_text'] = $info['pdc_add_time']?@date('Y-m-d H:i:s',$info['pdc_add_time']):'';
        $info['pdc_payment_time_text'] = $info['pdc_payment_time']?@date('Y-m-d H:i:s',$info['pdc_payment_time']):'';
        $info['pdc_payment_state_text'] = $info['pdc_payment_state']==1?'已支付':'未支付';
        output_data(array('info' => $info));
    }
    public function member_level_name($value){
      $content='';
      switch ($value)
       {
            case 0:
              $content= "未激活";
              break;
            case 1:
              $content= "会  员";
              break;
            case 2:
              $content= "端口代理";
              break;
            case 3:
              $content= "区县代理";
              break;
            case 4:
              $content= "市级代理";
              break;
            case 5:
              $content= "省级代理";
              break;
            default:
              $content= "等级未知";
       }
       return $content;
    }
    public function makeSns($uid) {
       return mt_rand(10,99)
              . sprintf('%010d',time() - 946656000)
              . sprintf('%03d', (float) microtime() * 1000)
              . sprintf('%03d', $this->member_info['member_id'] % 1000);
    }
    public function rcg_logOp(){
        $rcb=Model('rcb_log');
        $where['type']=array('in','tofrend,fromfrend');
        $where['member_id']=$this->member_info['member_id'];
        $rcbinfo=$rcb->where($where)->select();
        $page_count = $rcb->gettotalpage();
        if ($rcbinfo) {
            foreach($rcbinfo as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['add_time']);
                $rcbinfo[$k] = $v;
            }
        }
       
        output_data(array('list' =>$rcbinfo), mobile_page($page_count));
    }
}