<?php

	defined('In33hao') or exit('Access Invalid!');
	class statisticsControl {

		public function indexOp(){
			ignore_user_abort(true);
			@set_time_limit(0);
			$statis=Model('statistics');
			$member=Model('member');
			$pd_log=Model('pd_log');
			$date=strtotime(date("Y-m-d"));
			//上个月第一天
			$first_day= strtotime(date('Y-m-01', strtotime('-1 month')));
			//上个月最后一天
			$last_day=strtotime(date('Y-m-t', strtotime('-1 month')));
			//上个月月份
			$last_month=strtotime(date('Y-m', strtotime('-1 month')));
			$memberinfo=$member->where(array('member_level'=>array('gt','1'),'statistics_time'=>array('lt',$date)))->limit(400)->select();
			$level=array('2'=>'portid','3'=>'member_areaid','4'=>'member_cityid','5'=>'member_provinceid');
			foreach ($memberinfo as $key => $meinfo) {
				$member->where(array('member_id'=>$meinfo['member_id']))->update(array('statistics_time'=>$date));
				$times=$date-86400;
				// //获取激活人数
				$where_profit[$level[$meinfo['member_level']]]=$meinfo[$level[$meinfo['member_level']]];
				$where_profit['member_time']=array('between',"$times,$date");
				$where_profit['member_level']=array('gt','0');
				
				$lg_av_amount=$member->where($where_profit)->count();
				unset($where_profit);
				//获取代理分成收益
				$where_pd['lg_member_id']=$meinfo['member_id'];
				$where_pd['lg_type']='agent';
				$where_pd['lg_add_time']=array('between',"$times,$date");
				$lg_freeze_amount=$pd_log->where($where_pd)->sum('lg_av_amount');
				
				$dat=array('lg_member_id'=>$meinfo['member_id'],'lg_member_name'=>$meinfo['member_name'],'lg_type'=>'profit','total_number'=>$lg_av_amount,'profit_amount'=>$lg_freeze_amount,'lg_add_time'=>$times,'lg_desc'=>'昨日收益');
				$statis->insert($dat);
				if($date==strtotime(date("Y-m-1"))){
					//获取代理冻结30%金额
					$where_freeze['lg_type']='agent';					
					$where_freeze['lg_desc']=array('like','%冻结30%');					
					$where_freeze['lg_add_time']=array('between',"$first_day,$last_day");
					$where_freeze['lg_member_id']=$meinfo['member_id'];
					$agent_freeze_amount=$pd_log->where($where_freeze)->sum('lg_av_amount');
					// echo $agent_freeze_amount;
					// print_r($where_freeze);
					$dat1=array('lg_member_id'=>$meinfo['member_id'],'lg_member_name'=>$meinfo['member_name'],'lg_type'=>'freeze_statis','total_number'=>$agent_freeze_amount,'lg_add_time'=>$last_month,'lg_desc'=>(date("m")-1).'月份代理冻结收益');
					$statis->insert($dat1);
				}
			}

			
		}

	}

?>