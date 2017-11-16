<?php

	defined('In33hao') or exit('Access Invalid!');
	class auto_splitControl {

		public function indexOp(){
			ignore_user_abort(true);
			@set_time_limit(0);
			
			$member=Model('member');
			$point['member_points']=array("gt",0);
			$date=strtotime(date("Y-m-d"));

			$pd_log	= Model('pd_log');
			
			$point['return_time']=array("eq",$date-'86400');
			$chief=Model('chief');
			$content=$chief->getfby_id(10,'chief');
				
			$total=$member->where($point)->limit(5000)->select();
		
			foreach ($total as $key => $tol) {
				$mount=$tol['member_points']*$content;

				// 当前云豆
				$data["member_points"]=$tol['member_points']-$mount;
				
				// 钱包余额
				if($tol['member_level']=='5'){
					$data["province_predeposit"]=$tol['province_predeposit']+$mount;
					$available=$data["province_predeposit"];
					// de_encode($tol['member_id'],$data["province_predeposit"]);
				}else{
					$data["available_predeposit"]=$tol['available_predeposit']+$mount;
					$available=$data["available_predeposit"];
					// de_encode($tol['member_id'],$data["available_predeposit"]);					
				}

			    //生成返现余额安全码   					   
			    $points_array_2=['id'=>$tol['member_id'],'amt'=>$available];

			    $data['available_code'] = 'asdasdasdasd';
			  
				$data["return_time"]=$date;

				$list = $member->where(array('member_id'=>$tol['member_id']))->update($data);

				$dat=array('lg_member_id'=>$tol['member_id'],'lg_member_name'=>$tol['member_name'],
					'lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_desc'=>'每日赠送');
		 		
		        $pd_log->insert($dat);

		     //    //生成云豆余额安全码   					   
			    // $points_array_1=['id'=>$tol['member_id'],'amt'=>$data["member_points"]];
			    // $dat_code['points_code'] = Ze\Secure::encode($points_array_1);
			    // $member->where(array('member_id'=>$tol['member_id']))->update($dat_code);
				unset($data);
				unset($dat);
				// unset($dat_code);
									
			}
			
		}
		// public function pointsOp(){
		// 	$points_log=Model('points_log');
		// 	$member=Model('member');
		// 	$order=Model('orders');

		// 	//获取去年上个月的时间戳
		// 	$last_time= date("Y-m-01", strtotime("-1 year -1 month"));  
		// 	$last_time=strtotime($last_time);
		// 	//获取去年该月的时间戳
		// 	$next_time= date("Y-m-01", strtotime("-1 year"));  
		// 	$next_time=strtotime($next_time);
		// 	//获取当月1号时间戳
		// 	$time_1 = date("Y-m-01");
		// 	$time_1=strtotime($time_1);
		// 	//获取哪些会员购买云豆满一年了
		// 	$point_where['pl_addtime']=array('time',array($last_time,$next_time));
		// 	$point_where['pl_stage']='rechart';
		// 	$points_array=$points_log->where($point_where)->group('pl_memberid')->field('pl_memberid,count(*) as count')->select();
		// 	foreach ($points_array as $key => $value) {
		// 		$member_id[]=$value['pl_memberid'];
		// 	}
			
		// 	$memberid=implode(',',$member_id);
		// 	//获取会员一年的充值购买云豆数量
		// 	$point_where_1['pl_addtime']=array('time',array($last_time,$time_1));
		// 	$point_where_1['pl_stage']='rechart';
		// 	$point_where_1['pl_memberid']=array('in',$memberid);
			
		// 	$points_array_1=$points_log->where($point_where_1)->group('pl_memberid')->field('pl_memberid,sum(pl_points) as points_count')->select();
		// 	foreach ($points_array_1 as $key => $value) {
		// 		$buy_points[$value['pl_memberid']]=$value['points_count'];
		// 	}
		// 	//获取会员一年的购买商品消耗云豆数量
		// 	$order_arrray['buyer_id']=array('in',$memberid);
		// 	$order_arrray['order_state']=array('gt','10');
		// 	$order_arrray['payment_time']=array('time',array($last_time,$time_1));
		// 	$order_arrray['refund_state']='0';
		// 	$order_info=$order->where($order_arrray)->group('buyer_id')->field('buyer_id,sum(order_pointsamount) as order_points')->select();
		// 	foreach ($order_info as $key => $value) {
		// 		$consumption_points[$value['buyer_id']]=$value['order_points'];
		// 	}
		// 	$point_date=array();
		// 	foreach ($buy_points as $key => $value) {
		// 		$point=$value*0.3;
				
		// 		if($consumption_points[$key]<$point){
		// 			// $member->where(array('member_id'=>$key))->setDec('member_points',$point);
		// 			$points_date[]=array(
		// 				'pl_memberid' => $key,
		// 				'pl_points'   => '-'.$point,
		// 				'pl_addtime'  => time(),
		// 				'pl_stage'    => 'consumption',
		// 				'pl_desc'     => '您在这一年之中，未购买商品消耗兑换云豆的30%，则扣除您兑换云豆的30%',
		// 				'pl_counter'  => $consumption_points[$key]
		// 			);
		// 		}
		// 	}
		// 	print_r($points_date);
		// 	$points_log->insertAll($points_date);

		// }
	}

?>