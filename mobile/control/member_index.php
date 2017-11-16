<?php
/**
 * 我的商城
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_indexControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexOp() {
		$map_id = isset($_POST['map_id'])?$_POST['map_id']:'';
		$str_key = uniqid(rand());
        $member_info = array();
        if(!empty($map_id)){   //地面商家店铺扫码处理并入库
        /*处理未完成的扫码或者支付支付的订单*/
        $stor_order = Model('order');
        $conditions = array();
        $conditions['is_dm']=1;
        $conditions['buyer_id']=$this->member_info['member_id'];
        $conditions['order_state']=array('neq',50);
        $bcq=$stor_order ->table('orders')->where($conditions)->delete();
        $store_model=Model('store');
		$model_member = Model('member');
		$store_info=$store_model->getStoreInfoByID($map_id);
		$member_info['store_name']=$store_info['store_name'];	
		$logic_buy = Logic('buy_1');
		$member_info['pay_sn']=$logic_buy->makePaySn($this->member_info['member_id']);
		//$member_info['order_sn']=$logic_buy->makeOrderSn($this->member_info['member_id']);
		$member_info['order_sn']=$this->member_info['member_id'].$this->getMillisecond();
		
       }
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['user_id'] = $this->member_info['member_id'];
        //$member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
      $member_info['avatar'] = getMemberAvatar($this->member_info['member_avatar']);
       if($this->member_info['free']=='1'){
        	$member_info['user_level'] = '见习会员';
        }else{
        	$member_info['user_level'] = $this->member_level_name($this->member_info['member_level']);
        }       
        $member_info['member_level']=$this->member_info['member_level'];
        $member_info['member_mobile']=$this->member_info['member_mobile'];
        $member_info['member_pid']=$this->member_info['member_pid'];
		$member_info['member_predeposit']=$this->member_info['member_predeposit'];
		$member_info['member_points'] = $this->member_info['member_points'];
        $member_gradeinfo = Model('member')->getOneMemberGrade(intval($this->member_info['member_exppoints']));
        $member_info['level_name'] = $member_gradeinfo['level_name'];
        $member_info['favorites_store'] = Model('favorites')->getStoreFavoritesCountByMemberId($this->member_info['member_id']);
        $member_info['favorites_goods'] = Model('favorites')->getGoodsFavoritesCountByMemberId($this->member_info['member_id']);
        // 交易提醒
        $model_order = Model('order');
        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'NewCount');
        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'SendCount');
        $member_info['order_notakes_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'TakesCount');
        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer', $this->member_info['member_id'], 'EvalCount');
        
        // 售前退款
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['refund_state'] = array('lt', 3);
        $member_info['return'] = Model('refund_return')->getRefundReturnCount($condition);
	    output_data(array('member_info' => $member_info));
    }
    
    /**
     * 我的资产
     */
    public function my_assetOp() {
        $param = $_GET;
        $fields_arr = array('point','predepoit','frozen_agent','available_rc_balance','redpacket','voucher','distributor_predeposit','card_chief','agent_predeposit','province_predeposit');
        $fields_str = trim($param['fields']);
        if ($fields_str) {
            $fields_arr = explode(',',$fields_str);
        }
        $member_info = array();
        if (in_array('point',$fields_arr)) {
            $member_info['point'] = $this->member_info['member_points'];
        }
        if (in_array('predepoit',$fields_arr)) {
            $member_info['predepoit'] = $this->member_info['available_predeposit'];
            $member_info['province'] = $this->member_info['province_predeposit'];
            $member_info['agent'] = $this->member_info['agent_predeposit'];
            $member_info['frozen_agent'] = $this->member_info['frozen_agent'];
            $member_info['level'] = $this->member_info['member_level'];

        }
        if (in_array('available_rc_balance',$fields_arr)) {
            $member_info['available_rc_balance'] = $this->member_info['member_predeposit'];
        }
        if (in_array('redpacket',$fields_arr)) {
            $member_info['redpacket'] = Model('redpacket')->getCurrentAvailableRedpacketCount($this->member_info['member_id']);
        }
        
        if (in_array('voucher',$fields_arr)) {
            $member_info['voucher'] = Model('voucher')->getCurrentAvailableVoucherCount($this->member_info['member_id']);
        }
        if (in_array('distributor_predeposit',$fields_arr)) {
            $member_info['distributor_predeposit'] = $this->member_info['distributor_predeposit'];
        }
        //if (in_array('card_chief',$fields_arr)) {
        //  $member_info['card_chief'] = $this->card_chief($this->member_info['member_id']);
        //}
        output_data($member_info);
    }
    //用户卡代提成
	public function card_chief($uid){		
		$members=Model('member');
		//上个月一号的开始时间
		$start_time=strtotime(date('Y-m-01', strtotime('-1 month')));
		//上个月最后一天的结束时间
	    $end_time=strtotime(date('Y-m-t', strtotime('-1 month'))); 	
	    $member_info=$members->where(array('member_id'=>$uid))->find();
		$level=$member_info['member_level'];		
		$money=0;
		$areas_id=array();
		if($level==2){
			//查询端口上个月下面新增会员
			$member_sum=$members->where(array('portid'=>$member_info['member_id'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
			$money=52 * $member_sum;
		}elseif($level==3){
			//查询上个月区代下面新增会员
			$member_sum=$members->where(array('member_areaid'=>$member_info['member_areaid'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
		    $area_money=72 * $member_sum;
		    $total_members=0;
		    //查询区代下面所有端口
		    $parts=$members->where(array('member_areaid'=>$member_info['member_areaid'],'member_level'=>2))->select();
		    foreach($parts as $value){
		    	//获取所有端口上个月下面新增总会员
			    $total_members += $members->where(array('portid'=>$value['member_id'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();		    
		    }
		    $money=$area_money - $total_members * 52 ;
		}elseif($level==4){
			//查询市代下所有上月新增会员
			$member_sum=$members->where(array('member_cityid'=>$member_info['member_cityid'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
			$city_money=92 * $member_sum;
			//所有区代总分成
			$area_total_money=0;
			//查询该市代下面的所有区代
			$area=$members->where(array('member_cityid'=>$member_info['member_cityid'],'member_level'=>3))->select();
			foreach($area as $value){
			  	//查询上个月区代下面新增会员
				$member_sum=$members->where(array('member_areaid'=>$value['member_areaid'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
			    $area_total_money +=72 * $member_sum;		    		    
			}
			//查询市代自己的端口
			$total_members=0;	   
		    $parts=$members->where(array('member_cityid'=>$member_info['member_cityid'],'member_areaid'=>'','member_level'=>2))->select();
		    foreach($parts as $value){
		    	//获取所有端口上个月下面新增总会员
			    $total_members += $members->where(array('portid'=>$value['member_id'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();		    
		    }	    
			$money=$city_money - $area_total_money - $total_members * 52 ;
		}elseif($level==5){
			//查询省下上个月所有新增会员
			$member_sum=$members->where(array('member_provinceid'=>$member_info['member_provinceid'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
			$province_money=102 * $member_sum;
			//查询省下所有市代
			$city_total_money=0;
			$city=$members->where(array('member_provinceid'=>$member_info['member_provinceid'],'member_level'=>4))->select();
			foreach($city as $value){
				//查询市代下所有上月新增会员
				$member_sum=$members->where(array('member_cityid'=>$value['member_cityid'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
				$city_total_money += 92 * $member_sum;
				//查询市代下面的区代
				$areas=	$members->where(array('member_cityid'=>$value['member_cityid'],'member_level'=>3))->select();
				foreach($areas as $valuee){
				  $areas_id[]=	$valuee['member_id'];
				}		
			}
			//查询没有市代的区代
			$areas_id=implode(',',$areas_id);
			$area=$members->where(array('member_id'=>array('not in',$areas_id),'member_provinceid'=>$member_info['member_provinceid'],'member_level'=>3))->select();
			$area_total_money=0;
			foreach($area as $valuee){
				//查询上个月区代下面新增会员
				$member_sum=$members->where(array('member_areaid'=>$valuee['member_areaid'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();
			    $area_total_money += 72 * $member_sum;		    
			}
			//查询省代自己的端口
			$total_members=0;	   
		    $parts=$members->where(array('member_provinceid'=>$member_info['member_provinceid'],'member_cityid'=>'','member_areaid'=>'','member_level'=>2))->select();
		    foreach($parts as $value){
		    	//获取所有端口上个月下面新增总会员
			    $total_members += $members->where(array('portid'=>$value['member_id'],'member_level'=>1,'member_time'=>array('between',"$start_time,$end_time")))->count();		    
		    }
		    $money = $province_money - $city_total_money - $area_total_money - $total_members * 52 ;	
		}
		return $money;
	}
	//级别名字
	public function member_level_name($value){
		$content='';
		switch ($value)
	   {
		case 0:
		  $content= "见习会员";
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
//注册会员
	public function registerOp(){
		$member=Model('member');
		$pd_log=Model('pd_log');
		$member_info=$member->where(array('member_id'=> $this->member_info['member_id']))->find();
		
		if($member_info['member_points']<5000){
			echo '5';exit;
		}
		$where['member_name']=$_POST['member_name'];
		$where['member_mobile']=$_POST['member_name'];
		$where['_op']='or';
		$member_check = $member->where($where)->find();

		if($_POST['member_name']=='' ){
			echo '1';
			exit;
		}
		if($member_check){
			echo '2';
			exit;
		}
		if($_POST['member_passwd']=='' || $_POST['member_paypwd']==''){
			echo '3';
			exit;
		}
		$register_info = array();
		$register_info['username'] = $_POST['member_name'];
		$register_info['password'] = $_POST['member_passwd'];
		$register_info['password_confirm'] = $_POST['member_passwd'];
		$register_info['member_pid'] = $member_info['member_id'];
		$register_info['member_mobile'] = $_POST['member_name'];
		$register_info['member_paypwd']=$_POST['member_paypwd'];
		if(empty($member_info['split_id'])){
			$register_info['register']='1';
			$data['member_points']=array('exp','member_points-5000');
			$update=$member->where(array('member_id'=> $member_info['member_id']))->update($data);
		}else{
			$register_info['register']='2';
		}	
		$member_param = $member->register($register_info);
		if(empty($member_info['split_id'])){
			$pd['lg_member_id']= $member_info['member_id'];
			$pd['lg_member_name']= $member_info['member_name'];
			$pd['lg_type']='activation';
			$pd['lg_av_amount']='5000';
			$pd['lg_admin_name']=$_POST['member_name'];
			$pd['lg_add_time']=time();
			$pd['lg_desc']='激活会员使用5000云豆';
		}else{
			$pd['lg_member_id']= $member_info['member_id'];
			$pd['lg_member_name']= $member_info['member_name'];
			$pd['lg_type']='split';
			$pd['lg_av_amount']='0';
			$pd['lg_admin_name']=$_POST['member_name'];
			$pd['lg_add_time']=time();
			$pd['lg_desc']='注册激活会员';
		}
		$insert=$pd_log->insert($pd);
		if($update && $member_param){
			echo '4';
		}
	}

	//注册会员获取奖金
		public function registerfxOp(){
		$member=Model('member');
		$pd_log=Model('pd_log');
		$member_info=$member->where(array('member_id'=> $this->member_info['member_id']))->find();
		if($member_info['distributor_predeposit']<500){   //分销奖金余额
			echo '5';exit;
		}
		$where['member_name']=$_POST['member_name'];
		$where['member_mobile']=$_POST['member_name'];
		$where['_op']='or';
		$member_check = $member->where($where)->find();

		if($_POST['member_name']=='' ){
			echo '1';
			exit;
		}
		if($member_check){
			echo '2';
			exit;
		}
		if($_POST['member_passwd']=='' || $_POST['member_paypwd']==''){
			echo '3';
			exit;
		}
		$register_info = array();
		$register_info['username'] = $_POST['member_name'];
		$register_info['password'] = $_POST['member_passwd'];
		$register_info['password_confirm'] = $_POST['member_passwd'];
		$register_info['member_pid'] = $member_info['member_id'];
		$register_info['member_mobile'] = $_POST['member_name'];
		$register_info['member_paypwd']=$_POST['member_paypwd'];
       if(empty($member_info['split_id'])){
			$register_info['register']='1';
			$data['distributor_predeposit']=array('exp','distributor_predeposit-500');
			$update=$member->where(array('member_id'=> $member_info['member_id']))->update($data);
		}else{
			$register_info['register']='2';
		}	
		$member_param = $member->register($register_info,1);
	
		if(empty($member_info['split_id'])){
			$pd['lg_member_id']= $member_info['member_id'];
			$pd['lg_member_name']= $member_info['member_name'];
			$pd['lg_type']='distribution';
			$pd['lg_av_amount']='-500';
			$pd['lg_admin_name']=$_POST['member_name'];
			$pd['lg_add_time']=time();
			$pd['lg_desc']='激活'.$_POST['member_name'].'会员使用500元';
		}else{
			$pd['lg_member_id']= $member_info['member_id'];
			$pd['lg_member_name']= $member_info['member_name'];
			$pd['lg_type']='split';
			$pd['lg_av_amount']='0';
			$pd['lg_admin_name']=$_POST['member_name'];
			$pd['lg_add_time']=time();
			$pd['lg_desc']='注册激活会员';
		}
		$insert=$pd_log->insert($pd);
		if($update && $member_param){
			echo '4';
		}
		if(!empty($member_info['member_id'])){  //给自己添加分销奖金
           $data1 = array();
           $data1['distributor_predeposit'] = array('exp','distributor_predeposit+100');
           $updatee1 = $member->where(array('member_id'=>$member_info['member_id']))->update($data1);
           if($updatee1){
           	$pd1['lg_member_id']= $member_info['member_id'];
			$pd1['lg_member_name']= $member_info['member_name'];
			$pd1['lg_type']='distribution';
			$pd1['lg_av_amount']='100';
			$pd1['lg_admin_name']=$_POST['member_name'];
			$pd1['lg_add_time']=time();
			$pd1['lg_desc']='注册会员'.$_POST['member_name'].'获得分销奖金';
			$insert1=$pd_log->insert($pd1);
           }
		}
		if(!empty($member_info['member_pid'])){  //通过member_pid找到上级
			$member_pid = $member->where(array('member_id'=>$member_info['member_pid']))->find(); 
			$data=array();              //更新上级会员500元金额30%
			$data['distributor_predeposit']=array('exp','distributor_predeposit+150');
			$updatee=$member->where(array('member_id'=> $member_pid['member_id']))->update($data);   //更新上级ID
			if($updatee){
            $pd2['lg_member_id']= $member_pid['member_id'];
			$pd2['lg_member_name']= $member_pid['member_name'];
			$pd2['lg_type']='distribution';
			$pd2['lg_av_amount']='150';
			$pd2['lg_admin_name']=$_POST['member_name'];
			$pd2['lg_add_time']=time();
			$pd2['lg_desc']='注册会员'.$_POST['member_name'].'获得分销奖金';
			$insert1=$pd_log->insert($pd2);
           }
       }
	   	if($member_param){
	   		 $condition = array();
			             $condition['member_name']=$register_info['username'];
			             $member_nid = $member->getMemberInfo($condition);
                        $seve=$member->where(array('member_id'=>$member_nid['member_id']))->find();
                        $data_point=array('lg_member_id'=>$seve['member_id'],'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500积分');
                        $update_point=Model()->table('pd_log')->insert($data_point);
			chief_card($register_info['username']);  //代理分润
		}  
	}
	public function rukuOp(){
		  $bool = true;
		  $flag = isset($_POST['flag'])?$_POST['flag']:'';
		  $map_id=isset($_POST['map_id'])?$_POST['map_id']:'';      //实体店铺ID地址
		  $yd_name=isset($_POST['yd_name'])?$_POST['yd_name']:'';    //消费金额
		  $yd_password = isset($_POST['yd_password'])?$_POST['yd_password']:'';   //支付密码
		  $pdr_store_id = isset($_POST['pdr_store_id'])?$_POST['pdr_store_id']:'';  //店铺store_id
		  $pdr_st_shop = isset($_POST['pdr_st_shop'])?$_POST['pdr_st_shop']:'';     //实体店铺名称
		  $member=Model('member');    //会员
		  $pd_log=Model('consume');    //消费日志
		  $pm = Model('points_log'); //积分日志
		  $store = Model('store');   //商店日志
		  $member_info=$member->where(array('member_id'=> $this->member_info['member_id']))->find();
		  if(empty($yd_name)){
		  	echo '1'; exit();              //请输入消费金额
		  }else{                             //获取金额算取积分并入库
			  $map = Model('store_map');
			  $condition = array();
			  $condition['map_id']=$map_id;
			  $abc = $map->getStStoreInfoByID($condition);
		  if($abc['custom_point']!=0){
		  $pdr_ptpoints =  $yd_name/$abc['custom_point'];    //平台积分
		  }else{
		  $pdr_ptpoints =0;
		  }
		  if($abc['points']!=0){
		  $pdr_shoppoints = $yd_name/$abc['points'];         //店铺积分
		  }else{
		  $pdr_shoppoints =0;
		  }
		  $maprk = Model('dianpufx');
		  $lookitt = $maprk->where(array('pdr_pay_sn'=>$flag))->find();
		  if($lookitt){

		  }else{
		  $data = array(
	             'pdr_member_id'=>$this->member_info['member_id'],
	             'pdr_store_id'=>$pdr_store_id,
	             'pdr_st_shop'=>$pdr_st_shop,
	             'pdr_status'=>0,
	             'pdr_paytime'=>strtotime(date("Y-m-d")),
	             'pdr_addtime'=>strtotime(date("Y-m-d")),
	             'pdr_ptpoints'=>$pdr_ptpoints,
	             'pdr_shoppoints'=>$pdr_shoppoints,
	             'pdr_map_id'=>$map_id,
	             'pdr_pay_sn'=>$flag,
	             'pdr_amount'=>$yd_name,
		);
	$maprk->insert($data);
}
	if(!empty($pdr_store_id)){
		$store_info=$store->getStoreInfoByID($pdr_store_id);
		$member_store=$member->where(array('member_id'=>$store_info['member_id']))->find();
		if($member_store['member_points']<$pdr_shoppoints){
			echo '8'; exit();           //商家积分余额不够
		}
			  
		
	}
	if($yd_name>$member_info['member_predeposit']){
			echo '4'; exit();                //您的充值金额不足以支付，请充值
		   }
    if($pdr_ptpoints>$member_info['member_points']){
    	   echo '5'; exit();               //您的积分不足以扣除，请充值
    }
		  }
	    if(empty($yd_password)){
	    	 echo '2'; exit();              //请输入支付密码 
		  }else{
			  $yd_passwords=md5($yd_password);
	if($yd_passwords!=$member_info['member_paypwd']){
                echo '3';exit();            //判断支付密码是否正确
			  }
	}
	 if($bool){                             //通过所有验证
	 	$maprk = Model('dianpufx'); 
	 	$lookitt = $maprk->where(array('pdr_pay_sn'=>$flag,'pdr_status'=>1))->find();
	 	if($lookitt){
	 		echo 7;           //您的支付已经完成，请不要重复提交订单。
	 		exit();   
	 	}else{
        $lookit = $maprk->where(array('pdr_pay_sn'=>$flag,'pdr_status'=>0))->find();
        $data=array();              //更新会员的充值金额
	    $data['member_predeposit']=array('exp','member_predeposit-'.$yd_name);     //扣除金额
		$data['member_points']=array('exp','member_points-'.$lookit['pdr_ptpoints']);   //扣除平台积分
        $data['member_points']=array('exp','member_points+'.$lookit['pdr_shoppoints']);   //添加店铺积分
		$updatee=$member->where(array('member_id'=>$member_info['member_id']))->update($data); //更新会员表
		$sdata=array();
		$sdata['member_points']=array('exp','member_points-'.$lookit['pdr_shoppoints']); 
		$store_info=$store->getStoreInfoByID($pdr_store_id);
		$member_store=$member->where(array('member_id'=>$store_info['member_id']))->find();
		$supdatee=$member->where(array('member_id'=>$member_store['member_id']))->update($sdata); //更新商家积分
		if($supdatee){
			$spm['pl_memberid']= $member_store['member_id'];
			$spm['pl_membername']=$member_store['member_name'];
			$spm['pl_points']='-'.$pdr_shoppoints;
			$spm['pl_addtime']=time();
			$spm['pl_stage']='rechart';
			$spm['pl_desc']=$member_info['member_name'].'在'.$pdr_st_shop.'消费赠送云豆'.$lookit['pdr_shoppoints'];
			$inserts=$pm->insert($spm);
		}       
        if($updatee){                                        //添加消费金额
            $pd['member_id']= $member_info['member_id'];
			$pd['member_name']=$member_info['member_name'];
			$pd['consume_amount']='-'.$yd_name;
			$pd['consume_time']=time();
			$pd['consume_remark']='在'.$pdr_st_shop.'消费金额'.$yd_name.'元,您的支付单号为：'.$flag;
			$insert=$pd_log->insert($pd);
            $pmd['pl_memberid']= $member_info['member_id'];
			$pmd['pl_membername']=$member_info['member_name'];
			$pmd['pl_points']='-'.$pdr_ptpoints;
			$pmd['pl_addtime']=time();
			$pmd['pl_stage']='rechart';
			$pmd['pl_desc']='在'.$pdr_st_shop.'消费扣除云豆'.$lookit['pdr_ptpoints'];
			$insert1=$pm->insert($pmd);
			$pms['pl_memberid']= $member_info['member_id'];
			$pms['pl_membername']=$member_info['member_name'];
			$pms['pl_points']=$pdr_shoppoints;
			$pms['pl_addtime']=time();
			$pms['pl_stage']='rechart';
			$pms['pl_desc']='在'.$pdr_st_shop.'消费赠送云豆'.$lookit['pdr_shoppoints'];
			$insert2=$pm->insert($pms);
			$data1 = array(
	             'pdr_status'=>1,
	             );
		    $maprk->where(array('pdr_pay_sn'=>$flag))->update($data1);  //更新数据记录
           }
             echo '6'; exit();       //充值成功！
         }
        }
	  }
	  public function pdrechargelistOp(){
                  $where = array();
                  $where['pdr_member_id'] = $this->member_info['member_id'];
                  $model_pd = Model('dianpufx');
                 
                  $list = $model_pd->where($where)->order('pdr_id desc')->select();
                  if ($list) {
            foreach($list as $k=>$v){
                $v['pdr_add_time_text'] = @date('Y-m-d H:i:s',$v['pdr_paytime']);
                $v['pdr_payment_state_text'] = $v['pdr_status']==1?'已支付':"未支付";
                $list[$k] = $v;
            }
        }
                  $page_count = $model_pd->gettotalpage();
               
        output_data(array('list' => $list), mobile_page($page_count));
	  }
	  public function dmorder_exitOp(){   //地面店铺扫码支付
	  	$model_order_common = Model('order_common');
		$model_order_goods = Model('order_goods');
		$model_order_pay = Model('order_pay');
		$model_orders = Model('orders');
		$model_member = Model('member');
		$model_store = Model('store');
	  	$yd_name = isset($_POST['yd_name'])?$_POST['yd_name']:'';
	  	$pay_sn  = isset($_POST['pay_sn'])?$_POST['pay_sn']:'';
        $order_sn = isset($_POST['order_sn'])?$_POST['order_sn']:'';
	  	$map_id = isset($_POST['map_id'])?$_POST['map_id']:'';
	  	$member_id = isset($_POST['member_id'])?$_POST['member_id']:'';
	  	$bs=$model_orders->where(array('order_sn'=>$order_sn,'pay_sn'=>$pay_sn))->select();
	  	if($bs){
	  		echo '3';
	  		exit();
	  	}
	   if(!empty($yd_name)&&!empty($map_id)){
        $store_info = $model_store->getStoreInfoByID($map_id);
		$meminfo = $model_member->getMemberInfoByID($member_id);
		$data=array(     //产生订单
          'order_sn'=>$order_sn,
          'pay_sn'=>$pay_sn,
          'store_id'=>$store_info['store_id'],
          'store_name'=>$store_info['store_name'],
          'buyer_id'=>$meminfo['member_id'],
          'buyer_name'=>$meminfo['member_name'],
          'buyer_email'=>$meminfo['member_email'],
          'buyer_phone'=>$meminfo['member_mobile'],
          'add_time'=>time(),
          'payment_code'=>'online',
          'goods_amount'=>$yd_name,
          'order_amount'=>$yd_name,
          'order_pointsamount'=>0,
          'order_state'=>10,
          'is_dm'=>1
        );
       $model_se = $model_orders->insert($data);
        if($model_se){
          $ds = $model_orders->where(array('order_sn'=>$order_sn,'pay_sn'=>$pay_sn))->select();
           $datac=array(
           'order_id'=>$ds[0]['order_id'],
           'store_id'=>$ds[0]['store_id'],
          	);
         $model_com = $model_order_common->insert($datac);
         $datag = array(
           'order_id'=>$ds[0]['order_id'],
           'goods_name'=>'扫码支付',
           'goods_price'=>$yd_name,
           'goods_points'=>'0',
           'goods_num' => 1,
           'goods_pay_price'=>$yd_name,
           'goods_pay_points'=>0,
           'store_id'=>$ds[0]['store_id'],
           'buyer_id'=>$meminfo['member_id'],
           'goods_type'=>100,
           'gc_id'=>0
         	);
         $model_good = $model_order_goods->insert($datag);
         $datap = array(
           'pay_sn'=>$pay_sn,
           'buyer_id'=>$meminfo['member_id'],
           'api_pay_state'=>'0'
         	);
         $model_pay = $model_order_pay->insert($datap);
         echo '2';
         }else{
        	echo '1';
        }
      }
   }
   private function getMillisecond() { 
     list($t1, $t2) = explode(' ', microtime()); 
  return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000); 
} 
	  
}
