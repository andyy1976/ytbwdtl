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

class member_moneyControl extends mobileMemberControl {
    public function __construct(){
        
    }
    //充值余额转云豆
    public function money_to_pointsOp(){
        // $points = Model('points_log');
        // $member = Model('member');
        // $rcb_log = Model('rcb_log');
        // // $fenxiao_log = Model('fenxiao_log');
        // $model_mb_user_token = Model('mb_user_token');                
        // $key = $_POST['key'];               
        // $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);               
        // $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);
        // $start=strtotime(date('Y-m-d',time()));//当天的开始时间
        // $t = time();
        // $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        // $data=array();
        // $data['pl_addtime']=array('gt',$start);
        // $data['pl_memberid']=$member_info['member_id'];
        // $data['pl_stage']=array('in','buy_points,rechart');
        // $money=$_POST['money'];  
        // $money_type=$_POST['money_type'];  
        // $code=$_POST['code'];
        // $paypwd=$_POST['paypwd'];
        // $member_common=Model('member_common');
        // //验证验证码是否正确
        // $common=$member_common->where(array('member_id'=>$mb_user_token_info['member_id']))->find();
        // if($common['auth_code']!=$code){
        //     echo '4';exit;
        // }
        // //验证验证码次数
        // if($common['auth_code_check_times']>0){
        //     echo '5';exit;
        // }
        // $member_common->where(array('member_id'=>$mb_user_token_info['member_id']))->update(array('auth_code_check_times'=>array('exp','auth_code_check_times+1')));
        // //验证安全密码是否正确
        // if($member_info['member_paypwd']!=md5($paypwd)){
        //     echo '3';exit;
        // }

        // //查询已有金额
        // if($money_type==2){
        //     $member_info=$member->where(array('member_id'=>$mb_user_token_info['member_id']))->find();
        //     $member_predeposit=$member_info['member_predeposit'];
        // }else{
        //     $member_predeposit=$member->getfby_member_id($member_info['member_id'],'distributor_predeposit');
        // }               
        // if($money > $member_predeposit){
        //     echo '2';exit();
        // }           
        // //查询当日充值送的云豆总和      
        // $today_points=$points ->where($data)->sum('pl_points');
        // $add_points=0;
        // $total_points = $today_points + $money * 16.666;
        // if($today_points >= 20000){
        //     $add_points=$money * 12.5;          
        // }
        // if($total_points < 20000){
        //  $add_points=$money * 16.666;            
        // }
        // if($today_points < 20000 && $total_points > 20000){
        //  $money_a = 1200 - $today_points/16.666;
        //  $points_a = $money_a * 16.666;  
        //  $money_b = $money - $money_a;
        //  $points_b = $money_b * 12.5;    
         
        //  $add_points = 20000-$today_points + $points_b;           
        // }
        // $add_points=ceil($add_points);
        // //判断转换云豆不超过1百万
        // if($today_points>1000000 || $add_points+$today_points>1000000){
        //     echo '6';exit();
        // }
        // if($money_type==2){
        //     //设置返现时间
        //     if(empty($member_info['return_time']) || empty($member_info['member_points'])){
        //         $update['return_time']=$start;
        //     }
        //     $update['member_points']=array('exp','member_points+'.$add_points);  
        //    $member->where(array('member_id'=>$member_info['member_id']))->setDec('member_predeposit',$money);
        //    $member->where(array('member_id'=>$member_info['member_id']))->setInc('member_points',$add_points);
        //    $a=$points->insert(array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name'],'pl_points'=>$add_points,'pl_addtime'=>time(),'pl_desc'=>'充值余额'.$money."元转云豆",'pl_stage'=>'buy_points','pl_counter'=>$money));
        //    $b=$rcb_log->insert(array('member_id'=>$member_info['member_id'],'member_name'=>$member_info['member_name'],'type'=>'points','available_amount'=>"-$money",'add_time'=>time(),'description'=>'转成'.$add_points.'云豆'));
        // }else{
        //    $member->where(array('member_id'=>$member_info['member_id']))->setDec('distributor_predeposit',$money);
        //    $member->where(array('member_id'=>$member_info['member_id']))->setInc('member_points',$add_points);
        //    $a=$points->insert(array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name'],'pl_points'=>$add_points,'pl_addtime'=>time(),'pl_desc'=>'奖金余额'.$money."元转云豆",'pl_stage'=>'rechart'));
        //    $b=$fenxiao_log->insert(array('member_id'=>$member_info['member_id'],'member_name'=>$member_info['member_name'],'type'=>'rechart','available_amount'=>"-$money",'add_time'=>time(),'description'=>'转成'.$add_points.'云豆'));
        // }
        // if($a && $b){
        //     give_se($member_info['member_id'],$add_points);
        //     echo '1';
        // }else{echo '0';}
    }
    //AJAX给朋友转账	
	public function tofrentOp(){   
        // $member = Model('member');  
        // $pd_log = Model('rcb_log');
        // $model_mb_user_token = Model('mb_user_token');                
        // $key = $_POST['key'];               
        // $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);               
        // $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);            
        // $money=abs($_POST['money']);
        // $pdw=md5($_POST['pwd']);
        // $member_id=$_POST['userid'];
        // $bankname=$_POST['bank_name'];
        // if($member_id==$member_info['member_id']){
        //     echo '5';exit();
        // }
        // //查询用户是否存在
        // $member_frend=$member->where(array('member_id'=>$member_id))->find();
        // if(!$member_frend){
        //     echo '4';exit();
        // }   
        // if($member_frend['member_bankname']!=$bankname){
        //     echo '7';exit;
        // }
        // //比对支付密码是否正确                
        // if($member_info['member_paypwd'] != $pdw){
        //     echo '3';exit();
        // }   
        
        // //每天转账总额不能超过10000元
        // $start=strtotime(date('Y-m-d',time()));//当天的开始时间
        // $count_money=$pd_log->where(array('member_id'=>$member_info['member_id'],'type'=>'tofrend','add_time'=>array('gt',$start)))->sum('available_amount');   
        // $count_money=abs($count_money);
        // $abc=$count_money+$money;      
        // if($count_money > 20000 || $abc > 20000 || $money>20000){echo '6';exit();}              
        // //查询已有金额
        // $member_predeposit=$member->getfby_member_id($member_info['member_id'],'member_predeposit');
        // if($money > $member_predeposit){
        //     echo '2';exit();
        // }                       
        // $member->where(array('member_id'=>$member_info['member_id']))->setDec('member_predeposit',$money);
        // $member->where(array('member_id'=>$member_frend['member_id']))->setInc('member_predeposit',$money);
        // $a=$pd_log->insert(array('member_id'=>$member_frend['member_id'],'member_name'=>$member_frend['member_name'],
        //                          'type'=>'fromfrend','available_amount'=>$money,'add_time'=>time(),'description'=>'id号为'.$member_info['member_id'].'会员给您转账'));
        // $b=$pd_log->insert(array('member_id'=>$member_info['member_id'],'member_name'=>$member_info['member_name'],
        //                          'type'=>'tofrend','available_amount'=>"-$money",'add_time'=>time(),'description'=>'给id号为'.$member_id.'的会员转账'));
        // if($a && $b){           
        //     echo '1';
        // }else{echo '0';}
    }
    //AJAX提现	
	public function tixianOp(){
		$member = Model('member');	
		$rcb_log = Model('rcb_log');
		$pd_cash=Model('pd_cash');
		$pd_log=Model('pd_log');
		$model_pd = Model('predeposit');
		$model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['key'];				
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        //限制充值提现，每日只能提现一次
        $pd_cash = Model('pd_cash');
        $date_time=strtotime(date('Y-m-d'));
        $pd_cash_info=$pd_cash->where(array('pdc_member_id'=>$mb_user_token_info['member_id'],'pdc_add_time'=>array('gt',$date_time),'predeposit_type'=>'2'))->count();
        if($pd_cash_info>'5' && $_POST['money_type']=='2'){
                echo '11';exit;
        }      		       
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);
        //判断是否是会员
        if($member_info['member_level']==0 && empty($member_info['free']))
        {
            echo '10';exit;
        }
        //判断支付密码是否正确
        $pwd=md5($_POST['pwd']);
        if($pwd != $member_info['member_paypwd']){echo '5';exit();}
        $money=$_POST['money'];  
        $money_type=$_POST['money_type']; 
        //判断是否绑定银行卡和开户人信息是否为空
        if(!$member_info['member_bankcard'] || !$member_info['member_bankname']){echo '4';exit();}
        $bankname=$this->getBankInfo($member_info['member_bankcard']);
        $str1='农业';
        if(strpos($bankname,$str1)===false){
            echo '16';exit;
        }
        //查询余额是否足够
        if($money_type==2){
        	$member_predeposit=$member->getfby_member_id($member_info['member_id'],'member_predeposit');
        }elseif($money_type==3){
        	$member_predeposit=$member->getfby_member_id($member_info['member_id'],'distributor_predeposit');        	
        }elseif($money_type==1){
        	$member_predeposit=$member->getfby_member_id($member_info['member_id'],'available_predeposit');
        }elseif($money_type==5){
            $member_predeposit=$member->getfby_member_id($member_info['member_id'],'province_predeposit');
        }elseif($money_type==6){
            $member_predeposit=$member->getfby_member_id($member_info['member_id'],'agent_predeposit');
        }else{
            echo '20';exit;
        }  
        $fenxiao=$money * 1.01;
        if($money_type==3 && $fenxiao > $member_predeposit){
        	echo '2';exit();
        }             
        if($money > $member_predeposit){
        	echo '2';exit();
        }	 
        $model_pd->beginTransaction();
        $pdc_sn = $this->makeSns($member_info['member_id']);
        $data = array();
        $data['pdc_sn'] = $pdc_sn;
        $data['pdc_member_id'] = $member_info['member_id'];
        $data['pdc_member_name'] = $member_info['member_name'];
        //云豆余额每月免费提现4次
        
