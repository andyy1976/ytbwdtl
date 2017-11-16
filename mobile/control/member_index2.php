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
		$custom_money = isset($_GET['custom_money'])?$_GET['custom_money']:'';
		$custom_point = isset($_GET['custom_point'])?$_GET['custom_point']:'';
		$store_state = isset($_GET['store_state'])?$_GET['store_state']:'';
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['user_id'] = $this->member_info['member_id'];
        // $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['avatar'] = getMemberAvatar($this->member_info['member_avatar']);
 		if($this->member_info['free']=='1'){
        	$member_info['user_level'] = '见习会员';
        }else{
        	$member_info['user_level'] = $this->member_level_name($this->member_info['member_level']);
        }       
       
        $member_info['member_level']=$this->member_info['member_level'];
        $member_info['member_mobile']=$this->member_info['member_mobile'];
        $member_info['member_pid']=$this->member_info['member_pid'];
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
}
