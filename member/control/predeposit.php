<?php
/**
 * 预存款管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class predepositControl extends BaseMemberControl {
    public function __construct(){
        parent::__construct();
        Language::read('member_predeposit');
    }

    /**
     * 充值添加
     */
    public function recharge_addOp(){
        
        
        //判断是否是业务员
        $agreement_id=$_REQUEST['agreement_id'];
        //确定成为业务员，就更新agreement_id
        if($agreement_id=='1'){
            $member->where(array('member_id'=>$_SESSION['member_id']))->update(array('agreement_id'=>'1'));
        }
        $member=Model('member');
        $info=$member->where(array('member_id'=>$_SESSION['member_id']))->find();
        if($info['member_bankcard']=='' || $info['member_bankname']==''){
            showMessage('农行卡信息未完善，请重新绑定农行卡！！！！', 'index.php?act=member_redpacket&op=rp_binding', 'html', 'error');
        }
        if (!chksubmit()){
            //信息输出
            self::profile_menu('recharge_add','recharge_add');
            Tpl::showpage('predeposit.pd_add');
            exit();
        }
        if($_REQUEST['type']){
            $pdr_amount = abs(floatval($_POST['pdr_amount']))-rand(0,10);
        }else{
            $pdr_amount = abs(floatval($_POST['pdr_amount']))+rand(0,10);
        }
        
        if ($pdr_amount <= 0) {
            showMessage(Language::get('predeposit_recharge_add_pricemin_error'),'','html','error');
        }
        $model_pdr = Model('predeposit');
        $data = array();
        //新增充值类型
        //类型5%手续费和8%手续费
        if($_REQUEST['type']){
            $type=$_REQUEST['type'];
            $data['pdr_type']=$type;
        }
        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();
        $data['pdr_member_id'] = $_SESSION['member_id'];
        $data['pdr_member_name'] = $_SESSION['member_name'];
        $data['pdr_amount'] = $pdr_amount;
        $data['pdr_add_time'] = TIMESTAMP;
        $insert = $model_pdr->addPdRecharge($data);
        if ($insert) {
            //转向到商城支付页面
            redirect(SHOP_SITE_URL . '/index.php?act=buy&op=pd_pay&pay_sn='.$pay_sn);
        }
    }

    /**
     * 平台充值卡
     */
    public function rechargecard_addOp()
    {
        
        self::profile_menu('rechargecard_add','rechargecard_add');
        Tpl::showpage('predeposit.rechargecard_add');
        
        /*
        $sn = (string) $_POST['rc_sn'];
        if (!$sn || strlen($sn) > 50) {
            showMessage('平台充值卡卡号不能为空且长度不能大于50', '', 'html', 'error');
            exit;
        }

        try {
            model('predeposit')->addRechargeCard($sn, $_SESSION);
            showMessage('平台充值卡使用成功', urlMember('predeposit', 'rcb_log_list'));
        } catch (Exception $e) {
            showMessage($e->getMessage(), '', 'html', 'error');
            exit;
        }
        */
    }

    /**
     * 充值列表
     */
    public function indexOp(){
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        if (!empty($_GET['pdr_sn'])) {
            $condition['pdr_sn'] = $_GET['pdr_sn'];
        }

        $model_pd = Model('predeposit');
        $list = $model_pd->getPdRechargeList($condition,20,'*','pdr_id desc');
        $mem = model('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
        self::profile_menu('log','recharge_list');
        Tpl::output('list',$list);
        Tpl::output('mem',$mem);
        Tpl::output('show_page',$model_pd->showpage());

        Tpl::showpage('predeposit.pd_list');
    }

    /**
     * 查看充值详细
     *
     */
    public function recharge_showOp(){

        $pdr_id = intval($_GET["id"]);
        if ($pdr_id <= 0){
            showDialog(Language::get('predeposit_parameter_error'),'','error');
        }

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        $condition['pdr_id'] = $pdr_id;
        $condition['pdr_payment_state'] = 1;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (!$info){
            showDialog(Language::get('predeposit_record_error'),'','error');
        }
        Tpl::output('info',$info);
        self::profile_menu('rechargeinfo','rechargeinfo');
        Tpl::showpage('predeposit.pd_info');
    }

    /**
     * 删除充值记录
     *
     */
    public function recharge_delOp(){
        $pdr_id = intval($_GET["id"]);
        if ($pdr_id <= 0){
            showDialog(Language::get('predeposit_parameter_error'),'','error');
        }

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        $condition['pdr_id'] = $pdr_id;
        $condition['pdr_payment_state'] = 0;
        $result = $model_pd->delPdRecharge($condition);
        if ($result){
            showDialog(Language::get('nc_common_del_succ'),'reload','succ','CUR_DIALOG.close()');
        }else {
            showDialog(Language::get('nc_common_del_fail'),'','error');
        }
    }

    /**
     * 预存款变更日志
     */
    public function pd_log_listOp(){
        $model_pd = Model('predeposit');
        $condition = array();
        $class_type=$_GET['class_type'];

        
        if($class_type=='1'){
            $condition['lg_desc']=array('like','%代理提成%');
        }
        elseif($class_type=='2'){
            $condition['lg_desc']=array('like','%注册分润提成%');
        }
        elseif($class_type=='3'){
            $condition['lg_desc']=array('like','%代理消费提成%');
        }
        elseif($class_type=='4'){
            $condition['lg_type']=array('in','port_split');
        }
        elseif($class_type=='5'){
            $condition['lg_type']=array('in','agent_sib');
        } elseif ($class_type == '6') {
            $condition['lg_type'] = array('eq','distribution');
        } elseif ($class_type == '7') {
            $condition['lg_desc'] = '每日赠送';
        }
        $condition['lg_member_id'] = $_SESSION['member_id'];
        $list = $model_pd->getPdLogList($condition,15,'*','lg_id desc');
        

        //信息输出
        self::profile_menu('log','loglist');
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::output('list',$list);
        Tpl::showpage('predeposit.pd_log_list');
    }

    /**
     * 充值余额变更日志
     */
    public function rcb_log_listOp()
    {
        $model = Model();
        $list = $model->table('rcb_log')->where(array(
            'member_id' => $_SESSION['member_id'],
        ))->page(20)->order('id desc')->select();

        //信息输出
        self::profile_menu('log', 'rcb_log_list');
        Tpl::output('show_page', $model->showpage());
        Tpl::output('list', $list);
        Tpl::showpage('predeposit.rcb_log_list');
    }
    
    //分销余额变更日志
    public function rcb_log_fenxiaoOp()
    {
        $model = Model();
        $list = $model->table('fenxiao_log')->where(array(
            'member_id' => $_SESSION['member_id'],
        ))->page(20)->order('id desc')->select();

        //信息输出
        self::profile_menu('log', 'rcb_log_fenxiao');
        Tpl::output('show_page', $model->showpage());
        Tpl::output('list', $list);
        Tpl::showpage('predeposit.rcb_log_fenxiao');
    }
    /**
     * 申请提现
     */
    public function pd_cash_addOp(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $pdc_amount = abs(floatval($_POST['pdc_amount']));
            $validate_arr[] = array("input"=>$pdc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>Language::get('predeposit_cash_add_pricemin_error'));
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }

            $model_pd = Model('predeposit');
            $model_member = Model('member');
            //判断是否是业务员
            $agreement_id=$_REQUEST['agreement_id'];
            //确定成为业务员，就更新agreement_id
            if($agreement_id=='1'){
                $model_member->where(array('member_id'=>$_SESSION['member_id']))->update(array('agreement_id'=>'1'));
            }
            //限制充值提现，每日只能提现一次
            $pd_cash = Model('pd_cash');
            $date_time=strtotime(date('Y-m-d'));
            $pd_cash_info=$pd_cash->where(array('pdc_member_id'=>$_SESSION['member_id'],'pdc_add_time'=>array('gt',$date_time),'predeposit_type'=>'2'))->count();
            if($pd_cash_info>'5' && $_POST['predeposit_type']==2){
                showDialog('充值提现每日只能提现五次！！！！','','error');
            }
            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
            //验证是否是会员
            if($member_info['member_level']==0 && empty($member_info['free']))
            {
                showDialog('未激活账号不予提现！！！！','','error');
            }
            //验证开户名是否填写
            if($member_info['member_bankname']=='' || $member_info['member_bankcard']=='')
            {
                showDialog('农行卡信息未完善，请重新绑定农行卡！！！！','','error');
            }
            $bankname=$this->getBankInfo($member_info['member_bankcard']);
            $str1='农业';
            if(strpos($bankname,$str1)===false){
                 showDialog('该卡不是农行卡，请重新绑定农行卡！！！！','','error');
            }
            //验证支付密码
            if (md5($_POST['password']) != $member_info['member_paypwd']) {
                showDialog('支付密码错误','','error');
            }
            //验证金额是否足够
            if ($_POST['predeposit_type']==1 && floatval($member_info['available_predeposit']) < floatval($pdc_amount)){
                showDialog('对不起！云豆余额不足！','index.php?act=predeposit&op=pd_cash_list','error');
            }
           
            if( $_POST['predeposit_type']==1 && $pdc_amount%100 > 0){
                 showMessage('对不起！可用余额提现必须是100的倍数！');
            }
            if ($_POST['predeposit_type']==2 && floatval($member_info['member_predeposit']) < $pdc_amount){
                showDialog('对不起！充值余额不足！','index.php?act=predeposit&op=pd_cash_list','error');
            }

            if ($_POST['predeposit_type']==3 && floatval($member_info['distributor_predeposit']) < $pdc_amount  ){
                showDialog('对不起！奖金余额不足！','index.php?act=predeposit&op=pd_cash_list','error');
            }
            if($_POST['predeposit_type']==3 && $pdc_amount%100 > 0){
                showMessage('对不起！奖金余额提现必须是100的倍数！');
            }
            if($_POST['predeposit_type']==3 && floatval($member_info['distributor_predeposit'])<floatval($pdc_amount*0.01+$pdc_amount)){
                showMessage('对不起！奖金余额不足！');
            }
            if ($_POST['predeposit_type']==5 && floatval($member_info['province_predeposit']) < floatval($pdc_amount)){
                showDialog('对不起！省代钱包余额不足！','index.php?act=predeposit&op=pd_cash_list','error');
            }
            
            if( $_POST['predeposit_type']==5 && $pdc_amount%100 > 0){
                 showMessage('对不起！省代钱包余额提现必须是100的倍数！');
            }
            if ($_POST['predeposit_type']==6 && floatval($member_info['agent_predeposit']) < floatval($pdc_amount)){
                showDialog('对不起！代理钱包余额不足！','index.php?act=predeposit&op=pd_cash_list','error');
            }
           
            if( $_POST['predeposit_type']==6 && $pdc_amount%100 > 0){
                 showMessage('对不起！代理钱包余额提现必须是100的倍数！');
            }
            if($_POST['predeposit_type']!=1 && $_POST['predeposit_type']!=2 && $_POST['predeposit_type']!=3 && $_POST['predeposit_type']!=5 && $_POST['predeposit_type']!=6 ){
                showMessage('提现数据异常！');
            }
            
            try {
                $model_pd->beginTransaction();
                $pdc_sn = $model_pd->makeSn();
                $data = array();
                $data['pdc_sn'] = $pdc_sn;
                $data['pdc_member_id'] = $_SESSION['member_id'];
                $data['pdc_member_name'] = $_SESSION['member_name'];
                //云豆余额每月免费提现5次
        
                $date_time=strtotime(date('Y-m-01'));
                $available_info=$pd_cash->where(array('pdc_member_id'=>$_SESSION['member_id'],'pdc_add_time'=>array('gt',$date_time),'predeposit_type'=>'1'))->count();

                if($_POST['predeposit_type']==1){
                    if($available_info<'4'){
                        $data['pdc_amount'] = $pdc_amount * 0.87;
                    }else{
                        $data['pdc_amount'] = ($pdc_amount * 0.87)-5;
                    }
                    
                }elseif($_POST['predeposit_type']==5){
                    if($available_info<'4'){
                        $data['pdc_amount'] = $pdc_amount * 0.87;
                    }else{
                        $data['pdc_amount'] = ($pdc_amount * 0.87)-5;
                    }
                }
                if($_POST['predeposit_type']==2){
                	$data['pdc_amount'] = $pdc_amount;
                }
                // if($_POST['predeposit_type']==2){
                //     // $member_data=member_cash($pdc_amount,$_SESSION['member_id']);
                    
                //     // if($member_data['type']=='1'){
                //     //     $data['type']='ybzf';
                //     // }elseif($member_data['type']=='2'){
                //     //     // $pdc_sn1 = $model_pd->makeSn();
                //     //     // $data_1['pdc_sn'] = $pdc_sn1;
                //     //     // $data_1['pdc_member_id'] = $_SESSION['member_id'];
                //     //     // $data_1['pdc_member_name'] = $_SESSION['member_name'];
                //     //     // $data_1['pdc_amount'] = $member_data['amount'];
                //     //     // $data_1['type']='unionpay';
                //     //     // $data_1['pdc_bank_name'] = '中国农业银行';
                //     //     // $data_1['predeposit_type'] = $_POST['predeposit_type'];
                //     //     // $data_1['pdc_bank_no'] = $member_info['member_bankcard'];
                //     //     // $data_1['pdc_bank_user'] = $member_info['member_bankname'];
                //     //     // $data_1['pdc_add_time'] = TIMESTAMP;
                //     //     // $data_1['pdc_payment_state'] = 0;
                       
                //     //     // $insert1 = $model_pd->addPdCash($data_1);
                //     //     // $data['type']='ybzf';
                //     //     // if (!$insert1) {
                //     //     //     echo '3';exit();
                //     //     // }
                //     //     $data['pdc_amount'] = $pdc_amount-$member_data['amount'];
                //     // }else{
                //     $data['type']='unionpay';
                //     // }
                // } 
                if($_POST['predeposit_type']==3){
                	$data['pdc_amount'] = $pdc_amount;
                }
                
                if($_POST['predeposit_type']==6){
                    $data['pdc_amount'] = $pdc_amount*0.98;
                }
                $data['pdc_bank_name'] ='农业银行';
                $data['predeposit_type'] = $_POST['predeposit_type'];//加入的。。。。。。
                $data['pdc_bank_no'] = $member_info['member_bankcard'];
                $data['pdc_bank_user'] = $member_info['member_bankname'];
                $data['pdc_add_time'] = TIMESTAMP;
                $data['pdc_payment_state'] = 0;
                $insert = $model_pd->addPdCash($data);
                if (!$insert) {
                    throw new Exception(Language::get('predeposit_cash_add_fail'));
                }
                //冻结可用预存款
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $pdc_amount;
                $data['order_sn'] = $pdc_sn;
                $data['predeposit_type'] = $_POST['predeposit_type'];//加入的。。。。。。
                $model_pd->changePd('cash_apply',$data);
                $model_pd->commit();
                 if($data['predeposit_type'] == 1){//可用金额体现
                   showDialog('亲，您的可用云豆提现申请成功在72小时到账','index.php?act=predeposit&op=pd_cash_list','succ');      
                }
                if($data['predeposit_type'] == 2){//充值金额体现
                   showDialog('亲,您的充值金额提现申请成功在T+1的时间到账','index.php?act=predeposit&op=pd_cash_list','succ');      
                }
                if($data['predeposit_type'] == 3){//奖金金额体现
                   showDialog('亲,你分销奖金提现申请成功在72小时内到账','index.php?act=predeposit&op=pd_cash_list','succ');      
                }
                if($data['predeposit_type'] == 5){//省代金额体现
                   showDialog('亲,你省代余额提现申请成功在72小时内到账','index.php?act=predeposit&op=pd_cash_list','succ');      
                }
                if($data['predeposit_type'] == 6){//省代金额体现
                   showDialog('亲,你代理余额提现申请成功在72小时内到账','index.php?act=predeposit&op=pd_cash_list','succ');      
                }
            } catch (Exception $e) {
                $model_pd->rollback();
                showDialog($e->getMessage(),'index.php?act=predeposit&op=pd_cash_list','error');
            }
        }else {
            //查询会员信息
            $member_model = Model('member');
            $member_info = $member_model->where(array('member_id'=>$_SESSION['member_id']) )->find();
            Tpl::output('member_info',$member_info);
            Tpl::showpage('member_pd_cash.add');
        }
    }

    /**
     * 提现列表
     */
    public function pd_cash_listOp(){
        $condition = array();
        $condition['pdc_member_id'] =  $_SESSION['member_id'];
        if (preg_match('/^\d+$/',$_GET['sn_search'])) {
            $condition['pdc_sn'] = $_GET['sn_search'];
        }
        if (isset($_GET['paystate_search'])){
            $condition['pdc_payment_state'] = intval($_GET['paystate_search']);
        }
        $model_pd = Model('predeposit');
        $cash_list = $model_pd->getPdCashList($condition,30,'*','pdc_id desc');
        // foreach($cash_list as $key=>$value){
        // 	// if($value['predeposit_type']==1){
        // 	// 	$cash_list[$key]['pdc_amount']=$value['pdc_amount'] / 0.87 ;
        // 	// }
        //      if($value['predeposit_type']==3){
        //         $cash_list[$key]['pdc_amount']=$value['pdc_amount']/(1-0.01) ;
        //     }  	        	
        // }
        self::profile_menu('log','cashlist');
        Tpl::output('list',$cash_list);
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::showpage('predeposit.pd_cash_list');
    }
    //卡代分成
    public function card_chiefOp(){
        $money=card_chief();
        self::profile_menu('log','card_chief');
        Tpl::output('money',$money);
        Tpl::showpage('predeposit.card_chief');
    }
    /**
     * 提现记录详细
     */
    public function pd_cash_infoOp(){
        $pdc_id = intval($_GET["id"]);
        if ($pdc_id <= 0){
            showMessage(Language::get('predeposit_parameter_error'),'index.php?act=predeposit&op=pd_cash_list','html','error');
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_member_id'] = $_SESSION['member_id'];
        $condition['pdc_id'] = $pdc_id;
        $info = $model_pd->getPdCashInfo($condition);
        if (empty($info)){
            showMessage(Language::get('predeposit_record_error'),'index.php?act=predeposit&op=pd_cash_list','html','error');
        }

        self::profile_menu('cashinfo','cashinfo');
        Tpl::output('info',$info);
        Tpl::showpage('predeposit.pd_cash_info');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key=''){
        $menu_array = array(
            array('menu_key'=>'loglist',        'menu_name'=>'云豆余额',    'menu_url'=>'index.php?act=predeposit&op=pd_log_list'),
            array('menu_key'=>'recharge_list',  'menu_name'=>'充值明细',    'menu_url'=>'index.php?act=predeposit&op=index'),
            array('menu_key'=>'cashlist',       'menu_name'=>'提现记录',    'menu_url'=>'index.php?act=predeposit&op=pd_cash_list'),
            array('menu_key'=>'rcb_log_list',   'menu_name'=>'充值余额',    'menu_url'=>'index.php?act=predeposit&op=rcb_log_list',),
            array('menu_key'=>'rcb_log_fenxiao','menu_name'=>'分销余额',    'menu_url'=>'index.php?act=predeposit&op=rcb_log_fenxiao',),//加入的。。。。。。。。        
            array('menu_key'=>'rcb_log',        'menu_name'=>'转账记录',    'menu_url'=>'index.php?act=predeposit&op=rcb_log',),
            // array('menu_key'=>'card_chief',     'menu_name'=>'上月卡代分成','menu_url'=>'index.php?act=predeposit&op=card_chief',),//加入的。。。。。。。。    
        );
        switch ($menu_type) {
            case 'rechargeinfo':
                $menu_array[] = array('menu_key'=>'rechargeinfo','menu_name'=>'充值详细',  'menu_url'=>'');
                break;
            case 'recharge_add':
                $menu_array[] = array('menu_key'=>'recharge_add','menu_name'=>'在线充值',   'menu_url'=>'');
                break;
            // case 'rechargecard_add':
            //     $menu_array[] = array('menu_key'=>'rechargecard_add','menu_name'=>'站内转账','menu_url'=>'javascript:;');
                break;
            case 'cashadd':
                $menu_array[] = array('menu_key'=>'cashadd','menu_name'=>'提现申请',    'menu_url'=>'index.php?act=predeposit&op=pd_cash_add');
                break;
            case 'cashinfo':
                $menu_array[] = array('menu_key'=>'cashinfo','menu_name'=>'提现详细',  'menu_url'=>'');
                break;
            case 'log':
            default:
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
    //充值余额转云豆
	public function money_to_pointsOp(){
        showMessage('云豆购买暂时关闭！！');
		// $points = Model('points_log');
  //       $member = Model('member');
  //       $rcb_log = Model('rcb_log');
  //       $member_common=Model('member_common');
  //       $t = time();
  //       $start=strtotime(date('Y-m-d',time()));//当天的开始时间
  //       $data=array();
  //       $data['pl_addtime']=array('gt',$start);
  //       $data['pl_memberid']=$_SESSION['member_id'];
  //       $data['pl_stage']=array('in','buy_points,rechart');
  //       $money=$_POST['money'];
  //       $pdw=md5($_POST['pwd']);
  //       $code=$_POST['code'];
  //       //验证验证码是否正确
  //       $common=$member_common->where(array('member_id'=>$_SESSION['member_id']))->find();

  //       if($common['auth_code']!=$code){
  //           echo '4';exit;
  //       }
  //        //验证验证码次数
  //       if($common['auth_code_check_times']>0){
  //           echo '5';exit;
  //       }
  //       $member_common->where(array('member_id'=>$_SESSION['member_id']))->update(array('auth_code_check_times'=>array('exp','auth_code_check_times+1')));
  //       //比对支付密码是否正确
  //       $member_pwd=$member->getfby_member_id($_SESSION['member_id'],'member_paypwd');   
  //       if($member_pwd != $pdw){
  //           echo '3';exit();
  //       }        
  //       //查询已有金额
  //       $member_info=$member->where(array('member_id'=>$_SESSION['member_id']))->find();
  //       // $member_predeposit=$member->getfby_member_id($_SESSION['member_id'],'member_predeposit');
  //       if($money > $member_info['member_predeposit']){
  //           echo '2';exit();
  //       }           
  //       //查询当日充值送的云豆总和      
  //       $today_points=$points ->where($data)->sum('pl_points');

  //       $add_points=0;
  //       $total_points = $today_points + $money * 16.666;

  //       if($today_points >= 20000){
  //       $add_points=$money * 12.5;          
  //       }
  //       if($total_points < 20000){
  //        $add_points=$money * 16.666;            
  //       }
  //       if($today_points < 20000 && $total_points > 20000){
  //        $money_a = 1200 - $today_points/16.666;
  //        $points_a = $money_a * 16.666;  
  //        $money_b = $money - $money_a;
  //        $points_b = $money_b * 12.5;    
         
  //        $add_points = 20000-$today_points + $points_b;        
  //       }
  //       $add_points=ceil($add_points);
  //       //判断转换云豆不超过1百万
  //       if($today_points>1000000 || $add_points+$today_points>1000000){
  //           echo '6';exit();
  //       }
  //       //设置返现时间
  //       if(empty($member_info['return_time']) || empty($member_info['member_points'])){
  //           $update['return_time']=$start;
  //       }
  //       $update['member_points']=array('exp','member_points+'.$add_points);      
  //       $member->where(array('member_id'=>$_SESSION['member_id']))->setDec('member_predeposit',$money);
  //       $member->where(array('member_id'=>$_SESSION['member_id']))->update($update);
  //       $a=$points->insert(array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],'pl_points'=>$add_points,'pl_addtime'=>time(),'pl_desc'=>'充值余额'.$money."元转云豆",'pl_stage'=>'buy_points','pl_counter'=>$money));
  //       $b=$rcb_log->insert(array('member_id'=>$_SESSION['member_id'],'member_name'=>$_SESSION['member_name'],'type'=>'points','available_amount'=>"-$money",'add_time'=>time(),'description'=>'转成'.$add_points.'云豆'));
  //       if($a && $b){
  //           give_se($_SESSION['member_id'],$add_points);
  //           echo '1';
  //       }else{echo '0';}
	}
	//分销余额转云豆
	public function fenxiao_to_pointsOp(){
		$points = Model('points_log');
		$member = Model('member');
		$rcb_log = Model('fenxiao_log');
		$t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $data=array();
        $data['pl_addtime']=array('gt',$start);
        $data['pl_memberid']=$_SESSION['member_id'];
        $data['pl_stage']='rechart';
        $money=$_POST['money'];
        $pdw=md5($_POST['pwd']);
        //比对支付密码是否正确
        $member_pwd=$member->getfby_member_id($_SESSION['member_id'],'member_paypwd');   
        if($member_pwd != $pdw){
        	echo '3';exit();
        }	     
        //查询已有金额
        $member_predeposit=$member->getfby_member_id($_SESSION['member_id'],'distributor_predeposit');
        if($money > $member_predeposit){
        	echo '2';exit();
        }	        
		//查询当日充值送的云豆总和		
		$today_points=$points ->where($data)->sum('pl_points');
		$add_points=0;
		$total_points = $today_points + $money * 20;
		if($today_points >= 20000){
			$add_points=$money * 12.5;			
		}
		if($total_points < 20000){
			$add_points=$money * 20;			
		}
		if($today_points < 20000 && $total_points > 20000){
			$money_a = (20000 - $today_points)/20;
			$points_a = $money_a * 20;	
			$money_b = $money - $money_a;
			$points_b = $money_b * 12.5;	
			$add_points = $points_a + $points_b;		
		}
		$member->where(array('member_id'=>$_SESSION['member_id']))->setDec('distributor_predeposit',$money);
		$member->where(array('member_id'=>$_SESSION['member_id']))->setInc('member_points',$add_points);
		$a=$points->insert(array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],'pl_points'=>$add_points,'pl_addtime'=>time(),'pl_desc'=>'分销余额'.$money."元转云豆",'pl_stage'=>'rechart'));
		$b=$rcb_log->insert(array('member_id'=>$_SESSION['member_id'],'member_name'=>$_SESSION['member_name'],'type'=>'rechart','available_amount'=>"-$money",'add_time'=>time(),'description'=>'转成'.$add_points.'云豆'));
	    if($a && $b){
	    	give_chief($_SESSION['member_id'],$add_points);
	    	echo '1';
	    }else{echo '0';}
	}
	//ajax获取转账人的用户名
	public function getnameOp(){
	  $member = Model('member');
      $member_id = $_POST['memberid'];
      $member_info=$member->where(array('member_id'=>$member_id))->find();
      $strlen     = mb_strlen($member_info['member_bankname'], 'utf-8');
      $firstStr     = mb_substr($member_info['member_bankname'], 0, 1, 'utf-8');
      $lastStr     = mb_substr($member_info['member_bankname'], -1, 1, 'utf-8');
      $member_info['member_bankname']=$strlen == 2 ? str_repeat('*', mb_strlen($member_info['member_bankname'], 'utf-8') - 1).$lastStr : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
      echo json_encode($member_info);	
	}
	//AJAX给朋友转账	
	public function tofrentOp(){		
		// $member = Model('member');
  //       $pd_log = Model('rcb_log');     
  //       $data=array();
  //       $data['pl_addtime']=array('gt',$start);
  //       $data['pl_memberid']=$_SESSION['member_id'];
  //       $data['pl_stage']='rechart';
  //       $money=$_POST['money'];
  //       $money=abs($money);
  //       $pdw=md5($_POST['pwd']);
  //       $bankname=$_POST['bank_name'];
  //       $member_id=$_POST['memberid'];
  //       //查询用户是否存在
  //       $member_frend=$member->where(array('member_id'=>$member_id))->find();
  //       if(!$member_frend){
  //           echo '4';exit();
  //       }
  //       if($member_frend['member_bankname']!=$bankname){
  //           echo '5';exit;
  //       }   
  //       //比对支付密码是否正确        
  //       $member_pwd=$member->getfby_member_id($_SESSION['member_id'],'member_paypwd');   
  //       if($member_pwd != $pdw){
  //           echo '3';exit();
  //       }        
  //       //查询已有金额
  //       $member_predeposit=$member->getfby_member_id($_SESSION['member_id'],'member_predeposit');
  //       $member_predeposit=intval($member_predeposit);
  //       if($money > $member_predeposit){
  //           echo '2';exit();
  //       }
  //       //每天转账总额不能超过10000元
  //       $start=strtotime(date('Y-m-d',time()));//当天的开始时间
  //       $count_money=$pd_log->where(array('member_id'=>$_SESSION['member_id'],'type'=>'tofrend','add_time'=>array('gt',$start)))->sum('available_amount');  
  //       $count_money=abs($count_money);
       
  //       $abc=$count_money +$money;
  //       if($count_money >=20000 || $abc >20000 || $money>20000){echo '6';exit();}  
  //       if($_SESSION['member_id']=='10088' || $_SESSION['member_id']=='10072'){
  //           //验证充值余额验证码
  //           $member_info=$member->where(array('member_id'=>$_SESSION['member_id']))->find();
  //           $member_arr=['id'=>$member_info['member_id'],'amt'=>intval($member_info['member_predeposit'])];
  //           $predeposit_code = Ze\Secure::verify($member_arr,$member_info['predeposit_code']);
  //           // var_dump($member_arr);
  //           if(!$predeposit_code){
  //               echo '11';exit;
  //           }
            
  //           //生成充值余额验证码           
  //           $predeposit_1=intval($member_info['member_predeposit']-$money);
  //           $member_arr1=['id'=>$member_info['member_id'],'amt'=>$predeposit_1];
  //           $predeposit_code1 = Ze\Secure::encode($member_arr1);
  //           $member_update['member_predeposit']=array('exp','member_predeposit-'.$money);
  //           $member_update['predeposit_code']=$predeposit_code1;
  //           $member->where(array('member_id'=>$_SESSION['member_id']))->update($member_update);

  //           //生成对方ID充值余额验证码
  //           $member_info_1=$member->where(array('member_id'=>$member_id))->find();
  //           $predeposit_2=intval($member_info_1['member_predeposit']+$money);
  //           $member_arr2=['id'=>$member_info_1['member_id'],'amt'=>$predeposit_2];
  //           $predeposit_code2 = Ze\Secure::encode($member_arr2);
  //           $member_update1['member_predeposit']=array('exp','member_predeposit+'.$money);
  //           $member_update1['predeposit_code']=$predeposit_code2;
  //           $member->where(array('member_id'=>$member_id))->update($member_update1);
  //       }else{
  //           $member->where(array('member_id'=>$_SESSION['member_id']))->setDec('member_predeposit',$money);
  //           $member->where(array('member_id'=>$member_id))->setInc('member_predeposit',$money);
  //       }
        
  //       $a=$pd_log->insert(array('member_id'=>$member_frend['member_id'],'member_name'=>$member_frend['member_name'],
  //                                'type'=>'fromfrend','available_amount'=>$money,'add_time'=>time(),'description'=>'id号为'.$_SESSION['member_id'].'会员给您转账'));
  //       $b=$pd_log->insert(array('member_id'=>$_SESSION['member_id'],'member_name'=>$_SESSION['member_name'],
  //                                'type'=>'tofrend','available_amount'=>"-$money",'add_time'=>time(),'description'=>'给id号为'.$member_id.'的会员转账'));
  //       if($a && $b){           
  //           echo '1';
  //       }else{echo '0';}
	}
    public function rcb_logOp(){
        $rcb_log=Model('rcb_log');
        $where['member_id']=$_SESSION['member_id'];
        $where['type']=array('in','tofrend,fromfrend');
        $rcb=$rcb_log->where($where)->page(10)->select();
        if ($rcb) {
            foreach($rcb as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['add_time']);
                $rcb[$k] = $v;
            }
        }
        self::profile_menu('rcb_log','rcb_log');
        Tpl::output('rcb',$rcb);
        Tpl::output('show_page',$rcb_log->showpage());
        Tpl::showpage('predeposit.rcb_info');
    }
    public function give_pointsOp()
    {
        Tpl::showpage('give_points');   
    }
    public function givepointOp(){

        $member = Model('member');
        $member_common=Model('member_common');
        $pd_log = Model('points_log');     
        // $data=array();
        // $data['pl_addtime']=array('gt',$start);
        // $data['pl_memberid']=$_SESSION['member_id'];
        // $data['pl_stage']='rechart';
        $money=$_POST['money'];
        $money=abs($money);
        $pdw=md5($_POST['pwd']);
        $member_id=$_POST['memberid'];
        $code=$_POST['code'];
        $bankname=$_POST['bank_name'];
        //验证验证码是否正确
        $common=$member_common->where(array('member_id'=>$_SESSION['member_id']))->find();
       
        if($common['auth_code']!=$code){
            echo '9';exit;
        }
         //验证验证码次数
        if($common['auth_code_check_times']>0){
            echo '10';exit;
        }
        $member_common->where(array('member_id'=>$_SESSION['member_id']))->update(array('auth_code_check_times'=>array('exp','auth_code_check_times+1')));
        //查询用户是否存在
        $member_frend=$member->where(array('member_id'=>$member_id))->find();
        if(!$member_frend){
            echo '4';exit();
        }
        if($member_frend['member_bankname']!=$bankname){
            echo '8';exit;
        }    
        //比对支付密码是否正确        
        $member_pwd=$member->getfby_member_id($_SESSION['member_id'],'member_paypwd');   
        if($member_pwd != $pdw){
            echo '3';exit();
        }        
        //查询已有金额
        $member_predeposit=$member->getfby_member_id($_SESSION['member_id'],'member_points');

        $member_predeposit=intval($member_predeposit);
        if($member_predeposit<200000){
            echo '7';exit();
        }
        if($money > $member_predeposit || $money+$money*0.03>$member_predeposit){
            echo '2';exit();
        }

        //每天转账总额不能超过10000元
        $start=strtotime(date('Y-m-d',time()));//当天的开始时间
        $count_money=$pd_log->where(array('pl_memberid'=>$_SESSION['member_id'],'pl_stage'=>'give_points','pl_addtime'=>array('gt',$start)))->sum('pl_points');  
        $count_money=abs($count_money);
        $abc=$count_money +$money;
        if($count_money >=1000000 || $abc >1000000 || $money>1000000){echo '6';exit();}  
        $money1=$money+$money*0.03;  
        //设置返现时间
        if(empty($member_frend['return_time']) || empty($member_frend['member_points'])){
            $update['return_time']=$start;
        }
        $update['member_points']=array('exp','member_points+'.$money);      
        $member->where(array('member_id'=>$_SESSION['member_id']))->setDec('member_points',$money1);
        $member->where(array('member_id'=>$member_id))->update($update);
        $a=$pd_log->insert(array('pl_memberid'=>$member_frend['member_id'],'pl_membername'=>$member_frend['member_name'],
                                 'pl_stage'=>'points','pl_points'=>$money,'pl_addtime'=>time(),'pl_desc'=>'id号为'.$_SESSION['member_id'].'会员给您转账'));
        $b=$pd_log->insert(array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],
                                 'pl_stage'=>'give_points','pl_points'=>"-$money",'pl_addtime'=>time(),'pl_desc'=>'给id号为'.$member_id.'的会员转账,扣除3%手续费'.$money*0.03));
        if($a && $b){           
            echo '1';
        }else{echo '0';}
    }
    public function point_infoOp(){
        $pd_log = Model('points_log');
        $where = array();
        $where['pl_memberid'] = $_SESSION['member_id'];
        $where['pl_stage'] = array('in','points,give_points');
        if (trim($_GET['stime']) && trim($_GET['etime'])) {
            $stime = strtotime($_GET['stime']);
            $etime = strtotime($_GET['etime']);
            $where['pl_addtime'] = array('between', "$stime,$etime");
        } elseif (trim($_GET['stime'])) {
            $stime = strtotime($_GET['stime']);
            $where['pl_addtime'] = array('egt', $stime);
        } elseif (trim($_GET['etime'])) {
            $etime = strtotime($_GET['etime']);
            $where['pl_addtime'] = array('elt', $etime);
        }
        // $where['pl_desc'] = array('like',"%{$_GET['description']}%");
        //查询云豆日志列表
        // $points_model = Model('points');
        $list_log = $pd_log->where($where)->limit('0,10')->select();
        //信息输出
        self::profile_menu('points_log');
        Tpl::output('show_page',$pd_log->showpage());
        Tpl::output('list_log',$list_log);
        Tpl::showpage('point_info');
    }
    //发送短信
    public function sendOp(){
        $model_member=Model('member');
        $common=Model('member_common');
        $member_id = $_POST['id'];
        $time = time();
        if(isset($_SESSION['codes']) && !empty($_SESSION['codes']) && $time -$_SESSION['codes'] < 120){

            echo '6';exit();
        }
        $member = $model_member->getMemberInfo(array('member_id'=> $member_id));
        // $member_info=$member->getfby_member_id($member_id,'member_mobile');
        $phone=$member['member_mobile'];

        $log_type = 3;//短信类型:1为注册,2为登录,3为找回密码        
        if (strlen($phone) != 11){
            echo '1';exit;
        }       
        $state = true;              
        

        $captcha = rand(100000, 999999);
        $log_msg = '【'.C('site_name').'】您于'.date("Y-m-d");
        switch ($log_type) {                
            case '3':                    
            $log_msg .= '验证码为：'.$captcha.'。';
                $log_array['member_id'] = $member['member_id'];
                $log_array['member_name'] = $member['member_name'];
                break;
            default:
                $state = false;
                $msg = '参数错误';
                break;
        }
        if($state == true){
            $sms = new Sms();
            $result = $sms->send($phone,$log_msg);
            if($result){
               $time = time();
               $_SESSION['codes'] = $time;
               $code_info=$common->where(array('member_id'=>$member['member_id']))->find();
               $acode=array('auth_code'=>$captcha,'send_acode_time'=>time(),'send_mb_time'=>time(),'send_acode_times'=>array('exp','send_acode_times+1'),'auth_code_check_times'=>'0');
               
               if(empty($code_info)){
                    $acode['member_id']=$member['member_id'];
                    $insert=$common->insert($acode);
               }else{
                 $comupdate=$common->where(array('member_id'=>$member['member_id']))->update($acode);
               }             
              
               echo '2';                  
            } else {
                    $state = false;
                    echo '3';exit;
            }
        }
        else{
            echo '4';exit;
        }                  
    }
    public function port_pointsOp()
    {
       
        Tpl::showpage('port_points');   
    }
    public function giveportOp(){
        $code=$_POST['code'];
        $points=$_POST['money'];
        $points=abs($points);
        $pwd=md5($_POST['pwd']);
        $other_id=$_POST['memberid'];
        $other_name=$_POST['bank_name'];
        $member_id=$_SESSION['member_id'];
        $data=port_give_point($member_id,$other_id,$other_name,$points,$pwd,$code);
        echo $data;
    }
    public function getBankInfo($card){
        $bankList = include __DIR__ . '/return.banklist.php';

        $card_8 = substr($card, 0, 8);  
        if (isset($bankList[$card_8])) {  
            return $bankList[$card_8];  
        }

        $card_6 = substr($card, 0, 6);  
        if (isset($bankList[$card_6])) {  
            return $bankList[$card_6];  
        }

        $card_5 = substr($card, 0, 5);  
        if (isset($bankList[$card_5])) {  
            return $bankList[$card_5];   
        }

        $card_4 = substr($card, 0, 4);  
        if (isset($bankList[$card_4])) {  
            return $bankList[$card_4];  
        }

        return '银行卡';

    }
}