        $date_time=strtotime(date('Y-m-01'));
        $available_info=$pd_cash->where(array('pdc_member_id'=>$mb_user_token_info['member_id'],'pdc_add_time'=>array('gt',$date_time),'predeposit_type'=>'1'))->count();

        if($money_type==1){
            if($available_info<'4'){
                $data['pdc_amount'] = $money * 0.87;
            }else{
                $data['pdc_amount'] = ($money * 0.87)-5;
            }
            
        }elseif($money_type==5){
            if($available_info<'4'){
                $data['pdc_amount'] = $money * 0.87;
            }else{
                $data['pdc_amount'] = ($money * 0.87)-5;
            }
        }elseif($money_type==6){
            $data['pdc_amount'] = $money * 0.98;
        }else{
        	$data['pdc_amount'] = $money;
        }
        // if($money_type==2){
            // $member_data=member_cash($money,$member_info['member_id']);
            // var_dump($member_data);exit;
            // if($member_data['type']=='1'){
            //     $data['type']='ybzf';
            // }
            // elseif($member_data['type']=='2'){
            //     $pdc_sn1 = $this->makeSns($member_info['member_id']);
            //     $data_1['pdc_sn'] = $pdc_sn1;
            //     $data_1['pdc_member_id'] = $member_info['member_id'];
            //     $data_1['pdc_member_name'] = $member_info['member_name'];
            //     $data_1['pdc_amount'] = $member_data['amount'];
            //     $data_1['type']='unionpay';
            //     $data_1['pdc_bank_name'] = '中国农业银行';
            //     $data_1['predeposit_type'] = $money_type;
            //     $data_1['pdc_bank_no'] = $member_info['member_bankcard'];
            //     $data_1['pdc_bank_user'] = $member_info['member_bankname'];
            //     $data_1['pdc_add_time'] = TIMESTAMP;
            //     $data_1['pdc_payment_state'] = 0;
               
