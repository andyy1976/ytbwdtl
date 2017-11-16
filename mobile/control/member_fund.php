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
        $pd_log=Model('pd_log');
        $where = array();
        $where['lg_member_id'] = $this->member_info['member_id'];
        $where['lg_av_amount'] = array('neq',0);
        

        if($_GET['lg_type']=='recharge'){
            $where['lg_desc']=array('like','%代理提成%');
        }elseif($_GET['lg_type']=='consumption'){
            $where['lg_desc']=array('like','%代理消费提成%');
        }elseif($_GET['lg_type']=='card'){
            $where['lg_desc']=array('like','%注册分润提成%');
        }elseif($_GET['lg_type']=='port_upgrade'){
            $where['lg_type']=array('in','port_split');
        }elseif($_GET['lg_type']=='duankou'){
            $where['lg_type']=array('in','agent_sib');
        }elseif($_GET['lg_type']=='distribution'){
            $where['lg_type']=$_GET['lg_type'];
        }elseif($_GET['lg_type']=='preday'){
            $where['lg_desc']=array('like','%每日赠送%');
        }else{
            // $where['lg_type']=array('in','complimentary,')
            $where['lg_desc'] = array(array('like','%代理提成%'),array('like','%每日赠送%'),array('like','%充值提成%'),array('like','%消费提成%'),array('like','%激活赠送%'),array('like','%消费分润%'),'or');
            // $where['lg_type'] ='complimentary';
            // $where['_op'] = 'or';
        }

        $list = $model_predeposit->getPdLogList($where, $this->page, '*', 'lg_id desc');
       
        
        // $list=$pd_log->where($where)->field('lg_desc,lg_av_amount,lg_add_time')->select();
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
				if($v['pdc_payment_state']=='1'){
					$v['pdc_payment_state_text'] ='已支付';
					}elseif($v['pdc_payment_state']=='2'){
					$v['pdc_payment_state_text'] ='处理中';
					}elseif($v['pdc_payment_state']=='0'){
					$v['pdc_payment_state_text'] ='未支付';	
					}
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
        
        $member=Model('member');
        //类型5%手续费和8%手续费
        $type=$_REQUEST['type'];
        $pd_recharge = Model('pd_recharge');
        //购买5%云豆，判断当天是否满1千  7/14 START
        if($type!=null){
            $where['pdr_member_id']=$this->member_info['member_id'];
            $where['pdr_type']='2';
            $where['pdr_payment_state']='1';
            $where['pdr_add_time']=array('gt',strtotime(date("Y-m-d")));
            $recharge_amount=$pd_recharge->where($where)->sum('pdr_amount');
            $points_log=Model('points_log');
            $recharge_count=$points_log->where(array('pl_memberid'=>$this->member_info['member_id'],'pl_addtime'=>array('gt',strtotime(date("Y-m-d"))),'pl_stage'=>'rechart'))->sum('pl_points');            
            $amount=$recharge_count-$recharge_amount*12.5+$_POST['money']*20;
            if($type=='1'){
                $count=$recharge_count+$_POST['money']*20;
            }elseif($type=='2'){
                $count=$recharge_count+$_POST['money']*12.5;
            }           
            
            if($amount>20000 && $type=='1'){
                echo '3';exit;
            }
            if($_POST['money']<50 && $type=='1'){
                echo '5';exit;
            }
            if($_POST['money']<50 && $type=='2'){
                echo '5';exit;
            }
            if($count>1000000){
                echo '6';exit;
            }
        }
       
         //购买5%云豆，判断当天是否满1千  7/14 END
        $info=$member->where(array('member_id'=>$this->member_info['member_id']))->find();
        if($info['member_bankcard']=='' || $info['member_bankname']==''){
            echo '1';exit;
        }
        if($info['member_level']==0 && empty($info['free'])){
            echo '2';exit;
        }
        //判断是否是业务员
        if($info['member_level']>0 && $info['agreement_id']=='0'){
            // echo '7';
        }
        $model_pdr = Model('predeposit');
        $data = array();
        //新增充值类型
        if($type!=null){
            $data['pdr_type']=$type;
        }
        
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
    /**
     * 伞下人员
     */
    public function underOp()
    {
        $model_fenxiao_log = Model('member');
        $where = array();
        $where['split_id'] = $this->member_info['member_id'];
        $where['member_level']=array('lt','6');
        $log_list=array();
        if($this->member_info['member_level']>5){
        $log_list = $model_fenxiao_log->where($where)->select();       
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
    public function points_logOp(){
        $rcb=Model('points_log');
        $where['pl_stage']=array('in','give_points,points');
        $where['pl_memberid']=$this->member_info['member_id'];
        $rcbinfo=$rcb->where($where)->select();
        $page_count = $rcb->gettotalpage();
        if ($rcbinfo) {
            foreach($rcbinfo as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['pl_addtime']);
                $rcbinfo[$k] = $v;
            }
        }
       
        output_data(array('list' =>$rcbinfo), mobile_page($page_count));
    }
    public function agentOp(){
        $statistics=Model('statistics');
        $statistics_info=$statistics->where(array('lg_member_id'=>$_GET['member_id']))->select();
        // print_r($statistics_info);
        $page_count = $statistics->gettotalpage();
        if ($statistics_info) {
            foreach($statistics_info as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['lg_add_time']);
                $statistics_info[$k] = $v;
            }
        }
       
        output_data(array('list' =>$statistics_info), mobile_page($page_count));
    }
    //更新为业务员
    public function agreement_idOp(){
        $member=Model('member');
        $member->where(array('member_id'=>$this->member_info['member_id']))->update(array('agreement_id'=>'1'));
    }
}