            //     $insert1 = $model_pd->addPdCash($data_1);
            //     $data['type'] = 'ybzf';
            //     if (!$insert1) {
            //         echo '3';
            //         exit();
            //     }
            //     $data['pdc_amount'] = $money - $member_data['amount'];
            // }
            // else{
            //     $data['type']='unionpay';
            // }
        // }          
        $data['pdc_bank_name'] = '中国农业银行';
        $data['predeposit_type'] = $money_type;
        $data['pdc_bank_no'] = $member_info['member_bankcard'];
        $data['pdc_bank_user'] = $member_info['member_bankname'];
        $data['pdc_add_time'] = TIMESTAMP;
        $data['pdc_payment_state'] = 0;
        $insert = $model_pd->addPdCash($data);
        if (!$insert) {
            echo '3';exit();
        }
        //冻结可用预存款
        $data = array();
        $data['member_id'] = $member_info['member_id'];
        $data['member_name'] = $member_info['member_name'];
        if($money_type==3){
        	$data['amount'] = $money;
        }else{
        	$data['amount'] = $money;
        }
        
        $data['order_sn'] = $pdc_sn;
        $data['predeposit_type'] = $money_type;
        $insert=$model_pd->changePd('cash_apply',$data);
        if ($insert) {
            if($member_info['member_level']>0 && $member_info['agreement_id']=='0'){
                echo '12';exit;
            }
            echo '1';exit();
        }else{
        	echo '6';exit();
        }
        $model_pd->commit();       
	}
	//生成充值编号
	private function makeSns($uid) {
       return mt_rand(10,99)
              . sprintf('%010d',time() - 946656000)
              . sprintf('%03d', (float) microtime() * 1000)
              . sprintf('%03d', (int) $uid % 1000);
    }
    public function blind_cardOp() {
        $member = Model('member');			
		$model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['key'];	
		$card=	$_POST['bankcard'];	
		$name=	$_POST['name'];	
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);		       
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);
        //判断银行卡是否已存在和修改次数
        if($member_info['bankcard_editor']=='3'){
            echo '2';exit;
        }
        $post_info=$member->where(array('member_bankcard'=>$card))->find();
        if(!empty($post_info)){
            echo '3';exit;
        }        
        if(!empty($post_info) && $member_info['member_bankname']!=''){
            echo '3';exit;
        }

        $data=array();
        $data['member_bankcard']=$card;
        if(!$member_info['member_bankname']){
        	$data['member_bankname']=$name;
        }
        $data['bankcard_editor']=array('exp','bankcard_editor+1');         
        $update=$member->where(array('member_id'=>$member_info['member_id']))->update($data);
        if($update){echo '4';}else{echo '1';}
    }
    public function namegetOp() {
        $member = Model('member');			
		$model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['key'];			
		$name=	$_POST['name'];	
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);		       
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);                              
        if($member_info['member_bankname']){echo $member_info['member_bankname'];}else{echo 0;}
    }
    public function getcardOp() {
        $member = Model('member');			
		$model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['key'];			
		$name=	$_POST['name'];	
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);		       
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);                              
        if($member_info['member_bankcard']){echo $member_info['member_bankcard'];}
    }
    //判断省代本月是否已经提现
	function province_cashOp(){
		$pd_cash=Model('pd_cash');
		$member = Model('member');			
		$model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['key'];					
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);		       
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);
		$year = date("Y");
	    $month = date("m");
	    $strat = strtotime($year."-".$month."-1");//本月1号时间戳
	    $strat_time = strtotime($year."-".$month."-5");//本月5号时间戳
	    $end_time = strtotime($year."-".$month."-11");//本月10号时间戳
	    $time=time();
	    $data=array();
	    $data['pdc_member_id']=$member_info['member_id'];
	    $data['pdc_add_time']=array('gt',$strat);
	    $data['predeposit_type']=1;
	    $count=$pd_cash->where($data)->count(); 	    
	    if($member_info['member_level']==5){       
		    
		    	echo '0';
		   
	    }elseif($member_info['member_level']>1 && $member_info['member_level']<5){
		    echo '1';
		}else{
            echo '2';
        }
	}
    function findnameOp(){
        $member=Model('member');
        $member_id=$_GET['userid'];
        $member_info=$member->where(array('member_id'=>$member_id))->find();
        $strlen     = mb_strlen($member_info['member_bankname'], 'utf-8');
        $firstStr     = mb_substr($member_info['member_bankname'], 0, 1, 'utf-8');
        $lastStr     = mb_substr($member_info['member_bankname'], -1, 1, 'utf-8');
        $member_info['member_bankname']=$strlen == 2 ? str_repeat('*', mb_strlen($member_info['member_bankname'], 'utf-8') - 1).$lastStr : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
       
        output_data($member_info);

    }
    public function transferOp(){
        $member=Model('member');
        $points_log=Model('points_log');
        $member_name=$_POST['userid'];
        $points=$_POST['money'];
        $pwd=$_POST['pwd'];
        $model_mb_user_token = Model('mb_user_token');                
        $key = $_POST['key'];               
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        //查询该会员id是否是该伞下会员
        $under_info=$member->where(array('member_name'=>$member_name))->find();
        if(!$under_info){
             echo '4';exit();
        }
        if($under_info['split_id']!=$mb_user_token_info['member_id']){
            echo '7';
            exit;
        }
        if($_POST['type']=='1'){
            $where['member_id']=$mb_user_token_info['member_id'];
        }else{
            $where['member_name']=$member_name;
        }
        $member_info=$member->where($where)->find();
        if($member_info['member_points']<$points){
            echo '2';
            exit;
        }
        if($member_info['member_paypwd'] != md5($pwd)){
            echo '3';exit;
        }
        $points_info=$points_log->where(array('pl_membername'=>$member_name))->sum('pl_points');
        if($points_info+$points>100000 && $_POST['type']=='1'){
            echo '6';exit;
        }
        if($_POST['type']=='1'){
            $member->where(array('member_id'=>$mb_user_token_info['member_id']))->setDec('member_points',$points);
            $member->where(array('member_name'=>$member_name))->setInc('member_points',$points);
            $a=$points_log->insert(array('pl_memberid'=>$under_info['member_id'],'pl_membername'=>$under_info['member_name'],
                                     'pl_points'=>$points,'pl_addtime'=>time(),'pl_desc'=>'id号为'.$mb_user_token_info['member_id'].'会员给您转云豆','pl_stage'=>'transfer'));
            $b=$points_log->insert(array('pl_memberid'=>$mb_user_token_info['member_id'],'pl_membername'=>$mb_user_token_info['member_name'],
                                     'pl_points'=>"-$points",'pl_addtime'=>time(),'pl_desc'=>'给手机号为'.$member_name.'的会员转云豆','pl_stage'=>'transfer'));
            if($a && $b){           
                echo '1';
            }else{echo '0';}
        }else{
            $member->where(array('member_id'=>$mb_user_token_info['member_id']))->setInc('member_points',$points);
            $member->where(array('member_name'=>$member_name))->setDec('member_points',$points);
            $a=$points_log->insert(array('pl_memberid'=>$under_info['member_id'],'pl_membername'=>$under_info['member_name'],
                                     'pl_points'=>$points,'pl_addtime'=>time(),'pl_desc'=>'id号为'.$mb_user_token_info['member_id'].'会员给您转云豆','pl_stage'=>'transfer'));
            $b=$points_log->insert(array('pl_memberid'=>$mb_user_token_info['member_id'],'pl_membername'=>$mb_user_token_info['member_name'],
                                     'pl_points'=>"-$points",'pl_addtime'=>time(),'pl_desc'=>'给手机号为'.$member_name.'的会员转云豆','pl_stage'=>'transfer'));
            if($a && $b){           
                echo '1';
            }else{echo '0';}
        }
    }
    public function give_pointOp(){   
        $member = Model('member');  
        $pd_log = Model('points_log');
        $member_common=Model('member_common');
        $model_mb_user_token = Model('mb_user_token');                
        $key = $_POST['key'];               
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);               
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);            
        $money=$_POST['money'];
        $pdw=md5($_POST['pwd']);
        $member_id=$_POST['userid'];
        $bankname=$_POST['bank_name'];
        if($member_id==$member_info['member_id']){
            echo '5';exit();
        }
        //查询用户是否存在
        $member_frend=$member->where(array('member_id'=>$member_id))->find();
        if(!$member_frend){
            echo '4';exit();
        }   
        if($member_frend['member_bankname']!=$bankname){
            echo '10';exit;
        }
        //比对支付密码是否正确                
        if($member_info['member_paypwd'] != $pdw){
            echo '3';exit();
        }   
        $code=$_POST['code'];
        //验证验证码是否正确
        $common=$member_common->where(array('member_id'=>$mb_user_token_info['member_id']))->find();
        if($common['auth_code']!=$code){
            echo '8';exit;
        }
        //验证验证码次数
        if($common['auth_code_check_times']>0){
            echo '9';exit;
        }
        $member_common->where(array('member_id'=>$mb_user_token_info['member_id']))->update(array('auth_code_check_times'=>array('exp','auth_code_check_times+1')));
        //每天转账云豆不能超过1000000云豆
        $start=strtotime(date('Y-m-d',time()));//当天的开始时间
        $count_money=$pd_log->where(array('pl_memberid'=>$member_info['member_id'],'pl_stage'=>'give_points','pl_addtime'=>array('gt',$start)))->sum('pl_points');   
        $count_money=abs($count_money);
        $abc=$count_money+$money;      
        if($count_money > 1000000 || $abc > 1000000 || $money>1000000){echo '6';exit();}              
        //查询已有金额
        $member_predeposit=$member->getfby_member_id($member_info['member_id'],'member_points');
        if($member_predeposit<200000){
            echo '7';exit;
        }
        if($money > $member_predeposit || $money+$money*0.03>$member_predeposit){
            echo '2';exit();
        }
        $money1=$money+$money*0.03;
         //设置返现时间
        if(empty($member_frend['return_time']) || empty($member_frend['member_points'])){
            $update['return_time']=$start;
        }
        $update['member_points']=array('exp','member_points+'.$money);                        
        $member->where(array('member_id'=>$member_info['member_id']))->setDec('member_points',$money1);
        $member->where(array('member_id'=>$member_frend['member_id']))->update($update);
        $a=$pd_log->insert(array('pl_memberid'=>$member_frend['member_id'],'pl_membername'=>$member_frend['member_name'],
                                 'pl_stage'=>'points','pl_points'=>$money,'pl_addtime'=>time(),'pl_desc'=>'id号为'.$member_info['member_id'].'会员给您转账'));
        $b=$pd_log->insert(array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name'],
                                 'pl_stage'=>'give_points','pl_points'=>"-$money",'pl_addtime'=>time(),'pl_desc'=>'给id号为'.$member_id.'的会员转账,扣除3%手续费'.$money*0.03));
        if($a && $b){           
            echo '1';
        }else{echo '0';}
    }
    public function sendOp(){
        $model_member = Model('member');  
        $model_mb_user_token = Model('mb_user_token');                
        $key = $_POST['key'];   
        $time = time();
        if(isset($_SESSION['codes']) && !empty($_SESSION['codes']) && $time -$_SESSION['codes'] < 120){

            echo '6';exit();
        }            
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);               
       
        // $member_info =$model_member->getMemberInfoByID($mb_user_token_info['member_id']);
        $common=Model('member_common');
        $member = $model_member->getMemberInfo(array('member_id'=>$mb_user_token_info['member_id']));
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
    public function give_portOp(){
        $model_mb_user_token = Model('mb_user_token');  
        $member = Model('member'); 
        $key = $_POST['key'];               
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);               
        $member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);            
        $points=$_POST['money'];
        $code=$_POST['code'];
        $pwd=md5($_POST['pwd']);
        $other_id=$_POST['userid'];
        $other_name=$_POST['bank_name'];
        $data=port_give_point($member_info['member_id'],$other_id,$other_name,$points,$pwd,$code);
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